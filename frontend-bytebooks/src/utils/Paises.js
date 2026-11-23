import lista from './paises_telefono.json';

export const PAISES = lista;

export const PAIS_POR_DEFECTO = 'AR';

export const buscarPais = (codigo) =>
	PAISES.find((p) => p.codigo === codigo) ?? PAISES.find((p) => p.codigo === PAIS_POR_DEFECTO);

export const limpiarTelefono = (valor = '', codigoPais = PAIS_POR_DEFECTO) => {
	const crudo = valor.trim();
	let telefono = crudo.replace(/\D+/g, '');

	const prefijo = buscarPais(codigoPais).prefijo.replace('+', '');
	if (crudo.startsWith('+') && telefono.startsWith(prefijo)) {
		telefono = telefono.slice(prefijo.length);
	}

	return telefono.replace(/^0+/, '');
};

