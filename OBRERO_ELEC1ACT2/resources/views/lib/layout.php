<?php
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

        // Logged-in user row
        $user = \Illuminate\Support\Facades\Auth::user();
        $urow = Dom::el('div', ['class' => 'flex items-center gap-3 mb-3']);
        $av   = Dom::el('div', ['class' => 'h-9 w-9 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0']);
        $avL  = Dom::el('span', ['class' => 'text-green-700 font-bold text-sm']);
        $avL->appendChild(Dom::text(strtoupper(substr($user ? $user->name : 'U', 0, 1))));
        $av->appendChild($avL);
        $nd = Dom::el('div', ['class' => 'flex-1 min-w-0']);
        $np = Dom::el('p', ['class' => 'text-sm font-semibold text-gray-800 truncate']);
        $np->appendChild(Dom::text($user ? $user->name : 'Guest'));
        $ep = Dom::el('p', ['class' => 'text-[10px] text-gray-400 truncate']);
        $ep->appendChild(Dom::text($user ? $user->email : ''));
        $nd->appendChild($np);
        $nd->appendChild($ep);
        $urow->appendChild($av);
        $urow->appendChild($nd);
        $fd->appendChild($urow);

        // Logout form
        $lf   = Dom::el('form', ['method' => 'POST', 'action' => '/logout']);
        $lcsrf = Dom::el('input', ['type' => 'hidden', 'name' => '_token', 'value' => csrf_token()]);
        $lf->appendChild($lcsrf);
        $lb   = Dom::el('button', [
            'type'  => 'submit',
            'class' => 'w-full flex items-center gap-2 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition',
        ]);
        $li   = Dom::el('i', ['class' => 'fas fa-right-from-bracket text-sm']);
        $li->appendChild(Dom::text(''));
        $lb->appendChild($li);
        $lb->appendChild(Dom::text(' Logout'));
        $lf->appendChild($lb);
        $fd->appendChild($lf);

        // Copyright
        $fp = Dom::el('p', ['class' => 'text-[10px] text-gray-400 text-center mt-2']);
        $fp->appendChild(Dom::text("\xC2\xA9 2025 PharmaCare"));
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
