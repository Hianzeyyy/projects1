<?php
require_once __DIR__ . '/auth.php';
$current_user = currentUser();
$initials = getInitials($current_user['fullname'] ?? 'U');
$page_file = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($page_title ?? 'Dashboard') ?> — NexusMIS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>
<div class="app-wrapper">

<!-- Backdrop -->
<div class="overlay-backdrop" id="sidebar-backdrop"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <a href="dashboard.php" class="sidebar-brand">
      <div class="brand-icon">⚡</div>
      <span class="brand-name">NexusMIS</span>
    </a>
    <button class="sidebar-close" id="sidebar-close">✕</button>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Main</div>

    <a href="dashboard.php" class="nav-item <?= $page_file === 'dashboard.php' ? 'active' : '' ?>">
      <span class="nav-icon">▦</span> Dashboard
    </a>
    <a href="records.php" class="nav-item <?= $page_file === 'records.php' ? 'active' : '' ?>">
      <span class="nav-icon">☰</span> All Records
    </a>
    <a href="add_record.php" class="nav-item <?= $page_file === 'add_record.php' ? 'active' : '' ?>">
      <span class="nav-icon">＋</span> Add Record
    </a>

    <div class="nav-section-label" style="margin-top:8px;">System</div>
    <a href="users.php" class="nav-item <?= $page_file === 'users.php' ? 'active' : '' ?>">
      <span class="nav-icon">👤</span> Users
    </a>
    <a href="activity.php" class="nav-item <?= $page_file === 'activity.php' ? 'active' : '' ?>">
      <span class="nav-icon">📋</span> Activity Log
    </a>
    <a href="profile.php" class="nav-item <?= $page_file === 'profile.php' ? 'active' : '' ?>">
      <span class="nav-icon">⚙</span> Profile
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="profile.php" class="sidebar-user">
      <div class="user-avatar"><?= htmlspecialchars($initials) ?></div>
      <div class="user-info">
        <div class="user-name"><?= htmlspecialchars($current_user['fullname'] ?? 'User') ?></div>
        <div class="user-role"><?= htmlspecialchars($current_user['role'] ?? '') ?></div>
      </div>
    </a>
  </div>
</aside>

<!-- MAIN -->
<div class="main-content">
  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <button class="menu-toggle" id="menu-toggle">☰</button>
      <div>
        <div class="page-title"><?= htmlspecialchars($page_title ?? 'Dashboard') ?></div>
        <div class="breadcrumb"><?= $breadcrumb ?? '<span>Home</span>' ?></div>
      </div>
    </div>
    <div class="topbar-right">
      <div class="topbar-search">
        <span class="search-icon">🔍</span>
        <input type="text" id="topbar-search" placeholder="Search records…" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"/>
      </div>
      <a href="add_record.php" class="topbar-btn" title="Add Record">＋</a>
      <a href="logout.php" class="topbar-btn" title="Logout">⏻</a>
    </div>
  </header>

  <main class="page-content">
