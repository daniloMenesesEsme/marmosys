<?php $__env->startSection('title', 'Fornecedores'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        <div class="row">
                            <div class="col s12 m6">
                                <h4><i class="material-icons left">local_shipping</i> Fornecedores</h4>
                            </div>
                            <div class="col s12 m6 right-align">
                                <a href="<?php echo e(route('suppliers.create')); ?>" class="btn-floating btn-large waves-effect waves-light green tooltipped" data-position="left" data-tooltip="Novo Fornecedor">
                                    <i class="material-icons">add</i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row">
                        <form action="<?php echo e(route('suppliers.index')); ?>" method="GET" class="col s12">
                            <div class="row">
                                <div class="input-field col s12 m5">
                                    <i class="material-icons prefix">search</i>
                                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>">
                                    <label for="search">Buscar por Razão Social, Nome Fantasia, CNPJ, Email ou Telefone</label>
                                </div>
                                <div class="input-field col s12 m3">
                                    <select name="status" id="status">
                                        <option value="" <?php echo e(request('status') == '' ? 'selected' : ''); ?>>Todos</option>
                                        <option value="ativo" <?php echo e(request('status') == 'ativo' ? 'selected' : ''); ?>>Ativos</option>
                                        <option value="inativo" <?php echo e(request('status') == 'inativo' ? 'selected' : ''); ?>>Inativos</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                                <div class="input-field col s12 m4">
                                    <button type="submit" class="btn waves-effect waves-light">
                                        <i class="material-icons left">search</i>
                                        Filtrar
                                    </button>
                                    <a href="<?php echo e(route('suppliers.index')); ?>" class="btn waves-effect waves-light red">
                                        <i class="material-icons left">clear</i>
                                        Limpar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela de Fornecedores -->
                    <div class="row">
                        <div class="col s12">
                            <?php if($suppliers->count() > 0): ?>
                                <table class="striped responsive-table">
                                    <thead>
                                        <tr>
                                            <th>Razão Social</th>
                                            <th>Nome Fantasia</th>
                                            <th>CNPJ</th>
                                            <th>Contato</th>
                                            <th>Cidade/UF</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($supplier->razao_social); ?></td>
                                                <td><?php echo e($supplier->nome_fantasia); ?></td>
                                                <td><?php echo e($supplier->formatted_cnpj); ?></td>
                                                <td>
                                                    <?php echo e($supplier->telefone); ?><br>
                                                    <?php echo e($supplier->email); ?>

                                                </td>
                                                <td><?php echo e($supplier->cidade); ?>/<?php echo e($supplier->estado); ?></td>
                                                <td>
                                                    <span class="chip <?php echo e($supplier->ativo ? 'green white-text' : 'red white-text'); ?>">
                                                        <?php echo e($supplier->ativo ? 'Ativo' : 'Inativo'); ?>

                                                    </span>
                                                </td>
                                                <td class="action-buttons">
                                                    <a href="<?php echo e(route('suppliers.show', $supplier)); ?>" class="btn-floating waves-effect waves-light blue tooltipped" data-position="top" data-tooltip="Visualizar">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    
                                                    <a href="<?php echo e(route('suppliers.edit', $supplier)); ?>" class="btn-floating waves-effect waves-light amber tooltipped" data-position="top" data-tooltip="Editar">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                    
                                                    <form action="<?php echo e(route('suppliers.destroy', $supplier)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja alterar o status deste fornecedor?');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn-floating waves-effect waves-light <?php echo e($supplier->ativo ? 'red' : 'green'); ?> tooltipped" data-position="top" data-tooltip="<?php echo e($supplier->ativo ? 'Inativar' : 'Ativar'); ?>">
                                                            <i class="material-icons"><?php echo e($supplier->ativo ? 'block' : 'check'); ?></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                
                                <!-- Paginação -->
                                <div class="row">
                                    <div class="col s12">
                                        <?php echo e($suppliers->appends(request()->query())->links('vendor.pagination.materialize')); ?>

                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="card-panel blue-grey lighten-4">
                                    <span class="blue-text text-darken-2">
                                        <i class="material-icons left">info</i>
                                        Nenhum fornecedor encontrado. 
                                        <a href="<?php echo e(route('suppliers.create')); ?>" class="btn-flat blue-text text-darken-2 waves-effect">Cadastrar novo fornecedor</a>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa os selects
        var selects = document.querySelectorAll('select');
        M.FormSelect.init(selects);
        
        // Inicializa os tooltips
        var tooltips = document.querySelectorAll('.tooltipped');
        M.Tooltip.init(tooltips);
        
        // Mensagens de feedback
        <?php if(session('success')): ?>
            M.toast({html: '<?php echo e(session("success")); ?>', classes: 'green'});
        <?php endif; ?>
        
        <?php if(session('error')): ?>
            M.toast({html: '<?php echo e(session("error")); ?>', classes: 'red'});
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    
    .card-title h4 {
        margin-top: 0;
        margin-bottom: 0;
    }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/suppliers/index.blade.php ENDPATH**/ ?>