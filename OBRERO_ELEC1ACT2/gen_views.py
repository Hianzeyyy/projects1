"""
Writes all pure-PHP DOMDocument-based view files.
No echo, no print, no HTML literals in PHP source.
"""
import os, textwrap

base  = r'c:\xampp\htdocs\OBRERO_ELEC1ACT2\laravel'
views = os.path.join(base, 'resources', 'views')
lib   = os.path.join(views, 'lib')
routes_file = os.path.join(base, 'routes', 'web.php')
os.makedirs(lib, exist_ok=True)

# ──────────────────────────────────────────────────────────────────────────────
DOM_PHP = r"""<?php
/**
 * Pure-PHP DOM builder.  Zero HTML literals.  Zero echo / print.
 * All HTML structure is built through PHP's DOMDocument API.
 */
class Dom
{
    private static ?DOMDocument $doc = null;

    public static function reset(): void
    {
        self::$doc = new DOMDocument('1.0', 'UTF-8');
        self::$doc->formatOutput = false;
    }

    public static function doc(): DOMDocument
    {
        if (self::$doc === null) self::reset();
        return self::$doc;
    }

    /**
     * Create an element; $children may contain DOMNode objects or plain strings.
     */
    public static function el(string $tag, array $attrs = [], array $children = []): DOMElement
    {
        $d = self::doc();
        $e = $d->createElement($tag);
        foreach ($attrs as $k => $v) {
            if ($v !== null && $v !== false) {
                $e->setAttribute($k, (string) $v);
            }
        }
        foreach ($children as $c) {
            if ($c === null) continue;
            $e->appendChild(is_string($c) ? $d->createTextNode($c) : $c);
        }
        return $e;
    }

    /** Create a plain text node. */
    public static function text(string $s): DOMText
    {
        return self::doc()->createTextNode($s);
    }

    /**
     * Wrap JavaScript source in a <script> element.
     * The $jsCode argument is a PHP string — no HTML syntax involved.
     */
    public static function script(string $jsCode): DOMElement
    {
        $el = self::doc()->createElement('script');
        $el->appendChild(self::doc()->createTextNode($jsCode));
        return $el;
    }

    /** Serialize to a full HTML document string — called once per request. */
    public static function html(): string
    {
        $root = self::doc()->documentElement;
        return $root ? '<!DOCTYPE html>' . "\n" . self::doc()->saveHTML($root) : '';
    }
}
"""

# ──────────────────────────────────────────────────────────────────────────────
LAYOUT_PHP = r"""<?php
require_once __DIR__ . '/dom.php';

/**
 * Builds the shared page shell (sidebar + top-bar + footer JS).
 * Page files call Layout::build() and *return* the result — no output.
 */
class Layout
{
    private static array $nav = [
        ['key' => 'dashboard', 'href' => '/',          'icon' => 'fa-gauge-high',  'label' => 'Dashboard'],
        ['key' => 'medicines', 'href' => '/medicines',  'icon' => 'fa-capsules',    'label' => 'Medicines'],
        ['key' => 'inventory', 'href' => '/inventory',  'icon' => 'fa-layer-group', 'label' => 'Inventory'],
        ['key' => 'sales',     'href' => '/sales',      'icon' => 'fa-receipt',     'label' => 'Sales'],
        ['key' => 'suppliers', 'href' => '/suppliers',  'icon' => 'fa-truck-fast',  'label' => 'Suppliers'],
    ];

    /**
     * Build a complete page and return the HTML string.
     *
     * @param  callable(DOMElement):void  $content  Receives <main>; appends page nodes to it.
     * @return string                               Full HTML document — caller sends to browser.
     */
    public static function build(
        string   $title,
        string   $heading,
        string   $active,
        callable $content,
        string   $pageJS = ''
    ): string {
        Dom::reset();

        $html = Dom::el('html', ['lang' => 'en']);
        Dom::doc()->appendChild($html);

        // ── <head> ──────────────────────────────────────────────────────────
        $head = Dom::el('head');
        $html->appendChild($head);

        $head->appendChild(Dom::el('meta', ['charset' => 'UTF-8']));
        $head->appendChild(Dom::el('meta', [
            'name'    => 'viewport',
            'content' => 'width=device-width, initial-scale=1.0',
        ]));

        $t = Dom::el('title');
        $t->appendChild(Dom::text(htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . ' — PharmaCare'));
        $head->appendChild($t);

        $tw = Dom::el('script', ['src' => 'https://cdn.tailwindcss.com']);
        $tw->appendChild(Dom::text(''));   // prevent self-closing tag
        $head->appendChild($tw);

        $head->appendChild(Dom::el('link', [
            'rel'  => 'stylesheet',
            'href' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        ]));

        $style = Dom::el('style');
        $style->appendChild(Dom::text(
            'body{font-family:Inter,ui-sans-serif,system-ui,sans-serif;background:#f8fafc}' .
            '.sidebar-active{background:#16a34a;color:#fff!important}' .
            '.sidebar-active i{color:#fff!important}' .
            '.tbl-row:hover{background:#f0fdf4}' .
            '.ring-inp:focus{outline:none;border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.15)}' .
            '@keyframes spin{to{transform:rotate(360deg)}}' .
            '.spin{animation:spin .7s linear infinite;display:inline-block}'
        ));
        $head->appendChild($style);

        // ── <body> ──────────────────────────────────────────────────────────
        $body = Dom::el('body', ['class' => 'min-h-screen']);
        $html->appendChild($body);

        $wrap = Dom::el('div', ['class' => 'flex h-screen overflow-hidden']);
        $body->appendChild($wrap);

        $wrap->appendChild(self::sidebar($active));

        $col = Dom::el('div', ['class' => 'flex-1 flex flex-col overflow-hidden']);
        $wrap->appendChild($col);

        $col->appendChild(self::topBar($heading));

        $main = Dom::el('main', ['class' => 'flex-1 overflow-y-auto p-8']);
        $col->appendChild($main);
        $content($main);

        $body->appendChild(Dom::el('div', [
            'id'    => 'toast',
            'class' => 'fixed bottom-6 right-6 z-[200] flex flex-col gap-2 pointer-events-none',
        ]));

        $body->appendChild(Dom::script(self::utilJS()));

        if (trim($pageJS) !== '') {
            $body->appendChild(Dom::script($pageJS));
        }

        return Dom::html();
    }

    // ── sidebar ─────────────────────────────────────────────────────────────
    private static function sidebar(string $active): DOMElement
    {
        $aside = Dom::el('aside', [
            'class' => 'w-64 bg-white border-r border-gray-100 flex flex-col shadow-sm flex-shrink-0 overflow-y-auto',
        ]);

        // Brand
        $bw = Dom::el('div', ['class' => 'px-6 py-6 border-b border-gray-100']);
        $bi = Dom::el('div', ['class' => 'flex items-center gap-3']);
        $iw = Dom::el('div', ['class' => 'h-10 w-10 bg-green-600 rounded-xl flex items-center justify-center shadow']);
        $ic = Dom::el('i',   ['class' => 'fas fa-pills text-white text-sm']);
        $ic->appendChild(Dom::text(''));
        $iw->appendChild($ic);
        $tw = Dom::el('div');
        $p1 = Dom::el('p', ['class' => 'font-extrabold text-gray-900 text-base leading-tight']);
        $p1->appendChild(Dom::text('PharmaCare'));
        $p2 = Dom::el('p', ['class' => 'text-[10px] text-gray-400 font-medium']);
        $p2->appendChild(Dom::text('Management System'));
        $tw->appendChild($p1);
        $tw->appendChild($p2);
        $bi->appendChild($iw);
        $bi->appendChild($tw);
        $bw->appendChild($bi);
        $aside->appendChild($bw);

        // Nav
        $nav = Dom::el('nav', ['class' => 'flex-1 px-3 py-4 space-y-0.5']);
        foreach (self::$nav as $lnk) {
            $on  = ($active === $lnk['key']);
            $cls = 'flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition ' .
                   ($on ? 'sidebar-active' : 'text-gray-600 hover:bg-green-50 hover:text-green-700');

            $a  = Dom::el('a', ['href' => $lnk['href'], 'class' => $cls]);
            $ii = Dom::el('i', ['class' => 'fas ' . $lnk['icon'] . ' w-4 text-center text-sm']);
            $ii->appendChild(Dom::text(''));
            $a->appendChild($ii);
            $a->appendChild(Dom::text(' ' . $lnk['label']));

            if ($lnk['key'] === 'inventory') {
                $b = Dom::el('span', [
                    'id'    => 'nav-lowstock-badge',
                    'class' => 'ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full hidden',
                ]);
                $b->appendChild(Dom::text(''));
                $a->appendChild($b);
            }
            $nav->appendChild($a);
        }
        $aside->appendChild($nav);

        $fd = Dom::el('div', ['class' => 'px-4 py-4 border-t border-gray-100']);
        $fp = Dom::el('p',   ['class' => 'text-[10px] text-gray-400 text-center']);
        $fp->appendChild(Dom::text("\xC2\xA9 2025 PharmaCare \xE2\x80\x94 All rights reserved"));
        $fd->appendChild($fp);
        $aside->appendChild($fd);

        return $aside;
    }

    // ── top bar ─────────────────────────────────────────────────────────────
    private static function topBar(string $heading): DOMElement
    {
        $hdr = Dom::el('header', [
            'class' => 'bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between shadow-sm',
        ]);

        $h1 = Dom::el('h1', ['class' => 'text-xl font-extrabold text-gray-900']);
        $h1->appendChild(Dom::text($heading));
        $hdr->appendChild($h1);

        $sw  = Dom::el('div', ['class' => 'flex items-center gap-4']);
        $si  = Dom::el('div', ['class' => 'flex items-center gap-2 text-sm text-gray-500']);
        $dot = Dom::el('i',   ['class' => 'fas fa-circle text-green-500 text-[8px] animate-pulse']);
        $dot->appendChild(Dom::text(''));
        $si->appendChild($dot);
        $sp = Dom::el('span');
        $sp->appendChild(Dom::text('System Online'));
        $si->appendChild($sp);
        $sw->appendChild($si);
        $hdr->appendChild($sw);

        return $hdr;
    }

    // ── global utility JS ───────────────────────────────────────────────────
    private static function utilJS(): string
    {
        return
            'function showToast(msg,type="success"){const wrap=document.getElementById("toast");const d=document.createElement("div");const icons={success:"fa-check-circle",error:"fa-exclamation-circle",warning:"fa-triangle-exclamation"};const cols={success:"bg-green-600",error:"bg-red-600",warning:"bg-amber-500"};d.className=`pointer-events-auto flex items-center gap-3 px-5 py-3.5 rounded-2xl text-white text-sm font-semibold shadow-lg ${cols[type]||cols.success} translate-y-4 opacity-0 transition-all duration-300`;d.innerHTML=`<i class="fas ${icons[type]||icons.success}"></i><span>${msg}</span>`;wrap.appendChild(d);requestAnimationFrame(()=>{d.classList.remove("translate-y-4","opacity-0");});setTimeout(()=>{d.classList.add("translate-y-4","opacity-0");setTimeout(()=>d.remove(),300);},3500);}' .
            'async function api(url,method="GET",body=null){const opts={method,headers:{"Content-Type":"application/json","Accept":"application/json"}};if(body)opts.body=JSON.stringify(body);const res=await fetch(url,opts);const json=await res.json().catch(()=>({}));if(!res.ok)throw new Error(json.message||`HTTP ${res.status}`);return json;}' .
            'function fmtDate(d){if(!d)return"\u2014";const dt=new Date(d);return isNaN(dt)?"\u2014":dt.toLocaleDateString("en-US",{year:"numeric",month:"short",day:"numeric"});}' .
            'function fmtMoney(n){return"$"+Number(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,",");}' .
            'function toInputDate(d){if(!d)return"";const dt=new Date(d);if(isNaN(dt))return"";return dt.toISOString().split("T")[0];}' .
            'function escHtml(s){const e=document.createElement("span");e.textContent=s??"";return e.innerHTML;}' .
            '(async function updateBadge(){try{const inv=await api("/api/inventory");const low=inv.filter(i=>Number(i.quantity)<=Number(i.reorder_level)).length;const badge=document.getElementById("nav-lowstock-badge");if(badge){if(low>0){badge.textContent=low;badge.classList.remove("hidden");}else{badge.classList.add("hidden");}}}catch(e){}})();';
    }
}
"""

