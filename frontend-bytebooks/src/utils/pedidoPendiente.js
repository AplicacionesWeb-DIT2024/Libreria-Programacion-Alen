export const CLAVE_PEDIDO = 'bytebooks-pedido-pendiente';

export const leerPedidoPendiente = () => {
	try {
		return JSON.parse(localStorage.getItem(CLAVE_PEDIDO));
	} catch {
		return null;
	}
};
