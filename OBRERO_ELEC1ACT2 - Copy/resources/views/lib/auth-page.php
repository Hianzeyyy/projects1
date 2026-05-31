<?php
require_once __DIR__ . '/dom.php';

/**
 * Builds a centered auth-page (no sidebar).
 * Usage: return AuthPage::build($title, fn(DOMElement $card):void { ... }, $optionalJS);
 */
class AuthPage
{
    /**
     * Resolve compiled app stylesheet URL with a static fallback.
     */
    private static function appCssHref(): string
    {
        try {
            return \Illuminate\Support\Facades\Vite::asset('resources/css/app.css');
        } catch (\Throwable $e) {
            return '/css/app.css';
        }
    }

    public static function build(string $title, callable $content, string $pageJS = ''): string
    {
        Dom::reset();

        $html = Dom::el('html', ['lang' => 'en']);
        Dom::doc()->appendChild($html);

        // ── <head> ──────────────────────────────────────────────────────
        $head = Dom::el('head');
        $html->appendChild($head);

        $head->appendChild(Dom::el('meta', ['charset' => 'UTF-8']));
        $head->appendChild(Dom::el('meta', [
            'name'    => 'viewport',
            'content' => 'width=device-width, initial-scale=1.0',
        ]));

        $t = Dom::el('title');
        $t->appendChild(Dom::text($title . ' \xe2\x80\x94 PharmaCare'));
        $head->appendChild($t);

        $head->appendChild(Dom::el('link', [
            'rel'  => 'stylesheet',
            'href' => self::appCssHref(),
        ]));

        $head->appendChild(Dom::el('link', [
            'rel'  => 'stylesheet',
            'href' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        ]));

        $style = Dom::el('style');
        $style->appendChild(Dom::text(
            'body{font-family:Inter,ui-sans-serif,system-ui,sans-serif}' .
            '.ring-inp:focus{outline:none;border-color:#b651f0;box-shadow:0 0 0 3px rgba(182,81,240,.15)}'
        ));
        $head->appendChild($style);

        // ── <body> ──────────────────────────────────────────────────────
        $body = Dom::el('body', [
            'class' => 'min-h-screen bg-gradient-to-br from-[#f3e6fa] via-white to-[#e9d3fa] flex items-center justify-center p-4',
        ]);
        $html->appendChild($body);

        $wrap = Dom::el('div', ['class' => 'w-full max-w-md']);
        $body->appendChild($wrap);

        // Brand header
        $brand = Dom::el('div', ['class' => 'text-center mb-8']);
        $iw    = Dom::el('div', ['class' => 'inline-flex h-16 w-16 bg-[#b651f0] rounded-2xl items-center justify-center shadow-lg mb-4']);
        $ic    = Dom::el('i',   ['class' => 'fas fa-pills text-white text-2xl']);
        $ic->appendChild(Dom::text(''));
        $iw->appendChild($ic);
        $h1 = Dom::el('h1', ['class' => 'text-3xl font-extrabold text-gray-900']);
        $h1->appendChild(Dom::text('PharmaCare'));
        $sub = Dom::el('p', ['class' => 'text-sm text-gray-500 mt-1']);
        $sub->appendChild(Dom::text('Pharmacy Management System'));
        $brand->appendChild($iw);
        $brand->appendChild($h1);
        $brand->appendChild($sub);
        $wrap->appendChild($brand);

        // Card
        $card = Dom::el('div', ['class' => 'bg-white rounded-3xl shadow-xl p-8']);
        $wrap->appendChild($card);

        $content($card);

        if (trim($pageJS) !== '') {
            $body->appendChild(Dom::script($pageJS));
        }

        return Dom::html();
    }

    /**
     * Build a labeled input field wrapped in a div.
     */
    public static function field(
        string $label,
        string $type,
        string $name,
        string $id,
        string $placeholder = '',
        string $value       = '',
        string $icon        = '',
        bool $togglePassword = false
    ): DOMElement {
        $wrap = Dom::el('div', ['class' => 'mb-5']);

        $lbl = Dom::el('label', ['for' => $id, 'class' => 'block text-sm font-semibold text-gray-700 mb-1.5']);
        $lbl->appendChild(Dom::text($label));
        $wrap->appendChild($lbl);

        $iw = Dom::el('div', ['class' => 'relative']);

        if ($icon) {
            $ico = Dom::el('i', [
                'class' => 'fas ' . $icon . ' absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none',
            ]);
            $ico->appendChild(Dom::text(''));
            $iw->appendChild($ico);
        }

        $inputClass = 'ring-inp w-full ' . ($icon ? 'pl-10 ' : '') . (($togglePassword && $type === 'password') ? 'pr-10 ' : 'pr-4 ') . 'py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white text-sm transition';
        $attrs = [
            'type'        => $type,
            'name'        => $name,
            'id'          => $id,
            'placeholder' => $placeholder,
            'class'       => $inputClass,
        ];
        if ($togglePassword && $type === 'password') {
            $attrs['style'] = 'padding-right:2.5rem;';
        }
        if ($value !== '') {
            $attrs['value'] = $value;
        }
        $iw->appendChild(Dom::el('input', $attrs));

        if ($togglePassword && $type === 'password') {
            $btn = Dom::el('button', [
                'type' => 'button',
                'class' => 'js-password-toggle text-gray-400 hover:text-gray-600 transition',
                'style' => 'position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:transparent;border:0;padding:0;line-height:1;cursor:pointer;',
                'aria-label' => 'Show password',
                'aria-pressed' => 'false',
                'data-target' => $id,
            ]);
            $eye = Dom::el('i', ['class' => 'fas fa-eye text-sm']);
            $eye->appendChild(Dom::text(''));
            $btn->appendChild($eye);
            $iw->appendChild($btn);
        }

        $wrap->appendChild($iw);

        return $wrap;
    }
}