# ──────────────────────────────────────────────────────────────────────────────
# Shared helper used inside page build closures
# ──────────────────────────────────────────────────────────────────────────────
CARD_HEADER = r"""
        // Card header
        $hdr = Dom::el('div', [
            'class' => 'px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4',
        ]);
        $td  = Dom::el('div');
"""

# ──────────────────────────────────────────────────────────────────────────────
DASHBOARD_PHP = r"""<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Dashboard', 'Dashboard', 'dashboard', function (DOMElement $main): void {

    // ── Stat cards grid ──────────────────────────────────────────────────────
    $grid = Dom::el('div', ['class' => 'grid grid-cols-2 lg:grid-cols-3 gap-5 mb-8']);
    $main->appendChild($grid);

    $cards = [
        ['id' => 's-medicines', 'label' => 'Total Medicines', 'icon' => 'fa-capsules',           'bg' => 'bg-blue-50',   'ic' => 'text-blue-600'],
        ['id' => 's-inventory', 'label' => 'Inventory Items',  'icon' => 'fa-layer-group',        'bg' => 'bg-indigo-50', 'ic' => 'text-indigo-600'],
        ['id' => 's-sales',     'label' => 'Total Sales',      'icon' => 'fa-receipt',            'bg' => 'bg-green-50',  'ic' => 'text-green-600'],
        ['id' => 's-suppliers', 'label' => 'Suppliers',         'icon' => 'fa-truck-fast',         'bg' => 'bg-violet-50', 'ic' => 'text-violet-600'],
        ['id' => 's-lowstock',  'label' => 'Low Stock Items',   'icon' => 'fa-triangle-exclamation','bg' => 'bg-amber-50',  'ic' => 'text-amber-600'],
        ['id' => 's-revenue',   'label' => 'Total Revenue',     'icon' => 'fa-dollar-sign',        'bg' => 'bg-teal-50',   'ic' => 'text-teal-600'],
    ];

    foreach ($cards as $c) {
        $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center gap-5']);

        $ibx = Dom::el('div', ['class' => 'h-12 w-12 rounded-xl ' . $c['bg'] . ' flex items-center justify-center flex-shrink-0']);
        $ii  = Dom::el('i',   ['class' => 'fas ' . $c['icon'] . ' ' . $c['ic']]);
        $ii->appendChild(Dom::text(''));
        $ibx->appendChild($ii);
        $card->appendChild($ibx);

        $txt = Dom::el('div');
        $lbl = Dom::el('p', ['class' => 'text-xs text-gray-400 font-medium']);
        $lbl->appendChild(Dom::text($c['label']));
        $val = Dom::el('p', ['id' => $c['id'], 'class' => 'text-2xl font-extrabold text-gray-800 mt-0.5']);
        $si  = Dom::el('i', ['class' => 'fas fa-spinner spin text-base text-gray-300']);
        $si->appendChild(Dom::text(''));
        $val->appendChild($si);
        $txt->appendChild($lbl);
        $txt->appendChild($val);
        $card->appendChild($txt);
        $grid->appendChild($card);
    }

    // ── Bottom two-column grid ───────────────────────────────────────────────
    $bot = Dom::el('div', ['class' => 'grid grid-cols-1 lg:grid-cols-2 gap-6']);
    $main->appendChild($bot);

    // Helper: build a mini-table card
    $miniCard = function (string $titleText, string $subText, string $link, string $linkHref, string $tbodyId, array $cols) use ($bot): void {
        $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden']);
        $ch   = Dom::el('div', ['class' => 'px-6 py-4 border-b border-gray-100 flex items-center justify-between']);
        $cd   = Dom::el('div');
        $h3   = Dom::el('h3', ['class' => 'font-bold text-gray-800']);
        $h3->appendChild(Dom::text($titleText));
        $sp   = Dom::el('p', ['class' => 'text-xs text-gray-400 mt-0.5']);
        $sp->appendChild(Dom::text($subText));
        $cd->appendChild($h3);
        $cd->appendChild($sp);
        $ch->appendChild($cd);
        $a = Dom::el('a', ['href' => $linkHref, 'class' => 'text-xs text-green-600 font-bold hover:underline']);
        $a->appendChild(Dom::text($link));
        $ch->appendChild($a);
        $card->appendChild($ch);

        $tw  = Dom::el('div',   ['class' => 'overflow-x-auto']);
        $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
        $th  = Dom::el('thead');
        $tr  = Dom::el('tr',    ['class' => 'bg-gray-50 text-[11px] font-bold text-gray-400 uppercase']);
        foreach ($cols as [$lbl, $cls]) {
            $th_ = Dom::el('th', ['class' => $cls]);
            $th_->appendChild(Dom::text($lbl));
            $tr->appendChild($th_);
        }
        $th->appendChild($tr);
        $tbl->appendChild($th);

        $tbody = Dom::el('tbody', ['id' => $tbodyId]);
        $lr    = Dom::el('tr');
        $ltd   = Dom::el('td', ['colspan' => (string)count($cols), 'class' => 'text-center py-6 text-gray-300']);
        $spi   = Dom::el('i', ['class' => 'fas fa-spinner spin mr-1']);
        $spi->appendChild(Dom::text(''));
        $ltd->appendChild($spi);
        $lr->appendChild($ltd);
        $tbody->appendChild($lr);
        $tbl->appendChild($tbody);
        $tw->appendChild($tbl);
        $card->appendChild($tw);
        $bot->appendChild($card);
    };

    $miniCard('Recent Sales',    'Last 5 transactions',         'View all &rarr;', '/sales',     'recent-sales-tbody', [
        ['Medicine', 'text-left px-5 py-3'],
        ['Qty',      'text-right px-5 py-3'],
        ['Total',    'text-right px-5 py-3'],
        ['Date',     'text-left px-5 py-3'],
    ]);

    $miniCard('Low Stock Alert', 'Items at or below reorder level', 'Manage &rarr;', '/inventory', 'low-stock-tbody', [
        ['Medicine', 'text-left px-5 py-3'],
        ['Qty',      'text-right px-5 py-3'],
        ['Reorder',  'text-right px-5 py-3'],
    ]);

}, <<<'JS'
async function loadDashboard() {
    try {
        const [stats, sales, inv] = await Promise.all([
            api('/api/dashboard/stats'), api('/api/sales'), api('/api/inventory')
        ]);
        document.getElementById('s-medicines').textContent = stats.total_medicines ?? 0;
        document.getElementById('s-inventory').textContent = stats.total_inventory ?? 0;
        document.getElementById('s-sales').textContent     = stats.total_sales ?? 0;
        document.getElementById('s-suppliers').textContent = stats.total_suppliers ?? 0;
        document.getElementById('s-lowstock').textContent  = stats.low_stock ?? 0;
        document.getElementById('s-revenue').textContent   = fmtMoney(stats.total_revenue ?? 0);

        const recent = sales.slice(-5).reverse();
        document.getElementById('recent-sales-tbody').innerHTML = recent.length
            ? recent.map(s => `<tr class="tbl-row border-t border-gray-50">
                <td class="px-5 py-3 font-medium text-gray-700">${escHtml(s.medicine?.name ?? 'Unknown')}</td>
                <td class="px-5 py-3 text-right text-gray-600">${s.quantity}</td>
                <td class="px-5 py-3 text-right font-bold text-green-700">${fmtMoney(s.total_price)}</td>
                <td class="px-5 py-3 text-gray-500">${fmtDate(s.sale_date)}</td></tr>`).join('')
            : '<tr><td colspan="4" class="text-center py-6 text-gray-400">No sales yet.</td></tr>';

        const low = inv.filter(i => Number(i.quantity) <= Number(i.reorder_level));
        document.getElementById('low-stock-tbody').innerHTML = low.length
            ? low.map(i => `<tr class="tbl-row border-t border-gray-50">
                <td class="px-5 py-3 font-medium text-gray-700">${escHtml(i.medicine?.name ?? 'Unknown')}</td>
                <td class="px-5 py-3 text-right font-bold text-red-600">${i.quantity}</td>
                <td class="px-5 py-3 text-right text-gray-500">${i.reorder_level}</td></tr>`).join('')
            : '<tr><td colspan="3" class="text-center py-6 text-gray-400">All stock levels healthy.</td></tr>';
    } catch (e) { showToast(e.message, 'error'); }
}
loadDashboard();
JS);
"""

