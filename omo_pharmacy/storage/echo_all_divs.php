<?php

declare(strict_types=1);

$base = __DIR__ . '/../resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));

$changed = 0;
$scanned = 0;

foreach ($iterator as $file) {
    if (!$file->isFile() || !str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $file->getPathname();
    $contents = file_get_contents($path);
    if ($contents === false) {
        continue;
    }

    $scanned++;

    $updated = preg_replace_callback('/<div\b[^>]*>|<\/div>/i', static function (array $m): string {
        $tag = $m[0];
        return '<?php echo ' . var_export($tag, true) . '; ?>';
    }, $contents);

    if ($updated === null) {
        continue;
    }

    if ($updated !== $contents) {
        file_put_contents($path, $updated);
        $changed++;
        echo "Div-echo updated: {$path}\n";
    }
}

echo "Scanned {$scanned}; changed {$changed}.\n";
