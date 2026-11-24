import { useEffect, useMemo, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { cancelarPedidoApi } from '../services/api';
import { CLAVE_PEDIDO } from '../utils/pedidoPendiente';

const CLAVE_INVITADO = 'bytebooks-carrito';

const claveCarrito = (usuarioId) => (usuarioId ? `bytebooks-carrito-${usuarioId}` : CLAVE_INVITADO);

const leer = (clave, porDefecto) => {
	try {
		return JSON.parse(localStorage.getItem(clave)) ?? porDefecto;
	} catch {
		return porDefecto;
	}
};

const guardar = (clave, valor) => {
	try {
		if (valor === null) localStorage.removeItem(clave);
		else localStorage.setItem(clave, JSON.stringify(valor));
	} catch {
	}
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

	
	const [carrito, setCarrito] = useState(() => ({ clave, items: leer(clave, []) }));

	const [pedido, setPedido] = useState(() => leer(CLAVE_PEDIDO, null));

	const [pedidoVencido, setPedidoVencido] = useState(false);

	useEffect(() => {
		if (carrito.clave === clave) return;

		let items = leer(clave, []);

		if (carrito.clave === CLAVE_INVITADO && clave !== CLAVE_INVITADO) {
			items = combinar(items, leer(CLAVE_INVITADO, []));
			guardar(CLAVE_INVITADO, null);
		}

		setCarrito({ clave, items });
	}, [clave, carrito.clave]);

	useEffect(() => {
		guardar(carrito.clave, carrito.items);
	}, [carrito]);

	useEffect(() => guardar(CLAVE_PEDIDO, pedido), [pedido]);

	const items = carrito.clave === clave ? carrito.items : [];

	const setItems = (cambio) =>
		setCarrito((actual) => ({
			...actual,
			items: typeof cambio === 'function' ? cambio(actual.items) : cambio,
		}));


	useEffect(() => {
		if (pedido && pedido.usuarioId !== usuario?.id) {
			setPedido(null);
		}
	}, [usuario?.id, pedido]);

	
	useEffect(() => {
		if (!pedido) return;

		const restante = new Date(pedido.venceEn).getTime() - Date.now();
		const vencer = () => {
			setPedido(null);
			setItems([]);
			setPedidoVencido(true);
		};

		if (restante <= 0) {
			vencer();
			return;
		}

		const timer = setTimeout(vencer, restante);
		return () => clearTimeout(timer);
	}, [pedido]);

	
	const cancelarPendiente = () => {
		if (!pedido) return;
		if (token) cancelarPedidoApi(pedido.id, token).catch(() => {});
		setPedido(null);
	};

	
	const modificar = (cambio) => {
		cancelarPendiente();
		setItems(cambio);
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

	const registrarPedido = ({ id, init_point, vence_en }) => {
		setPedidoVencido(false);
		setPedido({ id, initPoint: init_point, venceEn: vence_en, usuarioId: usuario?.id });
	};

	const completarPedido = () => {
		setPedido(null);
		setItems([]);
	};

	const ocultarAvisoVencido = () => setPedidoVencido(false);

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
		pedidoVencido,
		registrarPedido,
		completarPedido,
		ocultarAvisoVencido,
	};
};