
<div class="modal fade" id="modal{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle{{ $id }}"> Informacion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
                @php
                    $imagenes = array_values(array_filter([$imagen_original, $imagen_referencia_2, $imagen_referencia_3]));
                @endphp

                @if (count($imagenes) > 0)
                    <div id="carrusel{{ $id }}" class="carousel slide mb-3" data-bs-ride="carousel">
                        @if (count($imagenes) > 1)
                            <div class="carousel-indicators">
                                @foreach ($imagenes as $index => $imagen)
                                    <button type="button"
                                        data-bs-target="#carrusel{{ $id }}"
                                        data-bs-interval= "false"
                                        data-bs-slide-to="{{ $index }}"
                                        class="{{ $index === 0 ? 'active' : '' }}"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Imagen {{ $index + 1 }}">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                        <div class="carousel-inner rounded">
                            @foreach ($imagenes as $index => $imagen)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $imagen }}" class="d-block w-100" style="max-height:300px;object-fit:contain;" alt="Imagen del libro">
                                </div>
                            @endforeach
                        </div>
                        @if (count($imagenes) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carrusel{{ $id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carrusel{{ $id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        @endif
                    </div>
                @endif

                <div class="mb-2"> <b> Nombre del libro: </b> {{$nombre}} </div>
                <div class="mb-2"> <b> Idioma: </b> {{$idioma}} </div>
                <div class="mb-2"> <b> Categoria a la que pertenece:</b> {{$categoria}} </div> 
                <div class="mb-2"> <b> Subcategoria a la que pertenece:</b> {{$subcategoria}} </div> 
                <div class="mb-2"> <b> Editorial a la que pertenece:</b> {{$editorial}} </div> 
                <div class="mb-2"> <b> Pais de origen:</b> {{$pais_origen}} </div> 
                <div class="mb-2"> 
                    <b> Autores: </b> 
                    @foreach ($autores as $autor)
                                {{ $autor }}@if (!$loop->last)<br>@endif
                    @endforeach
                </div>
                <div class="mb-2"> <b> Pais donde se imprimió:</b> {{$pais_impresion}} </div> 
                <div class="mb-2"> <b> Edición:</b> {{$edicion}} </div> 
                <div class="mb-2"> <b> Año de publicación:</b> {{$anio_publicacion}} </div> 
                <div class="mb-2"> <b> Stock disponible:</b> {{$stock}} </div> 
                <div class="mb-2"> <b> Precio:</b> {{$precio}} </div> 
                <div class="mb-2"> <b> Fecha de creación:</b> {{$creado}} </div> 
                <div class="mb-2"> <b> Fecha de modificación:</b> {{$actualizado}} </div> 
                <div class="mb-2"> <b> Usuario de creación:</b> {{$usuario_creacion}} </div> 
                <div class="mb-2"> <b> Usuario de modificación:</b> {{$usuario_actualizacion}} </div> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" > Cerrar </button>
        </div>
      </div>
    </div>
</div>