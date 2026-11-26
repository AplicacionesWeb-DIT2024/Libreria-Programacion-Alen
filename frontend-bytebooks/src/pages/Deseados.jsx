import { Link } from 'react-router-dom';
import { Icon } from '../components/Icon';
import { ProductCard, Skeleton } from '../components/ProductList';
import { useDeseados } from '../context/DeseadosContext';

export const Deseados = ({ carrito }) => {
	const { items, cantidad, cargando } = useDeseados();
	const noDisponibles = items.filter((l) => l.disponible === false).length;

	return (
		<main className='deseados-page'>
			<div className='deseados-head'>
				<h1>Lista de deseados</h1>
				{cantidad > 0 && (
					<p>
						{cantidad === 1 ? '1 libro guardado' : `${cantidad} libros guardados`}
						{noDisponibles > 0 && ` · ${noDisponibles} no disponible${noDisponibles > 1 ? 's' : ''} por ahora`}
					</p>
				)}
			</div>

			{cargando ? (
				<div className='grid' aria-busy='true'>
					{Array.from({ length: 4 }, (_, i) => (
						<Skeleton key={i} />
					))}
				</div>
			) : cantidad === 0 ? (
				<div className='state'>
					<Icon name='heart' size={48} strokeWidth={1.2} />
					<h3>Todavía no guardó libros</h3>
					<p>Toque el corazón de un libro para guardarlo acá y comprarlo cuando desee.</p>
					<Link to='/' className='btn btn-primary'>
						Ir a la tienda
					</Link>
				</div>
			) : (
				<div className='grid'>
					{items.map((libro) => (
						<ProductCard
							key={libro.id}
							libro={libro}
							enCarrito={carrito.items.find((i) => i.id === libro.id)?.quantity ?? 0}
							onAgregar={carrito.agregar}
						/>
					))}
				</div>
			)}
		</main>
	);
};