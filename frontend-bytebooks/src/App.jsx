import { useEffect, useState } from 'react';
import { Navigate, Route, Routes, useLocation, useSearchParams } from 'react-router-dom';
import { Header } from './components/Header';
import { Tienda } from './pages/Tienda';
import { Login } from './pages/Login';
import { Registro } from './pages/Registro';
import { useCarrito } from './hooks/useCarrito';
import { useCuentaRegresiva } from './hooks/useCuentaRegresiva';
import { useAuth } from './context/AuthContext';
import { useToast } from './context/ToastContext';

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
	const mostrarToast = useToast();

	useEffect(() => {
		if (!pago) return;

		if (pago === 'aprobado') {
			completarPedido();
			mostrarToast('¡Pago aprobado! Su pedido fue registrado.', { duracion: 5000 });
		} else if (pago === 'pendiente') {
			completarPedido();
			mostrarToast('Su pago está en revisión. Le avisaremos cuando se acredite.', { duracion: 5000 });
		} else if (pago === 'rechazado') {
			mostrarToast('El pago no se pudo completar. Puede reintentarlo antes de que venza el pedido.', {
				tipo: 'error',
				duracion: 6000,
			});
		}

		setParams({}, { replace: true });
	}, [pago]);

	useEffect(() => {
		if (!pedidoVencido) return;
		mostrarToast('El tiempo para pagar venció y el pedido fue cancelado.', { tipo: 'error', duracion: 6000 });
		ocultarAvisoVencido();
	}, [pedidoVencido]);

	if (!pedido) return null;

	return (
		<div className='aviso-pago aviso-pago-error' role='status'>
			<span>
				Tiene un pago pendiente. Quedan <strong>{tiempoRestante}</strong> para completarlo; después el pedido se
				cancela y se vacía el carrito.
			</span>
			<div className='aviso-pago-acciones'>
				<button type='button' className='btn btn-primary' onClick={() => (window.location.href = pedido.initPoint)}>
					Reintentar pago
				</button>
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