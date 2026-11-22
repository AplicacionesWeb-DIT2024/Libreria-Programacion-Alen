import { useEffect, useRef, useState } from 'react';
import { Icon } from './Icon';
import { Link } from 'react-router-dom';
import { CartDropdown } from './CartDropdown';
import { useAuth } from '../context/AuthContext';
import { createPortal } from 'react-dom';
import './Header.css';


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

export const Header = ({ carrito }) => {
	const { usuario, logout } = useAuth();
	const [carritoAbierto, setCarritoAbierto] = useState(false);
	const [menuUsuario, setMenuUsuario] = useState(false);
	const [cerrando, setCerrando] = useState(false);
	const [aviso, setAviso] = useState('');
	const refCarrito = useCerrarAlSalir(carritoAbierto, () => setCarritoAbierto(false));
	const refUsuario = useCerrarAlSalir(menuUsuario && !cerrando, () => setMenuUsuario(false));

	const cerrarSesion = async () => {
		setCerrando(true);
		await logout();
		setCerrando(false);
		setMenuUsuario(false);
		setAviso('Cerraste sesión. ¡Hasta pronto!');
	};

	useEffect(() => {
		if (!aviso) return;
		const t = setTimeout(() => setAviso(''), 3000);
		return () => clearTimeout(t);
	}, [aviso]);

	return (
		<header className='site-header'>
			<div className='header-inner'>
				<Link to='/' className='brand' aria-label='ByteBooks, ir al inicio'>
					<img src='/logo.png' alt='' className='brand-logo' width='48' height='48' />
					<span className='brand-name'>
						Byte<span>Books</span>
					</span>
				</Link>

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
						<div className={`user-wrapper ${cerrando ? 'is-leaving' : ''}`} ref={refUsuario}>
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
									<div className='user-menu-info'>
										<strong>{usuario.nombre}</strong>
										<span>{usuario.email}</span>
									</div>
									<button
										type='button'
										className='logout-btn'
										onClick={cerrarSesion}
										disabled={cerrando}
										aria-busy={cerrando}
									>
										{cerrando ? (
											<>
												<span className='spinner' aria-hidden='true' /> Cerrando sesión…
											</>
										) : (
											<>
												<Icon name='logout' size={18} /> Cerrar sesión
											</>
										)}
									</button>
								</div>
							)}
						</div>
					) : (
						<div className='auth-buttons'>
							<Link to='/login' className='btn btn-ghost btn-login' aria-label='Iniciar sesión'>
								<Icon name='user' size={18} />
								<span>Iniciar sesión</span>
							</Link>
							<Link to='/registro' className='btn btn-primary'>
								Registrarse
							</Link>
						</div>
					)}
				</div>
			</div>
			{aviso &&
				createPortal(
					<div className='toast' role='status'>
						<Icon name='check' size={18} strokeWidth={2.4} />
						{aviso}
					</div>,
					document.body,
				)}
		</header>
	);
};
