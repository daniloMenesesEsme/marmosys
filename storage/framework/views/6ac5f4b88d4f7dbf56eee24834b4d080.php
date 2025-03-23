<!-- Modal de Simulação -->
<div id="simulate-modal" class="modal">
    <div class="modal-content">
        <h4>Simulação de Parcelas</h4>
        
        <div class="row">
            <form id="simulate-form" class="col s12">
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">attach_money</i>
                        <input type="number" id="valor_simulacao" name="valor" step="0.01" min="0" required>
                        <label for="valor_simulacao">Valor Total</label>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col s12">
                <table class="striped" id="simulation-results" style="display: none;">
                    <thead>
                        <tr>
                            <th>Parcela</th>
                            <th>Valor</th>
                            <th>Vencimento</th>
                            <th>Taxa</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="right-align"><strong>Total Final:</strong></td>
                            <td><strong id="total-final">R$ 0,00</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn-flat">Fechar</a>
        <button type="button" class="waves-effect waves-green btn" onclick="simularParcelas()">
            <i class="material-icons left">calculate</i>
            Simular
        </button>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.querySelector('#simulate-modal');
    M.Modal.init(modal);
});

function openSimulateModal(planId) {
    var modal = M.Modal.getInstance(document.querySelector('#simulate-modal'));
    document.getElementById('simulation-results').style.display = 'none';
    document.getElementById('simulate-form').reset();
    window.currentPlanId = planId;
    modal.open();
}

function simularParcelas() {
    var valor = document.getElementById('valor_simulacao').value;
    if (!valor || valor <= 0) {
        M.toast({html: 'Por favor, informe um valor válido!', classes: 'red'});
        return;
    }

    fetch(`/financial/registration/payment-plans/${window.currentPlanId}/simulate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ valor: valor })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            M.toast({html: data.error, classes: 'red'});
            return;
        }

        const tbody = document.querySelector('#simulation-results tbody');
        tbody.innerHTML = '';
        let totalFinal = 0;

        data.forEach(parcela => {
            const valorComTaxa = parcela.valor * (1 + (parcela.taxa / 100));
            totalFinal += valorComTaxa;

            tbody.innerHTML += `
                <tr>
                    <td>${parcela.numero}ª</td>
                    <td>R$ ${parcela.valor.toFixed(2)}</td>
                    <td>${new Date(parcela.vencimento).toLocaleDateString()}</td>
                    <td>${parcela.taxa}%</td>
                    <td>R$ ${valorComTaxa.toFixed(2)}</td>
                </tr>
            `;
        });

        document.getElementById('total-final').textContent = `R$ ${totalFinal.toFixed(2)}`;
        document.getElementById('simulation-results').style.display = 'table';
    })
    .catch(error => {
        console.error('Erro:', error);
        M.toast({html: 'Erro ao simular parcelas!', classes: 'red'});
    });
}
</script>
<?php $__env->stopPush(); ?> <?php /**PATH C:\laragon\www\marmosys\resources\views/financial/registration/payment-plans/_simulate_modal.blade.php ENDPATH**/ ?>