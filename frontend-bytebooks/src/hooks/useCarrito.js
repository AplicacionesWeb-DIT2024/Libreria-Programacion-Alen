import { useEffect, useMemo, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { cancelarPedidoApi } from '../services/api';

const CLAVE_INVITADO = 'bytebooks-carrito';

const claveCarrito = (usuarioId) => (usuarioId ? `bytebooks-carrito-${usuarioId}` : CLAVE_INVITADO);

const leer = (clave) => {
	try {
		return JSON.parse(localStorage.getItem(clave));
	} catch {
		return null;
	}
};

const guardar = (clave, valor) => {
	try {
		if (valor === null) localStorage.removeItem(clave);
		else localStorage.setItem(clave, JSON.stringify(valor));
	} catch {
	}
};

const leerCarrito = (clave) => {
	const dato = leer(clave);
	if (Array.isArray(dato)) return { items: dato, pedido: null };
	return { items: dato?.items ?? [], pedido: dato?.pedido ?? null };
};

const yaVencio = (pedido) => Boolean(pedido) && new Date(pedido.venceEn).getTime() <= Date.now();

const cargar = (clave) => {
	const { items, pedido } = leerCarrito(clave);
	if (yaVencio(pedido)) return { clave, items: [], pedido: null, vencio: true };
	return { clave, items, pedido, vencio: false };
};

const combinar = (base, extra) => {
	const resultado = base.map((i) => ({ ...i }));
	for (const item of extra) {
		const existente = resultado.find((i) => i.id === item.id);
		if (existente) existente.quantity += item.quantity;
		else resultado.push({ ...item });
	}
	return resultado;
};

export const useCarrito = () => {
	const { usuario, token } = useAuth();
	const clave = claveCarrito(usuario?.id);


	const [estado, setEstado] = useState(() => cargar(clave));

	useEffect(() => {
		if (estado.clave === clave) return;

		const nuevo = cargar(clave);

		if (estado.clave === CLAVE_INVITADO && clave !== CLAVE_INVITADO) {
			const delInvitado = leerCarrito(CLAVE_INVITADO).items;

			if (delInvitado.length > 0) {
				if (nuevo.pedido && token) cancelarPedidoApi(nuevo.pedido.id, token).catch(() => {});
				nuevo.pedido = null;
				nuevo.items = combinar(nuevo.items, delInvitado);
			}

			guardar(CLAVE_INVITADO, null);
		}

		setEstado(nuevo);
	}, [clave, estado.clave]);

	useEffect(() => {
		guardar(estado.clave, { items: estado.items, pedido: estado.pedido });
	}, [estado.clave, estado.items, estado.pedido]);

	
	useEffect(() => {
		const pedido = estado.pedido;
		if (!pedido) return;

		const vencer = () =>
			setEstado((e) => (e.pedido?.id === pedido.id ? { ...e, items: [], pedido: null, vencio: true } : e));

		const restante = new Date(pedido.venceEn).getTime() - Date.now();
		if (restante <= 0) {
			vencer();
			return;
		}

		const timer = setTimeout(vencer, restante);
		return () => clearTimeout(timer);
	}, [estado.pedido]);

	const esActual = estado.clave === clave;
	const items = esActual ? estado.items : [];
	const pedido = esActual ? estado.pedido : null;

	
	const modificar = (cambio) => {
		if (estado.pedido && token) cancelarPedidoApi(estado.pedido.id, token).catch(() => {});

		setEstado((e) => ({
			...e,
			pedido: null,
			items: typeof cambio === 'function' ? cambio(e.items) : cambio,
		}));
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

	const completarPedido = () => setEstado((e) => ({ ...e, items: [], pedido: null }));

	const ocultarAvisoVencido = () => setEstado((e) => ({ ...e, vencio: false }));

	const { total, cantidad } = useMemo(
		() => ({
			total: items.reduce((acc, i) => acc + i.precio * i.quantity, 0),
			cantidad: items.reduce((acc, i) => acc + i.quantity, 0),
		}),
		[items],
	);

	return {
		items,
		total,
		cantidad,
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