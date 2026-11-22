import { useEffect, useState } from 'react';
import './SplashScreen.css';

const MINIMO_MS = 1800; 
const MAXIMO_MS = 8000; 
const SALIDA_MS = 600; 


export const SplashScreen = ({ listo, onFin }) => {
	const [minimoCumplido, setMinimoCumplido] = useState(false);
	const [saliendo, setSaliendo] = useState(false);

	useEffect(() => {
		const tMin = setTimeout(() => setMinimoCumplido(true), MINIMO_MS);
		const tMax = setTimeout(() => setSaliendo(true), MAXIMO_MS);
		return () => {
			clearTimeout(tMin);
			clearTimeout(tMax);
		};
	}, []);

	useEffect(() => {
		if (listo && minimoCumplido) setSaliendo(true);
	}, [listo, minimoCumplido]);

	useEffect(() => {
		if (!saliendo) return;
		const t = setTimeout(onFin, SALIDA_MS);
		return () => clearTimeout(t);
	}, [saliendo, onFin]);

	useEffect(() => {
		document.body.style.overflow = 'hidden';
		return () => {
			document.body.style.overflow = '';
		};
	}, []);

	return (
		<div className={`splash ${saliendo ? 'is-leaving' : ''}`} role='status' aria-label='Cargando ByteBooks'>
			<div className='splash-logo'>
				<svg className='splash-ring' viewBox='0 0 200 200' aria-hidden='true'>
					<defs>
						<linearGradient id='splash-arco' x1='0' y1='0' x2='1' y2='1'>
							<stop offset='0%' stopColor='#20b2aa' stopOpacity='0' />
							<stop offset='100%' stopColor='#5ff5e8' />
						</linearGradient>
					</defs>
					<circle className='splash-track' cx='100' cy='100' r='94' />
					<circle className='splash-arc' cx='100' cy='100' r='94' stroke='url(#splash-arco)' />
				</svg>
				<svg className='splash-dots' viewBox='0 0 200 200' aria-hidden='true'>
					<circle cx='100' cy='100' r='99' />
				</svg>

				<img src='/logo-splash.webp' alt='ByteBooks' width='200' height='200' />
			</div>

			<p className='splash-text'>
				Cargando la librería<span className='splash-ellipsis' aria-hidden='true' />
			</p>
		</div>
	);
};
