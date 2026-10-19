<div class="modal fade" id="transferModal{{ $id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirmar transferencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Al realizar esta acción dejarás de ser superadmin y <strong>{{ $nombre }}</strong> pasará a serlo.
                ¿Estás seguro de proceder?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    No
                </button>
                <button type="submit" class="btn btn-danger" form="transferForm{{ $id }}">
                    Sí
                </button>
            </div>

        </div>
    </div>
</div>