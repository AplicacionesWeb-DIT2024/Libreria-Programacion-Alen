
const aTexto = (valor) => {
	if (valor == null) return '';
	if (Array.isArray(valor)) return valor.map(aTexto).filter(Boolean).join(', ');
	if (typeof valor === 'object') return valor.nombre ?? valor.name ?? valor.titulo ?? '';
	return String(valor);
};

const aAnio = (valor) => {
	if (valor == null || valor === '') return null;
	const match = String(valor).match(/\d{4}/);
	return match ? Number(match[0]) : null;
};

export const normalizarLibro = (raw) => ({
	...raw,
	id: raw.id,
	nombre: aTexto(raw.nombre ?? raw.titulo),
	precio: Number(raw.precio) || 0,
	autor: aTexto(raw.autor ?? raw.autores),
	editorial: aTexto(raw.editorial),
	categoria: aTexto(raw.categoria),
	subcategoria: aTexto(raw.subcategoria),
	anio: aAnio(raw.anio_publicacion ?? raw.anio ?? raw.fecha_publicacion),
	imagenes: [raw.imagen_original, raw.imagen_referencia_2, raw.imagen_referencia_3].filter(Boolean),
});

export const sinTildes = (texto) =>
	String(texto ?? '')
		.normalize('NFD')
		.replace(/[̀-ͯ]/g, '')
		.toLowerCase()
		.trim();

const unicosOrdenados = (valores) =>
	[...new Set(valores.filter(Boolean))].sort((a, b) => a.localeCompare(b, 'es'));


export const obtenerOpciones = (libros, categoriaElegida) => ({
	categorias: unicosOrdenados(libros.map((l) => l.categoria)),
	subcategorias: unicosOrdenados(
		libros
			.filter((l) => !categoriaElegida || l.categoria === categoriaElegida)
			.map((l) => l.subcategoria),
	),
	editoriales: unicosOrdenados(libros.map((l) => l.editorial)),
	autores: unicosOrdenados(libros.flatMap((l) => l.autor.split(',').map((a) => a.trim()))),
});

export const FILTROS_INICIALES = {
	categoria: '',
	subcategoria: '',
	editorial: '',
	autor: '',
	precioMin: '',
	precioMax: '',
};

export const OPCIONES_ORDEN = [
	{ valor: 'relevancia', texto: 'Más relevantes' },
	{ valor: 'precio-asc', texto: 'Precio: menor a mayor' },
	{ valor: 'precio-desc', texto: 'Precio: mayor a menor' },
	{ valor: 'anio-desc', texto: 'Año: más recientes' },
	{ valor: 'anio-asc', texto: 'Año: más antiguos' },
	{ valor: 'editorial-asc', texto: 'Editorial: A → Z' },
	{ valor: 'editorial-desc', texto: 'Editorial: Z → A' },
	{ valor: 'nombre-asc', texto: 'Título: A → Z' },
];

export const filtrarLibros = (libros, busqueda, filtros) => {
	const q = sinTildes(busqueda);
	const min = filtros.precioMin === '' ? -Infinity : Number(filtros.precioMin);
	const max = filtros.precioMax === '' ? Infinity : Number(filtros.precioMax);

	return libros.filter((l) => {
		if (filtros.categoria && l.categoria !== filtros.categoria) return false;
		if (filtros.subcategoria && l.subcategoria !== filtros.subcategoria) return false;
		if (filtros.editorial && l.editorial !== filtros.editorial) return false;
		if (filtros.autor && !l.autor.split(',').map((a) => a.trim()).includes(filtros.autor)) return false;
		if (l.precio < min || l.precio > max) return false;
		if (!q) return true;
		const texto = sinTildes([l.nombre, l.autor, l.editorial, l.categoria, l.subcategoria, l.isbn].join(' '));
		return q.split(/\s+/).every((palabra) => texto.includes(palabra));
	});
};

const comparar = {
	'precio-asc': (a, b) => a.precio - b.precio,
	'precio-desc': (a, b) => b.precio - a.precio,
	'anio-desc': (a, b) => (b.anio ?? -Infinity) - (a.anio ?? -Infinity),
	'anio-asc': (a, b) => (a.anio ?? Infinity) - (b.anio ?? Infinity),
	'editorial-asc': (a, b) => a.editorial.localeCompare(b.editorial, 'es'),
	'editorial-desc': (a, b) => b.editorial.localeCompare(a.editorial, 'es'),
	'nombre-asc': (a, b) => a.nombre.localeCompare(b.nombre, 'es'),
};

export const ordenarLibros = (libros, orden) =>
	comparar[orden] ? [...libros].sort(comparar[orden]) : libros;

export const formatearPrecio = (valor) =>
	new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS', maximumFractionDigits: 0 }).format(valor);
