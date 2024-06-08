
<div class="modal fade" id="modal{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle{{ $id }}"> Informacion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <li>
                <ul> <b> Nombre: </b> {{$nombre}} </ul>
                <ul> <b> Pais de origen: </b> {{ $pais_origen }} </ul>
                <ul> <b> Fecha de creacion: </b> {{$creado}} </ul>
                <ul> <b> Fecha de ultima actualizacion: </b> {{$actualizado}} </ul>
            </li>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal" > Cerrar </button>
        </div>
      </div>
    </div>
</div>

