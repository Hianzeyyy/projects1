<?php ob_start(); ?>
<?php echo '<div class="mb-4 text-sm text-gray-600">'; ?>
        <?= __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') ?>
    <?php echo '</div>'; ?>

    <?php if(session('status') == 'verification-link-sent'): ?>
        <?php echo '<div class="mb-4 font-medium text-sm text-blue-600">'; ?>
            <?= __('A new verification link has been sent to the email address you provided during registration.') ?>
        <?php echo '</div>'; ?>
    <?php endif; ?>

    <?php echo '<div class="mt-4 flex items-center justify-between">'; ?>
        <form method="POST" action="<?= route('verification.send', [], false) ?>">
            <?php echo csrf_field() ?>

            <?php echo '<div>'; ?>
                <button type="submit" class="btn btn-primary">
                    <?= __('Resend Verification Email') ?>
                </button>
            <?php echo '</div>'; ?>
        </form>

        <form method="POST" action="<?= route('logout', [], false) ?>">
            <?php echo csrf_field() ?>

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <?= __('Log Out') ?>
            </button>
        </form>
    <?php echo '</div>'; ?>
<?php $slot = ob_get_clean(); ?>
<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>
