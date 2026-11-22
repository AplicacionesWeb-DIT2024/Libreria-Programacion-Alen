import { createContext, useContext, useState } from 'react';
import { loginApi, logoutApi, registroApi } from '../services/api';

const CLAVE = 'bytebooks-sesion';
const AuthContext = createContext(null);

const leerSesion = () => {
	try {
		return JSON.parse(localStorage.getItem(CLAVE));
	} catch {
		return null;
	}
};

const guardarSesion = (sesion) => {
	try {
		if (sesion) localStorage.setItem(CLAVE, JSON.stringify(sesion));
		else localStorage.removeItem(CLAVE);
	} catch {
	}
};

const aSesion = ({ token, user }) => ({
	token,
	usuario: {
		id: user.id,
		username: user.username,
		email: user.email,
		nombre: [user.name, user.apellido].filter(Boolean).join(' '),
		cliente: user.cliente ?? null,
	},
});

export const AuthProvider = ({ children }) => {
	const [sesion, setSesion] = useState(leerSesion);

	const iniciar = (respuesta) => {
		const nueva = aSesion(respuesta);
		guardarSesion(nueva);
		setSesion(nueva);
		return nueva.usuario;
	};

	const login = async (username, password) => iniciar(await loginApi(username, password));

	const registrar = async (datos) => iniciar(await registroApi(datos));

	const logout = async () => {
		const token = sesion?.token;

		const minimo = new Promise((r) => setTimeout(r, 600));

		const avisarServidor = token
			? Promise.race([logoutApi(token).catch(() => {}), new Promise((r) => setTimeout(r, 5000))])
			: null;

		await Promise.all([minimo, avisarServidor]);
		guardarSesion(null);
		setSesion(null);
	};

	return (
		<AuthContext.Provider
			value={{ usuario: sesion?.usuario ?? null, token: sesion?.token ?? null, login, registrar, logout }}
		>
			{children}
		</AuthContext.Provider>
	);
};

export const useAuth = () => {
	const ctx = useContext(AuthContext);
	if (!ctx) throw new Error('useAuth tiene que usarse dentro de <AuthProvider>');
	return ctx;
};