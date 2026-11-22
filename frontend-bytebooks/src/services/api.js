
const BASE = `${import.meta.env.VITE_API_URL}/api/api`;

export const RUTAS = {
	libros: '/libros',
	login: '/loginApi',
	registro: '/registro',
	logout: '/logout',
};

export class ApiError extends Error {
	constructor(status, mensaje, errores = {}) {
		super(mensaje);
		this.status = status;
		this.errores = errores;
	}
}

const request = async (ruta, { method = 'GET', body, token, signal } = {}) => {
	let res;
	try {
		res = await fetch(`${BASE}${ruta}`, {
			method,
			signal,
			headers: {
				// Sin "Accept: application/json", Laravel responde los errores
				// de validación con una redirección en vez de un JSON 422.
				Accept: 'application/json',
				...(body ? { 'Content-Type': 'application/json' } : {}),
				...(token ? { Authorization: `Bearer ${token}` } : {}),
			},
			body: body ? JSON.stringify(body) : undefined,
		});
	} catch (e) {
		if (e.name === 'AbortError') throw e;
		throw new ApiError(0, 'No pudimos conectarnos con el servidor. Revisá tu conexión e intentá de nuevo.');
	}

	const data = await res.json().catch(() => null);

	if (!res.ok) {
		const mensaje =
			data?.error ??
			data?.message ??
			(res.status === 429 ? 'Demasiados intentos. Esperá un minuto y volvé a probar.' : `Error ${res.status}`);
		throw new ApiError(res.status, mensaje, data?.errors ?? {});
	}

	return data;
};

export const getLibros = async (signal) => {
	const data = await request(RUTAS.libros, { signal });
	return Array.isArray(data) ? data : data?.data ?? [];
};

export const loginApi = (username, password) =>
	request(RUTAS.login, { method: 'POST', body: { username, password } });

export const registroApi = (datos) => request(RUTAS.registro, { method: 'POST', body: datos });

export const logoutApi = (token) => request(RUTAS.logout, { method: 'POST', token });
