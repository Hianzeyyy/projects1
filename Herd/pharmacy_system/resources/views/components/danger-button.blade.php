<?php
$attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag();
$slot = $slot ?? '';
?>
<button <?= $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-violet-600 border border-violet-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 active:bg-violet-800 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:ring-offset-2 transition ease-in-out duration-150']) ?>>
    <?= $slot ?>
</button>
