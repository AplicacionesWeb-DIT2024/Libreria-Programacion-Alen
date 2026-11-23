import lista from './paises_telefono.json';

export const PAISES = lista;

export const PAIS_POR_DEFECTO = 'AR';

export const buscarPais = (codigo) =>
	PAISES.find((p) => p.codigo === codigo) ?? PAISES.find((p) => p.codigo === PAIS_POR_DEFECTO);

export const limpiarTelefono = (valor = '') => valor.replace(/\D+/g, '').replace(/^0+/, '');

export const urlBandera = (codigo) => `https://flagcdn.com/${codigo.toLowerCase()}.svg`;