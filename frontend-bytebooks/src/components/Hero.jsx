import { Icon } from './Icon';

export const Hero = ({ busqueda, setBusqueda, totalLibros }) => (
	<section className='hero'>
		<div className='hero-inner'>
			<p className='hero-kicker'>&lt;/&gt; Librería de programación</p>
			<h1>Encontrá tu próximo libro para seguir aprendiendo</h1>
			<p className='hero-sub'>
				Libros de desarrollo web, bases de datos, inteligencia artificial y mucho más...
			</p>

			<form className='search' role='search' onSubmit={(e) => e.preventDefault()}>
				<Icon name='search' size={22} className='search-icon' />
				<input
					type='search'
					value={busqueda}
					onChange={(e) => setBusqueda(e.target.value)}
					placeholder='Buscá por título, autor, editorial o edición...'
					aria-label='Buscar libros'
				/>
				{busqueda && (
					<button type='button' className='search-clear' onClick={() => setBusqueda('')} aria-label='Borrar búsqueda'>
						<Icon name='close' size={18} />
					</button>
				)}
			</form>
		</div>
	</section>
);
