import { useState } from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import { Header } from './components/Header';
import { Tienda } from './pages/Tienda';
import { Login } from './pages/Login';
import { Registro } from './pages/Registro';
import { useCarrito } from './hooks/useCarrito';
import { useAuth } from './context/AuthContext';


const SoloInvitados = ({ children }) => {
	const { usuario } = useAuth();
	const [yaLogueado] = useState(Boolean(usuario));
	return yaLogueado ? <Navigate to='/' replace /> : children;
};

function App() {
	const carrito = useCarrito();

	return (
		<>
			<Header carrito={carrito} />

			<Routes>
				<Route path='/' element={<Tienda carrito={carrito} />} />
				<Route
					path='/login'
					element={
						<SoloInvitados>
							<Login />
						</SoloInvitados>
					}
				/>
				<Route
					path='/registro'
					element={
						<SoloInvitados>
							<Registro />
						</SoloInvitados>
					}
				/>
				<Route path='*' element={<Navigate to='/' replace />} />
			</Routes>

			<footer className='site-footer'>
				<p>© {new Date().getFullYear()} ByteBooks · Libros de programación</p>
			</footer>
		</>
	);
}

export default App;
