import { useState } from 'react';
import { Icon } from './Icon';

export const Campo = ({ id, label, error, ayuda, opcional, className = '', children }) => (
	<div className={`form-field ${error ? 'has-error' : ''} ${className}`}>
		<label htmlFor={id}>
			{label}
			{opcional && <span className='optional'> (opcional)</span>}
		</label>
		{children}
		{error ? (
			<p className='field-error' id={`${id}-error`} role='alert'>
				<Icon name='alert' size={15} /> {error}
			</p>
		) : (
			ayuda && (
				<p className='field-help' id={`${id}-help`}>
					{ayuda}
				</p>
			)
		)}
	</div>
);

export const ariaCampo = (id, error, ayuda) => ({
	id,
	name: id,
	'aria-invalid': Boolean(error),
	'aria-describedby': error ? `${id}-error` : ayuda ? `${id}-help` : undefined,
});

export const InputPassword = (props) => {
	const [visible, setVisible] = useState(false);
	return (
		<div className='password-input'>
			<input {...props} type={visible ? 'text' : 'password'} />
			<button
				type='button'
				className='password-toggle'
				onClick={() => setVisible((v) => !v)}
				aria-label={visible ? 'Ocultar contraseña' : 'Mostrar contraseña'}
			>
				<Icon name={visible ? 'eyeSlash' : 'eye'} size={20} />
			</button>
		</div>
	);
};
