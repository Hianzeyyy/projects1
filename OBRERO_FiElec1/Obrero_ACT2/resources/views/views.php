<?php
include_once 'ui.php';

// --- AREA 1: DASHBOARD (Status & Records) ---
function render_dashboard($conn) {
    $total = $conn->query("SELECT COUNT(*) as t FROM inventory")->fetch_assoc()['t'];
    $val = $conn->query("SELECT SUM(stock_qty * price) as v FROM inventory")->fetch_assoc()['v'] ?? 0;

    $stats = ui("div",
        ui("div", ui("small", "Total Medicines") . ui("h2", $total), ["class" => "stat-box"]) .
        ui("div", ui("small", "Inventory Value") . ui("h2", "₱".number_format($val, 2)), ["class" => "stat-box"]) .
        ui("div", ui("small", "Active Users") . ui("h2", "4"), ["class" => "stat-box"]),
    ["class" => "stat-grid"]);

    return ui("h2", "System Dashboard") . $stats;
}

// --- AREA 2: MANAGEMENT FORM (Adding Records) ---
function render_management_form() {
    $form = ui("form",
        ui("input", "", ["name" => "med_name", "placeholder" => "Medicine Name", "required" => "true"]) .
        ui("input", "", ["name" => "qty", "type" => "number", "placeholder" => "Quantity"]) .
        ui("input", "", ["name" => "price", "type" => "number", "step" => "0.01", "placeholder" => "Price"]) .
        ui("button", "ADD TO INVENTORY", ["name" => "add_med", "class" => "btn"]),
    ["method" => "POST", "action" => "index.php?page=records"]);

    return ui("h2", "Stock Entry") . ui("div", $form, ["class" => "card", "style" => "max-width:500px"]);
}

// --- AREA 3: INFORMATION & RECORDS (View, Edit, Delete) ---
function render_records_table($conn) {
    $res = $conn->query("SELECT * FROM inventory ORDER BY id DESC");
    $header = ui("tr", ui("th", "Name") . ui("th", "Stock") . ui("th", "Price") . ui("th", "Actions"));

    $rows = "";
    while($r = $res->fetch_assoc()) {
        $actions = ui("a", "Edit", ["href" => "?page=edit&id=".$r['id'], "style" => "color:blue; margin-right:10px"]) .
                   ui("a", "Delete", ["href" => "?page=delete&id=".$r['id'], "style" => "color:red"]);

        $rows .= ui("tr",
            ui("td", $r['med_name']) .
            ui("td", $r['stock_qty']) .
            ui("td", "₱".$r['price']) .
            ui("td", $actions)
        );
    }

    return ui("h2", "Inventory Records") . ui("table", $header . $rows);
}