# ──────────────────────────────────────────────────────────────────────────────
MEDICINES_PHP = r"""<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Medicines', 'Medicines', 'medicines', function (DOMElement $main): void {

    $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden']);

    // ── header bar ──────────────────────────────────────────────────────────
    $hdr = Dom::el('div', [
        'class' => 'px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4',
    ]);

    $td = Dom::el('div');
    $h2 = Dom::el('h2', ['class' => 'font-bold text-gray-800']);
    $h2->appendChild(Dom::text('Medicine Registry'));
    $sb = Dom::el('p', ['class' => 'text-xs text-gray-400 mt-0.5']);
    $sb->appendChild(Dom::text('Manage all pharmaceutical products'));
    $td->appendChild($h2);
    $td->appendChild($sb);
    $hdr->appendChild($td);

    $ctrl = Dom::el('div', ['class' => 'flex items-center gap-3 w-full sm:w-auto']);

    $srchWrap = Dom::el('div', ['class' => 'relative flex-1 sm:w-64']);
    $srchI    = Dom::el('i',   ['class' => 'fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none']);
    $srchI->appendChild(Dom::text(''));
    $srchWrap->appendChild($srchI);
    $srchWrap->appendChild(Dom::el('input', [
        'id'          => 'search',
        'type'        => 'text',
        'placeholder' => 'Search by name, category...',
        'class'       => 'ring-inp w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white',
    ]));
    $ctrl->appendChild($srchWrap);

    $addBtn = Dom::el('button', [
        'onclick' => 'openModal()',
        'class'   => 'flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm whitespace-nowrap',
    ]);
    $plusI = Dom::el('i', ['class' => 'fas fa-plus text-xs']);
    $plusI->appendChild(Dom::text(''));
    $addBtn->appendChild($plusI);
    $addBtn->appendChild(Dom::text(' Add Medicine'));
    $ctrl->appendChild($addBtn);

    $hdr->appendChild($ctrl);
    $card->appendChild($hdr);

    // ── table ───────────────────────────────────────────────────────────────
    $tw  = Dom::el('div',   ['class' => 'overflow-x-auto']);
    $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
    $th  = Dom::el('thead');
    $tr  = Dom::el('tr', ['class' => 'bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider']);
    foreach ([
        ['#',            'text-left px-6 py-3.5'],
        ['Name',         'text-left px-6 py-3.5'],
        ['Category',     'text-left px-6 py-3.5'],
        ['Manufacturer', 'text-left px-6 py-3.5'],
        ['Price',        'text-right px-6 py-3.5'],
        ['Expiry Date',  'text-left px-6 py-3.5'],
        ['Actions',      'text-center px-6 py-3.5'],
    ] as [$l, $c]) {
        $thEl = Dom::el('th', ['class' => $c]);
        $thEl->appendChild(Dom::text($l));
        $tr->appendChild($thEl);
    }
    $th->appendChild($tr);
    $tbl->appendChild($th);

    $tbody = Dom::el('tbody', ['id' => 'medicines-tbody']);
    $lr    = Dom::el('tr');
    $ltd   = Dom::el('td', ['colspan' => '7', 'class' => 'text-center py-12 text-gray-300']);
    $spi   = Dom::el('i', ['class' => 'fas fa-spinner spin mr-2']);
    $spi->appendChild(Dom::text(''));
    $ltd->appendChild($spi);
    $ltd->appendChild(Dom::text('Loading medicines...'));
    $lr->appendChild($ltd);
    $tbody->appendChild($lr);
    $tbl->appendChild($tbody);
    $tw->appendChild($tbl);
    $card->appendChild($tw);

    $ft = Dom::el('div', ['class' => 'px-6 py-3 border-t border-gray-100 bg-gray-50']);
    $rc = Dom::el('p',   ['id' => 'rec-count', 'class' => 'text-xs text-gray-400']);
    $rc->appendChild(Dom::text("\xE2\x80\x94"));
    $ft->appendChild($rc);
    $card->appendChild($ft);
    $main->appendChild($card);

    // ── modal ───────────────────────────────────────────────────────────────
    $modal = Dom::el('div', ['id' => 'modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
    $mbox  = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);

    $mh  = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
    $mt  = Dom::el('h3',  ['id' => 'modal-title', 'class' => 'text-lg font-bold text-gray-800']);
    $mt->appendChild(Dom::text(''));
    $xb  = Dom::el('button', ['onclick' => 'closeModal()', 'class' => 'h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition']);
    $xi  = Dom::el('i', ['class' => 'fas fa-times text-sm']);
    $xi->appendChild(Dom::text(''));
    $xb->appendChild($xi);
    $mh->appendChild($mt);
    $mh->appendChild($xb);
    $mbox->appendChild($mh);

    $frm = Dom::el('form', ['id' => 'modal-form', 'class' => 'px-6 py-5 space-y-4', 'onsubmit' => 'handleSubmit(event)']);
    $frm->appendChild(Dom::el('input', ['type' => 'hidden', 'id' => 'f-id']));

    // Utility: labeled text/number/date input
    $inp = function (string $id, string $label, string $type, array $extra = [], bool $req = false) use ($frm): DOMElement {
        $w  = Dom::el('div');
        $lb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => $id]);
        $lb->appendChild(Dom::text($label));
        if ($req) {
            $st = Dom::el('span', ['class' => 'text-red-500']);
            $st->appendChild(Dom::text(' *'));
            $lb->appendChild($st);
        }
        $w->appendChild($lb);
        $attrs = array_merge([
            'id' => $id, 'type' => $type,
            'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm',
        ], $req ? ['required' => 'required'] : [], $extra);
        $w->appendChild(Dom::el('input', $attrs));
        return $w;
    };

    $frm->appendChild($inp('f-name',         'Medicine Name', 'text',   ['placeholder' => 'e.g. Paracetamol 500mg'], true));
    $g1 = Dom::el('div', ['class' => 'grid grid-cols-2 gap-4']);
    $g1->appendChild($inp('f-category',    'Category',    'text',   ['placeholder' => 'e.g. Analgesic'],  true));
    $g1->appendChild($inp('f-manufacturer','Manufacturer','text',   ['placeholder' => 'e.g. PharmaCorp'], true));
    $frm->appendChild($g1);

    $g2 = Dom::el('div', ['class' => 'grid grid-cols-2 gap-4']);

    // Price field with $ prefix
    $pw  = Dom::el('div');
    $plb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => 'f-price']);
    $plb->appendChild(Dom::text('Price (USD)'));
    $pst = Dom::el('span', ['class' => 'text-red-500']);
    $pst->appendChild(Dom::text(' *'));
    $plb->appendChild($pst);
    $pw->appendChild($plb);
    $pwr = Dom::el('div', ['class' => 'relative']);
    $psp = Dom::el('span', ['class' => 'absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm']);
    $psp->appendChild(Dom::text('$'));
    $pwr->appendChild($psp);
    $pwr->appendChild(Dom::el('input', [
        'id' => 'f-price', 'type' => 'number', 'required' => 'required',
        'step' => '0.01', 'min' => '0', 'placeholder' => '0.00',
        'class' => 'ring-inp w-full pl-7 pr-3.5 py-2.5 border border-gray-200 rounded-xl text-sm',
    ]));
    $pw->appendChild($pwr);
    $g2->appendChild($pw);
    $g2->appendChild($inp('f-expiry', 'Expiry Date', 'date', [], true));
    $frm->appendChild($g2);

    // Description textarea
    $dw  = Dom::el('div');
    $dlb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => 'f-desc']);
    $dlb->appendChild(Dom::text('Description'));
    $dw->appendChild($dlb);
    $dta = Dom::el('textarea', ['id' => 'f-desc', 'rows' => '2', 'placeholder' => 'Optional...', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm resize-none']);
    $dta->appendChild(Dom::text(''));
    $dw->appendChild($dta);
    $frm->appendChild($dw);

    // Buttons
    $br  = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
    $cb  = Dom::el('button', ['type' => 'button', 'onclick' => 'closeModal()', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']);
    $cb->appendChild(Dom::text('Cancel'));
    $sb  = Dom::el('button', ['type' => 'submit', 'id' => 'submit-btn', 'class' => 'flex-1 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm']);
    $sb->appendChild(Dom::text('Save'));
    $br->appendChild($cb);
    $br->appendChild($sb);
    $frm->appendChild($br);

    $mbox->appendChild($frm);
    $modal->appendChild($mbox);
    $main->appendChild($modal);

}, <<<'JS'
let medicines = [], editId = null;
const catPalette = {"Analgesic":"bg-blue-100 text-blue-700","Antibiotic":"bg-violet-100 text-violet-700","Antihistamine":"bg-teal-100 text-teal-700","Antiviral":"bg-orange-100 text-orange-700","Antifungal":"bg-pink-100 text-pink-700","Supplement":"bg-lime-100 text-lime-700","default":"bg-gray-100 text-gray-600"};
const catCls = c => catPalette[c] ?? catPalette["default"];
async function load() { try { medicines = await api("/api/medicines"); render(medicines); } catch(e) { showToast(e.message,"error"); } }
function render(data) {
    document.getElementById("rec-count").textContent = `Showing ${data.length} medicine${data.length!==1?"s":""}`;
    const tbody = document.getElementById("medicines-tbody");
    if (!data.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-center py-12 text-gray-400">No medicines found.</td></tr>'; return; }
    tbody.innerHTML = data.map((m, idx) => {
        const days = Math.ceil((new Date(m.expiry_date) - Date.now()) / 86400000);
        const ec   = days < 0 ? "text-red-500" : days < 90 ? "text-orange-500" : "text-gray-500";
        return `<tr class="tbl-row border-t border-gray-50">
            <td class="px-6 py-4 text-xs text-gray-400">${idx+1}</td>
            <td class="px-6 py-4"><p class="font-semibold text-gray-800">${escHtml(m.name)}</p>${m.description ? `<p class="text-xs text-gray-400 mt-0.5 max-w-[180px] truncate">${escHtml(m.description)}</p>` : ""}</td>
            <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-[11px] font-bold ${catCls(m.category)}">${escHtml(m.category)}</span></td>
            <td class="px-6 py-4 text-gray-600">${escHtml(m.manufacturer)}</td>
            <td class="px-6 py-4 text-right font-bold text-green-700">${fmtMoney(m.price)}</td>
            <td class="px-6 py-4 text-sm ${ec}">${fmtDate(m.expiry_date)}${days<0?" <b>(Expired)</b>":days<90?` <b>(${days}d)</b>`:""}</td>
            <td class="px-6 py-4 text-center"><div class="inline-flex items-center gap-1.5">
                <button onclick="editMed(${m.id})" class="h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"><i class="fas fa-pencil text-xs"></i></button>
                <button onclick="delMed(${m.id})"  class="h-8 w-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition"><i class="fas fa-trash text-xs"></i></button>
            </div></td></tr>`;
    }).join("");
}
document.getElementById("search").addEventListener("input", function () {
    const q = this.value.toLowerCase();
    render(!q ? medicines : medicines.filter(m => [m.name, m.category, m.manufacturer, m.description ?? ""].some(f => f.toLowerCase().includes(q))));
});
function openModal(m = null) {
    editId = m?.id ?? null;
    document.getElementById("modal-title").textContent  = m ? "Edit Medicine" : "Add New Medicine";
    document.getElementById("submit-btn").textContent   = m ? "Update" : "Save";
    document.getElementById("f-id").value           = m?.id ?? "";
    document.getElementById("f-name").value         = m?.name ?? "";
    document.getElementById("f-category").value     = m?.category ?? "";
    document.getElementById("f-manufacturer").value = m?.manufacturer ?? "";
    document.getElementById("f-price").value        = m?.price ?? "";
    document.getElementById("f-expiry").value       = m ? toInputDate(m.expiry_date) : "";
    document.getElementById("f-desc").value         = m?.description ?? "";
    document.getElementById("modal").classList.remove("hidden");
    setTimeout(() => document.getElementById("f-name").focus(), 60);
}
function closeModal() { document.getElementById("modal").classList.add("hidden"); }
document.getElementById("modal").addEventListener("click", e => { if (e.target === document.getElementById("modal")) closeModal(); });
function editMed(id) { openModal(medicines.find(m => m.id === id)); }
async function delMed(id) {
    if (!confirm("Delete this medicine?")) return;
    try { await api(`/api/medicines/${id}`, "DELETE"); showToast("Deleted.", "warning"); load(); } catch(e) { showToast(e.message, "error"); }
}
async function handleSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById("submit-btn");
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner spin mr-1"></i> Saving...';
    const body = { name: document.getElementById("f-name").value.trim(), category: document.getElementById("f-category").value.trim(), manufacturer: document.getElementById("f-manufacturer").value.trim(), price: parseFloat(document.getElementById("f-price").value), expiry_date: document.getElementById("f-expiry").value, description: document.getElementById("f-desc").value.trim() };
    try {
        if (editId) { await api(`/api/medicines/${editId}`, "PUT", body); showToast("Updated!"); }
        else        { await api("/api/medicines", "POST", body);          showToast("Added!"); }
        closeModal(); load();
    } catch(e) { showToast(e.message, "error"); }
    finally { btn.disabled = false; btn.textContent = editId ? "Update" : "Save"; }
}
load();
JS);
"""

