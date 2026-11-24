import { useEffect, useState } from 'react';

export const useCuentaRegresiva = (hasta) => {
	const calcular = () => (hasta ? Math.max(0, new Date(hasta).getTime() - Date.now()) : 0);
	const [restante, setRestante] = useState(calcular);

	useEffect(() => {
		setRestante(calcular());
		if (!hasta) return;
		const intervalo = setInterval(() => setRestante(calcular()), 1000);
		return () => clearInterval(intervalo);
	}, [hasta]);

	const segundos = Math.ceil(restante / 1000);
	const mm = String(Math.floor(segundos / 60)).padStart(2, '0');
	const ss = String(segundos % 60).padStart(2, '0');

	return `${mm}:${ss}`;
};