<!-- Confirmation Modal-->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalTitleLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalTitleLabel">{{ $title ?? 'Confirmation' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmationModalMsgLine1">{!! $msgLine1 ?? 'Do you confirm the operation?' !!}</p>
                <p id="confirmationModalMsgLine2">{!! $msgLine2 ?? '' !!}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                <a class="btn btn-primary" id="confirmationModalButton"
                    onclick="event.preventDefault(); document.getElementById('confirmationModalForm').submit();">{{ $confirmationButton ?? 'OK' }}</a>
                <form id="confirmationModalForm" action="{{ $formAction ?? '' }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="_method" value="{{ $formMethod ?? 'POST' }}"
                        id="confirmationModalFormMethod">
                </form>
            </div>
        </div>
    </div>
</div>
