<?php ob_start(); ?>
<?php echo '<div class="mb-4 text-sm text-gray-600">'; ?>
        <?= __('This is a secure area of the application. Please confirm your password before continuing.') ?>
    <?php echo '</div>'; ?>

    <form method="POST" action="<?= route('password.confirm') ?>">
        <?php echo csrf_field() ?>

        <!-- Password -->
        <?php echo '<div>'; ?>
            <label for="password"><?= __('Password') ?></label>

            <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password">

            <?php foreach ($errors->get('password') as $message): ?><?php echo '<div class="error-text mt-2">'; ?><?= e($message) ?><?php echo '</div>'; ?><?php endforeach; ?>
        <?php echo '</div>'; ?>

        <?php echo '<div class="flex justify-end mt-4">'; ?>
            <button type="submit" class="btn btn-primary">
                <?= __('Confirm') ?>
            </button>
        <?php echo '</div>'; ?>
    </form>
<?php $slot = ob_get_clean(); ?>
<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>
