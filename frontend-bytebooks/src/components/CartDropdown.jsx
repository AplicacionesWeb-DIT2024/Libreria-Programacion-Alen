import { useEffect, useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Icon } from './Icon';
import { formatearPrecio } from '../utils/libros';
import { useCuentaRegresiva } from '../hooks/useCuentaRegresiva';
import { crearPedidoApi } from '../services/api';
import { ModalConfirmacion } from './ModalConfirmacion';
import {
	CAMPOS_DOMICILIO,
	DOMICILIO_VACIO,
	EnvioPedido,
	domicilioCompleto,
	domicilioDelCliente,
	idCampoEnvio,
	limpiarDomicilio,
	textoDomicilio,
	validarDomicilio,
} from './EnvioPedido';

export const CartDropdown = ({ carrito, onCerrar }) => {
	const { items, total, cambiarCantidad, quitar, vaciar, pedido, registrarPedido } = carrito;
	const { usuario, token } = useAuth();
	const navigate = useNavigate();
	const location = useLocation();

	const [procesando, setProcesando] = useState(false);
	const [error, setError] = useState('');
	const tiempoRestante = useCuentaRegresiva(pedido?.venceEn);

	const [sinStock, setSinStock] = useState(null);

	const [confirmando, setConfirmando] = useState(false);

	const [tipoEnvio, setTipoEnvio] = useState('propio');
	const [otroDomicilio, setOtroDomicilio] = useState(DOMICILIO_VACIO);
	const [erroresEnvio, setErroresEnvio] = useState({});

	const domicilioElegido =
		tipoEnvio === 'propio' ? domicilioDelCliente(usuario?.cliente) : limpiarDomicilio(otroDomicilio);

	const cambiarOtroDomicilio = (campo, valor) => {
		setOtroDomicilio((d) => ({ ...d, [campo]: valor }));
		setErroresEnvio((e) => ({ ...e, [campo]: undefined }));
	};

	const enfocarPrimerError = (errores) => {
		const primero = CAMPOS_DOMICILIO.find((c) => errores[c]);
		if (primero) document.getElementById(idCampoEnvio(primero))?.focus();
	};

	useEffect(() => {
		const alVolver = (e) => {
			if (e.persisted) {
				setProcesando(false);
				setConfirmando(false);
			}
		};
		window.addEventListener('pageshow', alVolver);
		return () => window.removeEventListener('pageshow', alVolver);
	}, []);

	useEffect(() => {
		setSinStock(null);
		setError('');
	}, [items]);

	const finalizarCompra = () => {
		setSinStock(null);
		setError('');

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

		if (!usuario.cliente) {
			setError('No encontramos sus datos de envío. Cierre sesión y vuelva a ingresar.');
			return;
		}

		setTipoEnvio('propio');
		setErroresEnvio({});
		setConfirmando(true);
	};

	const confirmarCompra = async () => {
		if (tipoEnvio === 'otro') {
			const errores = validarDomicilio(domicilioElegido);
			if (Object.keys(errores).length > 0) {
				setErroresEnvio(errores);
				enfocarPrimerError(errores);
				return;
			}
		}

		setProcesando(true);
		setError('');
		setErroresEnvio({});

		try {
			const datos = await crearPedidoApi(
				{
					detalles: items.map((i) => ({ libro: i.id, cantidad: i.quantity })),
					...domicilioElegido,
				},
				token,
			);

			if (!datos?.init_point) {
				throw new Error('No se pudo iniciar el pago. Intente de nuevo.');
			}

			registrarPedido(datos);

			window.location.href = datos.init_point;
		} catch (err) {
			setProcesando(false);

			const erroresDomicilio = Object.fromEntries(
				Object.entries(err.errores ?? {})
					.filter(([campo]) => CAMPOS_DOMICILIO.includes(campo))
					.map(([campo, mensajes]) => [campo, mensajes[0]]),
			);
			if (err.status === 422 && tipoEnvio === 'otro' && Object.keys(erroresDomicilio).length > 0) {
				setErroresEnvio(erroresDomicilio);
				enfocarPrimerError(erroresDomicilio);
				return;
			}
			setConfirmando(false);

			if (err.status === 409 && err.datos?.libro) {
				setSinStock({ libro: err.datos.libro, disponible: err.datos.disponible });
			}
			setError(err.message);
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
										: 'No hay stock suficiente: reduzca la cantidad.'}
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
						Si modifica o vacía el carrito, el pedido pendiente se cancela.
					</p>
				)}
				{!usuario && <p className='cart-login-hint'>Debe iniciar sesión para terminar la compra.</p>}
				<button type='button' className='btn btn-link' onClick={vaciar} disabled={procesando}>
					Vaciar carrito
				</button>
			</div>

			<ModalConfirmacion
				abierto={confirmando}
				titulo='¿Está seguro de querer realizar la compra?'
				textoConfirmar='Confirmar y pagar'
				textoProcesando='Redirigiendo a Mercado Pago…'
				procesando={procesando}
				onConfirmar={confirmarCompra}
				onCancelar={() => setConfirmando(false)}
			>
				<ul className='modal-resumen'>
					{items.map((item) => (
						<li key={item.id}>
							<span>
								{item.nombre}
								{item.quantity > 1 && <span className='modal-cantidad'> × {item.quantity}</span>}
							</span>
							<span>{formatearPrecio(item.precio * item.quantity)}</span>
						</li>
					))}
				</ul>

				<div className='modal-total'>
					<span>Total</span>
					<strong>{formatearPrecio(total)}</strong>
				</div>

				<EnvioPedido
					cliente={usuario?.cliente}
					tipo={tipoEnvio}
					onTipo={(tipo) => {
						setTipoEnvio(tipo);
						setErroresEnvio({});
					}}
					otro={otroDomicilio}
					onCambiarOtro={cambiarOtroDomicilio}
					errores={erroresEnvio}
					deshabilitado={procesando}
				/>

				<p className='modal-nota' aria-live='polite'>
					<strong>Envío a:</strong>{' '}
					{domicilioCompleto(domicilioElegido) ? (
						textoDomicilio(domicilioElegido)
					) : (
						<span className='modal-nota-pendiente'>complete la dirección de entrega</span>
					)}
				</p>

				<p className='modal-nota'>Será redirigido a Mercado Pago. Se le reservarán los libros durante 20 minutos.</p>
			</ModalConfirmacion>
		</div>
	);
};