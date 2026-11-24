import { useEffect, useState } from 'react';
import { Navigate, Route, Routes, useLocation, useSearchParams } from 'react-router-dom';
import { Header } from './components/Header';
import { Icon } from './components/Icon';
import { Tienda } from './pages/Tienda';
import { Login } from './pages/Login';
import { Registro } from './pages/Registro';
import { useCarrito } from './hooks/useCarrito';
import { useCuentaRegresiva } from './hooks/useCuentaRegresiva';
import { useAuth } from './context/AuthContext';

const SoloInvitados = ({ children }) => {
	const { usuario } = useAuth();
	const [yaLogueado] = useState(Boolean(usuario));
	return yaLogueado ? <Navigate to='/' replace /> : children;
};

const ScrollArriba = () => {
	const { pathname } = useLocation();
	useEffect(() => {
		window.scrollTo(0, 0);
	}, [pathname]);
	return null;
};

const AvisoPago = ({ carrito }) => {
	const [params, setParams] = useSearchParams();
	const pago = params.get('pago');
	const { pedido, pedidoVencido, completarPedido, ocultarAvisoVencido } = carrito;
	const tiempoRestante = useCuentaRegresiva(pedido?.venceEn);

	useEffect(() => {
		if (pago === 'aprobado' || pago === 'pendiente') completarPedido();
	}, [pago]);

	const limpiarUrl = () => setParams({}, { replace: true });

	let tipo = null;
	let texto = null;
	let accion = null;

	if (pago === 'aprobado') {
		tipo = 'ok';
		texto = '¡Pago aprobado! Su pedido fue registrado.';
	} else if (pago === 'pendiente') {
		tipo = 'info';
		texto = 'Su pago está en revisión. Le avisaremos cuando se acredite.';
	} else if (pedido) {
		tipo = 'error';
		texto =
			pago === 'rechazado'
				? `El pago no se pudo completar. Tiene ${tiempoRestante} para reintentarlo; después el pedido se cancela y se vacía el carrito.`
				: `Tiene un pago pendiente. Quedan ${tiempoRestante} para completarlo; después el pedido se cancela y se vacía el carrito.`;
		accion = (
			<button type='button' className='btn btn-primary' onClick={() => (window.location.href = pedido.initPoint)}>
				Reintentar pago
			</button>
		);
	} else if (pedidoVencido) {
		tipo = 'error';
		texto = 'El tiempo para pagar venció y el pedido fue cancelado. Puede volver a armar su carrito.';
	}

	if (!texto) return null;

	const cerrar = () => {
		limpiarUrl();
		ocultarAvisoVencido();
	};

	return (
		<div className={`aviso-pago aviso-pago-${tipo}`} role='status'>
			<span>{texto}</span>
			<div className='aviso-pago-acciones'>
				{accion}
				{!pedido && (
					<button type='button' className='icon-btn icon-btn-sm' onClick={cerrar} aria-label='Cerrar aviso'>
						<Icon name='x' size={16} />
					</button>
				)}
			</div>
		</div>
	);
};

function App() {
	const carrito = useCarrito();

	return (
		<>
			<ScrollArriba />
			<Header carrito={carrito} />
			<AvisoPago carrito={carrito} />

			<Routes>
				<Route path='/' element={<Tienda carrito={carrito} />} />
				<Route
					path='/login'
					element={
						<SoloInvitados>
							<Login />
						</SoloInvitados>
					}
				/>
				<Route
					path='/registro'
					element={
						<SoloInvitados>
							<Registro />
						</SoloInvitados>
					}
				/>
				<Route path='*' element={<Navigate to='/' replace />} />
			</Routes>

			<footer className='site-footer'>
				<p>© {new Date().getFullYear()} ByteBooks · Libros de programación</p>
			</footer>
		</>
	);
}

export default App;