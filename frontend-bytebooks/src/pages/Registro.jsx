import { useRef, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Campo, InputPassword, ariaCampo } from '../components/Campo';
import { Icon } from '../components/Icon';
import {PROVINCIAS,fechaMaximaNacimiento,prepararRegistro,requisitosPassword,validarCampo,validarRegistro,} from '../utils/validaciones';
import './Auth.css';

const INICIAL = {
	nombre: '',
	apellido: '',
	fecha_nacimiento: '',
	telefono: '',
	username: '',
	email: '',
	password: '',
	password_confirmation: '',
	domicilio_calle: '',
	domicilio_numero: '',
	domicilio_localidad: '',
	domicilio_provincia: '',
	domicilio_codigo_postal: '',
};

export const Registro = () => {
	const { registrar } = useAuth();
	const navigate = useNavigate();
	const formRef = useRef(null);

	const [datos, setDatos] = useState(INICIAL);
	const [errores, setErrores] = useState({});
	const [tocados, setTocados] = useState({});
	const [errorGeneral, setErrorGeneral] = useState('');
	const [enviando, setEnviando] = useState(false);

	const cambiar = (e) => {
		const { name, value } = e.target;
		const nuevos = { ...datos, [name]: value };
		setDatos(nuevos);
		setErrorGeneral('');
		setErrores((er) => {
			const act = { ...er };
			if (tocados[name]) act[name] = validarCampo(name, nuevos);
			if (name === 'password' && tocados.password_confirmation) {
				act.password_confirmation = validarCampo('password_confirmation', nuevos);
			}
			return act;
		});
	};

	const salir = (e) => {
		const { name } = e.target;
		setTocados((t) => ({ ...t, [name]: true }));
		setErrores((er) => ({ ...er, [name]: validarCampo(name, datos) }));
	};

	const enfocarPrimerError = (errs) => {
		const primero = Object.keys(INICIAL).find((campo) => errs[campo]);
		if (primero) formRef.current?.querySelector(`[name="${primero}"]`)?.focus();
	};

	const enviar = async (e) => {
		e.preventDefault();
		const errs = validarRegistro(datos);
		setErrores(errs);
		setTocados(Object.fromEntries(Object.keys(INICIAL).map((k) => [k, true])));
		if (Object.keys(errs).length) {
			enfocarPrimerError(errs);
			return;
		}

		setEnviando(true);
		try {
			await registrar(prepararRegistro(datos));
			navigate('/', { replace: true, state: { bienvenida: true } });
		} catch (err) {
			if (err.status === 422 && Object.keys(err.errores).length) {
				const delServidor = Object.fromEntries(
					Object.entries(err.errores).map(([campo, mensajes]) => [campo, mensajes[0]]),
				);
				setErrores(delServidor);
				enfocarPrimerError(delServidor);
				setErrorGeneral('Revisá los campos marcados.');
			} else {
				setErrorGeneral(err.message);
			}
		} finally {
			setEnviando(false);
		}
	};

	const props = (campo, ayuda) => ({
		...ariaCampo(campo, errores[campo], ayuda),
		value: datos[campo],
		onChange: cambiar,
		onBlur: salir,
	});

	const requisitos = requisitosPassword(datos.password);

	return (
		<main className='auth-page'>
			<div className='auth-card'>
				<Link to='/' className='auth-back'>
					<Icon name='arrowLeft' size={18} /> Volver a la tienda
				</Link>

				<div className='auth-head'>
					<img src='/logo.png' alt='' width='72' height='72' />
					<h1>Crear cuenta</h1>
					<p>Registrate para comprar libros y recibirlos en tu casa.</p>
				</div>

				{errorGeneral && (
					<div className='auth-alert' role='alert'>
						<Icon name='alert' size={20} /> {errorGeneral}
					</div>
				)}

				<form ref={formRef} onSubmit={enviar} noValidate className='auth-form'>
					<fieldset className='form-section'>
						<legend>Datos personales</legend>
						<div className='form-grid'>
							<Campo id='nombre' label='Nombre' error={errores.nombre}>
								<input {...props('nombre')} type='text' autoComplete='given-name' />
							</Campo>
							<Campo id='apellido' label='Apellido' error={errores.apellido}>
								<input {...props('apellido')} type='text' autoComplete='family-name' />
							</Campo>
							<Campo
								id='fecha_nacimiento'
								label='Fecha de nacimiento'
								error={errores.fecha_nacimiento}
								ayuda='Tenés que ser mayor de 18 años.'
							>
								<input
									{...props('fecha_nacimiento', 'Tenés que ser mayor de 18 años.')}
									type='date'
									min='1900-01-01'
									max={fechaMaximaNacimiento()}
									autoComplete='bday'
								/>
							</Campo>
							<Campo id='telefono' label='Teléfono' opcional error={errores.telefono}>
								<input
									{...props('telefono')}
									type='tel'
									inputMode='tel'
									autoComplete='tel'
									placeholder='Ej: 11 2345-6789'
								/>
							</Campo>
						</div>
					</fieldset>

					<fieldset className='form-section'>
						<legend>Tu cuenta</legend>
						<div className='form-grid'>
							<Campo
								id='username'
								label='Nombre de usuario'
								error={errores.username}
								ayuda='Entre 4 y 50 caracteres: letras, números, . _ -'
							>
								<input
									{...props('username', 'Entre 4 y 50 caracteres: letras, números, . _ -')}
									type='text'
									autoComplete='username'
									autoCapitalize='none'
									spellCheck='false'
								/>
							</Campo>
							<Campo id='email' label='Email' error={errores.email}>
								<input
									{...props('email')}
									type='email'
									autoComplete='email'
									autoCapitalize='none'
									spellCheck='false'
								/>
							</Campo>
							<Campo id='password' label='Contraseña' error={errores.password}>
								<InputPassword {...props('password')} autoComplete='new-password' maxLength={72} />
								<ul className='password-rules' aria-label='Requisitos de la contraseña'>
									{requisitos.map((r) => (
										<li key={r.texto} className={r.ok ? 'ok' : ''}>
											<Icon name={r.ok ? 'check' : 'minus'} size={14} strokeWidth={2.4} /> {r.texto}
										</li>
									))}
								</ul>
							</Campo>
							<Campo id='password_confirmation' label='Repetí la contraseña' error={errores.password_confirmation}>
								<InputPassword
									{...props('password_confirmation')}
									autoComplete='new-password'
									maxLength={72}
								/>
							</Campo>
						</div>
					</fieldset>

					<fieldset className='form-section'>
						<legend>Domicilio de entrega</legend>
						<div className='form-grid form-grid-address'>
							<Campo id='domicilio_calle' label='Calle' error={errores.domicilio_calle} className='span-calle'>
								<input {...props('domicilio_calle')} type='text' autoComplete='address-line1' />
							</Campo>
							<Campo id='domicilio_numero' label='Número' error={errores.domicilio_numero}>
								<input {...props('domicilio_numero')} type='text' placeholder='1234 o S/N' />
							</Campo>
							<Campo id='domicilio_localidad' label='Localidad' error={errores.domicilio_localidad} className='span-2'>
								<input {...props('domicilio_localidad')} type='text' autoComplete='address-level2' />
							</Campo>
							<Campo id='domicilio_provincia' label='Provincia' error={errores.domicilio_provincia} className='span-2'>
								<div className='select-wrap'>
									<select {...props('domicilio_provincia')} autoComplete='address-level1'>
										<option value=''>Elegí tu provincia</option>
										{PROVINCIAS.map((p) => (
											<option key={p} value={p}>
												{p}
											</option>
										))}
									</select>
									<Icon name='chevronDown' size={16} className='select-arrow' />
								</div>
							</Campo>
							<Campo id='domicilio_codigo_postal' label='Código postal' error={errores.domicilio_codigo_postal}>
								<input
									{...props('domicilio_codigo_postal')}
									type='text'
									inputMode='numeric'
									maxLength={4}
									autoComplete='postal-code'
									placeholder='Ej: 1425'
								/>
							</Campo>
						</div>
					</fieldset>

					<button type='submit' className='btn btn-primary btn-block auth-submit' disabled={enviando}>
						{enviando ? 'Creando tu cuenta…' : 'Crear cuenta'}
					</button>
				</form>

				<p className='auth-switch'>
					¿Ya tenés cuenta? <Link to='/login'>Iniciá sesión</Link>
				</p>
			</div>
		</main>
	);
};
