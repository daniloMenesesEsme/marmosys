<?php if($paginator->hasPages()): ?>
    <div class="row">
        <div class="col s12">
            <ul class="pagination center-align">
                
                <?php if($paginator->onFirstPage()): ?>
                    <li>
                        <a href="#!" class="disabled"><i class="material-icons">chevron_left</i></a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev"><i class="material-icons">chevron_left</i></a>
                    </li>
                <?php endif; ?>

                
                <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if(is_string($element)): ?>
                        <li>
                            <a href="#!" class="disabled"><?php echo e($element); ?></a>
                        </li>
                    <?php endif; ?>

                    
                    <?php if(is_array($element)): ?>
                        <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($page == $paginator->currentPage()): ?>
                                <li>
                                    <a href="#!" class="active blue"><?php echo e($page); ?></a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php if($paginator->hasMorePages()): ?>
                    <li>
                        <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next"><i class="material-icons">chevron_right</i></a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="#!" class="disabled"><i class="material-icons">chevron_right</i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col s12 center-align">
            <p class="grey-text">
                <?php echo __('Mostrando'); ?>

                <?php if($paginator->firstItem()): ?>
                    <span class="bold"><?php echo e($paginator->firstItem()); ?></span>
                    <?php echo __('até'); ?>

                    <span class="bold"><?php echo e($paginator->lastItem()); ?></span>
                <?php else: ?>
                    <?php echo e($paginator->count()); ?>

                <?php endif; ?>
                <?php echo __('de'); ?>

                <span class="bold"><?php echo e($paginator->total()); ?></span>
                <?php echo __('resultados'); ?>

            </p>
        </div>
    </div>
<?php endif; ?> <?php /**PATH C:\laragon\www\marmosys\resources\views/vendor/pagination/materialize.blade.php ENDPATH**/ ?>