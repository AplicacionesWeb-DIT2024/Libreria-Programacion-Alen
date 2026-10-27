
<div class="modal fade" id="deleteModal{{ $id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Está seguro de que desea eliminar el elemento "{{ $nombre }}"?
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-danger"
                        data-bs-dismiss="modal">
                    No
                </button>

                <button type="submit"
                        class="btn btn-primary"
                        form="deleteForm{{ $id }}">
                    Sí
                </button>
            </div>

        </div>
    </div>
</div>
