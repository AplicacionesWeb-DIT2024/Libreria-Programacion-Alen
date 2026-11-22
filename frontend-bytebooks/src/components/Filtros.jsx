import { Icon } from './Icon';

const Selector = ({ id, label, valor, opciones, onChange, todos, disabled }) => (
	<div className='field'>
		<label htmlFor={id}>{label}</label>
		<div className='select-wrap'>
			<select id={id} value={valor} onChange={(e) => onChange(e.target.value)} disabled={disabled}>
				<option value=''>{todos}</option>
				{opciones.map((op) => (
					<option key={op} value={op}>
						{op}
					</option>
				))}
			</select>
			<Icon name='chevronDown' size={16} className='select-arrow' />
		</div>
	</div>
);

export const Filtros = ({ filtros, setFiltro, opciones, onLimpiar, hayFiltros, abierto, onCerrar }) => (
	<>
		<div className={`filters-backdrop ${abierto ? 'is-open' : ''}`} onClick={onCerrar} aria-hidden='true' />
		<aside className={`filters ${abierto ? 'is-open' : ''}`} aria-label='Filtros'>
			<div className='filters-head'>
				<h2>
					<Icon name='filter' size={20} /> Filtros
				</h2>
				<button type='button' className='icon-btn filters-close' onClick={onCerrar} aria-label='Cerrar filtros'>
					<Icon name='close' />
				</button>
			</div>

			<Selector
				id='f-categoria'
				label='Categoría'
				todos='Todas'
				valor={filtros.categoria}
				opciones={opciones.categorias}
				onChange={(v) => setFiltro('categoria', v)}
			/>
			<Selector
				id='f-subcategoria'
				label='Subcategoría'
				todos='Todas'
				valor={filtros.subcategoria}
				opciones={opciones.subcategorias}
				onChange={(v) => setFiltro('subcategoria', v)}
				disabled={opciones.subcategorias.length === 0}
			/>
			<Selector
				id='f-editorial'
				label='Editorial'
				todos='Todas'
				valor={filtros.editorial}
				opciones={opciones.editoriales}
				onChange={(v) => setFiltro('editorial', v)}
			/>
			<Selector
				id='f-autor'
				label='Autor'
				todos='Todos'
				valor={filtros.autor}
				opciones={opciones.autores}
				onChange={(v) => setFiltro('autor', v)}
			/>

			<fieldset className='field'>
				<legend>Precio</legend>
				<div className='price-range'>
					<input
						type='number'
						min='0'
						inputMode='numeric'
						placeholder='Mín.'
						value={filtros.precioMin}
						onChange={(e) => setFiltro('precioMin', e.target.value)}
						aria-label='Precio mínimo'
					/>
					<span>—</span>
					<input
						type='number'
						min='0'
						inputMode='numeric'
						placeholder='Máx.'
						value={filtros.precioMax}
						onChange={(e) => setFiltro('precioMax', e.target.value)}
						aria-label='Precio máximo'
					/>
				</div>
			</fieldset>

			<div className='filters-actions'>
				<button type='button' className='btn btn-ghost btn-block' onClick={onLimpiar} disabled={!hayFiltros}>
					Limpiar filtros
				</button>
				<button type='button' className='btn btn-primary btn-block filters-apply' onClick={onCerrar}>
					Ver resultados
				</button>
			</div>
		</aside>
	</>
);
