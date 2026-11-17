
<div class="modal fade" id="cambioEstado{{ $id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirmar cambio de estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Está seguro de que desea cambiar el estado a <strong>{{ $estado }}</strong>?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    No
                </button>

                <button type="submit" class="btn btn-primary" form="cambioEstadoForm{{ $id }}">
                    Sí
                </button>
            </div>
        </div>
    </div>
</div>