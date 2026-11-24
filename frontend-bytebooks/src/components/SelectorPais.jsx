import { useEffect, useId, useMemo, useRef, useState } from 'react';
import { PAISES, buscarPais } from '../utils/Paises';
import { Icon } from './Icon';
import './SelectorPais.css';

const normalizar = (t) =>
	t
		.toLowerCase()
		.normalize('NFD')
		.replace(/[̀-ͯ]/g, '')
		.trim();

const Bandera = ({ codigo }) => <span className={`bandera fi fi-${codigo.toLowerCase()}`} aria-hidden='true' />;

export const SelectorPais = ({ name, value, onChange, onBlur, invalido = false, disabled = false }) => {
	const [abierto, setAbierto] = useState(false);
	const [filtro, setFiltro] = useState('');
	const [activo, setActivo] = useState(0);

	const raizRef = useRef(null);
	const botonRef = useRef(null);
	const buscadorRef = useRef(null);
	const listaRef = useRef(null);
	const listaId = useId();

	const actual = buscarPais(value);

	const opciones = useMemo(() => {
		const f = normalizar(filtro);
		if (!f) return PAISES;
		const digitos = f.replace(/\D/g, '');
		return PAISES.filter(
			(p) =>
				normalizar(p.nombre).includes(f) ||
				p.codigo.toLowerCase() === f ||
				(digitos && p.prefijo.slice(1).startsWith(digitos)),
		);
	}, [filtro]);

	const abrir = () => {
		if (disabled) return;
		setFiltro('');
		setActivo(Math.max(0, PAISES.findIndex((p) => p.codigo === actual.codigo)));
		setAbierto(true);
	};

	const cerrar = (devolverFoco = true) => {
		setAbierto(false);
		onBlur?.({ target: { name, value: actual.codigo } });
		if (devolverFoco) botonRef.current?.focus();
	};

	const elegir = (pais) => {
		onChange?.({ target: { name, value: pais.codigo } });
		setAbierto(false);
		onBlur?.({ target: { name, value: pais.codigo } });
		botonRef.current?.focus();
	};

	useEffect(() => {
		if (abierto) buscadorRef.current?.focus();
	}, [abierto]);

	useEffect(() => {
		if (!abierto) return;
		const alClickear = (e) => {
			if (!raizRef.current?.contains(e.target)) cerrar(false);
		};
		document.addEventListener('mousedown', alClickear);
		return () => document.removeEventListener('mousedown', alClickear);
	}, [abierto]);

	useEffect(() => {
		if (!abierto) return;
		listaRef.current?.querySelector(`[data-indice="${activo}"]`)?.scrollIntoView({ block: 'nearest' });
	}, [activo, abierto]);

	const teclaBuscador = (e) => {
		switch (e.key) {
			case 'ArrowDown':
				e.preventDefault();
				setActivo((i) => Math.min(i + 1, opciones.length - 1));
				break;
			case 'ArrowUp':
				e.preventDefault();
				setActivo((i) => Math.max(i - 1, 0));
				break;
			case 'Enter':
				e.preventDefault();
				if (opciones[activo]) elegir(opciones[activo]);
				break;
			case 'Escape':
				e.preventDefault();
				cerrar();
				break;
			case 'Tab':
				cerrar(false);
				break;
			default:
		}
	};

	const teclaBoton = (e) => {
		if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
			e.preventDefault();
			abrir();
		}
	};

	return (
		<div className={`selector-pais${invalido ? ' invalido' : ''}`} ref={raizRef}>
			<button
				ref={botonRef}
				type='button'
				name={name}
				className='selector-pais-boton'
				onClick={() => (abierto ? cerrar() : abrir())}
				onKeyDown={teclaBoton}
				disabled={disabled}
				aria-haspopup='listbox'
				aria-expanded={abierto}
				aria-label={`País del teléfono: ${actual.nombre} ${actual.prefijo}`}
			>
				<Bandera codigo={actual.codigo} />
				<span className='selector-pais-prefijo'>{actual.prefijo}</span>
				<Icon name='chevronDown' size={14} />
			</button>

			{abierto && (
				<div className='selector-pais-panel'>
					<input
						ref={buscadorRef}
						type='text'
						className='selector-pais-buscador'
						placeholder='Buscar país o código'
						value={filtro}
						onChange={(e) => {
							setFiltro(e.target.value);
							setActivo(0);
						}}
						onKeyDown={teclaBuscador}
						role='combobox'
						aria-expanded='true'
						aria-controls={listaId}
						aria-activedescendant={opciones[activo] ? `${listaId}-${opciones[activo].codigo}` : undefined}
						aria-autocomplete='list'
						autoComplete='off'
						spellCheck='false'
					/>
					<ul id={listaId} ref={listaRef} role='listbox' className='selector-pais-lista' aria-label='Países'>
						{opciones.length === 0 && <li className='selector-pais-vacio'>Sin resultados</li>}
						{opciones.map((p, i) => (
							<li
								key={p.codigo}
								id={`${listaId}-${p.codigo}`}
								data-indice={i}
								role='option'
								aria-selected={p.codigo === actual.codigo}
								className={`${i === activo ? 'activo' : ''} ${p.codigo === actual.codigo ? 'elegido' : ''}`}
								onMouseEnter={() => setActivo(i)}
								onMouseDown={(e) => e.preventDefault()}
								onClick={() => elegir(p)}
							>
								<Bandera codigo={p.codigo} />
								<span className='selector-pais-nombre'>{p.nombre}</span>
								<span className='selector-pais-prefijo'>{p.prefijo}</span>
							</li>
						))}
					</ul>
				</div>
			)}
		</div>
	);
};
