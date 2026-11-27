import { createContext, useContext, useEffect, useMemo, useRef, useState } from 'react';
import { useAuth } from './AuthContext';
import { useToast } from './ToastContext';
import { agregarDeseadoApi, getDeseadosApi, quitarDeseadoApi } from '../services/api';
import { normalizarLibro } from '../utils/libros';

const DeseadosContext = createContext(null);

export const DeseadosProvider = ({ children }) => {
	const { usuario, token } = useAuth();
	const mostrarToast = useToast();

	const [items, setItems] = useState([]);
	const [cargando, setCargando] = useState(false);

	const enCurso = useRef(new Set());

	useEffect(() => {
		if (!usuario) {
			setItems([]);
			return;
		}

		let activo = true;
		const control = new AbortController();
		setCargando(true);

		getDeseadosApi(token, control.signal)
			.then((datos) => activo && setItems(datos.items.map(normalizarLibro)))
			.catch((err) => {
				if (activo && err.name !== 'AbortError') {
					mostrarToast('No pudimos cargar su lista de deseados.', { tipo: 'error' });
				}
			})
			.finally(() => activo && setCargando(false));

		return () => {
			activo = false;
			control.abort();
		};
	}, [usuario?.id]);

	const ids = useMemo(() => new Set(items.map((i) => i.id)), [items]);
	const esDeseado = (libroId) => ids.has(libroId);

	const alternar = async (libro) => {
		if (!usuario || enCurso.current.has(libro.id)) return;
		enCurso.current.add(libro.id);

		const estaba = ids.has(libro.id);

		setItems((prev) =>
			estaba ? prev.filter((i) => i.id !== libro.id) : [{ ...libro, disponible: true }, ...prev],
		);

		try {
			if (estaba) {
				await quitarDeseadoApi(libro.id, token);
				mostrarToast('Se quitó de su lista de deseados.');
			} else {
				await agregarDeseadoApi(libro.id, token);
				mostrarToast('Se agregó a su lista de deseados.');
			}
		} catch (err) {
			setItems((prev) =>
				estaba ? [libro, ...prev.filter((i) => i.id !== libro.id)] : prev.filter((i) => i.id !== libro.id),
			);
			mostrarToast(err.message ?? 'No se pudo actualizar la lista de deseados.', { tipo: 'error' });
		} finally {
			enCurso.current.delete(libro.id);
		}
	};

	return (
		<DeseadosContext.Provider value={{ items, cantidad: items.length, cargando, esDeseado, alternar }}>
			{children}
		</DeseadosContext.Provider>
	);
};

export const useDeseados = () => {
	const ctx = useContext(DeseadosContext);
	if (!ctx) throw new Error('useDeseados tiene que usarse dentro de <DeseadosProvider>');
	return ctx;
};