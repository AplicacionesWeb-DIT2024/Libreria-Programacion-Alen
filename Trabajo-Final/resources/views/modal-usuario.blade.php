
<div class="modal fade" id="modal{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle{{ $id }}"> Informacion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
                <div class="mb-2"> <b> Nombre: </b> {{$nombre}} </div>
                <div class="mb-2"> <b> Apellido: </b> {{$apellido}} </div>
                <div class="mb-2"> <b> Usuario: </b> {{$usuario}} </div>
                <div class="mb-2"> <b> Domicilio: </b> {{$domicilio}} </div>
                <div class="mb-2"> <b> Fecha de creacion: </b> {{$creado}} </div>
                <div class="mb-2"> <b> Fecha de ultima actualización: </b> {{$actualizado}} </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" > Cerrar </button>
        </div>
      </div>
    </div>
</div>