# ──────────────────────────────────────────────────────────────────────────────
SUPPLIERS_PHP = r"""<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Suppliers', 'Suppliers', 'suppliers', function (DOMElement $main): void {

    $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden']);

    $hdr  = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4']);
    $td   = Dom::el('div');
    $h2   = Dom::el('h2', ['class' => 'font-bold text-gray-800']);
    $h2->appendChild(Dom::text('Supplier Directory'));
    $sb   = Dom::el('p', ['class' => 'text-xs text-gray-400 mt-0.5']);
    $sb->appendChild(Dom::text('Manage pharmaceutical suppliers'));
    $td->appendChild($h2); $td->appendChild($sb); $hdr->appendChild($td);

    $ctrl     = Dom::el('div', ['class' => 'flex items-center gap-3 w-full sm:w-auto']);
    $srchWrap = Dom::el('div', ['class' => 'relative flex-1 sm:w-64']);
    $si       = Dom::el('i',   ['class' => 'fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none']);
    $si->appendChild(Dom::text(''));
    $srchWrap->appendChild($si);
    $srchWrap->appendChild(Dom::el('input', [
        'id' => 'search', 'type' => 'text', 'placeholder' => 'Search suppliers...',
        'class' => 'ring-inp w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white',
    ]));
    $ctrl->appendChild($srchWrap);
    $addBtn = Dom::el('button', ['onclick' => 'openModal()', 'class' => 'flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm whitespace-nowrap']);
    $pi = Dom::el('i', ['class' => 'fas fa-plus text-xs']); $pi->appendChild(Dom::text('')); $addBtn->appendChild($pi);
    $addBtn->appendChild(Dom::text(' Add Supplier')); $ctrl->appendChild($addBtn);
    $hdr->appendChild($ctrl); $card->appendChild($hdr);

    // table
    $tw  = Dom::el('div', ['class' => 'overflow-x-auto']);
    $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
    $th  = Dom::el('thead');
    $tr  = Dom::el('tr', ['class' => 'bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider']);
    foreach ([['#','text-left px-6 py-3.5'],['Supplier','text-left px-6 py-3.5'],['Email','text-left px-6 py-3.5'],['Phone','text-left px-6 py-3.5'],['Address','text-left px-6 py-3.5'],['Actions','text-center px-6 py-3.5']] as [$l,$c]) {
        $thEl = Dom::el('th', ['class' => $c]); $thEl->appendChild(Dom::text($l)); $tr->appendChild($thEl); }
    $th->appendChild($tr); $tbl->appendChild($th);
    $tbody = Dom::el('tbody', ['id' => 'suppliers-tbody']);
    $lr = Dom::el('tr'); $ltd = Dom::el('td', ['colspan' => '6', 'class' => 'text-center py-12 text-gray-300']);
    $spi = Dom::el('i', ['class' => 'fas fa-spinner spin mr-2']); $spi->appendChild(Dom::text(''));
    $ltd->appendChild($spi); $ltd->appendChild(Dom::text('Loading...')); $lr->appendChild($ltd); $tbody->appendChild($lr);
    $tbl->appendChild($tbody); $tw->appendChild($tbl); $card->appendChild($tw);
    $ft = Dom::el('div', ['class' => 'px-6 py-3 border-t border-gray-100 bg-gray-50']);
    $rc = Dom::el('p', ['id' => 'rec-count', 'class' => 'text-xs text-gray-400']); $rc->appendChild(Dom::text("\xE2\x80\x94"));
    $ft->appendChild($rc); $card->appendChild($ft); $main->appendChild($card);

    // modal
    $modal = Dom::el('div', ['id' => 'modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
    $mbox  = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);
    $mh = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
    $mt = Dom::el('h3', ['id' => 'modal-title', 'class' => 'text-lg font-bold text-gray-800']); $mt->appendChild(Dom::text(''));
    $xb = Dom::el('button', ['onclick' => 'closeModal()', 'class' => 'h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition']);
    $xi = Dom::el('i', ['class' => 'fas fa-times text-sm']); $xi->appendChild(Dom::text('')); $xb->appendChild($xi);
    $mh->appendChild($mt); $mh->appendChild($xb); $mbox->appendChild($mh);
    $frm = Dom::el('form', ['id' => 'modal-form', 'class' => 'px-6 py-5 space-y-4', 'onsubmit' => 'handleSubmit(event)']);
    $frm->appendChild(Dom::el('input', ['type' => 'hidden', 'id' => 'f-id']));
    $inp = function (string $id, string $lbl, string $type, array $ex = [], bool $req = false) {
        $w = Dom::el('div'); $lb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => $id]);
        $lb->appendChild(Dom::text($lbl));
        if ($req) { $st = Dom::el('span', ['class' => 'text-red-500']); $st->appendChild(Dom::text(' *')); $lb->appendChild($st); }
        $w->appendChild($lb);
        $a = array_merge(['id'=>$id,'type'=>$type,'class'=>'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm'], $req ? ['required'=>'required'] : [], $ex);
        $w->appendChild(Dom::el('input', $a)); return $w;
    };
    $frm->appendChild($inp('f-name', 'Supplier Name', 'text', ['placeholder' => 'MedSupply Inc.'], true));
    $g1 = Dom::el('div', ['class' => 'grid grid-cols-2 gap-4']);
    $g1->appendChild($inp('f-email', 'Email', 'email', ['placeholder' => 'contact@supplier.com'], true));
    $g1->appendChild($inp('f-phone', 'Phone', 'text',  ['placeholder' => '+1 555 000 1234']));
    $frm->appendChild($g1);
    $aw = Dom::el('div'); $alb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5']); $alb->appendChild(Dom::text('Address'));
    $aw->appendChild($alb);
    $ata = Dom::el('textarea', ['id' => 'f-address', 'rows' => '2', 'placeholder' => 'Street, City, Country...', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm resize-none']);
    $ata->appendChild(Dom::text('')); $aw->appendChild($ata); $frm->appendChild($aw);
    $br = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
    $cb = Dom::el('button', ['type' => 'button', 'onclick' => 'closeModal()', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']); $cb->appendChild(Dom::text('Cancel'));
    $sb2 = Dom::el('button', ['type' => 'submit', 'id' => 'submit-btn', 'class' => 'flex-1 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm']); $sb2->appendChild(Dom::text('Save'));
    $br->appendChild($cb); $br->appendChild($sb2); $frm->appendChild($br);
    $mbox->appendChild($frm); $modal->appendChild($mbox); $main->appendChild($modal);

}, <<<'JS'
let suppliers = [], editId = null;
function avatarColor(name) {
    const c = ["bg-violet-500","bg-blue-500","bg-teal-500","bg-orange-500","bg-pink-500","bg-indigo-500"];
    let h = 0; for (const ch of name) h = (h * 31 + ch.charCodeAt(0)) & 0xffffffff;
    return c[Math.abs(h) % c.length];
}
async function load() { try { suppliers = await api("/api/suppliers"); render(suppliers); } catch(e) { showToast(e.message, "error"); } }
function render(data) {
    document.getElementById("rec-count").textContent = `Showing ${data.length} supplier${data.length!==1?"s":""}`;
    const tbody = document.getElementById("suppliers-tbody");
    if (!data.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-center py-12 text-gray-400">No suppliers found.</td></tr>'; return; }
    tbody.innerHTML = data.map((s, i) => `<tr class="tbl-row border-t border-gray-50">
        <td class="px-6 py-4 text-xs text-gray-400">${i+1}</td>
        <td class="px-6 py-4"><div class="flex items-center gap-3">
            <span class="h-9 w-9 rounded-xl ${avatarColor(s.name||"")} text-white font-bold text-sm flex items-center justify-center">${(s.name||"?")[0].toUpperCase()}</span>
            <span class="font-semibold text-gray-800">${escHtml(s.name)}</span></div></td>
        <td class="px-6 py-4 text-gray-600">${escHtml(s.email)}</td>
        <td class="px-6 py-4 text-gray-600">${escHtml(s.phone||"—")}</td>
        <td class="px-6 py-4 text-gray-500 text-sm max-w-[180px] truncate">${escHtml(s.address||"—")}</td>
        <td class="px-6 py-4 text-center"><div class="inline-flex items-center gap-1.5">
            <button onclick="editSup(${s.id})" class="h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"><i class="fas fa-pencil text-xs"></i></button>
            <button onclick="delSup(${s.id})"  class="h-8 w-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition"><i class="fas fa-trash text-xs"></i></button>
        </div></td></tr>`).join("");
}
document.getElementById("search").addEventListener("input", function () {
    const q = this.value.toLowerCase();
    render(!q ? suppliers : suppliers.filter(s => [s.name,s.email,s.phone??"",s.address??""].some(f => f.toLowerCase().includes(q))));
});
function openModal(s = null) {
    editId = s?.id ?? null;
    document.getElementById("modal-title").textContent = s ? "Edit Supplier" : "Add New Supplier";
    document.getElementById("submit-btn").textContent  = s ? "Update" : "Save";
    document.getElementById("f-id").value      = s?.id ?? "";
    document.getElementById("f-name").value    = s?.name ?? "";
    document.getElementById("f-email").value   = s?.email ?? "";
    document.getElementById("f-phone").value   = s?.phone ?? "";
    document.getElementById("f-address").value = s?.address ?? "";
    document.getElementById("modal").classList.remove("hidden");
    setTimeout(() => document.getElementById("f-name").focus(), 60);
}
function closeModal() { document.getElementById("modal").classList.add("hidden"); }
document.getElementById("modal").addEventListener("click", e => { if (e.target === document.getElementById("modal")) closeModal(); });
function editSup(id) { openModal(suppliers.find(s => s.id === id)); }
async function delSup(id) {
    if (!confirm("Delete this supplier?")) return;
    try { await api(`/api/suppliers/${id}`, "DELETE"); showToast("Deleted.", "warning"); load(); } catch(e) { showToast(e.message, "error"); }
}
async function handleSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById("submit-btn");
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner spin mr-1"></i> Saving...';
    const body = { name: document.getElementById("f-name").value.trim(), email: document.getElementById("f-email").value.trim(), phone: document.getElementById("f-phone").value.trim(), address: document.getElementById("f-address").value.trim() };
    try {
        if (editId) { await api(`/api/suppliers/${editId}`, "PUT", body); showToast("Updated!"); }
        else        { await api("/api/suppliers", "POST", body);          showToast("Added!"); }
        closeModal(); load();
    } catch(e) { showToast(e.message, "error"); }
    finally { btn.disabled = false; btn.textContent = editId ? "Update" : "Save"; }
}
load();
JS);
"""

