import { useCallback, useDeferredValue, useEffect, useMemo, useState } from 'react';
import { SplashScreen } from '../components/SplashScreen';
import { Hero } from '../components/Hero';
import { Filtros } from '../components/Filtros';
import { Toolbar } from '../components/Toolbar';
import { ProductList } from '../components/ProductList';
import { useLibros } from '../hooks/useLibros';
import { FILTROS_INICIALES, filtrarLibros, obtenerOpciones, ordenarLibros } from '../utils/libros';

export const Tienda = ({ carrito }) => {
	const { libros, cargando, error, reintentar } = useLibros();

	const [busqueda, setBusqueda] = useState('');
	const [filtros, setFiltros] = useState(FILTROS_INICIALES);
	const [orden, setOrden] = useState('relevancia');
	const [filtrosAbiertos, setFiltrosAbiertos] = useState(false);

	const [mostrarIntro, setMostrarIntro] = useState(() => {
		try {
			return !sessionStorage.getItem('bytebooks-intro');
		} catch {
			return true;
		}
	});

	const terminarIntro = useCallback(() => {
		try {
			sessionStorage.setItem('bytebooks-intro', 'visto');
		} catch {
		}
		setMostrarIntro(false);
	}, []);

	const busquedaDiferida = useDeferredValue(busqueda);

	const opciones = useMemo(() => obtenerOpciones(libros, filtros.categoria), [libros, filtros.categoria]);

	const resultado = useMemo(
		() => ordenarLibros(filtrarLibros(libros, busquedaDiferida, filtros), orden),
		[libros, busquedaDiferida, filtros, orden],
	);

	const setFiltro = (clave, valor) =>
		setFiltros((prev) => {
			const nuevo = { ...prev, [clave]: valor };
			if (clave === 'categoria') nuevo.subcategoria = '';
			return nuevo;
		});

	const cantidadFiltros = Object.values(filtros).filter((v) => v !== '').length;

	const limpiarTodo = () => {
		setFiltros(FILTROS_INICIALES);
		setBusqueda('');
	};

	useEffect(() => {
		document.body.style.overflow = filtrosAbiertos ? 'hidden' : '';
		return () => {
			document.body.style.overflow = '';
		};
	}, [filtrosAbiertos]);

	return (
		<>
			{mostrarIntro && <SplashScreen listo={!cargando} onFin={terminarIntro} />}

			<Hero busqueda={busqueda} setBusqueda={setBusqueda} />

			<main className='catalog'>
				<Filtros
					filtros={filtros}
					setFiltro={setFiltro}
					opciones={opciones}
					onLimpiar={() => setFiltros(FILTROS_INICIALES)}
					hayFiltros={cantidadFiltros > 0}
					abierto={filtrosAbiertos}
					onCerrar={() => setFiltrosAbiertos(false)}
				/>

				<section className='catalog-content' aria-label='Listado de libros'>
					<Toolbar
						resultados={resultado.length}
						orden={orden}
						setOrden={setOrden}
						filtros={filtros}
						setFiltro={setFiltro}
						busqueda={busqueda}
						setBusqueda={setBusqueda}
						onAbrirFiltros={() => setFiltrosAbiertos(true)}
						cantidadFiltros={cantidadFiltros}
					/>
					<ProductList
						libros={resultado}
						cargando={cargando}
						error={error}
						onReintentar={reintentar}
						carrito={carrito}
						onLimpiar={limpiarTodo}
					/>
				</section>
			</main>
		</>
	);
};