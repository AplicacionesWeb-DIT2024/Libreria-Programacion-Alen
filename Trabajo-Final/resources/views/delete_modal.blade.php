
<div class="modal fade" id="deleteModal{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel{{ $id }}">Confirmar Eliminación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ¿Está seguro de que desea eliminar el elemento "{{$nombre}}" ? Luego de eliminarlo no se puede deshacer.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal" >No</button>
          <button type="button" class="btn btn-primary" id="confirmDeleteButton{{ $id }}" >Si </button>
        </div>
      </div>
    </div>
  </div>