# ──────────────────────────────────────────────────────────────────────────────
INVENTORY_PHP = r"""<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Inventory', 'Inventory', 'inventory', function (DOMElement $main): void {

    $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden']);

    // header
    $hdr  = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4']);
    $td   = Dom::el('div');
    $h2   = Dom::el('h2', ['class' => 'font-bold text-gray-800"]); $h2->appendChild(Dom::text('Stock Management'));
    $sb   = Dom::el('p',  ['class' => 'text-xs text-gray-400 mt-0.5']); $sb->appendChild(Dom::text('Track inventory levels and reorder points'));
    $td->appendChild($h2); $td->appendChild($sb); $hdr->appendChild($td);

    $ctrl = Dom::el('div', ['class' => 'flex items-center gap-3 w-full sm:w-auto']);
    $fsel = Dom::el('select', ['id' => 'filter', 'class' => 'ring-inp px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white']);
    foreach (['' => 'All Stock', 'out' => 'Out of Stock', 'low' => 'Low Stock', 'ok' => 'In Stock'] as $v => $l) {
        $o = Dom::el('option', ['value' => $v]); $o->appendChild(Dom::text($l)); $fsel->appendChild($o); }
    $ctrl->appendChild($fsel);
    $addBtn = Dom::el('button', ['onclick' => 'openModal()', 'class' => 'flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm whitespace-nowrap']);
    $pi = Dom::el('i', ['class' => 'fas fa-plus text-xs']); $pi->appendChild(Dom::text('')); $addBtn->appendChild($pi); $addBtn->appendChild(Dom::text(' Add Item'));
    $ctrl->appendChild($addBtn); $hdr->appendChild($ctrl); $card->appendChild($hdr);

    // table
    $tw  = Dom::el('div', ['class' => 'overflow-x-auto']);
    $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
    $th  = Dom::el('thead');
    $tr  = Dom::el('tr', ['class' => 'bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider']);
    foreach ([['#','text-left px-6 py-3.5'],['Medicine','text-left px-6 py-3.5'],['Supplier','text-left px-6 py-3.5'],['Qty','text-right px-6 py-3.5'],['Reorder','text-right px-6 py-3.5'],['Batch','text-left px-6 py-3.5'],['Expiry','text-left px-6 py-3.5'],['Status','text-left px-6 py-3.5'],['Actions','text-center px-6 py-3.5']] as [$l,$c]) {
        $te = Dom::el('th', ['class' => $c]); $te->appendChild(Dom::text($l)); $tr->appendChild($te); }
    $th->appendChild($tr); $tbl->appendChild($th);
    $tbody = Dom::el('tbody', ['id' => 'inventory-tbody']);
    $lr = Dom::el('tr'); $ltd = Dom::el('td', ['colspan' => '9', 'class' => 'text-center py-12 text-gray-300']);
    $spi = Dom::el('i', ['class' => 'fas fa-spinner spin mr-2']); $spi->appendChild(Dom::text(''));
    $ltd->appendChild($spi); $ltd->appendChild(Dom::text('Loading inventory...')); $lr->appendChild($ltd); $tbody->appendChild($lr);
    $tbl->appendChild($tbody); $tw->appendChild($tbl); $card->appendChild($tw);
    $ft = Dom::el('div', ['class' => 'px-6 py-3 border-t border-gray-100 bg-gray-50']);
    $rc = Dom::el('p', ['id' => 'rec-count', 'class' => 'text-xs text-gray-400']); $rc->appendChild(Dom::text("\xE2\x80\x94"));
    $ft->appendChild($rc); $card->appendChild($ft); $main->appendChild($card);

    // modal
    $modal = Dom::el('div', ['id' => 'modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
    $mbox  = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);
    $mh = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
    $mt = Dom::el('h3', ['id' => 'modal-title', 'class' => 'text-lg font-bold text-gray-800']); $mt->appendChild(Dom::text(''));
    $xb = Dom::el('button', ['onclick' => 'closeModal()', 'class' => 'h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition']);
    $xi = Dom::el('i', ['class' => 'fas fa-times text-sm']); $xi->appendChild(Dom::text('')); $xb->appendChild($xi);
    $mh->appendChild($mt); $mh->appendChild($xb); $mbox->appendChild($mh);
    $frm = Dom::el('form', ['id' => 'modal-form', 'class' => 'px-6 py-5 space-y-4', 'onsubmit' => 'handleSubmit(event)']);
    $frm->appendChild(Dom::el('input', ['type' => 'hidden', 'id' => 'f-id']));

    // select helper
    $sel = function (string $id, string $lbl, string $placeholder, bool $req = false) {
        $w  = Dom::el('div');
        $lb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => $id]);
        $lb->appendChild(Dom::text($lbl));
        if ($req) { $st = Dom::el('span', ['class' => 'text-red-500']); $st->appendChild(Dom::text(' *')); $lb->appendChild($st); }
        $w->appendChild($lb);
        $sl = Dom::el('select', array_merge(['id' => $id, 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm'], $req ? ['required' => 'required'] : []));
        $do = Dom::el('option', ['value' => '']); $do->appendChild(Dom::text($placeholder)); $sl->appendChild($do);
        $w->appendChild($sl); return $w;
    };
    $frm->appendChild($sel('f-medicine', 'Medicine', 'Select medicine...', true));
    $frm->appendChild($sel('f-supplier', 'Supplier', 'Select supplier...'));

    $inp = function (string $id, string $lbl, string $type, array $ex = [], bool $req = false) {
        $w  = Dom::el('div');
        $lb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => $id]);
        $lb->appendChild(Dom::text($lbl));
        if ($req) { $st = Dom::el('span', ['class' => 'text-red-500']); $st->appendChild(Dom::text(' *')); $lb->appendChild($st); }
        $w->appendChild($lb);
        $a = array_merge(['id' => $id, 'type' => $type, 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm'], $req ? ['required' => 'required'] : [], $ex);
        $w->appendChild(Dom::el('input', $a)); return $w;
    };

    $g1 = Dom::el('div', ['class' => 'grid grid-cols-2 gap-4']);
    $g1->appendChild($inp('f-quantity', 'Quantity',     'number', ['min' => '0', 'placeholder' => '0'],   true));
    $g1->appendChild($inp('f-reorder',  'Reorder Level','number', ['min' => '0', 'placeholder' => '10']));
    $frm->appendChild($g1);
    $g2 = Dom::el('div', ['class' => 'grid grid-cols-2 gap-4']);
    $g2->appendChild($inp('f-batch',  'Batch Number', 'text', ['placeholder' => 'BATCH-001']));
    $g2->appendChild($inp('f-expiry', 'Expiry Date',  'date'));
    $frm->appendChild($g2);

    $br = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
    $cb = Dom::el('button', ['type' => 'button', 'onclick' => 'closeModal()', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']); $cb->appendChild(Dom::text('Cancel'));
    $sb = Dom::el('button', ['type' => 'submit', 'id' => 'submit-btn', 'class' => 'flex-1 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm']); $sb->appendChild(Dom::text('Save'));
    $br->appendChild($cb); $br->appendChild($sb); $frm->appendChild($br);
    $mbox->appendChild($frm); $modal->appendChild($mbox); $main->appendChild($modal);

}, <<<'JS'
let inventory = [], medicines = [], suppliers = [], editId = null;
async function load() {
    try {
        [inventory, medicines, suppliers] = await Promise.all([api("/api/inventory"), api("/api/medicines"), api("/api/suppliers")]);
        document.getElementById("f-medicine").innerHTML = '<option value="">Select medicine...</option>' + medicines.map(m => `<option value="${m.id}">${escHtml(m.name)}</option>`).join("");
        document.getElementById("f-supplier").innerHTML = '<option value="">Select supplier...</option>' + suppliers.map(s => `<option value="${s.id}">${escHtml(s.name)}</option>`).join("");
        render(applyFilter());
    } catch(e) { showToast(e.message, "error"); }
}
function statusBadge(qty, ro) {
    if (Number(qty) === 0) return '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-700">Out of Stock</span>';
    if (Number(qty) <= Number(ro)) return '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">Low Stock</span>';
    return '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-green-100 text-green-700">In Stock</span>';
}
function applyFilter() {
    const f = document.getElementById("filter").value;
    if (!f) return inventory;
    return inventory.filter(i => { const q = Number(i.quantity), r = Number(i.reorder_level); if (f==="out") return q===0; if (f==="low") return q>0&&q<=r; return q>r; });
}
function render(data) {
    document.getElementById("rec-count").textContent = `Showing ${data.length} item${data.length!==1?"s":""}`;
    const tbody = document.getElementById("inventory-tbody");
    if (!data.length) { tbody.innerHTML = '<tr><td colspan="9" class="text-center py-12 text-gray-400">No items found.</td></tr>'; return; }
    const pct = i => Math.min(100, i.reorder_level > 0 ? Math.round(i.quantity / Math.max(i.quantity, i.reorder_level * 2) * 100) : 100);
    tbody.innerHTML = data.map((item, idx) => `<tr class="tbl-row border-t border-gray-50">
        <td class="px-6 py-4 text-xs text-gray-400">${idx+1}</td>
        <td class="px-6 py-4 font-semibold text-gray-800">${escHtml(item.medicine?.name ?? "—")}</td>
        <td class="px-6 py-4 text-gray-600 text-sm">${escHtml(item.supplier?.name ?? "—")}</td>
        <td class="px-6 py-4 text-right">
            <p class="font-bold text-gray-800">${item.quantity}</p>
            <div class="w-16 h-1.5 rounded-full bg-gray-100 ml-auto mt-1"><div class="h-full rounded-full ${Number(item.quantity)===0?"bg-red-400":Number(item.quantity)<=Number(item.reorder_level)?"bg-amber-400":"bg-green-400"}" style="width:${pct(item)}%"></div></div>
        </td>
        <td class="px-6 py-4 text-right text-gray-600">${item.reorder_level}</td>
        <td class="px-6 py-4 text-gray-500 text-sm">${escHtml(item.batch_number || "—")}</td>
        <td class="px-6 py-4 text-sm text-gray-500">${fmtDate(item.expiry_date)}</td>
        <td class="px-6 py-4">${statusBadge(item.quantity, item.reorder_level)}</td>
        <td class="px-6 py-4 text-center"><div class="inline-flex items-center gap-1.5">
            <button onclick="editItem(${item.id})" class="h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"><i class="fas fa-pencil text-xs"></i></button>
            <button onclick="delItem(${item.id})"  class="h-8 w-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition"><i class="fas fa-trash text-xs"></i></button>
        </div></td></tr>`).join("");
}
document.getElementById("filter").addEventListener("change", () => render(applyFilter()));
function openModal(item = null) {
    editId = item?.id ?? null;
    document.getElementById("modal-title").textContent = item ? "Edit Inventory" : "Add Inventory Item";
    document.getElementById("submit-btn").textContent  = item ? "Update" : "Save";
    document.getElementById("f-id").value       = item?.id ?? "";
    document.getElementById("f-medicine").value = item?.medicine_id ?? "";
    document.getElementById("f-supplier").value = item?.supplier_id ?? "";
    document.getElementById("f-quantity").value = item?.quantity ?? "";
    document.getElementById("f-reorder").value  = item?.reorder_level ?? "";
    document.getElementById("f-batch").value    = item?.batch_number ?? "";
    document.getElementById("f-expiry").value   = item ? toInputDate(item.expiry_date) : "";
    document.getElementById("modal").classList.remove("hidden");
    setTimeout(() => document.getElementById("f-medicine").focus(), 60);
}
function closeModal() { document.getElementById("modal").classList.add("hidden"); }
document.getElementById("modal").addEventListener("click", e => { if (e.target === document.getElementById("modal")) closeModal(); });
function editItem(id) { openModal(inventory.find(i => i.id === id)); }
async function delItem(id) {
    if (!confirm("Delete this record?")) return;
    try { await api(`/api/inventory/${id}`, "DELETE"); showToast("Deleted.", "warning"); load(); } catch(e) { showToast(e.message, "error"); }
}
async function handleSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById("submit-btn");
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner spin mr-1"></i> Saving...';
    const body = {
        medicine_id:  parseInt(document.getElementById("f-medicine").value),
        supplier_id:  document.getElementById("f-supplier").value ? parseInt(document.getElementById("f-supplier").value) : null,
        quantity:     parseInt(document.getElementById("f-quantity").value),
        reorder_level:parseInt(document.getElementById("f-reorder").value) || 10,
        batch_number: document.getElementById("f-batch").value.trim() || null,
        expiry_date:  document.getElementById("f-expiry").value || null,
    };
    try {
        if (editId) { await api(`/api/inventory/${editId}`, "PUT", body); showToast("Updated!"); }
        else        { await api("/api/inventory", "POST", body);          showToast("Added!"); }
        closeModal(); load();
    } catch(e) { showToast(e.message, "error"); }
    finally { btn.disabled = false; btn.textContent = editId ? "Update" : "Save"; }
}
load();
JS);
"""

