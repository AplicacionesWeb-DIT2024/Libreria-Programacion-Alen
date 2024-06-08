@extends('main')

@section('title', 'Modificar libro')
@section('body')
    <div class = "container mt-5" >
        <h1> Modificar libro </h1>
        <form action = "{{route('libros.update', $libro )}}" method = "POST" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="row align-items-start">
                <div class = "col">
                    <div class="form-group mt-3 mb-3">
                        <label for="nombre">Nombre del libro </label>
                        <input type="text" class="form-control" required = "" id="nombre" name = "nombre" value = "{{$libro->nombre }}">
                    </div>
                    <div class="form-group mt-3 mb-3">
                        <label for="autor"> Autor </label>
                    </div>
                    <select class = "form-control" id = "autor" name = "autor"> 
                        @foreach ($autores as $autor) 
                            <option value = "{{$autor->id}}" @if ($libro->autor == $autor->id) selected @endif> {{$autor->nombre}} </option>
                        @endforeach
                    </select>

                    <div class="form-group mt-3 mb-3">
                        <label for="autor2"> Autor secundario (opcional) </label>
                    </div>
                    <select class = "form-control" id = "autor2" name = "autor2"> 
                        @foreach ($autores as $autor) 
                            <option value = "{{$autor->id}}" @if ($libro->autor2 == $autor->id) selected @endif> {{$autor->nombre}} </option>
                        @endforeach
                    </select>
                    <div class="form-group mt-3 mb-3">
                        <label for="pais_origen"> Pais de origen </label>
                    </div>
                    <select class = "form-control" id = "pais_origen" name = "pais_origen"> 
                        @foreach ($paises as $pais) 
                            <option value = "{{$pais->id}}" @if ($libro->pais_origen == $pais->id) selected @endif> {{$pais->nombre}} </option>
                        @endforeach
                    </select>
                    <div class="form-group mt-3 mb-3">
                        <label for="pais_impresion"> Pais donde se imprimió </label>
                    </div>
                    <select class = "form-control" id = "pais_impresion" name = "pais_impresion"> 
                        @foreach ($paises as $pais) 
                            <option value = "{{$pais->id}}" @if ($libro->pais_impresion == $pais->id) selected @endif> {{$pais->nombre}} </option>
                        @endforeach
                    </select>

                    <div class="form-group mt-3 mb-3">
                        <label for="categoria"> Categoria </label>
                    </div>
                    <select class = "form-control" id = "categoria" name = "categoria"> 
                        @foreach ($categorias as $categoria) 
                            <option value = "{{$categoria->id}}" @if ($libro->categoria == $categoria->id) selected @endif> {{$categoria->nombre}} </option>
                        @endforeach
                    </select>

                    <div class="form-group mt-3 mb-3">
                        <label for="subcategoria"> Subcategoria </label>
                    </div>
                    <select class = "form-control" id = "subcategoria" name = "subcategoria"> 
                        @foreach ($subcategorias as $subcategoria) 
                            <option value = "{{$subcategoria->id}}" @if ($libro->subcategoria == $subcategoria->id) selected @endif> {{$subcategoria->nombre}} </option>
                        @endforeach
                    </select>
                </div>
                <div class = "col">

                    <div class="form-group mt-3 mb-3">
                        <label for="editorial"> Editorial </label>
                    </div>
                    <select class = "form-control" id = "editorial" name = "editorial"> 
                        @foreach ($editoriales as $editorial) 
                            <option value = "{{$editorial->id}}" @if ($libro->editorial == $editorial->id) selected @endif> {{$editorial->nombre}} </option>
                        @endforeach
                    </select>
                    <div class="form-group mt-3 mb-3">
                        <label for="autor3"> Autor secundario (opcional) </label>
                    </div>
                    <select class = "form-control" id = "autor3" name = "autor3"> 
                        @foreach ($autores as $autor) 
                            <option value = "{{$autor->id}}" @if ($libro->autor3 == $autor->id) selected @endif> {{$autor->nombre}} </option>
                        @endforeach
                    </select>
                    
                    <div class="form-group mt-3 mb-3">
                        <label for="nombre"> Stock disponible </label>
                        <input type="number" class="form-control" required = "" id="stock" name = "stock" value = "{{$libro->stock}}">
                    </div>
                    
                    <div class="form-group mt-3 mb-3">
                        <label for="nombre"> Edicion </label>
                        <input type="number" class="form-control" required = "" id="edicion" name = "edicion" value = "{{$libro->edicion}}">
                    </div>

                    
                    <div class="form-group mt-3 mb-3">
                        <label for="nombre"> Año de  publicacion </label>
                        <input type="number" class="form-control" required = "" id="anio" name = "anio_publicacion" value = "{{$libro->anio_publicacion}}">
                    </div>
                    <div class="form-group mt-3 mb-3">
                        <label for="nombre"> Precio </label>
                        <input type="text" class="form-control" required = "" id="precio" name = "precio" value = "{{$libro->precio}}">
                    </div>

                    <div class="form-control mt-3 mb-3">
                        <label for="imagen"> Imagen principal </label>
                        @if($libro->imagen_referencia)
                            <div class = "mb-3">
                                <img src="{{asset($libro->imagen_referencia)}}" alt="Imagen actual" style="max-width: 200px;">
                            </div>
                        @endif
                        <input type= "file" name = "imagen" class = "form-control" id = "imagen" >
                    </div>
                </div>
            </div>
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-primary btn-block btn-login align-items-center center"> Modificar libro</button>
            </div>
        </form>
        @if (session('success'))
        <div class = "alert alert-success">
            {{ session('success') }}
        </div>
        @endif
    </div>
@endsection