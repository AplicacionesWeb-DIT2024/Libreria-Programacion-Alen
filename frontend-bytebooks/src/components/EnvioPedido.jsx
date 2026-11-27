import { Campo, ariaCampo } from './Campo';
import { Icon } from './Icon';
import { PROVINCIAS } from '../utils/validaciones';
import './EnvioPedido.css';

export const DOMICILIO_VACIO = {
	domicilio_calle: '',
	domicilio_numero: '',
	domicilio_localidad: '',
	domicilio_provincia: '',
	domicilio_codigo_postal: '',
};

export const domicilioDelCliente = (cliente) => ({
	domicilio_calle: cliente?.domicilio_calle ?? '',
	domicilio_numero: cliente?.domicilio_numero ?? '',
	domicilio_localidad: cliente?.domicilio_localidad ?? '',
	domicilio_provincia: cliente?.domicilio_provincia ?? '',
	domicilio_codigo_postal: cliente?.domicilio_codigo_postal ?? '',
});

export const limpiarDomicilio = (d) =>
	Object.fromEntries(Object.entries(d).map(([k, v]) => [k, String(v ?? '').replace(/\s+/g, ' ').trim()]));

export const textoDomicilio = (d) =>
	`${d.domicilio_calle} ${d.domicilio_numero}, ${d.domicilio_localidad}, ${d.domicilio_provincia} (${d.domicilio_codigo_postal})`;

export const domicilioCompleto = (d) => Object.values(d).every((v) => String(v).trim() !== '');

export const validarDomicilio = (d) => {
	const errores = {};
	if (!d.domicilio_calle) errores.domicilio_calle = 'Ingrese la calle.';
	else if (d.domicilio_calle.length > 255) errores.domicilio_calle = 'La calle no puede superar los 255 caracteres.';

	if (!d.domicilio_numero) errores.domicilio_numero = 'Ingrese el número.';
	else if (!/^([0-9]+[A-Za-z]?|[Ss]\/[Nn])$/.test(d.domicilio_numero))
		errores.domicilio_numero = 'Número inválido (por ejemplo 1234, 12B o S/N).';

	if (!d.domicilio_localidad) errores.domicilio_localidad = 'Ingrese la localidad.';

	if (!PROVINCIAS.includes(d.domicilio_provincia)) errores.domicilio_provincia = 'Elija una provincia de la lista.';

	if (!/^\d{4}$/.test(d.domicilio_codigo_postal)) errores.domicilio_codigo_postal = 'El código postal debe tener 4 números.';

	return errores;
};

export const CAMPOS_DOMICILIO = Object.keys(DOMICILIO_VACIO);
export const idCampoEnvio = (campo) => `envio_${campo}`;

export const EnvioPedido = ({ cliente, tipo, onTipo, otro, onCambiarOtro, errores = {}, deshabilitado = false }) => {
	const props = (campo) => ({
		...ariaCampo(idCampoEnvio(campo), errores[campo]),
		value: otro[campo],
		onChange: (e) => onCambiarOtro(campo, e.target.value),
		disabled: deshabilitado,
	});

	return (
		<fieldset className='envio'>
			<legend className='envio-pregunta'>¿Dónde lo enviamos?</legend>

			<label className={`envio-opcion ${tipo === 'propio' ? 'elegida' : ''}`}>
				<input
					type='radio'
					name='tipo-envio'
					value='propio'
					checked={tipo === 'propio'}
					onChange={() => onTipo('propio')}
					disabled={deshabilitado}
				/>
				<span>
					<strong>A mi domicilio</strong>
					<small>{textoDomicilio(domicilioDelCliente(cliente))}</small>
				</span>
			</label>

			<label className={`envio-opcion ${tipo === 'otro' ? 'elegida' : ''}`}>
				<input
					type='radio'
					name='tipo-envio'
					value='otro'
					checked={tipo === 'otro'}
					onChange={() => onTipo('otro')}
					disabled={deshabilitado}
				/>
				<span>
					<strong>A otra dirección</strong>
					<small>Por ejemplo, su trabajo o la casa de alguien a quien le hace un regalo.</small>
				</span>
			</label>

			{tipo === 'otro' && (
				<div className='envio-form'>
					<Campo id={idCampoEnvio('domicilio_calle')} label='Calle' error={errores.domicilio_calle} className='envio-calle'>
						<input {...props('domicilio_calle')} type='text' autoComplete='shipping address-line1' />
					</Campo>
					<Campo id={idCampoEnvio('domicilio_numero')} label='Número' error={errores.domicilio_numero}>
						<input {...props('domicilio_numero')} type='text' placeholder='1234 o S/N' />
					</Campo>
					<Campo id={idCampoEnvio('domicilio_localidad')} label='Localidad' error={errores.domicilio_localidad}>
						<input {...props('domicilio_localidad')} type='text' autoComplete='shipping address-level2' />
					</Campo>
					<Campo id={idCampoEnvio('domicilio_codigo_postal')} label='Código postal' error={errores.domicilio_codigo_postal}>
						<input
							{...props('domicilio_codigo_postal')}
							type='text'
							inputMode='numeric'
							maxLength={4}
							autoComplete='shipping postal-code'
							placeholder='Ej: 1425'
						/>
					</Campo>
					<Campo
						id={idCampoEnvio('domicilio_provincia')}
						label='Provincia'
						error={errores.domicilio_provincia}
						className='envio-provincia'
					>
						<div className='select-wrap'>
							<select {...props('domicilio_provincia')} autoComplete='shipping address-level1'>
								<option value=''>Elija la provincia</option>
								{PROVINCIAS.map((p) => (
									<option key={p} value={p}>
										{p}
									</option>
								))}
							</select>
							<Icon name='chevronDown' size={16} className='select-arrow' />
						</div>
					</Campo>
				</div>
			)}
		</fieldset>
	);
};