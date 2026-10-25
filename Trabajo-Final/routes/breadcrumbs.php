<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

use App\Models\User;
use App\Models\Factura;



Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('libros.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Listado de libros', route('libros.index'));
});

Breadcrumbs::for('autores.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Listado de autores', route('autores.index'));
});

Breadcrumbs::for('editoriales.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Listado de editoriales', route('editoriales.index'));
});

Breadcrumbs::for('categorias.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Listado de categorias', route('categorias.index'));
});

Breadcrumbs::for('subcategorias.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Listado de subcategorias', route('subcategorias.index'));
});

Breadcrumbs::for('administradores.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Listado de administradores', route('administradores.index'));
});

Breadcrumbs::for('clientes.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Listado de clientes', route('clientes.index'));
});

Breadcrumbs::for('clientes.pedidos', function (BreadcrumbTrail $trail, User $usuario) {
    $trail->parent('clientes.index');
    $trail->push('Pedidos de ' . $usuario->name . ' , ' . $usuario->apellido, route('clientes.pedidos', $usuario));
});

Breadcrumbs::for('pedidos.show', function (BreadcrumbTrail $trail, User $usuario, Factura $factura) {
    $trail->parent('clientes.pedidos', $usuario);
    $trail->push('Pedido #' . $factura->id);
});

Breadcrumbs::for('pedidos.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Listado de pedidos', route('pedidos.index'));
});

Breadcrumbs::for('pedidos.detalle', function (BreadcrumbTrail $trail, Factura $factura) {
    $trail->parent('pedidos.index');
    $trail->push('Pedido #' . $factura->id);
});