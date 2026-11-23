import { Icon } from './Icon';
import { formatearPrecio } from '../utils/libros';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const RUTA_CHECKOUT = '/checkout';

export const CartDropdown = ({ carrito }) => {
	const { items, total, cambiarCantidad, quitar, vaciar } = carrito;
	const { usuario } = useAuth();
	const navigate = useNavigate();

	const finalizarCompra = () => {
		onCerrar?.();

		if (!usuario) {
			navigate('/login', { state: { desde: RUTA_CHECKOUT, motivo: 'checkout' } });
			return;
		}

		navigate(RUTA_CHECKOUT);
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
							<div className='qty'>
								<button type='button' onClick={() => cambiarCantidad(item.id, -1)} aria-label='Restar uno'>
									<Icon name='minus' size={14} />
								</button>
								<span>{item.quantity}</span>
								<button type='button' onClick={() => cambiarCantidad(item.id, 1)} aria-label='Sumar uno'>
									<Icon name='plus' size={14} />
								</button>
							</div>
						</div>
						<button
							type='button'
							className='icon-btn icon-btn-sm'
							onClick={() => quitar(item.id)}
							aria-label={`Quitar ${item.nombre}`}
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
				<button type='button' className='btn btn-primary btn-block' onClick={finalizarCompra}>
					Finalizar compra
				</button>
				{!usuario && <p className='cart-login-hint'>Debe iniciar sesión para terminar la compra.</p>}
				<button type='button' className='btn btn-link' onClick={vaciar}>
					Vaciar carrito
				</button>
			</div>
		</div>
	);
};
