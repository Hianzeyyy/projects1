<?php

declare(strict_types=1);

use Illuminate\Support\Arr;

$base = __DIR__ . '/../resources/views';

function writeFile(string $path, string $content): void
{
    file_put_contents($path, $content);
    echo "Updated: {$path}\n";
}

function convertAppLayoutPage(string $path): void
{
    $content = file_get_contents($path);
    if ($content === false || !str_contains($content, '<x-app-layout')) {
        return;
    }

    $start = strpos($content, '<x-app-layout');
    $prefix = substr($content, 0, $start);
    $rest = substr($content, $start);

    if (!preg_match('/^<x-app-layout([^>]*)>/m', $rest, $openMatch)) {
        return;
    }

    $openTag = $openMatch[0];
    $attrs = $openMatch[1] ?? '';
    $bodyClass = '';
    if (preg_match('/body-class="([^"]*)"/', $attrs, $m)) {
        $bodyClass = $m[1];
    }

    $inner = preg_replace('/^<x-app-layout[^>]*>/m', '', $rest, 1);
    $inner = preg_replace('/<\/x-app-layout>\s*$/m', '', (string) $inner, 1);

    $header = '';
    if (preg_match('/<x-slot\s+name="header">([\s\S]*?)<\/x-slot>/m', (string) $inner, $slotMatch)) {
        $header = trim($slotMatch[1]);
        $inner = str_replace($slotMatch[0], '', (string) $inner);
    }

    $inner = trim((string) $inner) . "\n";

    $out = rtrim($prefix) . "\n";
    if ($bodyClass !== '') {
        $out .= "<?php \$bodyClass = '" . addslashes($bodyClass) . "'; ?>\n";
    }
    if ($header !== '') {
        $out .= "<?php ob_start(); ?>\n" . $header . "\n<?php \$header = ob_get_clean(); ?>\n";
    }

    $out .= "<?php ob_start(); ?>\n";
    $out .= $inner;
    $out .= "<?php \$slot = ob_get_clean(); ?>\n";
    $out .= "<?php echo \$__env->make('layouts.app', Arr::except(get_defined_vars(), [\"__data\", \"__path\"]))->render(); ?>\n";

    writeFile($path, $out);
}

function convertGuestLayoutPage(string $path): void
{
    $content = file_get_contents($path);
    if ($content === false || !str_contains($content, '<x-guest-layout>')) {
        return;
    }

    $inner = preg_replace('/^<x-guest-layout>\s*/m', '', $content, 1);
    $inner = preg_replace('/\s*<\/x-guest-layout>\s*$/m', '', (string) $inner, 1);

    $inner = str_replace('<x-primary-button>', '<button type="submit" class="btn btn-primary">', (string) $inner);
    $inner = str_replace('</x-primary-button>', '</button>', (string) $inner);

    // Handle the confirm-password page custom components.
    $inner = preg_replace('/<x-input-label\s+for="password"\s+:value="__\(\'Password\'\)"\s*\/>/', '<label for="password"><?= __(\'Password\') ?></label>', (string) $inner);
    $inner = preg_replace('/<x-text-input\s+id="password"\s+class="block mt-1 w-full"\s+type="password"\s+name="password"\s+required autocomplete="current-password"\s*\/>/s', '<input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password">', (string) $inner);
    $inner = preg_replace('/<x-input-error\s+:messages="\$errors->get\(\'password\'\)"\s+class="mt-2"\s*\/>/', '<?php foreach ($errors->get(\'password\') as $message): ?><div class="error-text mt-2"><?= e($message) ?></div><?php endforeach; ?>', (string) $inner);

    $out = "<?php ob_start(); ?>\n" . trim((string) $inner) . "\n<?php \$slot = ob_get_clean(); ?>\n";
    $out .= "<?php echo \$__env->make('layouts.guest', Arr::except(get_defined_vars(), [\"__data\", \"__path\"]))->render(); ?>\n";

    writeFile($path, $out);
}

$targets = [
    $base . '/dashboard/index.blade.php',
    $base . '/management/index.blade.php',
    $base . '/records/index.blade.php',
    $base . '/details.blade.php',
    $base . '/auth/confirm-password.blade.php',
    $base . '/auth/verify-email.blade.php',
];

foreach ($targets as $target) {
    if (!is_file($target)) {
        continue;
    }

    convertAppLayoutPage($target);
    convertGuestLayoutPage($target);
}

$all = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
foreach ($all as $file) {
    if (!$file->isFile() || !str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $file->getPathname();
    $content = file_get_contents($path);
    if ($content === false) {
        continue;
    }

    $updated = preg_replace('/@vite\((.*?)\)/s', '<?php echo app()->make(\'vite\')($1); ?>', $content);

    if (str_contains((string) $updated, '<x-application-logo')) {
        $updated = preg_replace('/<x-application-logo[^>]*\/>/', '<span class="app-logo-text">PHARMACY</span>', (string) $updated);
    }

    if ($updated !== $content) {
        writeFile($path, (string) $updated);
    }
}

echo "Pure PHP conversion pass complete.\n";