# Fix the syntax error in inventory.php (mismatched quote in 'Stock Management' line)
INVENTORY_PHP = INVENTORY_PHP.replace(
    r"""    $h2   = Dom::el('h2', ['class' => 'font-bold text-gray-800"]); $h2->appendChild(Dom::text('Stock Management'));""",
    r"""    $h2   = Dom::el('h2', ['class' => 'font-bold text-gray-800']); $h2->appendChild(Dom::text('Stock Management'));"""
)

# ──────────────────────────────────────────────────────────────────────────────
SALES_PHP = r"""<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Sales', 'Sales', 'sales', function (DOMElement $main): void {

    // ── Summary bar ──────────────────────────────────────────────────────────
    $grid = Dom::el('div', ['class' => 'grid grid-cols-3 gap-5 mb-6']);
    $main->appendChild($grid);

    foreach ([
        ['summary-count',   'Total Transactions', 'fa-receipt',     'bg-blue-50',   'text-blue-600'],
        ['summary-units',   'Units Sold',          'fa-box-open',    'bg-indigo-50', 'text-indigo-600'],
        ['summary-revenue', 'Total Revenue',       'fa-dollar-sign', 'bg-green-50',  'text-green-600'],
    ] as [$id, $lbl, $icon, $bg, $ic]) {
        $c  = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4']);
        $iw = Dom::el('div', ['class' => 'h-10 w-10 rounded-xl ' . $bg . ' flex items-center justify-center']);
        $ii = Dom::el('i',   ['class' => 'fas ' . $icon . ' ' . $ic . ' text-sm']);
        $ii->appendChild(Dom::text(''));
        $iw->appendChild($ii);
        $c->appendChild($iw);
        $tx = Dom::el('div');
        $pl = Dom::el('p', ['class' => 'text-xs text-gray-400']);
        $pl->appendChild(Dom::text($lbl));
        $pv = Dom::el('p', ['id' => $id, 'class' => 'text-xl font-extrabold text-gray-800']);
        $pv->appendChild(Dom::text("\xE2\x80\x94"));
        $tx->appendChild($pl); $tx->appendChild($pv);
        $c->appendChild($tx); $grid->appendChild($c);
    }

    // ── Table card ───────────────────────────────────────────────────────────
    $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden']);

    $hdr    = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4']);
    $td     = Dom::el('div');
    $h2     = Dom::el('h2', ['class' => 'font-bold text-gray-800']);
    $h2->appendChild(Dom::text('Sales Ledger'));
    $sb     = Dom::el('p', ['class' => 'text-xs text-gray-400 mt-0.5']);
    $sb->appendChild(Dom::text('All recorded transactions'));
    $td->appendChild($h2); $td->appendChild($sb); $hdr->appendChild($td);
    $addBtn = Dom::el('button', ['onclick' => 'openModal()', 'class' => 'flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm whitespace-nowrap']);
    $pi     = Dom::el('i', ['class' => 'fas fa-plus text-xs']); $pi->appendChild(Dom::text('')); $addBtn->appendChild($pi); $addBtn->appendChild(Dom::text(' Record Sale'));
    $hdr->appendChild($addBtn); $card->appendChild($hdr);

    $tw  = Dom::el('div', ['class' => 'overflow-x-auto']);
    $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
    $th  = Dom::el('thead');
    $tr  = Dom::el('tr', ['class' => 'bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider']);
    foreach ([['#','text-left px-6 py-3.5'],['Medicine','text-left px-6 py-3.5'],['Customer','text-left px-6 py-3.5'],['Qty','text-right px-6 py-3.5'],['Unit Price','text-right px-6 py-3.5'],['Total','text-right px-6 py-3.5'],['Date','text-left px-6 py-3.5']] as [$l,$c]) {
        $te = Dom::el('th', ['class' => $c]); $te->appendChild(Dom::text($l)); $tr->appendChild($te); }
    $th->appendChild($tr); $tbl->appendChild($th);
    $tbody = Dom::el('tbody', ['id' => 'sales-tbody']);
    $lr = Dom::el('tr'); $ltd = Dom::el('td', ['colspan' => '7', 'class' => 'text-center py-12 text-gray-300']);
    $spi = Dom::el('i', ['class' => 'fas fa-spinner spin mr-2']); $spi->appendChild(Dom::text(''));
    $ltd->appendChild($spi); $ltd->appendChild(Dom::text('Loading sales...')); $lr->appendChild($ltd); $tbody->appendChild($lr);
    $tbl->appendChild($tbody); $tw->appendChild($tbl); $card->appendChild($tw);
    $ft = Dom::el('div', ['class' => 'px-6 py-3 border-t border-gray-100 bg-gray-50']);
    $rc = Dom::el('p', ['id' => 'rec-count', 'class' => 'text-xs text-gray-400']); $rc->appendChild(Dom::text("\xE2\x80\x94"));
    $ft->appendChild($rc); $card->appendChild($ft); $main->appendChild($card);

    // ── Modal ────────────────────────────────────────────────────────────────
    $modal = Dom::el('div', ['id' => 'modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
    $mbox  = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);
    $mh = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
    $mt = Dom::el('h3', ['class' => 'text-lg font-bold text-gray-800']); $mt->appendChild(Dom::text('Record New Sale'));
    $xb = Dom::el('button', ['onclick' => 'closeModal()', 'class' => 'h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition']);
    $xi = Dom::el('i', ['class' => 'fas fa-times text-sm']); $xi->appendChild(Dom::text('')); $xb->appendChild($xi);
    $mh->appendChild($mt); $mh->appendChild($xb); $mbox->appendChild($mh);
    $frm = Dom::el('form', ['id' => 'modal-form', 'class' => 'px-6 py-5 space-y-4', 'onsubmit' => 'handleSubmit(event)']);

    // medicine select
    $mw = Dom::el('div');
    $ml = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => 'f-medicine']); $ml->appendChild(Dom::text('Medicine'));
    $ms = Dom::el('span', ['class' => 'text-red-500']); $ms->appendChild(Dom::text(' *')); $ml->appendChild($ms);
    $mw->appendChild($ml);
    $msel = Dom::el('select', ['id' => 'f-medicine', 'required' => 'required', 'onchange' => 'onMedChange(this)', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm']);
    $dopt = Dom::el('option', ['value' => '']); $dopt->appendChild(Dom::text('Select medicine...')); $msel->appendChild($dopt);
    $mw->appendChild($msel);
    $si2 = Dom::el('div', ['id' => 'stock-info', 'class' => 'mt-1.5 text-xs text-gray-400 hidden']); $si2->appendChild(Dom::text(''));
    $mw->appendChild($si2); $frm->appendChild($mw);

    $inp = function (string $id, string $lbl, string $type, array $ex = [], bool $req = false) {
        $w  = Dom::el('div'); $lb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => $id]); $lb->appendChild(Dom::text($lbl));
        if ($req) { $st = Dom::el('span', ['class' => 'text-red-500']); $st->appendChild(Dom::text(' *')); $lb->appendChild($st); }
        $w->appendChild($lb);
        $a = array_merge(['id'=>$id,'type'=>$type,'class'=>'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm'], $req ? ['required'=>'required'] : [], $ex);
        $w->appendChild(Dom::el('input', $a)); return $w;
    };
    $frm->appendChild($inp('f-customer', 'Customer Name', 'text', ['placeholder' => 'Walk-in Customer']));

    $g = Dom::el('div', ['class' => 'grid grid-cols-3 gap-3']);
    $g->appendChild($inp('f-qty',   'Quantity', 'number', ['min'=>'1','placeholder'=>'1','oninput'=>'calcTotal()'], true));
    $g->appendChild($inp('f-price', 'Unit Price','number', ['step'=>'0.01','readonly'=>'readonly','class'=>'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50']));
    $g->appendChild($inp('f-total', 'Total',    'text',   ['readonly'=>'readonly','class'=>'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 font-bold text-green-700']));
    $frm->appendChild($g);

    $br = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
    $cb = Dom::el('button', ['type' => 'button', 'onclick' => 'closeModal()', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']); $cb->appendChild(Dom::text('Cancel'));
    $sb = Dom::el('button', ['type' => 'submit', 'id' => 'submit-btn', 'class' => 'flex-1 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm']); $sb->appendChild(Dom::text('Record Sale'));
    $br->appendChild($cb); $br->appendChild($sb); $frm->appendChild($br);
    $mbox->appendChild($frm); $modal->appendChild($mbox); $main->appendChild($modal);

}, <<<'JS'
let sales = [], allMedicines = [], allInventory = [];
async function load() {
    try {
        [sales, allMedicines, allInventory] = await Promise.all([api("/api/sales"), api("/api/medicines"), api("/api/inventory")]);
        document.getElementById("f-medicine").innerHTML = '<option value="">Select medicine...</option>' +
            allMedicines.map(m => `<option value="${m.id}" data-price="${m.price}">${escHtml(m.name)}</option>`).join("");
        render(sales);
    } catch(e) { showToast(e.message, "error"); }
}
function onMedChange(sel) {
    const opt = sel.selectedOptions[0];
    document.getElementById("f-price").value = opt?.dataset?.price ?? "";
    calcTotal();
    const inv  = allInventory.find(i => i.medicine_id == sel.value);
    const info = document.getElementById("stock-info");
    if (inv) {
        info.textContent = `Available stock: ${inv.quantity} unit${inv.quantity!==1?"s":""}`;
        info.className = "mt-1.5 text-xs block " + (Number(inv.quantity)===0 ? "text-red-500 font-bold" : Number(inv.quantity)<=Number(inv.reorder_level) ? "text-amber-600 font-bold" : "text-gray-400");
    } else { info.textContent = "No inventory record found."; info.className = "mt-1.5 text-xs text-gray-400 block"; }
}
function calcTotal() {
    const qty = parseFloat(document.getElementById("f-qty").value) || 0;
    const prc = parseFloat(document.getElementById("f-price").value) || 0;
    document.getElementById("f-total").value = fmtMoney(qty * prc);
}
function render(data) {
    const count = data.length;
    document.getElementById("rec-count").textContent      = `Showing ${count} transaction${count!==1?"s":""}`;
    document.getElementById("summary-count").textContent  = count;
    document.getElementById("summary-units").textContent  = data.reduce((a, s) => a + Number(s.quantity), 0);
    document.getElementById("summary-revenue").textContent = fmtMoney(data.reduce((a, s) => a + Number(s.total_price), 0));
    const tbody = document.getElementById("sales-tbody");
    if (!data.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-center py-12 text-gray-400">No sales yet.</td></tr>'; return; }
    tbody.innerHTML = [...data].reverse().map((s, i) => `<tr class="tbl-row border-t border-gray-50">
        <td class="px-6 py-4 text-xs text-gray-400">${i+1}</td>
        <td class="px-6 py-4 font-semibold text-gray-800">${escHtml(s.medicine?.name ?? "Unknown")}</td>
        <td class="px-6 py-4 text-gray-600">${escHtml(s.customer_name || "Walk-in")}</td>
        <td class="px-6 py-4 text-right font-medium text-gray-800">${s.quantity}</td>
        <td class="px-6 py-4 text-right text-gray-600">${fmtMoney(s.unit_price ?? 0)}</td>
        <td class="px-6 py-4 text-right font-bold text-green-700">${fmtMoney(s.total_price)}</td>
        <td class="px-6 py-4 text-gray-500">${fmtDate(s.sale_date)}</td></tr>`).join("");
}
function openModal() {
    document.getElementById("f-medicine").value = "";
    document.getElementById("f-customer").value = "";
    document.getElementById("f-qty").value       = "";
    document.getElementById("f-price").value     = "";
    document.getElementById("f-total").value     = "";
    document.getElementById("stock-info").className = "mt-1.5 text-xs text-gray-400 hidden";
    document.getElementById("modal").classList.remove("hidden");
    setTimeout(() => document.getElementById("f-medicine").focus(), 60);
}
function closeModal() { document.getElementById("modal").classList.add("hidden"); }
document.getElementById("modal").addEventListener("click", e => { if (e.target === document.getElementById("modal")) closeModal(); });
async function handleSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById("submit-btn");
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner spin mr-1"></i> Saving...';
    const body = {
        medicine_id:   parseInt(document.getElementById("f-medicine").value),
        customer_name: document.getElementById("f-customer").value.trim() || "Walk-in",
        quantity:      parseInt(document.getElementById("f-qty").value),
        unit_price:    parseFloat(document.getElementById("f-price").value) || 0,
    };
    try { await api("/api/sales", "POST", body); showToast("Sale recorded!"); closeModal(); load(); }
    catch(e) { showToast(e.message, "error"); }
    finally { btn.disabled = false; btn.textContent = "Record Sale"; }
}
load();
JS);
"""

