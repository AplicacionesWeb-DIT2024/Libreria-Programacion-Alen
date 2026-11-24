
const BASE = `${import.meta.env.VITE_API_URL}/api/api`;

export const RUTAS = {
	libros: '/libros',
	login: '/loginApi',
	registro: '/registro',
	logout: '/logoutApi',
	crearPedido: '/crearPedido',
};

export class ApiError extends Error {
	constructor(status, mensaje, errores = {}, datos = {}) {
		super(mensaje);
		this.status = status;
		this.errores = errores;
		this.datos = datos;
	}
}

const request = async (ruta, { method = 'GET', body, token, signal } = {}) => {
	let res;
	try {
		res = await fetch(`${BASE}${ruta}`, {
			method,
			signal,
			headers: {
				Accept: 'application/json',
				...(body ? { 'Content-Type': 'application/json' } : {}),
				...(token ? { Authorization: `Bearer ${token}` } : {}),
			},
			body: body ? JSON.stringify(body) : undefined,
		});
	} catch (e) {
		if (e.name === 'AbortError') throw e;
		throw new ApiError(0, 'No pudimos conectarnos con el servidor. Revise su conexión e intente de nuevo.');
	}

	const data = await res.json().catch(() => null);

	if (!res.ok) {
		const mensaje =
			data?.error ??
			data?.message ??
			(res.status === 429 ? 'Demasiados intentos. Espere un minuto y vuelva a probar.' : `Error ${res.status}`);
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

export const crearPedidoApi = (pedido, token) => request(RUTAS.crearPedido, { method: 'POST', body: pedido, token });
