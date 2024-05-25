@extends('main')

@section('title', 'Home')
@section('body')
    <div class= "container mt-4">
        <h1> Bienvenido al panel de administracion de la libreria para programadores Bytebooks </h1>
        <p> En este panel usted podrá realizar las siguientes acciones: </p>
        <ul> 
            <li> <b> ABM de libros:</b>  Podras crear libros, listarlos, modificarlos y eliminarlos.  </li>
            <li> <b> ABM de editoriales:</b>  Podras crear editoriales, listarlos, modificarlos y eliminarlos.  </li>
            <li> <b> ABM de autores:</b> Podras crear autores para los libros, listarlos, modificarlos y eliminarlos.  </li>
            <li> <b> ABM de paises:</b>  Podras crear paises, listarlos, modificarlos y eliminarlos.  </li>
            <li> <b> ABM de categorias:</b>  Podras crear categorias para los libros, listarlos, modificarlos y eliminarlos.  </li>
            <li> <b> ABM de subcategorias:</b> Podras crear subcategorias para los libros, listarlos, modificarlos y eliminarlos. </li>
            <li> <b> ABM de bancos:</b> Podras crear los bancos que seran usados para las tarjetas, listarlos, modificarlos y eliminarlos. </li>
            <li> <b> ABM de entidades crediticias:</b>  Podras crear entidades para las tarjetas, listarlos, modificarlos y eliminarlos. </li>
            <li> <b> ABM de usuarios:</b>  Podras crear usuarios, listarlos, modificarlos y eliminarlos. </li>
        </ul>
    </div>
@endsection

