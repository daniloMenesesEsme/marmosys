<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('app.name')); ?> - <?php echo $__env->yieldContent('title', 'Login'); ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body class="grey lighten-4">
    <nav class="blue darken-2">
        <div class="nav-wrapper container">
            <a href="<?php echo e(url('/')); ?>" class="brand-logo"><?php echo e(config('app.name')); ?></a>
        </div>
    </nav>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH C:\laragon\www\marmosys\resources\views/layouts/guest.blade.php ENDPATH**/ ?>