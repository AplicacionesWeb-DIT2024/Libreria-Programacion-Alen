<div class="modal fade" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog {{ $size ?? 'modal-lg' }}">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>

            {{-- BODY --}}
            <div class="modal-body">
                {{ $body }}
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                {{ $footer }}
            </div>

        </div>
    </div>
</div>
