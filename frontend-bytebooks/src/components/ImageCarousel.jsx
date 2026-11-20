import { useState } from 'react';

export const ImageCarousel = ({ images, alt }) => {
	const [index, setIndex] = useState(0);
	const total = images.length;

	if (total === 0) return null;

	const anterior = () => setIndex((i) => (i - 1 + total) % total);
	const siguiente = () => setIndex((i) => (i + 1) % total);

	return (
		<div className='carousel'>
			<img
				key={images[index]}
				src={images[index]}
				alt={`${alt} (${index + 1} de ${total})`}
			/>

			{total > 1 && (
				<>
					<button
						type='button'
						className='carousel-btn carousel-prev'
						onClick={anterior}
						aria-label='Imagen anterior'
					>
						&#10094;
					</button>
					<button
						type='button'
						className='carousel-btn carousel-next'
						onClick={siguiente}
						aria-label='Imagen siguiente'
					>
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