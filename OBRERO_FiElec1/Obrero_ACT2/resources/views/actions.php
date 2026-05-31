<?php
include 'db_connect.php';
session_start();

$page = $_GET['page'] ?? 'login';

// Handle Login
if (isset($_POST['login'])) {
    $user = $conn->real_escape_string($_POST['user']);
    $res = $conn->query("SELECT * FROM users WHERE username='$user'")->fetch_assoc();
    if ($res && password_verify($_POST['pass'], $res['password'])) {
        $_SESSION['user'] = $user;
        header("Location: index.php?page=dashboard");
        exit();
    }
}

// Handle Adding Medicine
if (isset($_POST['add_med'])) {
    $name = $conn->real_escape_string($_POST['med_name']);
    $qty = (int)$_POST['qty'];
    $price = (float)$_POST['price'];
    $conn->query("INSERT INTO inventory (med_name, stock_qty, price) VALUES ('$name', '$qty', '$price')");
    header("Location: index.php?page=records");
    exit();
}

// Handle Deleting
if ($page === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("DELETE FROM inventory WHERE id = $id");
    header("Location: index.php?page=records");
    exit();
}
?>
