import { useState } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Campo, InputPassword, ariaCampo } from '../components/Campo';
import { Icon } from '../components/Icon';
import './Auth.css';

export const Login = () => {
	const { login } = useAuth();
	const navigate = useNavigate();
	const location = useLocation();
	const destino = location.state?.desde ?? '/';

	const [datos, setDatos] = useState({ username: '', password: '' });
	const [errores, setErrores] = useState({});
	const [errorGeneral, setErrorGeneral] = useState('');
	const [enviando, setEnviando] = useState(false);

	const cambiar = (e) => {
		const { name, value } = e.target;
		setDatos((d) => ({ ...d, [name]: value }));
		setErrores((er) => ({ ...er, [name]: null }));
		setErrorGeneral('');
	};

	const enviar = async (e) => {
		e.preventDefault();
		const nuevos = {
			username: datos.username.trim() ? null : 'Ingresá tu nombre de usuario.',
			password: datos.password ? null : 'Ingresá tu contraseña.',
		};
		setErrores(nuevos);
		if (nuevos.username || nuevos.password) return;

		setEnviando(true);
		try {
			await login(datos.username.trim(), datos.password);
			navigate(destino, { replace: true });
		} catch (err) {
			setErrorGeneral(err.message);
			setDatos((d) => ({ ...d, password: '' }));
		} finally {
			setEnviando(false);
		}
	};

	return (
		<main className='auth-page'>
			<div className='auth-card auth-card-sm'>
				<Link to='/' className='auth-back'>
					<Icon name='arrowLeft' size={18} /> Volver a la tienda
				</Link>

				<div className='auth-head'>
					<img src='/logo.png' alt='' width='72' height='72' />
					<h1>Iniciar sesión</h1>
					<p>Entrá a tu cuenta para comprar y seguir tus pedidos.</p>
				</div>

				{errorGeneral && (
					<div className='auth-alert' role='alert'>
						<Icon name='alert' size={20} /> {errorGeneral}
					</div>
				)}

				<form onSubmit={enviar} noValidate className='auth-form'>
					<Campo id='username' label='Nombre de usuario' error={errores.username}>
						<input
							{...ariaCampo('username', errores.username)}
							type='text'
							autoComplete='username'
							autoCapitalize='none'
							spellCheck='false'
							value={datos.username}
							onChange={cambiar}
							autoFocus
						/>
					</Campo>

					<Campo id='password' label='Contraseña' error={errores.password}>
						<InputPassword
							{...ariaCampo('password', errores.password)}
							autoComplete='current-password'
							value={datos.password}
							onChange={cambiar}
						/>
					</Campo>

					<button type='submit' className='btn btn-primary btn-block auth-submit' disabled={enviando}>
						{enviando ? 'Ingresando…' : 'Iniciar sesión'}
					</button>
				</form>

				<p className='auth-switch'>
					¿No tenés cuenta? <Link to='/registro'>Registrate</Link>
				</p>
			</div>
		</main>
	);
};
