import { useDeferredValue, useEffect, useMemo, useState } from 'react';
import { Header } from './components/Header';
import { Hero } from './components/Hero';
import { Filtros } from './components/Filtros';
import { Toolbar } from './components/Toolbar';
import { ProductList } from './components/ProductList';
import { useLibros } from './hooks/useLibros';
import { useCarrito } from './hooks/useCarrito';
import { FILTROS_INICIALES, filtrarLibros, obtenerOpciones, ordenarLibros } from './utils/libros';

const leerUsuario = () => {
	try {
		return JSON.parse(localStorage.getItem('usuario'));
	} catch {
		return null;
	}
};

function App() {
	const { libros, cargando, error, reintentar } = useLibros();
	const carrito = useCarrito();
	const [usuario, setUsuario] = useState(leerUsuario);

	const [busqueda, setBusqueda] = useState('');
	const [filtros, setFiltros] = useState(FILTROS_INICIALES);
	const [orden, setOrden] = useState('relevancia');
	const [filtrosAbiertos, setFiltrosAbiertos] = useState(false);

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

	const cerrarSesion = () => {
		localStorage.removeItem('usuario');
		setUsuario(null);
	};

	useEffect(() => {
		document.body.style.overflow = filtrosAbiertos ? 'hidden' : '';
	}, [filtrosAbiertos]);

	return (
		<>
			<Header carrito={carrito} usuario={usuario} onLogout={cerrarSesion} />
			<Hero busqueda={busqueda} setBusqueda={setBusqueda} totalLibros={libros.length} />

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

			<footer className='site-footer'>
				<p>
					© {new Date().getFullYear()} ByteBooks · Libros de programación
				</p>
			</footer>
		</>
	);
}

export default App;
