import { useEffect, useState } from 'react';
import { Header } from './components/Header';
import { ProductList } from './components/ProductList';

function App() {

	const [allProducts, setAllProducts] = useState([]);


	const [total, setTotal] = useState(0);
	const [countProducts, setCountProducts] = useState(0);

	const [cartProducts, setCartProducts] = useState([]);

	useEffect(() => {
        fetch('http://127.0.0.1:8000/api/libros')
            .then(res => res.json())
            .then(data => {
                // Añadir el campo 'quantity' inicializado en 1 a cada producto
                const productsWithQuantity = data.map(product => ({
                    ...product,
                    quantity: 1 // Inicializar 'quantity' en 1
                }));
                setAllProducts(productsWithQuantity);
            })
            .catch(error => console.error('Error fetching products:', error));
    }, []);

	

	return (
		<>
			<Header
				cartProducts={cartProducts}
				setCartProducts={setCartProducts}
				total={total}
				setTotal={setTotal}
				countProducts={countProducts}
				setCountProducts={setCountProducts}
			/>
			<ProductList
				allProducts={allProducts}
				setAllProducts={setAllProducts}
				total={total}
				setTotal={setTotal}
				countProducts={countProducts}
				setCountProducts={setCountProducts}
				cartProducts={cartProducts}
				setCartProducts={setCartProducts}
			/>
		</>
	);
}

export default App;
