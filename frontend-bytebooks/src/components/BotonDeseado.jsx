import { useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useDeseados } from '../context/DeseadosContext';
import { useToast } from '../context/ToastContext';
import { Icon } from './Icon';

export const BotonDeseado = ({ libro }) => {
	const { usuario } = useAuth();
	const { esDeseado, alternar } = useDeseados();
	const mostrarToast = useToast();
	const navigate = useNavigate();
	const location = useLocation();

	const activo = esDeseado(libro.id);

	const click = (e) => {
		e.stopPropagation();

		if (!usuario) {
			mostrarToast('Inicie sesión para guardar libros en su lista de deseados.');
			navigate('/login', { state: { desde: location.pathname } });
			return;
		}

		alternar(libro);
	};

	return (
		<button
			type='button'
			className={`btn-deseado ${activo ? 'activo' : ''}`}
			onClick={click}
			aria-pressed={activo}
			aria-label={activo ? `Quitar ${libro.nombre} de la lista de deseados` : `Agregar ${libro.nombre} a la lista de deseados`}
			title={activo ? 'Quitar de la lista de deseados' : 'Agregar a la lista de deseados'}
		>
			<Icon name='heart' size={20} />
		</button>
	);
};