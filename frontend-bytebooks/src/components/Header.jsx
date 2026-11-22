import { useEffect, useRef, useState } from 'react';
import { Icon } from './Icon';
import { CartDropdown } from './CartDropdown';
import './Header.css';

const URL_LOGIN = import.meta.env.VITE_LOGIN_URL ?? '/login';
const URL_REGISTRO = import.meta.env.VITE_REGISTRO_URL ?? '/registro';

const useCerrarAlSalir = (abierto, cerrar) => {
	const ref = useRef(null);
	useEffect(() => {
		if (!abierto) return;
		const onClick = (e) => ref.current && !ref.current.contains(e.target) && cerrar();
		const onKey = (e) => e.key === 'Escape' && cerrar();
		document.addEventListener('mousedown', onClick);
		document.addEventListener('keydown', onKey);
		return () => {
			document.removeEventListener('mousedown', onClick);
			document.removeEventListener('keydown', onKey);
		};
	}, [abierto, cerrar]);
	return ref;
};

const iniciales = (nombre = '') =>
	nombre
		.split(' ')
		.slice(0, 2)
		.map((p) => p[0]?.toUpperCase())
		.join('');

export const Header = ({ carrito, usuario, onLogout }) => {
	const [carritoAbierto, setCarritoAbierto] = useState(false);
	const [menuUsuario, setMenuUsuario] = useState(false);
	const refCarrito = useCerrarAlSalir(carritoAbierto, () => setCarritoAbierto(false));
	const refUsuario = useCerrarAlSalir(menuUsuario, () => setMenuUsuario(false));

	return (
		<header className='site-header'>
			<div className='header-inner'>
				<a href='/' className='brand' aria-label='ByteBooks, ir al inicio'>
					<img src='/logo.png' alt='' className='brand-logo' width='48' height='48' />
					<span className='brand-name'>
						Byte<span>Books</span>
					</span>
				</a>

				<div className='header-actions'>
					<div className='cart-wrapper' ref={refCarrito}>
						<button
							type='button'
							className='icon-btn'
							onClick={() => setCarritoAbierto((v) => !v)}
							aria-expanded={carritoAbierto}
							aria-label={`Carrito, ${carrito.cantidad} productos`}
						>
							<Icon name='cart' size={24} />
							{carrito.cantidad > 0 && <span className='badge'>{carrito.cantidad}</span>}
						</button>
						{carritoAbierto && <CartDropdown carrito={carrito} />}
					</div>

					{usuario ? (
						<div className='user-wrapper' ref={refUsuario}>
							<button
								type='button'
								className='user-btn'
								onClick={() => setMenuUsuario((v) => !v)}
								aria-expanded={menuUsuario}
							>
								<span className='avatar'>{iniciales(usuario.nombre)}</span>
								<span className='user-name'>{usuario.nombre}</span>
								<Icon name='chevronDown' size={16} />
							</button>
							{menuUsuario && (
								<div className='dropdown user-menu'>
									<a href='/mi-cuenta'>
										<Icon name='user' size={18} /> Mi cuenta
									</a>
									<button type='button' onClick={onLogout}>
										<Icon name='logout' size={18} /> Cerrar sesión
									</button>
								</div>
							)}
						</div>
					) : (
						<div className='auth-buttons'>
							<a href={URL_LOGIN} className='btn btn-ghost'>
								Iniciar sesión
							</a>
							<a href={URL_REGISTRO} className='btn btn-primary'>
								Registrarse
							</a>
						</div>
					)}
				</div>
			</div>
		</header>
	);
};
