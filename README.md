# Aplicaciones-Web

## Alumno
- Morillo Meneses, Alen Misael

## Descripcion del proyecto
El proyecto que se va a desarrollar en la materia será una aplicacion de venta de libros orientada a los programadores. Esta aplicacion tendrá como nombre "ByteBooks" y tendrá 2 partes, una en el backend y otra en el frontend. 

### Backend
El backend será desarrollado en Laravel y los administradores podrán llevar a cabo las siguientes acciones: 
- **Gestión de libros**: Los administradores podrán agregar, modificar o eliminar libros existentes. Estos libros tendrán como atributos el codigo (clave primaria), el nombre, la cantidad, el autor (que será en si una clave foranea), la editorial a la que pertenece (la cual será otra clave foranea), el pais de origen, pais de impresión, la edición del libro, el año de publicación, un precio, una categoria (clave foranea), una subcategoria (clave foranea).
- **Gestión de editoriales**: Los administradores podrán agregar, modificar o eliminar editoriales. Dichas editoriales tendrán un codigo, un nombre, pais.
- **Gestión de autores**: Los administradores podrán agregar, modificar o eliminar autores. El autor va a poseer como atributos un numero de identificación (clave primaria), nombre, pais de origen, año de nacimiento. 
- **Gestión de paises**: Los administradores podrán agregar, modificar o eliminar paises. Los paises tendrán un codigo y un nombre.
- **Gestión de categorias**: Los administradores podrán agregar, modificar o eliminar categorias. Las categorias van a tener un codigo y un nombre.
- **Gestión de subcategorias**: Los administradores podrán agregar. modificar o eliminar subcategorias. Las subcategorias van a tener un codigo y un nombre.
- **Gestión de entidades crediticias**: Los administradores podrán agregar, modificar o eliminar entidades crediticias. Las entidades van a tener un codigo, un nombre, el tipo(si es debito o credito) y el codigo del banco al cual pertenece.
- **Gestión de bancos**: Los administradores podrán agregar, modificar o eliminar bancos. Los bancos van a tener un codigo, un nombre y un país de origen (clave foranea).
- **Gestión de usuarios**: Los usuarios van a tener como atributos un nombre de usuario, una contraseña y un tipo (si es el superadmin, el que puede dar de alta libros y los clientes). 

### Frontend
El frontend por su parte estará orientado a los clientes y será desarrollado en React. Los clientes podrán realizar las siguientes acciones: 
- **Gestión de compras**: Los clientes van a poder solicitar libros o modificar la cantidad de libros solicitados.
- Los clientes no registrados podrán ver los libros pero sin poder comprarlos. Para poder comprarlos deberán registrarse e ingresar por medio de un Login. 
- Los clientes podrán calificar el libro comprado e indicar alguna reseña sobre el mismo.
- Los clientes podrán filtrar los libros según el nombre, año de edición, editorial.
- Los clientes recibirán anuncios de libros relacionados con los que haya comprado.
- **Gestión de pagos**: Los clientes van a poder especificar en que medio de pago lo van a pagar al libro, si es efectivo, debito, credito. Si es en crédito indicará el número de cuotas, si será en 3, 6, 12 o 24 cuotas.
- **Gestión de pedidos**: Los pedidos realizados por los clientes requerirán el domicilio del cliente, la empresa de correo que transportará el pedido, la fecha estimada de entrega. También se le otorgará un número de monitoreo para el seguimiento del pedido.
