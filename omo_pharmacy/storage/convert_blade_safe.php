<?php

declare(strict_types=1);

$base = __DIR__ . '/../resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));

$replacements = [
    '/\{!!\s*(.*?)\s*!!\}/s' => '<?= $1 ?>',
    '/\{\{\s*(.*?)\s*\}\}/s' => '<?= e($1) ?>',

    '/@csrf\b/' => '<?= csrf_field() ?>',
    '/@method\((.*?)\)/' => '<?= method_field($1) ?>',

    '/@if\s*\((.*?)\)/' => '<?php if ($1): ?>',
    '/@elseif\s*\((.*?)\)/' => '<?php elseif ($1): ?>',
    '/@else\b/' => '<?php else: ?>',
    '/@endif\b/' => '<?php endif; ?>',

    '/@foreach\s*\((.*?)\)/' => '<?php foreach ($1): ?>',
    '/@endforeach\b/' => '<?php endforeach; ?>',

    '/@for\s*\((.*?)\)/' => '<?php for ($1): ?>',
    '/@endfor\b/' => '<?php endfor; ?>',

    '/@while\s*\((.*?)\)/' => '<?php while ($1): ?>',
    '/@endwhile\b/' => '<?php endwhile; ?>',

    '/@isset\s*\((.*?)\)/' => '<?php if (isset($1)): ?>',
    '/@endisset\b/' => '<?php endif; ?>',

    '/@empty\s*\((.*?)\)/' => '<?php if (empty($1)): ?>',
    '/@endempty\b/' => '<?php endif; ?>',

    '/@php\b/' => '<?php',
    '/@endphp\b/' => '?>',

    '/@error\((.*?)\)/' => '<?php $__key = $1; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>',
    '/@enderror\b/' => '<?php endforeach; endif; ?>',

    '/@include\((.*?)\)/' => '<?php echo $__env->make($1, \Illuminate\Support\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>',

    '/\{\{--\s*(.*?)\s*--\}\}/s' => '<?php /* $1 */ ?>',
];

$changed = 0;
$files = 0;

foreach ($iterator as $file) {
    if (!$file->isFile()) {
        continue;
    }

    if (!str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $file->getPathname();
    $contents = file_get_contents($path);
    if ($contents === false) {
        continue;
    }

    $updated = preg_replace(array_keys($replacements), array_values($replacements), $contents);
    if ($updated === null) {
        continue;
    }

    $files++;
    if ($updated !== $contents) {
        file_put_contents($path, $updated);
        $changed++;
        echo "Converted: {$path}\n";
    }
}

echo "Scanned {$files} Blade files; changed {$changed}.\n";
