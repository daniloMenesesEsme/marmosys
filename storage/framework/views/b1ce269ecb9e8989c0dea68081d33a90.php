

<?php $__env->startSection('title', 'Detalhes do Vendedor'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        <div class="row">
                            <div class="col s12 m6">
                                <h4>
                                    <i class="material-icons left">person</i> 
                                    <?php echo e($seller->nome); ?>

                                    <span class="chip <?php echo e($seller->ativo ? 'green white-text' : 'red white-text'); ?>">
                                        <?php echo e($seller->ativo ? 'Ativo' : 'Inativo'); ?>

                                    </span>
                                </h4>
                            </div>
                            <div class="col s12 m6 right-align">
                                <a href="<?php echo e(route('sellers.edit', $seller)); ?>" class="btn waves-effect waves-light amber">
                                    <i class="material-icons left">edit</i>
                                    Editar
                                </a>
                                
                                <a href="<?php echo e(route('sellers.index')); ?>" class="btn waves-effect waves-light blue">
                                    <i class="material-icons left">arrow_back</i>
                                    Voltar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <ul class="tabs tabs-fixed-width">
                                <li class="tab col s3"><a class="active" href="#dados-pessoais">Dados Pessoais</a></li>
                                <li class="tab col s3"><a href="#endereco">Endereço</a></li>
                                <li class="tab col s3"><a href="#dados-profissionais">Dados Profissionais</a></li>
                                <li class="tab col s3"><a href="#vendas">Vendas</a></li>
                            </ul>
                        </div>
                        
                        <!-- Aba Dados Pessoais -->
                        <div id="dados-pessoais" class="col s12">
                            <div class="card-panel">
                                <div class="row">
                                    <div class="col s12 m6">
                                        <div class="info-item">
                                            <span class="info-label">Nome Completo:</span>
                                            <span class="info-value"><?php echo e($seller->nome); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m6">
                                        <div class="info-item">
                                            <span class="info-label">CPF:</span>
                                            <span class="info-value"><?php echo e($seller->cpf); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m6">
                                        <div class="info-item">
                                            <span class="info-label">E-mail:</span>
                                            <span class="info-value"><?php echo e($seller->email); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m6">
                                        <div class="info-item">
                                            <span class="info-label">Telefone:</span>
                                            <span class="info-value"><?php echo e($seller->telefone); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Aba Endereço -->
                        <div id="endereco" class="col s12">
                            <div class="card-panel">
                                <div class="row">
                                    <div class="col s12 m4">
                                        <div class="info-item">
                                            <span class="info-label">CEP:</span>
                                            <span class="info-value"><?php echo e($seller->cep ?: 'Não informado'); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m8">
                                        <div class="info-item">
                                            <span class="info-label">Logradouro:</span>
                                            <span class="info-value"><?php echo e($seller->endereco ?: 'Não informado'); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m4">
                                        <div class="info-item">
                                            <span class="info-label">Número:</span>
                                            <span class="info-value"><?php echo e($seller->numero ?: 'Não informado'); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m8">
                                        <div class="info-item">
                                            <span class="info-label">Complemento:</span>
                                            <span class="info-value"><?php echo e($seller->complemento ?: 'Não informado'); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m4">
                                        <div class="info-item">
                                            <span class="info-label">Bairro:</span>
                                            <span class="info-value"><?php echo e($seller->bairro ?: 'Não informado'); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m4">
                                        <div class="info-item">
                                            <span class="info-label">Cidade:</span>
                                            <span class="info-value"><?php echo e($seller->cidade ?: 'Não informado'); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m4">
                                        <div class="info-item">
                                            <span class="info-label">Estado:</span>
                                            <span class="info-value"><?php echo e($seller->estado ?: 'Não informado'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Mapa (se houver endereço completo) -->
                                <?php if($seller->endereco && $seller->cidade && $seller->estado): ?>
                                <div class="row">
                                    <div class="col s12">
                                        <div class="map-container">
                                            <div id="map" style="height: 300px; width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Aba Dados Profissionais -->
                        <div id="dados-profissionais" class="col s12">
                            <div class="card-panel">
                                <div class="row">
                                    <div class="col s12 m6">
                                        <div class="info-item">
                                            <span class="info-label">Percentual de Comissão:</span>
                                            <span class="info-value"><?php echo e(number_format($seller->percentual_comissao, 2)); ?>%</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m6">
                                        <div class="info-item">
                                            <span class="info-label">Meta Mensal:</span>
                                            <span class="info-value">R$ <?php echo e(number_format($seller->meta_mensal, 2, ',', '.')); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12">
                                        <div class="info-item">
                                            <span class="info-label">Observações:</span>
                                            <span class="info-value"><?php echo e($seller->observacoes ?: 'Nenhuma observação registrada'); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m6">
                                        <div class="info-item">
                                            <span class="info-label">Cadastrado em:</span>
                                            <span class="info-value"><?php echo e($seller->created_at->format('d/m/Y H:i')); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m6">
                                        <div class="info-item">
                                            <span class="info-label">Última atualização:</span>
                                            <span class="info-value"><?php echo e($seller->updated_at->format('d/m/Y H:i')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Aba Vendas -->
                        <div id="vendas" class="col s12">
                            <div class="card-panel">
                                <!-- Resumo das Vendas -->
                                <div class="row">
                                    <div class="col s12 m4">
                                        <div class="card-panel green white-text center-align">
                                            <i class="material-icons medium">shopping_cart</i>
                                            <h5>Total de Vendas</h5>
                                            <h4>0</h4>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m4">
                                        <div class="card-panel blue white-text center-align">
                                            <i class="material-icons medium">attach_money</i>
                                            <h5>Valor Total</h5>
                                            <h4>R$ 0,00</h4>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m4">
                                        <div class="card-panel orange white-text center-align">
                                            <i class="material-icons medium">trending_up</i>
                                            <h5>Comissões</h5>
                                            <h4>R$ 0,00</h4>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Lista de Vendas (quando tiver o módulo de vendas implementado) -->
                                <div class="row">
                                    <div class="col s12">
                                        <p class="center-align">
                                            <i class="material-icons medium grey-text">info</i><br>
                                            O histórico de vendas estará disponível quando o módulo de vendas for implementado.
                                        </p>
                                    </div>
                                </div>
                            </div>
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
        // Inicializa as tabs
        var tabs = document.querySelectorAll('.tabs');
        M.Tabs.init(tabs);
        
        // Inicializa o mapa (se houver elementos com ID 'map')
        if (document.getElementById('map')) {
            const address = "<?php echo e($seller->endereco ?? ''); ?>, <?php echo e($seller->numero ?? ''); ?>, <?php echo e($seller->cidade ?? ''); ?>, <?php echo e($seller->estado ?? ''); ?>";
            
            // Aqui seria a implementação do mapa com Google Maps ou outra API
            // Este é apenas um placeholder para uma futura implementação
            console.log("Endereço para o mapa:", address);
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .info-item {
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .info-label {
        font-weight: bold;
        color: #26a69a;
        display: block;
    }
    
    .info-value {
        display: block;
        margin-top: 5px;
        font-size: 1.1rem;
    }
    
    .tabs .tab a {
        color: rgba(38, 166, 154, 0.7);
    }
    
    .tabs .tab a:hover, .tabs .tab a.active {
        color: #26a69a;
    }
    
    .tabs .indicator {
        background-color: #26a69a;
    }
    
    .map-container {
        margin-top: 20px;
        border: 1px solid #ddd;
        border-radius: 2px;
    }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/sellers/show.blade.php ENDPATH**/ ?>