import { useCallback, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { Icon } from '../components/Icon';
import { useAuth } from '../context/AuthContext';
import { getPedidosApi } from '../services/api';
import { formatearPrecio } from '../utils/libros';
import { useCuentaRegresiva } from '../hooks/useCuentaRegresiva';
import { SeguimientoPedido } from '../components/SeguimientoPedido';
import './MisPedidos.css';

const formatearFecha = (fecha = '') => fecha.split('-').reverse().join('/');

const CLASE_ESTADO = {
	Pendiente: 'estado-pendiente',
	Aceptada: 'estado-proceso',
	'En preparación': 'estado-proceso',
	'En distribución': 'estado-proceso',
	'Recibido en domicilio': 'estado-ok',
	Rechazada: 'estado-cancelado',
	Cancelada: 'estado-cancelado',
};

// Botón para pagar un pedido pendiente (solo si es el que tiene el carrito, con link vigente)
const PagarPendiente = ({ pedido }) => {
	const tiempo = useCuentaRegresiva(pedido.venceEn);
	return (
		<button type='button' className='btn btn-primary' onClick={() => (window.location.href = pedido.initPoint)}>
			Pagar ({tiempo})
		</button>
	);
};

const TarjetaPedido = ({ pedido, pedidoPendiente }) => {
	const [abierto, setAbierto] = useState(false);
	const puedePagar = pedido.estado === 'Pendiente' && pedidoPendiente?.id === pedido.id;

	return (
		<li className='pedido'>
			<div className='pedido-resumen'>
				<div className='pedido-datos'>
					<h2 className='pedido-numero'>Pedido #{pedido.id}</h2>
					<p className='pedido-fecha'>
						{formatearFecha(pedido.fecha)} · {pedido.hora?.slice(0, 5)} hs ·{' '}
						{pedido.cantidad === 1 ? '1 libro' : `${pedido.cantidad} libros`}
					</p>
				</div>

				<span className={`estado ${CLASE_ESTADO[pedido.estado] ?? ''}`}>{pedido.estado_texto}</span>

				<strong className='pedido-total'>{formatearPrecio(pedido.total)}</strong>
			</div>

			<div className='pedido-acciones'>
				<button
					type='button'
					className='btn btn-ghost'
					onClick={() => setAbierto((v) => !v)}
					aria-expanded={abierto}
					aria-controls={`pedido-${pedido.id}`}
				>
					{abierto ? 'Ocultar detalle' : 'Ver detalle y seguimiento'}
					<Icon name='chevronDown' size={16} className={abierto ? 'girado' : ''} />
				</button>
				{puedePagar && <PagarPendiente pedido={pedidoPendiente} />}
			</div>

			{abierto && (
				<div className='pedido-detalle' id={`pedido-${pedido.id}`}>
					<SeguimientoPedido pedido={pedido} />
					<ul className='pedido-items'>
						{pedido.items.map((item) => (
							<li key={item.libro} className='pedido-item'>
								{item.imagen ? (
									<img src={item.imagen} alt='' className='cart-thumb' loading='lazy' />
								) : (
									<span className='cart-thumb cart-thumb-empty'>
										<Icon name='book' />
									</span>
								)}
								<div className='pedido-item-info'>
									<p className='cart-name'>{item.nombre}</p>
									<p className='pedido-item-precio'>
										{item.cantidad} × {formatearPrecio(item.precio_unitario)}
									</p>
								</div>
								<strong>{formatearPrecio(item.precio_total)}</strong>
							</li>
						))}
					</ul>
					<p className='pedido-domicilio'>
						Envío a: {pedido.domicilio}
					</p>
				</div>
			)}
		</li>
	);
};

export const MisPedidos = ({ carrito }) => {
	const { token } = useAuth();
	const [pedidos, setPedidos] = useState([]);
	const [cargando, setCargando] = useState(true);
	const [error, setError] = useState('');

	const cargar = useCallback(
		async (signal) => {
			setCargando(true);
			setError('');
			try {
				setPedidos(await getPedidosApi(token, signal));
			} catch (err) {
				if (err.name !== 'AbortError') setError(err.message);
			} finally {
				if (!signal?.aborted) setCargando(false);
			}
		},
		[token],
	);

	useEffect(() => {
		const control = new AbortController();
		cargar(control.signal);
		return () => control.abort();
	}, [cargar]);

	return (
		<main className='pedidos-page'>
			<div className='pedidos-head'>
				<h1>Mis pedidos</h1>
				{!cargando && !error && pedidos.length > 0 && (
					<p>{pedidos.length === 1 ? '1 pedido' : `${pedidos.length} pedidos`}</p>
				)}
			</div>

			{cargando ? (
				<ul className='pedidos-lista' aria-busy='true'>
					{Array.from({ length: 3 }, (_, i) => (
						<li key={i} className='pedido pedido-skeleton'>
							<div className='sk sk-line' />
							<div className='sk sk-line sk-short' />
						</li>
					))}
				</ul>
			) : error ? (
				<div className='state'>
					<Icon name='alert' size={48} strokeWidth={1.2} />
					<h3>No pudimos cargar sus pedidos</h3>
					<p>{error}</p>
					<button type='button' className='btn btn-primary' onClick={() => cargar()}>
						Reintentar
					</button>
				</div>
			) : pedidos.length === 0 ? (
				<div className='state'>
					<Icon name='cart' size={48} strokeWidth={1.2} />
					<h3>Todavía no hizo pedidos</h3>
					<p>Cuando compre un libro, va a poder seguir su pedido desde acá.</p>
					<Link to='/' className='btn btn-primary'>
						Ir a la tienda
					</Link>
				</div>
			) : (
				<ul className='pedidos-lista'>
					{pedidos.map((pedido) => (
						<TarjetaPedido key={pedido.id} pedido={pedido} pedidoPendiente={carrito.pedido} />
					))}
				</ul>
			)}
		</main>
	);
};