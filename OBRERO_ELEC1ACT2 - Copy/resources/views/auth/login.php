<?php
require_once __DIR__ . '/../lib/auth-page.php';

$errors   = session()->get('errors');
$status   = session()->get('status');
$emailErr = $errors ? $errors->first('email') : null;
$oldEmail = old('email', '');

$pageJS = <<<'JS'
document.querySelectorAll('.js-password-toggle').forEach(function (button) {
    button.addEventListener('click', function () {
        var targetId = button.getAttribute('data-target');
        if (!targetId) {
            return;
        }

        var input = document.getElementById(targetId);
        if (!input || input.tagName !== 'INPUT') {
            return;
        }

        var nextType = input.type === 'password' ? 'text' : 'password';
        input.type = nextType;

        var icon = button.querySelector('i');
        var isVisible = nextType === 'text';

        button.setAttribute('aria-pressed', isVisible ? 'true' : 'false');
        button.setAttribute('aria-label', isVisible ? 'Hide password' : 'Show password');

        if (icon) {
            icon.className = 'fas ' + (isVisible ? 'fa-eye-slash' : 'fa-eye') + ' text-sm';
        }
    });
});
JS;

return AuthPage::build('Sign In', function (DOMElement $card) use ($emailErr, $status, $oldEmail): void {

    $h2 = Dom::el('h2', ['class' => 'text-2xl font-extrabold text-gray-900 mb-1']);
    $h2->appendChild(Dom::text('Welcome back'));
    $card->appendChild($h2);

    $sub = Dom::el('p', ['class' => 'text-sm text-gray-500 mb-6']);
    $sub->appendChild(Dom::text('Sign in to access the pharmacy system.'));
    $card->appendChild($sub);

    // Status banner (e.g. after password reset)
    if ($status) {
        $msg = Dom::el('div', ['class' => 'mb-5 flex items-center gap-2 p-3.5 bg-violet-50 border border-violet-200 rounded-xl text-violet-700 text-sm font-medium']);
        $ic  = Dom::el('i',  ['class' => 'fas fa-check-circle text-violet-500 flex-shrink-0']);
        $ic->appendChild(Dom::text(''));
        $msg->appendChild($ic);
        $msg->appendChild(Dom::text(' ' . htmlspecialchars((string) $status, ENT_QUOTES, 'UTF-8')));
        $card->appendChild($msg);
    }

    // Error banner
    if ($emailErr) {
        $err = Dom::el('div', ['class' => 'mb-5 flex items-center gap-2 p-3.5 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm font-medium']);
        $ic  = Dom::el('i',  ['class' => 'fas fa-exclamation-circle text-red-500 flex-shrink-0']);
        $ic->appendChild(Dom::text(''));
        $err->appendChild($ic);
        $err->appendChild(Dom::text(' ' . htmlspecialchars((string) $emailErr, ENT_QUOTES, 'UTF-8')));
        $card->appendChild($err);
    }

    $form = Dom::el('form', ['method' => 'POST', 'action' => '/login', 'novalidate' => 'novalidate']);
    $csrf = Dom::el('input', ['type' => 'hidden', 'name' => '_token', 'value' => csrf_token()]);
    $form->appendChild($csrf);

    $form->appendChild(AuthPage::field('Email address', 'email', 'email', 'email', 'you@example.com', $oldEmail, 'fa-envelope'));
    $form->appendChild(AuthPage::field('Password', 'password', 'password', 'password', "\xe2\x80\xa2\xe2\x80\xa2\xe2\x80\xa2\xe2\x80\xa2\xe2\x80\xa2\xe2\x80\xa2\xe2\x80\xa2\xe2\x80\xa2", '', 'fa-lock', true));

    // Remember me + Forgot password row
    $row  = Dom::el('div', ['class' => 'flex items-center justify-between mb-6']);
    $rml  = Dom::el('label', ['class' => 'flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none']);
    $rmcb = Dom::el('input', ['type' => 'checkbox', 'name' => 'remember', 'class' => 'rounded border-gray-300 text-violet-600 cursor-pointer']);
    $rml->appendChild($rmcb);
    $rml->appendChild(Dom::text(' Remember me'));
    $row->appendChild($rml);
    $fp = Dom::el('a', ['href' => '/forgot-password', 'class' => 'text-sm text-violet-600 font-semibold hover:underline']);
    $fp->appendChild(Dom::text('Forgot password?'));
    $row->appendChild($fp);
    $form->appendChild($row);

    $btn = Dom::el('button', [
        'type'  => 'submit',
        'class' => 'w-full bg-violet-600 hover:bg-violet-700 active:scale-95 text-white font-bold py-3 rounded-xl transition text-sm shadow-sm',
    ]);
    $btn->appendChild(Dom::text('Sign In'));
    $form->appendChild($btn);

    $card->appendChild($form);

    // Register link
    $sep = Dom::el('div', ['class' => 'mt-6 text-center text-sm text-gray-500']);
    $sep->appendChild(Dom::text("Don\xe2\x80\x99t have an account? "));
    $al = Dom::el('a', ['href' => '/signup', 'class' => 'text-violet-600 font-semibold hover:underline']);
    $al->appendChild(Dom::text('Create one'));
    $sep->appendChild($al);
    $card->appendChild($sep);
}, $pageJS);
