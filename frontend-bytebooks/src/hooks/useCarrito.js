import { useEffect, useMemo, useRef, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useToast } from '../context/ToastContext';
import {
	actualizarCarritoApi,
	cancelarPedidoApi,
	fusionarCarritoApi,
	getCarritoApi,
	quitarDelCarritoApi,
	vaciarCarritoApi,
} from '../services/api';


const CLAVE_INVITADO = 'bytebooks-carrito';
const clavePedido = (usuarioId) => `bytebooks-pedido-${usuarioId}`;
const claveVieja = (usuarioId) => `bytebooks-carrito-${usuarioId}`;

const leer = (clave) => {
	try {
		return JSON.parse(localStorage.getItem(clave));
	} catch {
		return null;
	}
};

const guardar = (clave, valor) => {
	try {
		if (valor === null || valor === undefined) 
			localStorage.removeItem(clave);
		else 
			localStorage.setItem(clave, JSON.stringify(valor));
	} catch {
	}
};

const itemsDe = (dato) => (Array.isArray(dato) ? dato : dato?.items ?? []);

const yaVencio = (pedido) => Boolean(pedido) && new Date(pedido.venceEn).getTime() <= Date.now();

const cargarPedido = (usuarioId) => {
	const pedido = leer(clavePedido(usuarioId)) ?? leer(claveVieja(usuarioId))?.pedido ?? null;
	if (yaVencio(pedido)) return { pedido: null, vencio: true };
	return { pedido, vencio: false };
};

const combinar = (base, extra) => {
	const resultado = base.map((i) => ({ ...i }));
	for (const item of extra) {
		const existente = resultado.find((i) => i.id === item.id);
		if (existente) 
			existente.quantity = Math.max(existente.quantity, item.quantity);
		else resultado.push({ ...item });
	}
	return resultado;
};

const cantidadDe = (lista, id) => lista.find((i) => i.id === id)?.quantity ?? 0;

const estadoInicial = (usuarioId, clave) =>
	usuarioId
		? { clave, items: [], ...cargarPedido(usuarioId), cargando: true }
		: { clave, items: itemsDe(leer(CLAVE_INVITADO)), pedido: null, vencio: false, cargando: false };

