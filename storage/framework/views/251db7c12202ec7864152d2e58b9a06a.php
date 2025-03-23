<!-- Modal de Confirmação de Exclusão -->
<div id="confirm-delete-modal" class="modal">
    <div class="modal-content">
        <h4>Confirmar Exclusão</h4>
        <p>Tem certeza que deseja excluir este registro?</p>
        <p>Esta ação não poderá ser desfeita.</p>
    </div>
    <div class="modal-footer">
        <form id="delete-form" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
        <button type="button" onclick="confirmDelete()" class="waves-effect waves-red btn red">
            <i class="material-icons left">delete</i>
            Excluir
        </button>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.querySelector('#confirm-delete-modal');
    M.Modal.init(modal);
});

function openDeleteModal(url) {
    var modal = M.Modal.getInstance(document.querySelector('#confirm-delete-modal'));
    var form = document.getElementById('delete-form');
    form.action = url;
    modal.open();
}

function confirmDelete() {
    document.getElementById('delete-form').submit();
}
</script>
<?php $__env->stopPush(); ?> <?php /**PATH C:\laragon\www\marmosys\resources\views/shared/_confirm_delete_modal.blade.php ENDPATH**/ ?>