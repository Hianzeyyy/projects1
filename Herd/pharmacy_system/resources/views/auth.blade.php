<?php if ($type === 'register'): ?>
    <?php include resource_path('views/authentication/register.blade.php'); ?>
<?php else: ?>
    <?php include resource_path('views/authentication/login.blade.php'); ?>
<?php endif; ?>
