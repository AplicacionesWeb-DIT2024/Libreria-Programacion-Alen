import { useEffect, useMemo, useState } from 'react';

const CLAVE = 'bytebooks-carrito';

const leerGuardado = () => {
	try {
		return JSON.parse(localStorage.getItem(CLAVE)) ?? [];
	} catch {
		return [];
	}
};


export const useCarrito = () => {
	const [items, setItems] = useState(leerGuardado);

	useEffect(() => {
		try {
			localStorage.setItem(CLAVE, JSON.stringify(items));
		} catch {
		}
	}, [items]);

	const agregar = (libro) =>
		setItems((prev) => {
			const existe = prev.find((i) => i.id === libro.id);
			if (existe) return prev.map((i) => (i.id === libro.id ? { ...i, quantity: i.quantity + 1 } : i));
			return [...prev, { id: libro.id, nombre: libro.nombre, precio: libro.precio, imagen: libro.imagenes?.[0] ?? null, quantity: 1 }];
		});

	const cambiarCantidad = (id, delta) =>
		setItems((prev) =>
			prev
				.map((i) => (i.id === id ? { ...i, quantity: i.quantity + delta } : i))
				.filter((i) => i.quantity > 0),
		);

	const quitar = (id) => setItems((prev) => prev.filter((i) => i.id !== id));
	const vaciar = () => setItems([]);

	const { total, cantidad } = useMemo(
		() => ({
			total: items.reduce((acc, i) => acc + i.precio * i.quantity, 0),
			cantidad: items.reduce((acc, i) => acc + i.quantity, 0),
		}),
		[items],
	);

	return { items, total, cantidad, agregar, cambiarCantidad, quitar, vaciar };
};
