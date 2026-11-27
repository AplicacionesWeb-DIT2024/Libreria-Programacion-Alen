
const PASOS = [
	{ estado: 'Aceptada', titulo: 'Pago aprobado', texto: 'Recibimos su pago.' },
	{ estado: 'En preparación', titulo: 'En preparación', texto: 'Estamos preparando sus productos.' },
	{ estado: 'En distribución', titulo: 'En camino', texto: 'Su pedido se encuentra en viaje.' },
	{ estado: 'Recibido en domicilio', titulo: 'Entregado', texto: 'El pedido ha llegado a su domicilio.' },
];

export const ESTADOS_CON_SEGUIMIENTO = PASOS.map((p) => p.estado);

const formatearFechaHora = (iso) =>
	new Date(iso).toLocaleString('es-AR', {
		day: '2-digit',
		month: '2-digit',
		year: 'numeric',
		hour: '2-digit',
		minute: '2-digit',
	});

const Camion = () => (
	<svg className='camion' viewBox='0 0 64 40' width='56' height='35' aria-hidden='true'>
		<rect className='camion-caja' x='2' y='6' width='36' height='22' rx='3' />
		<path className='camion-cabina' d='M38 12h12l8 9v7H38z' />
		<path className='camion-ventana' d='M41 15h7.5l5 6H41z' />
		<rect className='camion-linea' x='7' y='15' width='22' height='3' rx='1.5' />
		<g className='camion-rueda' style={{ transformOrigin: '14px 30px' }}>
			<circle cx='14' cy='30' r='6' />
			<rect x='13' y='25' width='2' height='10' />
		</g>
		<g className='camion-rueda' style={{ transformOrigin: '48px 30px' }}>
			<circle cx='48' cy='30' r='6' />
			<rect x='47' y='25' width='2' height='10' />
		</g>
	</svg>
);


const posicion = (i) => ((i + 0.5) / PASOS.length) * 100;

const Tilde = () => (
	<svg viewBox='0 0 24 24' width='14' height='14' aria-hidden='true'>
		<path d='M5 12.5l4.5 4.5L19 7.5' fill='none' stroke='currentColor' strokeWidth='3' strokeLinecap='round' strokeLinejoin='round' />
	</svg>
);

export const SeguimientoPedido = ({ pedido }) => {
	const actual = PASOS.findIndex((p) => p.estado === pedido.estado);
	if (actual === -1) return null;

	// Fecha en que llegó a cada estado (la última vez, por si se repitió)
	const fechaDe = (estado) => [...(pedido.historial ?? [])].reverse().find((h) => h.estado === estado)?.fecha;

	const ultimo = PASOS.length - 1;
	const hayEnvio = actual >= 1;
	const enCamino = pedido.estado === 'En distribución';
	const entregado = actual === ultimo;

	return (
		<section className='seguimiento' aria-label='Seguimiento del pedido'>
			{!hayEnvio && (
				<p className='seguimiento-aviso'>Su pago fue aprobado. En breve vamos a empezar a preparar su pedido.</p>
			)}

			<div
				className={`ruta ${hayEnvio ? '' : 'sin-envio'} ${enCamino ? 'en-camino' : ''} ${entregado ? 'entregado' : ''}`}
				aria-hidden='true'
			>
				<div className='ruta-calle' style={{ left: `${posicion(0)}%`, right: `${100 - posicion(ultimo)}%` }} />
				<div
					className='ruta-progreso'
					style={{ left: `${posicion(0)}%`, width: `${posicion(actual) - posicion(0)}%` }}
				/>

				{PASOS.map((paso, i) => (
					<span
						key={paso.estado}
						className={`ruta-punto ${i <= actual ? 'lleno' : ''} ${entregado && i === ultimo ? 'final' : ''}`}
						style={{ left: `${posicion(i)}%` }}
					>
						{entregado && i === ultimo && <Tilde />}
					</span>
				))}

				{hayEnvio && (
					<div className='ruta-camion' style={{ left: `${posicion(actual)}%` }}>
						<Camion />
					</div>
				)}
			</div>

			<ol className='pasos'>
				{PASOS.map((paso, i) => {
					const fecha = fechaDe(paso.estado);
					const estadoPaso = i < actual ? 'hecho' : i === actual ? 'actual' : 'pendiente';

					return (
						<li key={paso.estado} className={`paso paso-${estadoPaso}`} aria-current={i === actual ? 'step' : undefined}>
							<p className='paso-titulo'>{paso.titulo}</p>
							{i <= actual && <p className='paso-texto'>{paso.texto}</p>}
							{fecha && i <= actual && <time dateTime={fecha}>{formatearFechaHora(fecha)}</time>}
						</li>
					);
				})}
			</ol>
		</section>
	);
};
