<?php
// Database connection using your 'pharmacy_db'
$conn = new mysqli('localhost', 'root', '', 'pharmacy_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Encrypting the password 'admin123'
$pass = password_hash('admin123', PASSWORD_DEFAULT);

// Inserting into your 'users' table
$sql = "INSERT INTO users (username, password, role) VALUES ('admin', '$pass', 'admin')";

if ($conn->query($sql) === TRUE) {
    echo "Admin user created successfully!<br>";
    echo "Username: <b>admin</b><br>";
    echo "Password: <b>admin123</b>";
} else {
    echo "Error: " . $conn->error;
}
function render_view($page, $conn) {
    switch($page) {
        case 'dashboard':
            $count = $conn->query("SELECT COUNT(*) as t FROM inventory")->fetch_assoc()['t'];
            $val = $conn->query("SELECT SUM(stock_qty * price) as v FROM inventory")->fetch_assoc()['v'];
            return ui("h2", "System Overview") . ui("div",
                ui("div", ui("small", "Total Medicines") . ui("h2", $count), ["class"=>"stat-box"]) .
                ui("div", ui("small", "Inventory Value") . ui("h2", "₱".number_format($val, 2)), ["class"=>"stat-box", "style"=>"border-left-color:#3b82f6"]),
            ["class"=>"stat-grid"]);

        case 'manage': // --- NEW: ADD MEDICINE FORM ---
            return ui("h2", "Add New Stock") . ui("div",
                ui("form",
                    ui("label", "Medicine Name", ["style"=>"font-size:0.8rem; font-weight:700"]) .
                    ui("input", "", ["name"=>"med_name", "placeholder"=>"e.g. Paracetamol 500mg", "required"=>"true"]) .
                    ui("div",
                        ui("div", ui("label", "Stock Quantity") . ui("input", "", ["name"=>"qty", "type"=>"number", "placeholder"=>"0"]), ["style"=>"flex:1"]) .
                        ui("div", ui("label", "Price per Unit") . ui("input", "", ["name"=>"price", "type"=>"number", "step"=>"0.01", "placeholder"=>"0.00"]), ["style"=>"flex:1"]),
                    ["style"=>"display:flex; gap:20px"]) .
                    ui("button", "➕ SAVE TO INVENTORY", ["name"=>"add_med", "class"=>"btn btn-primary", "style"=>"width:100%"]),
                ["method"=>"POST"]),
            ["class"=>"card", "style"=>"max-width:600px"]);

        case 'records':
            $res = $conn->query("SELECT * FROM inventory ORDER BY id DESC");
            $rows = ui("tr", ui("th", "ID") . ui("th", "Medicine Name") . ui("th", "Stock Status") . ui("th", "Price") . ui("th", "Action"));
            while($r = $res->fetch_assoc()) {
                $status_color = ($r['stock_qty'] < 10) ? "#ef4444" : "#10b981";
                $rows .= ui("tr",
                    ui("td", "#".$r['id']) .
                    ui("td", ui("b", $r['med_name'])) .
                    ui("td", ui("span", $r['stock_qty'] . " Units", ["style"=>"color:$status_color; font-weight:700"])) .
                    ui("td", "₱".number_format($r['price'], 2)) .
                    ui("td", ui("a", "Edit", ["href"=>"#", "style"=>"color:#3b82f6; text-decoration:none; font-weight:600"]))
                );
            }
            return ui("h2", "Inventory Management") . ui("div", ui("table", $rows), ["class"=>"card"]);

        default: return "Select a menu item.";
    }
}
$conn->close();
?>
