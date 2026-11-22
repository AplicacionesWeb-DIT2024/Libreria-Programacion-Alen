

export const PROVINCIAS = [
	'Buenos Aires', 'Ciudad Autónoma de Buenos Aires', 'Catamarca', 'Chaco', 'Chubut',
	'Córdoba', 'Corrientes', 'Entre Ríos', 'Formosa', 'Jujuy', 'La Pampa', 'La Rioja',
	'Mendoza', 'Misiones', 'Neuquén', 'Río Negro', 'Salta', 'San Juan', 'San Luis',
	'Santa Cruz', 'Santa Fe', 'Santiago del Estero', 'Tierra del Fuego', 'Tucumán',
];

const REGEX = {
	nombre: /^[\p{L}][\p{L}\s'’.-]*$/u,
	username: /^[A-Za-z0-9._-]+$/,
	email: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,
	telefono: /^\+?[0-9\s-]{6,20}$/,
	numero: /^([0-9]+[A-Za-z]?|[Ss]\/[Nn])$/,
	codigoPostal: /^\d{4}$/,
};

export const limpiar = (valor) => String(valor ?? '').trim().replace(/\s+/g, ' ');

const aISO = (fecha) =>
	`${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}-${String(fecha.getDate()).padStart(2, '0')}`;

export const fechaMaximaNacimiento = () => {
	const hoy = new Date();
	const limite = new Date(hoy.getFullYear() - 18, hoy.getMonth(), hoy.getDate());
	if (limite.getMonth() !== hoy.getMonth()) limite.setDate(0);
	return aISO(limite);
};

export const requisitosPassword = (password = '') => [
	{ ok: password.length >= 8, texto: 'Al menos 8 caracteres' },
	{ ok: /[a-z]/.test(password) && /[A-Z]/.test(password), texto: 'Mayúsculas y minúsculas' },
	{ ok: /\d/.test(password), texto: 'Al menos un número' },
];

const REGLAS = {
	nombre: (v) => {
		v = limpiar(v);
		if (!v) return 'Ingrese su nombre.';
		if (v.length > 255) return 'El nombre no puede superar los 255 caracteres.';
		if (!REGEX.nombre.test(v)) return 'El nombre solo puede contener letras, espacios, apóstrofes, puntos y guiones.';
	},
	apellido: (v) => {
		v = limpiar(v);
		if (!v) return 'Ingrese su apellido.';
		if (v.length > 255) return 'El apellido no puede superar los 255 caracteres.';
		if (!REGEX.nombre.test(v)) return 'El apellido solo puede contener letras, espacios, apóstrofes, puntos y guiones.';
	},
	fecha_nacimiento: (v) => {
		if (!v) return 'Ingrese su fecha de nacimiento.';
		if (!/^\d{4}-\d{2}-\d{2}$/.test(v) || v < '1900-01-01') return 'Ingrese una fecha de nacimiento válida.';
		if (v > fechaMaximaNacimiento()) return 'Debe ser mayor de 18 años para registrarte.';
	},
	telefono: (v) => {
		v = limpiar(v);
		if (v && !REGEX.telefono.test(v)) return 'Use solo números, espacios, guiones y un + al inicio (entre 6 y 20 caracteres).';
	},
	username: (v) => {
		v = limpiar(v);
		if (!v) return 'Elija un nombre de usuario.';
		if (v.length < 4) return 'El nombre de usuario debe tener al menos 4 caracteres.';
		if (v.length > 50) return 'El nombre de usuario no puede superar los 50 caracteres.';
		if (!REGEX.username.test(v)) return 'Use solo letras, números, puntos, guiones y guiones bajos (sin espacios).';
	},
	email: (v) => {
		v = String(v ?? '').trim();
		if (!v) return 'Ingrese su email.';
		if (v.length > 255) return 'El email no puede superar los 255 caracteres.';
		if (!REGEX.email.test(v)) return 'Ingrese un email válido.';
	},
	password: (v) => {
		if (!v) return 'Elija una contraseña.';
		if (v.length > 72) return 'La contraseña no puede superar los 72 caracteres.';
		if (requisitosPassword(v).some((r) => !r.ok)) return 'La contraseña no cumple los requisitos.';
	},
	password_confirmation: (v, datos) => {
		if (!v) return 'Repita la contraseña.';
		if (v !== datos.password) return 'Las contraseñas no coinciden.';
	},
	domicilio_calle: (v) => {
		v = limpiar(v);
		if (!v) return 'Ingrese la calle.';
		if (v.length > 255) return 'La calle no puede superar los 255 caracteres.';
	},
	domicilio_numero: (v) => {
		v = limpiar(v);
		if (!v) return 'Ingrese el número.';
		if (!REGEX.numero.test(v)) return 'Ej: 1234, 12B o S/N.';
	},
	domicilio_localidad: (v) => {
		v = limpiar(v);
		if (!v) return 'Ingrese la localidad.';
		if (v.length > 255) return 'La localidad no puede superar los 255 caracteres.';
	},
	domicilio_provincia: (v) => {
		if (!v) return 'Elija una provincia.';
		if (!PROVINCIAS.includes(v)) return 'Elegí una provincia de la lista.';
	},
	domicilio_codigo_postal: (v) => {
		v = limpiar(v);
		if (!v) return 'Ingrese el código postal.';
		if (!REGEX.codigoPostal.test(v)) return 'El código postal debe tener 4 números.';
	},
};

export const validarCampo = (campo, datos) => REGLAS[campo]?.(datos[campo], datos) ?? null;

export const validarRegistro = (datos) =>
	Object.fromEntries(
		Object.keys(REGLAS)
			.map((campo) => [campo, validarCampo(campo, datos)])
			.filter(([, error]) => error),
	);

export const prepararRegistro = (datos) => ({
	...Object.fromEntries(Object.entries(datos).map(([k, v]) => [k, k.startsWith('password') ? v : limpiar(v)])),
	email: String(datos.email).trim().toLowerCase(),
	telefono: limpiar(datos.telefono) || null,
});