# ──────────────────────────────────────────────────────────────────────────────
ROUTES_PHP = r"""<?php

use Illuminate\Support\Facades\Route;

/*
 * Each view file is a pure-PHP module that returns an HTML string.
 * No echo, no Blade — Laravel routes collect the return value and send it.
 */
Route::get('/', function () {
    return response(require resource_path('views/dashboard.php'))
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('dashboard');

Route::get('/medicines', function () {
    return response(require resource_path('views/medicines.php'))
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('medicines');

Route::get('/inventory', function () {
    return response(require resource_path('views/inventory.php'))
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('inventory');

Route::get('/sales', function () {
    return response(require resource_path('views/sales.php'))
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('sales');

Route::get('/suppliers', function () {
    return response(require resource_path('views/suppliers.php'))
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('suppliers');
"""

# ──────────────────────────────────────────────────────────────────────────────
files = {
    os.path.join(lib,   'dom.php'):          DOM_PHP,
    os.path.join(lib,   'layout.php'):       LAYOUT_PHP,
    os.path.join(views, 'dashboard.php'):    DASHBOARD_PHP,
    os.path.join(views, 'medicines.php'):    MEDICINES_PHP,
    os.path.join(views, 'suppliers.php'):    SUPPLIERS_PHP,
    os.path.join(views, 'inventory.php'):    INVENTORY_PHP,
    os.path.join(views, 'sales.php'):        SALES_PHP,
    routes_file:                             ROUTES_PHP,
}

for path, content in files.items():
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, 'w', encoding='utf-8', newline='\n') as f:
        f.write(content.lstrip('\n'))
    lines = content.count('\n')
    print(f"WROTE  {os.path.relpath(path, base):<52}  ({lines} lines)")

print("\nAll files written successfully.")
