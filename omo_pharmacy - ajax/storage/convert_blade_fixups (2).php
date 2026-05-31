<?php

declare(strict_types=1);

$base = __DIR__ . '/../resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));

$changed = 0;
$files = 0;

foreach ($iterator as $file) {
    if (!$file->isFile() || !str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $file->getPathname();
    $contents = file_get_contents($path);
    if ($contents === false) {
        continue;
    }

    $updated = $contents;

    // Fix malformed control structures produced by simple regex conversion.
    $updated = str_replace(': ?>)', '): ?>', $updated);

    // Convert Blade forelse blocks.
    $updated = preg_replace('/@forelse\s*\((.*?)\)/', '<?php $__empty = true; foreach ($1): $__empty = false; ?>', $updated);
    $updated = preg_replace('/@empty\b/', '<?php endforeach; if ($__empty): ?>', $updated);
    $updated = preg_replace('/@endforelse\b/', '<?php endif; ?>', $updated);

    // Convert includeIf.
    $updated = preg_replace('/@includeIf\((.*?)\)/', '<?php if ($__env->exists($1)) echo $__env->make($1, \\Illuminate\\Support\\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>', $updated);

    // Normalize comment artifacts from Blade comments.
    $updated = str_replace('<?= e(-- SHOW VIEWS --) ?>', '<?php /* SHOW VIEWS */ ?>', $updated);
    $updated = str_replace('<?= e(-- EDIT VIEWS --) ?>', '<?php /* EDIT VIEWS */ ?>', $updated);

    $files++;
    if ($updated !== $contents) {
        file_put_contents($path, $updated);
        $changed++;
        echo "Fixed: {$path}\n";
    }
}

echo "Scanned {$files} Blade files; fixed {$changed}.\n";
