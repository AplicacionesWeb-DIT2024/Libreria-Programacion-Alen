import { useCallback, useEffect, useState } from 'react';
import { normalizarLibro } from '../utils/libros';
import { getLibros } from '../services/api';

export const useLibros = () => {
	const [libros, setLibros] = useState([]);
	const [cargando, setCargando] = useState(true);
	const [error, setError] = useState(null);

	const cargar = useCallback(async (signal) => {
		setCargando(true);
		setError(null);
		try {
			const lista = await getLibros(signal);
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
