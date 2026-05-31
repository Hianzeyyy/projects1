<?php
function render_footer(string $pageScripts = ''): void
{
    echo '</main>';
    echo '</div>';
    echo '</div>';

    // Toast
    echo '<div id="toast" class="fixed bottom-5 right-5 z-50 opacity-0 translate-y-2 pointer-events-none transition-all duration-300">';
    echo '  <div id="toast-box" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-white text-sm font-medium min-w-[220px]">';
    echo '    <i id="toast-icon" class="fas fa-check-circle text-base"></i>';
    echo '    <span id="toast-msg"></span>';
    echo '  </div>';
    echo '</div>';

    // Shared JS helpers
    echo '<script>';
    echo 'function showToast(msg, type = "success") {';
    echo '  const t = document.getElementById("toast");';
    echo '  const b = document.getElementById("toast-box");';
    echo '  const i = document.getElementById("toast-icon");';
    echo '  document.getElementById("toast-msg").textContent = msg;';
    echo '  const cfg = { success:["bg-[#b651f0]","fa-check-circle"], error:["bg-red-500","fa-circle-xmark"], warning:["bg-amber-500","fa-triangle-exclamation"], info:["bg-blue-500","fa-circle-info"] };';
    echo '  const [bg, cls] = cfg[type] ?? cfg.success;';
    echo '  b.className = `flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-white text-sm font-medium min-w-[220px] ${bg}`;';
    echo '  i.className = `fas ${cls} text-base`;';
    echo '  t.classList.remove("opacity-0","translate-y-2","pointer-events-none");';
    echo '  clearTimeout(t._tmr);';
    echo '  t._tmr = setTimeout(() => t.classList.add("opacity-0","translate-y-2","pointer-events-none"), 3200);';
    echo '}';
    echo 'async function api(url, method = "GET", body = null) {';
    echo '  const opts = { method, headers: { "Content-Type": "application/json", "Accept": "application/json" } };';
    echo '  if (body) opts.body = JSON.stringify(body);';
    echo '  const r = await fetch(url, opts);';
    echo '  const json = await r.json();';
    echo '  if (!r.ok) throw new Error(json.message ?? json.error ?? "Request failed");';
    echo '  return json;';
    echo '}';
    echo 'function fmtDate(d) { if (!d) return "\u2014"; return new Date(d).toLocaleDateString("en-US", { year:"numeric", month:"short", day:"2-digit" }); }';
    echo 'function fmtMoney(n) { return new Intl.NumberFormat("en-PH", { style:"currency", currency:"PHP" }).format(n ?? 0); }';
    echo 'function toInputDate(d) { return d ? d.split("T")[0] : ""; }';
    echo 'function escHtml(s) { return String(s ?? "").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;"); }';
    echo '(async function updateBadge() {';
    echo '  try {';
    echo '    const inv = await api("/api/inventory");';
    echo '    const count = inv.filter(i => i.quantity <= i.reorder_level).length;';
    echo '    const badge = document.getElementById("nav-lowstock-badge");';
    echo '    if (badge && count > 0) { badge.textContent = count; badge.classList.remove("hidden"); }';
    echo '  } catch(_) {}';
    echo '})();';
    echo '</script>';

    // Page-specific scripts injected here
    echo $pageScripts;

    echo '</body>';
    echo '</html>';
}
