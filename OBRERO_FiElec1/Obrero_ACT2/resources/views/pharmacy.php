<?php
session_start();
// Database Connection - Ensure 'pharmacy_db' exists in your phpMyAdmin
$conn = new mysqli('localhost', 'root', '', 'pharmacy_db');

// --- 1. LOGIC CONTROLLER (Handles all Actions) ---
$page = $_GET['page'] ?? 'login';
$error = "";
$success = "";

// A. Logout Logic
if ($page === 'logout') {
    session_destroy();
    header("Location: ?page=login");
    exit();
}

// B. Login Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user = $conn->real_escape_string($_POST['user']);
    $pass = $_POST['pass'];
    $res = $conn->query("SELECT * FROM users WHERE username='$user'")->fetch_assoc();

    if ($res && password_verify($pass, $res['password'])) {
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $res['role'];
        header("Location: ?page=dashboard");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}

// C. Registration Logic
// C. Registration Logic - UPDATED
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $user = $conn->real_escape_string($_POST['user']);

    // Check if username already exists
    $check = $conn->query("SELECT id FROM users WHERE username='$user'");
    if ($check->num_rows > 0) {
        $error = "Error: Username '$user' is already registered. Please choose another.";
        $page = 'signup'; // Keep them on the signup page
    } else {
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $conn->query("INSERT INTO users (username, password, role) VALUES ('$user', '$pass', '$role')");
        $success = "Registration successful! You can now login.";
        $page = 'login';
    }
}

// D. Add Medicine Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_med'])) {
    $name = $conn->real_escape_string($_POST['med_name']);
    $qty = (int)$_POST['qty'];
    $price = (float)$_POST['price'];
    $conn->query("INSERT INTO inventory (med_name, stock_qty, price) VALUES ('$name', '$qty', '$price')");
    header("Location: ?page=records&msg=added");
    exit();
}

// --- 2. UI RENDER ENGINE ---
function ui($tag, $content = "", $attr = []) {
    $props = "";
    foreach ($attr as $k => $v) { $props .= " $k='$v'"; }
    return "<{$tag}{$props}>{$content}</{$tag}>";
}

