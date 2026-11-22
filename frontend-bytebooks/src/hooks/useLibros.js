import { useCallback, useEffect, useState } from 'react';
import { normalizarLibro } from '../utils/libros';

const URL_LIBROS = `${import.meta.env.VITE_API_URL}/api/api/libros`;

export const useLibros = () => {
	const [libros, setLibros] = useState([]);
	const [cargando, setCargando] = useState(true);
	const [error, setError] = useState(null);

	const cargar = useCallback(async (signal) => {
		setCargando(true);
		setError(null);
		try {
			const res = await fetch(URL_LIBROS, { signal });
			if (!res.ok) throw new Error(`Error ${res.status}`);
			const data = await res.json();
			const lista = Array.isArray(data) ? data : data.data ?? [];
			setLibros(lista.map(normalizarLibro));
		} catch (e) {
			if (e.name !== 'AbortError') {
				console.error('Error al traer los libros:', e);
				setError('No pudimos cargar los libros. Revisá tu conexión e intentá de nuevo.');
			}
		} finally {
			if (!signal?.aborted) setCargando(false);
		}
	}, []);

	useEffect(() => {
		const controller = new AbortController();
		cargar(controller.signal);
		return () => controller.abort();
	}, [cargar]);

	return { libros, cargando, error, reintentar: () => cargar() };
};
