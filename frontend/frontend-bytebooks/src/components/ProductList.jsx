import React from 'react';
export const ProductList = ({
	allProducts,
	setAllProducts,
	countProducts,
	setCountProducts,
	total,
	setTotal,
	cartProducts,
	setCartProducts
}) => {
	const onAddProduct = product => {
		const productInCart = cartProducts.find(item => item.id === product.id); 
		if (productInCart) {
			const updatedCartProducts = cartProducts.map(item =>
				item.id === product.id ? { ...item, quantity: item.quantity + 1 } : item);
			setCartProducts(updatedCartProducts);

		} else {
			setCartProducts([...cartProducts, { ...product, quantity: 1}]);
			console.log(cartProducts);
		};
		  setTotal(total + product.precio * product.quantity);
		  setCountProducts(countProducts + 1);
	};


	return (
		<div className='container-items'>
			{allProducts.map((product) => (
				<div className='item' key={product.id}>
					<figure>
						<img src={`${product.imagen_referencia}`} alt={product.nombre} />
					</figure>
					<div className='info-product'>
						<h2>{product.nombre}</h2>
						<p className='precio'>${product.precio}</p>
						<button onClick={() => onAddProduct(product)}>
							Añadir al carrito
						</button>
					</div>
				</div>
			))}
		</div>
	);
};
