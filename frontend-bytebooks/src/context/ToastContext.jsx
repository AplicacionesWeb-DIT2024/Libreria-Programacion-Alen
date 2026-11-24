import { createContext, useCallback, useContext, useEffect, useState } from 'react';
import { createPortal } from 'react-dom';
import { Icon } from '../components/Icon';

const ToastContext = createContext(null);


export const ToastProvider = ({ children }) => {
	const [toast, setToast] = useState(null); 

	const mostrarToast = useCallback((mensaje, duracion = 3500) => {
		setToast({ id: Date.now(), mensaje, duracion });
	}, []);

	useEffect(() => {
		if (!toast) return;
		const t = setTimeout(() => setToast(null), toast.duracion);
		return () => clearTimeout(t);
	}, [toast]);

	return (
		<ToastContext.Provider value={mostrarToast}>
			{children}
			{toast &&
				createPortal(
					<div key={toast.id} className='toast' role='status'>
						<Icon name='check' size={18} strokeWidth={2.4} />
						<span>{toast.mensaje}</span>
					</div>,
					document.body,
				)}
		</ToastContext.Provider>
	);
};

export const useToast = () => {
	const ctx = useContext(ToastContext);
	if (!ctx) throw new Error('useToast tiene que usarse dentro de <ToastProvider>');
	return ctx;
};