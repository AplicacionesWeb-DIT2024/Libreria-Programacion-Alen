import { useRef, useState } from 'react';

const UMBRAL = 40; 

export const ImageCarousel = ({ images, alt }) => {
	const [index, setIndex] = useState(0);
	const inicio = useRef({ x: 0, y: 0 });
	const total = images.length;

	if (total === 0) return null;

	const anterior = () => setIndex((i) => (i - 1 + total) % total);
	const siguiente = () => setIndex((i) => (i + 1) % total);

	const onTouchStart = (e) => {
		const t = e.touches[0];
		inicio.current = { x: t.clientX, y: t.clientY };
	};

	const onTouchEnd = (e) => {
		if (total < 2) return;
		const t = e.changedTouches[0];
		const dx = t.clientX - inicio.current.x;
		const dy = t.clientY - inicio.current.y;

		if (Math.abs(dx) < UMBRAL || Math.abs(dx) < Math.abs(dy)) return;

		if (dx < 0) siguiente();   
		else anterior();           
	};

	const onKeyDown = (e) => {
		if (total < 2) return;
		if (e.key === 'ArrowLeft') anterior();
		if (e.key === 'ArrowRight') siguiente();
	};

	return (
		<div
			className='carousel'
			onTouchStart={onTouchStart}
			onTouchEnd={onTouchEnd}
			onKeyDown={onKeyDown}
			tabIndex={0}
		>
			<img
				key={images[index]}
				src={images[index]}
				alt={`${alt} (${index + 1} de ${total})`}
				draggable={false}
			/>

			{total > 1 && (
				<>
					<button type='button' className='carousel-btn carousel-prev' onClick={anterior} aria-label='Imagen anterior'>
						&#10094;
					</button>
					<button type='button' className='carousel-btn carousel-next' onClick={siguiente} aria-label='Imagen siguiente'>
						&#10095;
					</button>

					<div className='carousel-dots'>
						{images.map((_, i) => (
							<span
								key={i}
								className={i === index ? 'dot active' : 'dot'}
								onClick={() => setIndex(i)}
							/>
						))}
					</div>
				</>
			)}
		</div>
	);
};