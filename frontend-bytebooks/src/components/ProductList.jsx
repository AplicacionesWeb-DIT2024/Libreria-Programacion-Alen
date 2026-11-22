import { useState } from 'react';
import { ImageCarousel } from './ImageCarousel';
import { Icon } from './Icon';
import { formatearPrecio } from '../utils/libros';


const formatearAutores = (texto = '') => {
	const lista = texto.split(',').map((a) => a.trim()).filter(Boolean);
	if (lista.length <= 1) return lista[0] ?? '';
	return `${lista.slice(0, -1).join(', ')} y ${lista.at(-1)}`;
};

const ProductCard = ({ libro, enCarrito, onAgregar }) => {
	const [recienAgregado, setRecienAgregado] = useState(false);

	const agregar = () => {
		onAgregar(libro);
		setRecienAgregado(true);
		setTimeout(() => setRecienAgregado(false), 1200);
	};

	const categoria = [libro.categoria, libro.subcategoria].filter(Boolean).join(' · ');
	const autores = formatearAutores(libro.autor);
	const editorialAnio = [libro.editorial, libro.anio].filter(Boolean).join(' · ');

	return (
		<article className='card'>
			<figure className='card-media'>
				<ImageCarousel alt={libro.nombre} images={libro.imagenes} />
			</figure>

			<div className='card-body'>
				<h3 className='card-title' title={libro.nombre}>
					{libro.nombre}
				</h3>
				{categoria && <p className='card-category'>{categoria}</p>}
				{autores && (
					<p className='card-author' title={autores}>
						{autores}
					</p>
				)}
				{editorialAnio && <p className='card-meta'>{editorialAnio}</p>}
				{libro.edicion && <p className='card-meta'>{libro.edicion}ª edición</p>}

				<div className='card-footer'>
					<span className='card-price'>{formatearPrecio(libro.precio)}</span>
					<button
						type='button'
						className={`btn btn-primary btn-add ${recienAgregado ? 'is-added' : ''}`}
						onClick={agregar}
					>
						<Icon name={recienAgregado ? 'check' : 'cart'} size={18} />
						{recienAgregado ? 'Agregado' : 'Agregar'}
					</button>
				</div>
				{enCarrito > 0 && <p className='card-incart'>Ya tenés {enCarrito} en el carrito</p>}
			</div>
		</article>
	);
};
const Skeleton = () => (
	<div className='card card-skeleton' aria-hidden='true'>
		<div className='sk sk-img' />
		<div className='card-body'>
			<div className='sk sk-line' />
			<div className='sk sk-line sk-short' />
			<div className='sk sk-line sk-btn' />
		</div>
	</div>
);

export const ProductList = ({ libros, cargando, error, onReintentar, carrito, onLimpiar }) => {
	if (cargando) {
		return (
			<div className='grid' aria-busy='true'>
				{Array.from({ length: 6 }, (_, i) => (
					<Skeleton key={i} />
				))}
			</div>
		);
	}

	if (error) {
		return (
			<div className='state'>
				<Icon name='book' size={48} strokeWidth={1.2} />
				<h3>Algo salió mal</h3>
				<p>{error}</p>
				<button type='button' className='btn btn-primary' onClick={onReintentar}>
					Reintentar
				</button>
			</div>
		);
	}

	if (libros.length === 0) {
		return (
			<div className='state'>
				<Icon name='search' size={48} strokeWidth={1.2} />
				<h3>No encontramos libros</h3>
				<p>Probá con otras palabras o quitá algunos filtros.</p>
				<button type='button' className='btn btn-ghost' onClick={onLimpiar}>
					Limpiar búsqueda y filtros
				</button>
			</div>
		);
	}

	return (
		<div className='grid'>
			{libros.map((libro) => (
				<ProductCard
					key={libro.id}
					libro={libro}
					enCarrito={carrito.items.find((i) => i.id === libro.id)?.quantity ?? 0}
					onAgregar={carrito.agregar}
				/>
			))}
		</div>
	);
};