export const useCarrito = () => {
	const { usuario, token } = useAuth();
	const mostrarToast = useToast();

	const usuarioId = usuario?.id ?? null;
	const clave = usuarioId ? `usuario-${usuarioId}` : 'invitado';

	const [estado, setEstado] = useState(() => estadoInicial(usuarioId, clave));

	const itemsRef = useRef(estado.items);
	itemsRef.current = estado.items;

	const cola = useRef(Promise.resolve());

	useEffect(() => {
		if (!usuarioId) {
			setEstado(estadoInicial(null, clave));
			return;
		}

		let activo = true;
		const control = new AbortController();
		const { pedido, vencio } = cargarPedido(usuarioId);

		setEstado({ clave, items: [], pedido, vencio, cargando: true });

		(async () => {
			const delInvitado = itemsDe(leer(CLAVE_INVITADO));
			const viejos = itemsDe(leer(claveVieja(usuarioId)));
			const aSumar = combinar(viejos, delInvitado);
			guardar(CLAVE_INVITADO, null);
			guardar(claveVieja(usuarioId), null);

			try {
				const carrito = aSumar.length
					? await fusionarCarritoApi(aSumar, token)
					: await getCarritoApi(token, control.signal);

				let pedidoFinal = pedido;
				if (pedido && delInvitado.length > 0) {
					cancelarPedidoApi(pedido.id, token).catch(() => {});
					pedidoFinal = null;
				}

				if (!activo) return;
				setEstado((e) => (e.clave === clave ? { ...e, items: carrito.items, pedido: pedidoFinal, cargando: false } : e));
			} catch (err) {
				if (err.name === 'AbortError') return;

				if (delInvitado.length) guardar(CLAVE_INVITADO, delInvitado);

				if (!activo) return;
				setEstado((e) => (e.clave === clave ? { ...e, cargando: false } : e));
				mostrarToast('No pudimos cargar su carrito. Intente recargar la página.', { tipo: 'error' });
			}
		})();

		return () => {
			activo = false;
			control.abort();
		};
	}, [clave]);

	useEffect(() => {
		if (clave === 'invitado' && estado.clave === 'invitado') guardar(CLAVE_INVITADO, estado.items);
	}, [clave, estado.clave, estado.items]);

	useEffect(() => {
		if (usuarioId && estado.clave === clave) guardar(clavePedido(usuarioId), estado.pedido);
	}, [usuarioId, clave, estado.clave, estado.pedido]);

	const encolar = (tarea) => {
		cola.current = cola.current.then(tarea).catch(async (err) => {
			mostrarToast(err.message ?? 'No se pudo actualizar el carrito.', { tipo: 'error' });

			try {
				const carrito = await getCarritoApi(token);
				setEstado((e) => (e.clave === clave ? { ...e, items: carrito.items } : e));
			} catch {
			}
		});
	};

	const sincronizar = (antes, despues) => {
		if (!usuarioId) return;
		const tk = token;

		if (despues.length === 0 && antes.length > 0) {
			encolar(() => vaciarCarritoApi(tk));
			return;
		}

		const ids = new Set([...antes, ...despues].map((i) => i.id));
		for (const id of ids) {
			const cantidad = cantidadDe(despues, id);
			if (cantidad === cantidadDe(antes, id)) continue;
			encolar(() => (cantidad === 0 ? quitarDelCarritoApi(id, tk) : actualizarCarritoApi(id, cantidad, tk)));
		}
	};

	const vaciarSinCancelar = () => {
		const antes = itemsRef.current;
		itemsRef.current = [];
		setEstado((e) => ({ ...e, items: [], pedido: null }));
		sincronizar(antes, []);
	};

	useEffect(() => {
		const pedido = estado.pedido;
		if (!pedido || estado.clave !== clave) return;

		const vencer = () => {
			vaciarSinCancelar();
			setEstado((e) => ({ ...e, vencio: true }));
		};

		const restante = new Date(pedido.venceEn).getTime() - Date.now();
		if (restante <= 0) {
			vencer();
			return;
		}

		const timer = setTimeout(vencer, restante);
		return () => clearTimeout(timer);
	}, [estado.pedido, estado.clave, clave]);

	const esActual = estado.clave === clave;
	const items = esActual ? estado.items : [];
	const pedido = esActual ? estado.pedido : null;
	const cargando = !esActual || estado.cargando;


	const modificar = (cambio) => {
		if (cargando) return;

		const antes = itemsRef.current;
		const despues = typeof cambio === 'function' ? cambio(antes) : cambio;
		itemsRef.current = despues;

		if (estado.pedido && token) cancelarPedidoApi(estado.pedido.id, token).catch(() => {});

		setEstado((e) => ({ ...e, items: despues, pedido: null }));
		sincronizar(antes, despues);
	};

	const agregar = (libro) =>
		modificar((prev) => {
			const existe = prev.find((i) => i.id === libro.id);
			if (existe) return prev.map((i) => (i.id === libro.id ? { ...i, quantity: i.quantity + 1 } : i));
			return [
				...prev,
				{ id: libro.id, nombre: libro.nombre, precio: libro.precio, imagen: libro.imagenes?.[0] ?? null, quantity: 1 },
			];
		});

	const cambiarCantidad = (id, delta) =>
		modificar((prev) =>
			prev.map((i) => (i.id === id ? { ...i, quantity: i.quantity + delta } : i)).filter((i) => i.quantity > 0),
		);

	const quitar = (id) => modificar((prev) => prev.filter((i) => i.id !== id));
	const vaciar = () => modificar([]);

	const registrarPedido = ({ id, init_point, vence_en }) =>
		setEstado((e) => ({ ...e, pedido: { id, initPoint: init_point, venceEn: vence_en }, vencio: false }));

	const completarPedido = () => vaciarSinCancelar();

	const ocultarAvisoVencido = () => setEstado((e) => ({ ...e, vencio: false }));

	const { total, cantidad } = useMemo(
		() => ({
			total: items.reduce((acc, i) => acc + Number(i.precio) * i.quantity, 0),
			cantidad: items.reduce((acc, i) => acc + i.quantity, 0),
		}),
		[items],
	);

	return {
		items,
		total,
		cantidad,
		cargando,
		agregar,
		cambiarCantidad,
		quitar,
		vaciar,
		pedido,
		pedidoVencido: esActual && estado.vencio,
		registrarPedido,
		completarPedido,
		ocultarAvisoVencido,
	};
};