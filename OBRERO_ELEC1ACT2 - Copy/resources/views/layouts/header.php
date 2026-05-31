<?php
function render_header(string $pageTitle, string $pageHeading, string $activePage): void
{
    $navLinks = [
        ['key' => 'dashboard', 'href' => '/',          'icon' => 'fa-gauge-high',  'label' => 'Dashboard'],
        ['key' => 'medicines', 'href' => '/medicines',  'icon' => 'fa-capsules',    'label' => 'Medicines'],
        ['key' => 'inventory', 'href' => '/inventory',  'icon' => 'fa-layer-group', 'label' => 'Inventory'],
        ['key' => 'sales',     'href' => '/sales',      'icon' => 'fa-receipt',     'label' => 'Sales'],
        ['key' => 'suppliers', 'href' => '/suppliers',  'icon' => 'fa-truck-fast',  'label' => 'Suppliers'],
    ];

    $safeTitle   = htmlspecialchars($pageTitle);
    $safeHeading = htmlspecialchars($pageHeading);
    $today       = date('M d, Y');

    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '  <meta charset="UTF-8">';
    echo '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo "  <title>{$safeTitle} &mdash; Pharmacy Management System</title>";
    echo '  <script src="https://cdn.tailwindcss.com"></script>';
    echo '  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">';
    echo '  <style>';
    echo '    body { font-family: system-ui, -apple-system, sans-serif; }';
    echo '    .sidebar-active { background: #b651f0 !important; color: #fff !important; }';
    echo '    .sidebar-active i { color: #b651f0 !important; }';
    echo '    .spin { animation: spin 0.8s linear infinite; }';
    echo '    @keyframes spin { to { transform: rotate(360deg); } }';
    echo '    ::-webkit-scrollbar { width: 5px; height: 5px; }';
    echo '    ::-webkit-scrollbar-track { background: transparent; }';
    echo '    ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }';
    echo '    .tbl-row { transition: background 0.1s; }';
    echo '    .tbl-row:hover { background: #f9fafb; }';
    echo '    .card-lift { transition: transform .15s, box-shadow .15s; }';
    echo '    .card-lift:hover { transform: translateY(-3px); box-shadow: 0 12px 28px -6px rgba(0,0,0,.1); }';
    echo '    .modal-bg { backdrop-filter: blur(3px); }';
    echo '    input, select, textarea { transition: border-color .15s, box-shadow .15s; }';
    echo '    .ring-inp:focus { outline: none; border-color: #b651f0; box-shadow: 0 0 0 3px rgba(182,81,240,.15); }';
    echo '  </style>';
    echo '</head>';
    echo '<body class="bg-gray-50">';
    echo '<div class="flex min-h-screen">';

    // ── Sidebar
    echo '<aside class="w-64 bg-gradient-to-b from-[#b651f0] to-[#a040c0] text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">';

    // Brand
    echo '  <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">';
    echo '    <div class="h-10 w-10 rounded-xl bg-violet-main flex items-center justify-center shadow-inner flex-shrink-0">';
    echo '      <i class="fas fa-pills text-violet-dark text-lg"></i>';
    echo '    </div>';
    echo '    <div>';
    echo '      <p class="font-extrabold text-base leading-tight">PharmaCare</p>';
    echo '      <p class="text-[11px] text-violet-light font-medium">Management System</p>';
    echo '    </div>';
    echo '  </div>';

    // Nav
    echo '  <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">';
    echo '    <p class="px-3 mb-2 text-[10px] font-bold text-[#b651f0] uppercase tracking-widest">Main Menu</p>';

    foreach ($navLinks as $nav) {
        $activeClass = ($activePage === $nav['key']) ? 'sidebar-active' : '';
        $href        = htmlspecialchars($nav['href']);
        $icon        = htmlspecialchars($nav['icon']);
        $label       = htmlspecialchars($nav['label']);

        echo "<a href=\"{$href}\" class=\"flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#b651f0] hover:bg-white/10 hover:text-white transition-all duration-150 {$activeClass}\">";
        echo "  <i class=\"fas {$icon} w-4 text-center text-xs text-[#b651f0]\"></i>";
        echo "  {$label}";
        if ($nav['key'] === 'inventory') {
            echo '  <span id="nav-lowstock-badge" class="ml-auto hidden px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500 text-white"></span>';
        }
        echo '</a>';
    }

    echo '  </nav>';

    // Profile
    echo '  <div class="px-4 py-4 border-t border-white/10">';
    echo '    <div class="flex items-center gap-2.5">';
    echo '      <div class="h-8 w-8 rounded-full bg-[#b651f0]/70 flex items-center justify-center text-xs font-bold flex-shrink-0">A</div>';
    echo '      <div class="min-w-0">';
    echo '        <p class="text-sm font-semibold truncate leading-tight">Administrator</p>';
    echo '        <p class="text-[11px] text-[#b651f0] truncate">System Admin</p>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';

    echo '</aside>';

    // ── Main
    echo '<div class="ml-64 flex-1 flex flex-col min-w-0">';

    // Top Header
    echo '<header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-20 shadow-sm">';
    echo '  <div>';
    echo "    <h1 class=\"text-xl font-bold text-gray-800\">{$safeHeading}</h1>";
    echo '    <nav class="flex items-center gap-1 text-xs text-gray-400 mt-0.5">';
    echo '      <span>PharmaCare</span>';
    echo '      <i class="fas fa-chevron-right text-[8px]"></i>';
    echo "      <span class=\"text-[#b651f0] font-semibold\">{$safeHeading}</span>";
    echo '    </nav>';
    echo '  </div>';
    echo '  <div class="flex items-center gap-3">';
    echo "    <span class=\"text-sm text-gray-500 flex items-center gap-1.5\"><span class=\"h-2 w-2 rounded-full bg-[#b651f0] inline-block animate-pulse\"></span>{$today}</span>";
    echo '  </div>';
    echo '</header>';

    // Page content opens here
    echo '<main class="flex-1 p-8">';
}
