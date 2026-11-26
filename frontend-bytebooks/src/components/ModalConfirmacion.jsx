import { useEffect, useId, useRef } from 'react';
import { createPortal } from 'react-dom';

const SELECTOR_ENFOCABLES =
	'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

export const ModalConfirmacion = ({
	abierto,
	titulo,
	children,
	textoConfirmar = 'Confirmar',
	textoCancelar = 'Cancelar',
	textoProcesando = 'Procesando…',
	procesando = false,
	onConfirmar,
	onCancelar,
}) => {
	const idTitulo = useId();
	const refModal = useRef(null);
	const refConfirmar = useRef(null);

	const refCancelar = useRef(onCancelar);
	refCancelar.current = onCancelar;
	const refProcesando = useRef(procesando);
	refProcesando.current = procesando;

	useEffect(() => {
		if (!abierto) return;

		const anterior = document.activeElement;
		refConfirmar.current?.focus();

		const overflowAnterior = document.body.style.overflow;
		document.body.style.overflow = 'hidden';

		const onKey = (e) => {
			if (e.key === 'Escape') {
				e.stopPropagation();
				if (!refProcesando.current) refCancelar.current();
				return;
			}

			if (e.key === 'Tab' && refModal.current) {
				const enfocables = [...refModal.current.querySelectorAll(SELECTOR_ENFOCABLES)];
				if (enfocables.length === 0) return;
				const primero = enfocables[0];
				const ultimo = enfocables[enfocables.length - 1];

				if (e.shiftKey && document.activeElement === primero) {
					e.preventDefault();
					ultimo.focus();
				} else if (!e.shiftKey && document.activeElement === ultimo) {
					e.preventDefault();
					primero.focus();
				}
			}
		};

		document.addEventListener('keydown', onKey);

		return () => {
			document.removeEventListener('keydown', onKey);
			document.body.style.overflow = overflowAnterior;
			anterior?.focus?.();
		};
	}, [abierto]);

	if (!abierto) return null;

	return createPortal(
		<div
			className='modal-fondo'
			data-ignorar-clic-afuera
			onMouseDown={(e) => {
				if (e.target === e.currentTarget && !procesando) onCancelar();
			}}
		>
			<div className='modal' role='dialog' aria-modal='true' aria-labelledby={idTitulo} ref={refModal}>
				<h2 id={idTitulo} className='modal-titulo'>
					{titulo}
				</h2>

				<div className='modal-cuerpo'>{children}</div>

				<div className='modal-acciones'>
					<button type='button' className='btn btn-ghost' onClick={onCancelar} disabled={procesando}>
						{textoCancelar}
					</button>
					<button
						type='button'
						className='btn btn-primary'
						onClick={onConfirmar}
						disabled={procesando}
						ref={refConfirmar}
					>
						{procesando ? textoProcesando : textoConfirmar}
					</button>
				</div>
			</div>
		</div>,
		document.body,
	);
};