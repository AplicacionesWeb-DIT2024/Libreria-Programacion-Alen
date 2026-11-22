import { useDeferredValue, useEffect, useMemo, useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { Hero } from '../components/Hero';
import { Filtros } from '../components/Filtros';
import { Toolbar } from '../components/Toolbar';
import { ProductList } from '../components/ProductList';
import { Icon } from '../components/Icon';
import { useLibros } from '../hooks/useLibros';
import { useAuth } from '../context/AuthContext';
import { FILTROS_INICIALES, filtrarLibros, obtenerOpciones, ordenarLibros } from '../utils/libros';

export const Tienda = ({ carrito }) => {
	const { libros, cargando, error, reintentar } = useLibros();
	const { usuario } = useAuth();
	const location = useLocation();
	const navigate = useNavigate();

	const [busqueda, setBusqueda] = useState('');
	const [filtros, setFiltros] = useState(FILTROS_INICIALES);
	const [orden, setOrden] = useState('relevancia');
	const [filtrosAbiertos, setFiltrosAbiertos] = useState(false);
	const [bienvenida, setBienvenida] = useState(Boolean(location.state?.bienvenida));

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

	useEffect(() => {
		if (location.state?.bienvenida) navigate('.', { replace: true, state: null });
	}, [location.state, navigate]);

	return (
		<>
			{bienvenida && usuario && (
				<div className='welcome' role='status'>
					<Icon name='check' size={20} strokeWidth={2.4} />
					<span>
						¡Bienvenido/a, <strong>{usuario.nombre}</strong>! Tu cuenta ya está creada.
					</span>
					<button type='button' onClick={() => setBienvenida(false)} aria-label='Cerrar aviso'>
						<Icon name='close' size={18} />
					</button>
				</div>
			)}

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
