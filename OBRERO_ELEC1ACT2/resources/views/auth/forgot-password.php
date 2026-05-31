<?php
require_once __DIR__ . '/../lib/auth-page.php';

$errors   = session()->get('errors');
$status   = session()->get('status');
$emailErr = $errors ? $errors->first('email')    : null;
$passErr  = $errors ? $errors->first('password') : null;
$firstErr = $emailErr ?? $passErr;

return AuthPage::build('Reset Password', function (DOMElement $card) use ($firstErr, $status): void {

    $h2 = Dom::el('h2', ['class' => 'text-2xl font-extrabold text-gray-900 mb-1']);
    $h2->appendChild(Dom::text('Reset Password'));
    $card->appendChild($h2);

    $sub = Dom::el('p', ['class' => 'text-sm text-gray-500 mb-6']);
    $sub->appendChild(Dom::text('Enter your account email and choose a new password.'));
    $card->appendChild($sub);

    // Success banner
    if ($status) {
        $msg = Dom::el('div', ['class' => 'mb-5 flex items-center gap-2 p-3.5 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium']);
        $ic  = Dom::el('i',  ['class' => 'fas fa-check-circle text-green-500 flex-shrink-0']);
        $ic->appendChild(Dom::text(''));
        $msg->appendChild($ic);
        $msg->appendChild(Dom::text(' ' . htmlspecialchars((string) $status, ENT_QUOTES, 'UTF-8')));
        $card->appendChild($msg);
    }

    // Error banner
    if ($firstErr) {
        $err = Dom::el('div', ['class' => 'mb-5 flex items-center gap-2 p-3.5 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm font-medium']);
        $ic  = Dom::el('i',  ['class' => 'fas fa-exclamation-circle text-red-500 flex-shrink-0']);
        $ic->appendChild(Dom::text(''));
        $err->appendChild($ic);
        $err->appendChild(Dom::text(' ' . htmlspecialchars((string) $firstErr, ENT_QUOTES, 'UTF-8')));
        $card->appendChild($err);
    }

    $form = Dom::el('form', ['method' => 'POST', 'action' => '/forgot-password', 'novalidate' => 'novalidate']);
    $csrf = Dom::el('input', ['type' => 'hidden', 'name' => '_token', 'value' => csrf_token()]);
    $form->appendChild($csrf);

    $form->appendChild(AuthPage::field('Account Email',    'email',    'email',                 'email',                 'you@example.com',     old('email', ''), 'fa-envelope'));
    $form->appendChild(AuthPage::field('New Password',     'password', 'password',              'password',              'Min 8 characters',    '',               'fa-lock'));
    $form->appendChild(AuthPage::field('Confirm Password', 'password', 'password_confirmation', 'password_confirmation', 'Repeat new password', '',               'fa-lock'));

    $btn = Dom::el('button', [
        'type'  => 'submit',
        'class' => 'w-full bg-green-600 hover:bg-green-700 active:scale-95 text-white font-bold py-3 rounded-xl transition text-sm shadow-sm mt-1',
    ]);
    $btn->appendChild(Dom::text('Reset Password'));
    $form->appendChild($btn);

    $card->appendChild($form);

    $sep = Dom::el('div', ['class' => 'mt-6 text-center text-sm text-gray-500']);
    $sep->appendChild(Dom::text('Remember your password? '));
    $al = Dom::el('a', ['href' => '/login', 'class' => 'text-green-600 font-semibold hover:underline']);
    $al->appendChild(Dom::text('Sign in'));
    $sep->appendChild($al);
    $card->appendChild($sep);
});
