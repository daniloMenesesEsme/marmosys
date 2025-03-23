@if($budget->status === 'aguardando_aprovacao')
    <div class="card-action">
        <form action="{{ route('budgets.approve', $budget) }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="action" value="approve">
            <button type="submit" class="btn green waves-effect waves-light">
                <i class="material-icons left">check</i>
                Aprovar Orçamento
            </button>
        </form>

        <button class="btn red waves-effect waves-light modal-trigger" data-target="modal-rejeitar">
            <i class="material-icons left">close</i>
            Rejeitar Orçamento
        </button>
    </div>

    <!-- Modal de Rejeição -->
    <div id="modal-rejeitar" class="modal">
        <form action="{{ route('budgets.approve', $budget) }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="reject">
            
            <div class="modal-content">
                <h4>Rejeitar Orçamento</h4>
                <div class="input-field">
                    <textarea name="motivo_reprovacao" class="materialize-textarea" required></textarea>
                    <label>Motivo da Rejeição</label>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn red">Confirmar Rejeição</button>
            </div>
        </form>
    </div>
@else
    <div class="card-panel {{ $budget->status === 'aprovado' ? 'green' : 'red' }} lighten-4">
        <span class="white-text">
            Status: {{ ucfirst($budget->status) }}
            @if($budget->status === 'reprovado')
                <br>
                Motivo: {{ $budget->motivo_reprovacao }}
            @endif
        </span>
    </div>
@endif 