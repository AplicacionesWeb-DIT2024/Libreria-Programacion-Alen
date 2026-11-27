
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

export const SeguimientoPedido = ({ pedido }) => {
	const actual = PASOS.findIndex((p) => p.estado === pedido.estado);
	if (actual === -1) return null;

	const fechaDe = (estado) => [...(pedido.historial ?? [])].reverse().find((h) => h.estado === estado)?.fecha;

	const INICIO_ENVIO = 1;
	const hayEnvio = actual >= INICIO_ENVIO;
	const progreso = hayEnvio ? ((actual - INICIO_ENVIO) / (PASOS.length - 1 - INICIO_ENVIO)) * 100 : 0;
	const enCamino = pedido.estado === 'En distribución';
	const entregado = pedido.estado === 'Recibido en domicilio';

	return (
		<section className='seguimiento' aria-label='Seguimiento del pedido'>
			{hayEnvio ? (
				<div className={`ruta ${enCamino ? 'en-camino' : ''} ${entregado ? 'entregado' : ''}`}>
					<div className='ruta-calle' />
					<div className='ruta-progreso' style={{ width: `${progreso}%` }} />
					<div className='ruta-camion' style={{ left: `${progreso}%` }}>
						<Camion />
					</div>
				</div>
			) : (
				<p className='seguimiento-aviso'>Su pago fue aprobado. En breve vamos a empezar a preparar su pedido.</p>
			)}

			<ol className='pasos'>
				{PASOS.map((paso, i) => {
					const fecha = fechaDe(paso.estado);
					const estadoPaso = i < actual ? 'hecho' : i === actual ? 'actual' : 'pendiente';

					return (
						<li key={paso.estado} className={`paso paso-${estadoPaso}`} aria-current={i === actual ? 'step' : undefined}>
							<span className='paso-punto' aria-hidden='true' />
							<div>
								<p className='paso-titulo'>{paso.titulo}</p>
								{i <= actual && <p className='paso-texto'>{paso.texto}</p>}
								{fecha && i <= actual && <time dateTime={fecha}>{formatearFechaHora(fecha)}</time>}
							</div>
						</li>
					);
				})}
			</ol>
		</section>
	);
};
