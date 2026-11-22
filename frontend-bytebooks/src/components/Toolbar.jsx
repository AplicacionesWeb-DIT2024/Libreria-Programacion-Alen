import { Icon } from './Icon';
import { OPCIONES_ORDEN, formatearPrecio } from '../utils/libros';

const ETIQUETAS = {
	categoria: 'Categoría',
	subcategoria: 'Subcategoría',
	editorial: 'Editorial',
	autor: 'Autor',
	precioMin: 'Desde',
	precioMax: 'Hasta',
};

export const Toolbar = ({ resultados, orden, setOrden, filtros, setFiltro, busqueda, setBusqueda, onAbrirFiltros, cantidadFiltros }) => {
	const chips = Object.entries(filtros).filter(([, v]) => v !== '');

	return (
		<div className='toolbar'>
			<div className='toolbar-row'>
				<p className='results-count' aria-live='polite'>
					<strong>{resultados}</strong> {resultados === 1 ? 'libro encontrado' : 'libros encontrados'}
				</p>

				<div className='toolbar-controls'>
					<button type='button' className='btn btn-ghost filters-toggle' onClick={onAbrirFiltros}>
						<Icon name='filter' size={18} /> Filtros
						{cantidadFiltros > 0 && <span className='badge badge-inline'>{cantidadFiltros}</span>}
					</button>

					<div className='sort'>
						<label htmlFor='orden'>Ordenar por</label>
						<div className='select-wrap'>
							<select id='orden' value={orden} onChange={(e) => setOrden(e.target.value)}>
								{OPCIONES_ORDEN.map((o) => (
									<option key={o.valor} value={o.valor}>
										{o.texto}
									</option>
								))}
							</select>
							<Icon name='chevronDown' size={16} className='select-arrow' />
						</div>
					</div>
				</div>
			</div>

			{(chips.length > 0 || busqueda) && (
				<ul className='chips' aria-label='Filtros activos'>
					{busqueda && (
						<li>
							<button type='button' className='chip' onClick={() => setBusqueda('')}>
								“{busqueda}” <Icon name='close' size={14} />
							</button>
						</li>
					)}
					{chips.map(([clave, valor]) => (
						<li key={clave}>
							<button type='button' className='chip' onClick={() => setFiltro(clave, '')}>
								{ETIQUETAS[clave]}: {clave.startsWith('precio') ? formatearPrecio(valor) : valor}
								<Icon name='close' size={14} />
							</button>
						</li>
					))}
				</ul>
			)}
		</div>
	);
};
