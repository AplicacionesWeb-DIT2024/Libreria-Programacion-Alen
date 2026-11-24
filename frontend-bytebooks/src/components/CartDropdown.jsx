import { useEffect, useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Icon } from './Icon';
import { formatearPrecio } from '../utils/libros';
import { useCuentaRegresiva } from '../hooks/useCuentaRegresiva';
import { crearPedidoApi } from '../services/api';

export const CartDropdown = ({ carrito, onCerrar }) => {
	const { items, total, cambiarCantidad, quitar, vaciar, pedido, registrarPedido } = carrito;
	const { usuario, token } = useAuth();
	const navigate = useNavigate();
	const location = useLocation();

	const [procesando, setProcesando] = useState(false);
	const [error, setError] = useState('');
	const tiempoRestante = useCuentaRegresiva(pedido?.venceEn);

	const [sinStock, setSinStock] = useState(null);

	useEffect(() => {
		setSinStock(null);
		setError('');
	}, [items]);

	const finalizarCompra = async () => {
		setSinStock(null);

		if (!usuario) {
			onCerrar?.();
			navigate('/login', { state: { desde: location.pathname, motivo: 'checkout' } });
			return;
		}

		if (pedido) {
			setProcesando(true);
			window.location.href = pedido.initPoint;
			return;
		}

		const cliente = usuario.cliente;
		if (!cliente) {
			setError('No encontramos sus datos de envío. Cierre sesión y vuelva a ingresar.');
			return;
		}

		setProcesando(true);
		setError('');

		try {
			const datos = await crearPedidoApi(
				{
					detalles: items.map((i) => ({ libro: i.id, cantidad: i.quantity })),
					domicilio_calle: cliente.domicilio_calle,
					domicilio_numero: cliente.domicilio_numero,
					domicilio_localidad: cliente.domicilio_localidad,
					domicilio_provincia: cliente.domicilio_provincia,
					domicilio_codigo_postal: cliente.domicilio_codigo_postal,
				},
				token,
			);

			if (!datos?.init_point) {
				throw new Error('No se pudo iniciar el pago. Intente de nuevo.');
			}

			registrarPedido(datos);

			window.location.href = datos.init_point;
		} catch (err) {
			if (err.status === 409 && err.datos?.libro) {
				setSinStock({ libro: err.datos.libro, disponible: err.datos.disponible });
			}
			setError(err.message);
			setProcesando(false);
		}
	};

	if (items.length === 0) {
		return (
			<div className='dropdown cart-dropdown'>
				<div className='cart-empty'>
					<Icon name='cart' size={40} strokeWidth={1.3} />
					<p>Tu carrito está vacío</p>
					<span>Agregue libros desde el catálogo.</span>
				</div>
			</div>
		);
	}

	return (
		<div className='dropdown cart-dropdown'>
			<h3 className='cart-title'>Tu carrito</h3>
			<ul className='cart-list'>
				{items.map((item) => (
					<li key={item.id} className='cart-item'>
						{item.imagen ? (
							<img src={item.imagen} alt='' className='cart-thumb' />
						) : (
							<span className='cart-thumb cart-thumb-empty'>
								<Icon name='book' />
							</span>
						)}
						<div className='cart-info'>
							<p className='cart-name'>{item.nombre}</p>
							<p className='cart-price'>{formatearPrecio(item.precio * item.quantity)}</p>
							{sinStock?.libro === item.id && (
								<p className='cart-error'>
									{sinStock.disponible === 0
										? 'Sin stock: quítelo para continuar.'
										: `Solo quedan ${sinStock.disponible}: ajuste la cantidad.`}
								</p>
							)}
							<div className='qty'>
								<button
									type='button'
									onClick={() => cambiarCantidad(item.id, -1)}
									aria-label='Restar uno'
									disabled={procesando}
								>
									<Icon name='minus' size={14} />
								</button>
								<span>{item.quantity}</span>
								<button
									type='button'
									onClick={() => cambiarCantidad(item.id, 1)}
									aria-label='Sumar uno'
									disabled={procesando}
								>
									<Icon name='plus' size={14} />
								</button>
							</div>
						</div>
						<button
							type='button'
							className='icon-btn icon-btn-sm'
							onClick={() => quitar(item.id)}
							aria-label={`Quitar ${item.nombre}`}
							disabled={procesando}
						>
							<Icon name='trash' size={18} />
						</button>
					</li>
				))}
			</ul>
			<div className='cart-footer'>
				<div className='cart-total'>
					<span>Total</span>
					<strong>{formatearPrecio(total)}</strong>
				</div>

				{error && (
					<p className='cart-error' role='alert'>
						{error}
					</p>
				)}

				<button
					type='button'
					className='btn btn-primary btn-block'
					onClick={finalizarCompra}
					disabled={procesando}
				>
					{procesando
						? 'Redirigiendo a Mercado Pago…'
						: pedido
							? `Reintentar pago (${tiempoRestante})`
							: 'Finalizar compra'}
				</button>
				{pedido && (
					<p className='cart-login-hint'>
						Si modifica el carrito, el pedido pendiente se descarta y se arma uno nuevo.
					</p>
				)}
				{!usuario && <p className='cart-login-hint'>Debe iniciar sesión para terminar la compra.</p>}
				<button type='button' className='btn btn-link' onClick={vaciar} disabled={procesando}>
					Vaciar carrito
				</button>
			</div>
		</div>
	);
};