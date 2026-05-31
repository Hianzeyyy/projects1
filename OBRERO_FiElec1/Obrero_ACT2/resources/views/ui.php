<?php
function ui($tag, $content = "", $attr = []) {
    $props = "";
    foreach ($attr as $k => $v) { $props .= " $k='$v'"; }
    // Handles self-closing tags like <input> or <link>
    if (in_array($tag, ['input', 'link', 'meta', 'br', 'hr'])) {
        return "<{$tag}{$props}>";
    }
    return "<{$tag}{$props}>{$content}</{$tag}>";
}

function get_styles() {
    return ui("style", "
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        :root { --primary: #10b981; --sidebar: #0f172a; --bg: #f1f5f9; --white: #ffffff; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); margin: 0; display: flex; }
        .sidebar { width: 280px; background: var(--sidebar); height: 100vh; position: fixed; padding: 2rem; color: white; box-sizing: border-box; }
        .nav-item { display: block; padding: 12px; color: #94a3b8; text-decoration: none; font-weight: 600; }
        .nav-item.active { color: var(--primary); background: rgba(16, 185, 129, 0.1); border-radius: 8px; }
        .main { margin-left: 280px; width: calc(100% - 280px); padding: 40px; box-sizing: border-box; }
        .card { background: var(--white); border-radius: 20px; padding: 30px; border: 1px solid #eef2f6; }
        .stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .stat-box { background: white; padding: 20px; border-radius: 15px; border-left: 5px solid var(--primary); }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 8px; }
        .btn { padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 700; background: var(--primary); color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 15px; border-bottom: 1px solid #eee; }
    ");
}