function get_css() {
    return ui("style", "
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        :root { --primary: #10b981; --primary-dark: #059669; --bg: #f1f5f9; --sidebar: #0f172a; --white: #ffffff; --text: #1e293b; --muted: #64748b; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); margin: 0; display: flex; }
        .sidebar { width: 280px; background: var(--sidebar); height: 100vh; position: fixed; padding: 2.5rem 1.5rem; color: white; box-sizing: border-box; display: flex; flex-direction: column; }
        .brand { font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 3rem; display: flex; align-items: center; gap: 10px; }
        .nav-item { display: block; padding: 14px 18px; color: #94a3b8; text-decoration: none; border-radius: 12px; margin-bottom: 8px; font-weight: 600; transition: 0.3s; }
        .nav-item:hover, .nav-item.active { background: rgba(255,255,255,0.05); color: white; }
        .nav-item.active { border-left: 4px solid var(--primary); background: rgba(16, 185, 129, 0.1); }
        .main { margin-left: 280px; width: calc(100% - 280px); min-height: 100vh; }
        .top-bar { background: var(--white); padding: 1.5rem 5%; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; }
        .content { padding: 40px 5%; }
        .card { background: var(--white); border-radius: 20px; padding: 30px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid #eef2f6; }
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 40px; }
        .stat-box { background: var(--white); padding: 25px; border-radius: 20px; border-left: 5px solid var(--primary); box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        input, select { width: 100%; padding: 14px; margin: 10px 0 20px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 1rem; box-sizing: border-box; }
        .btn { padding: 14px 28px; border-radius: 12px; border: none; cursor: pointer; font-weight: 700; display: inline-block; text-decoration: none; text-align: center; transition: 0.3s; }
        .btn-primary { background: var(--primary); color: white; }
        .alert { padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem; font-weight: 600; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        td { background: white; padding: 20px; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
        td:first-child { border-left: 1px solid #f1f5f9; border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
        td:last-child { border-right: 1px solid #f1f5f9; border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
    ");
}

echo get_css();

// --- 3. GUEST VIEWS (Login / Signup / Forgot) ---
if (!isset($_SESSION['user'])) {
    $auth_content = "";
    if ($page === 'signup') {
        $auth_content = ui("h2", "Staff Registration") . ui("form",
            ui("input", "", ["name"=>"user", "placeholder"=>"Username", "required"=>"true"]) .
            ui("input", "", ["name"=>"pass", "type"=>"password", "placeholder"=>"Password", "required"=>"true"]) .
            ui("select", ui("option", "Pharmacist", ["value"=>"staff"]) . ui("option", "Manager", ["value"=>"admin"]), ["name"=>"role"]) .
            ui("button", "CREATE ACCOUNT", ["name"=>"signup", "class"=>"btn btn-primary", "style"=>"width:100%"]),
        ["method"=>"POST"]) . ui("p", ui("a", "Already have an account? Login", ["href"=>"?page=login", "style"=>"color:var(--muted); font-size:0.8rem"]));
    } elseif ($page === 'forgot') {
        $auth_content = ui("h2", "Account Recovery") . ui("div", "💊", ["style"=>"font-size:3rem; margin:15px 0"]) .
            ui("div", "Please contact the <b>IT Security Desk</b> to reset your credentials. Unauthorized reset attempts are logged.", ["style"=>"background:#eff6ff; padding:20px; border-radius:12px; color:#1e40af; margin-bottom:20px; font-size:0.85rem; text-align:left;"]) .
            ui("a", "Back to Login", ["href"=>"?page=login", "class"=>"btn", "style"=>"background:#64748b; color:white; width:100%"]);
    } else {
        $auth_content = ui("h2", "Pharmacy Portal") .
            ($error ? ui("div", $error, ["class"=>"alert alert-error"]) : "") .
            ($success ? ui("div", $success, ["class"=>"alert alert-success"]) : "") .
            ui("form",
                ui("input", "", ["name"=>"user", "placeholder"=>"Username", "required"=>"true"]) .
                ui("input", "", ["name"=>"pass", "type"=>"password", "placeholder"=>"Password", "required"=>"true"]) .
                ui("button", "SECURE LOGIN", ["name"=>"login", "class"=>"btn btn-primary", "style"=>"width:100%"]),
            ["method"=>"POST"]) .
            ui("div", ui("a", "Register", ["href"=>"?page=signup"]) . " • " . ui("a", "Forgot Password?", ["href"=>"?page=forgot"]), ["style"=>"margin-top:20px; text-align:center; color:var(--muted); font-size:0.8rem"]);
    }
    echo ui("div", ui("div", "PHARMA CORE", ["style"=>"font-weight:900; color:var(--primary); margin-bottom:20px"]) . ui("div", $auth_content, ["class"=>"card", "style"=>"width:400px; text-align:center"]), ["style"=>"width:100%; display:flex; flex-direction:column; justify-content:center; align-items:center; min-height:100vh; background:#f8fafc"]);
    exit();
}

// --- 4. AUTHENTICATED DASHBOARD ---
echo ui("div",
    ui("div", "💊 PHARMA-MIS", ["class"=>"brand"]) .
    ui("a", "📊 Dashboard", ["href"=>"?page=dashboard", "class"=>"nav-item ".($page=='dashboard'?'active':'')]) .
    ui("a", "📦 Inventory", ["href"=>"?page=records", "class"=>"nav-item ".($page=='records' || $page=='add_new'?'active':'')]) .
    ui("a", "👤 Profile", ["href"=>"?page=profile", "class"=>"nav-item ".($page=='profile'?'active':'')]) .
    ui("a", "🚪 Sign Out", ["href"=>"?page=logout", "class"=>"nav-item", "style"=>"margin-top:auto; color:#ef4444"]),
["class"=>"sidebar"]);

echo ui("div",
    ui("div", ui("span", "Active Staff: " . ui("b", $_SESSION['user'])) . ui("span", date('F d, Y'), ["style"=>"color:var(--muted)"]), ["class"=>"top-bar"]) .
    ui("div", render_view($page, $conn), ["class"=>"content"]),
["class"=>"main"]);

function render_view($page, $conn) {
    switch($page) {
        case 'dashboard':
            $count = $conn->query("SELECT COUNT(*) as t FROM inventory")->fetch_assoc()['t'];
            $low = $conn->query("SELECT COUNT(*) as t FROM inventory WHERE stock_qty < 10")->fetch_assoc()['t'];
            return ui("h2", "System Overview") . ui("div",
                ui("div", ui("small", "Total Medicines") . ui("h2", $count), ["class"=>"stat-box"]) .
                ui("div", ui("small", "Low Stock Alert") . ui("h2", $low, ["style"=>"color:#ef4444"]), ["class"=>"stat-box", "style"=>"border-left-color:#ef4444"]),
            ["class"=>"stat-grid"]);

        case 'add_new':
            return ui("h2", "Add New Medicine") . ui("div",
                ui("form",
                    ui("input", "", ["name"=>"med_name", "placeholder"=>"Medicine Name", "required"=>"true"]) .
                    ui("input", "", ["name"=>"qty", "type"=>"number", "placeholder"=>"Initial Stock Quantity", "required"=>"true"]) .
                    ui("input", "", ["name"=>"price", "type"=>"number", "step"=>"0.01", "placeholder"=>"Unit Price (₱)", "required"=>"true"]) .
                    ui("button", "Save to Inventory", ["name"=>"add_med", "class"=>"btn btn-primary", "style"=>"width:100%"]),
                ["method"=>"POST"]),
            ["class"=>"card", "style"=>"max-width:500px"]);

        case 'records':
            $res = $conn->query("SELECT * FROM inventory ORDER BY id DESC");
            $rows = ui("tr", ui("th", "ID") . ui("th", "Medicine Name") . ui("th", "Stock Status") . ui("th", "Price") . ui("th", "Action"));
            while($r = $res->fetch_assoc()) {
                $status = ($r['stock_qty'] < 10) ? "color:#ef4444; font-weight:700" : "color:var(--primary)";
                $rows .= ui("tr", ui("td", "#".$r['id']) . ui("td", ui("b", $r['med_name'])) . ui("td", $r['stock_qty'] . " Units", ["style"=>$status]) . ui("td", "₱".number_format($r['price'], 2)) . ui("td", ui("a", "Edit", ["href"=>"#", "style"=>"color:#3b82f6; text-decoration:none;"])));
            }
            return ui("div", ui("h2", "Inventory Management") . ui("a", "➕ Add New", ["href"=>"?page=add_new", "class"=>"btn btn-primary"]), ["style"=>"display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;"]) . ui("table", $rows);

        case 'profile':
            return ui("h2", "My Profile") . ui("div", ui("p", "Account Role: " . ui("b", strtoupper($_SESSION['role']))) . ui("p", "Username: " . $_SESSION['user']), ["class"=>"card"]);

        default: return "Select a menu item.";
    }
}
?>
