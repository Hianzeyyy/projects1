<?php



// 1. Setup Timezone and Logic
date_default_timezone_set('Asia/Manila');
$currentDay = date('l');

// 2. Start String Construction
$output = "<!DOCTYPE html><html><head><title>Schedule - " . ($university_name ?? 'PSU') . "</title>";
$output .= "<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap' rel='stylesheet'>";
$output .= "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
$output .= "<style>
    body { font-family: 'Montserrat', sans-serif; background-color: #001a33; color: white; margin: 0; min-height: 100vh; display: flex; flex-direction: column; }
    .psu-logo { height: 50px; width: auto; margin-right: 15px; }
    .brand-text { color: #ffcc00; font-weight: 900; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }

    /* Navbar Styles */
    .nav-link { color: white !important; font-weight: 700; text-transform: uppercase; font-size: 14px; }
    .nav-link:hover { color: #ffcc00 !important; }
    .dropdown-menu { background-color: #002b5c; border: 2px solid #ffcc00; border-radius: 10px; padding: 10px 0; }
    .dropdown-item { color: white !important; font-weight: 600; font-size: 13px; padding: 10px 25px; text-transform: uppercase; }
    .dropdown-item:hover { background-color: #ffcc00 !important; color: #001a33 !important; }

    /* Table & Highlight Styles */
    .schedule-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.6); }
    .table thead th { color: #333; font-weight: 900; border-bottom: 3px solid #ffcc00; text-transform: uppercase; font-size: 12px; text-align: center; }
    .table tbody td { padding: 20px 10px; color: #444; font-size: 14px; border: none; text-align: center; }

    .day-cell { color: #002b5c !important; font-weight: 700; }
    .subject-cell { color: #002b5c !important; font-weight: 800; text-align: left !important; }

    /* REAL-TIME HIGHLIGHT */
    .highlight-active { background-color: #fff9e6 !important; border-left: 12px solid #ffcc00 !important; }
    .highlight-active td { color: #000 !important; font-weight: 600; background-color: rgba(255, 204, 0, 0.15) !important; }
    .today-badge { background: #ffcc00; color: #001a33; font-size: 10px; padding: 3px 10px; border-radius: 50px; font-weight: 900; margin-left: 10px; }
</style></head><body>";

// --- Navbar Section ---
$output .= "<nav class='navbar navbar-expand-lg sticky-top' style='background-color: #002b5c; padding: 15px 0;'>";
$output .= "<div class='container'>";
$output .= "<a class='navbar-brand d-flex align-items-center' href='" . url('/') . "'>";
$output .= "<img src='https://main.psu.edu.ph/wp-content/uploads/2022/07/PSU-LABEL-LOGO.png' class='psu-logo'>";
$output .= "<span class='brand-text'>PSU PORTAL</span></a>";

$output .= "<button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navContent' style='border-color: #ffcc00;'>";
$output .= "<span class='navbar-toggler-icon' style='filter: invert(1);'></span></button>";

$output .= "<div class='collapse navbar-collapse' id='navContent'>";
$output .= "<ul class='navbar-nav ms-auto align-items-center' style='gap: 20px;'>";
$output .= "<li class='nav-item'><a class='nav-link' href='" . url('/') . "'>HOME</a></li>";

// --- DYNAMIC DROPDOWN LOOP ---
$output .= "<li class='nav-item dropdown'>";
$output .= "<a class='nav-link dropdown-toggle' href='#' id='activeDrop' role='button' data-bs-toggle='dropdown' aria-expanded='false'>ACTIVITIES</a>";
$output .= "<ul class='dropdown-menu dropdown-menu-end shadow' aria-labelledby='activeDrop'>";

if (isset($menu) && is_array($menu)) {
    foreach ($menu as $item) {
        $output .= "<li><a class='dropdown-item' href='" . url($item['route']) . "'>{$item['label']}</a></li>";
    }
} else {
    // Fallback if $menu is not passed
    $output .= "<li><a class='dropdown-item' href='" . url('/syllabus') . "'>Syllabus</a></li>";
    $output .= "<li><a class='dropdown-item' href='" . url('/schedule') . "'>Schedule</a></li>";
}
$output .= "<li><hr class='dropdown-divider'></li>";
$output .= "<li><a class='dropdown-item' href='http://localhost/Herd/elective1-act1/elctive1-act2/laravel/public' target='_blank'>Activity 2 - Pharmacy System</a></li>";

$output .= "</ul></li>";
$output .= "<li class='nav-item dropdown'>";
$output .= "<a class='nav-link dropdown-toggle' href='#' id='projectDrop' role='button' data-bs-toggle='dropdown' aria-expanded='false'>PROJECTS</a>";
$output .= "<ul class='dropdown-menu dropdown-menu-end shadow' aria-labelledby='projectDrop'>";
$output .= "<li><a class='dropdown-item' href='#'>Project 1</a></li>";
$output .= "<li><a class='dropdown-item' href='#'>Project 2</a></li>";
$output .= "<li><a class='dropdown-item' href='#'>Project 3</a></li>";
$output .= "</ul></li></ul></div></div></nav>";
$output .= "<div style='background-color: #ffcc00; height: 5px; width: 100%;'></div>";

// --- Main Table Content ---
$output .= "<main class='container my-5 text-center'>";
$output .= "<h1 class='fw-black mb-4'>" . ($hero_welcome ?? 'Class Schedule') . "</h1>";

$output .= "<div class='schedule-card'><div class='table-responsive'><table class='table table-hover align-middle'>";
$output .= "<thead><tr><th>DAY</th><th>TIME</th><th>SUBJECT</th><th>ROOM</th><th>INSTRUCTOR</th></tr></thead><tbody>";

if (isset($schedule) && is_array($schedule)) {
    foreach($schedule as $s) {
        $isToday = (trim($s['day']) === $currentDay);
        $rowClass = $isToday ? "class='highlight-active'" : "";
        $badge = $isToday ? "<span class='today-badge'>TODAY</span>" : "";

        $output .= "<tr $rowClass>";
        $output .= "<td class='day-cell'>{$s['day']}{$badge}</td>";
        $output .= "<td>{$s['time']}</td>";
        $output .= "<td class='subject-cell'>{$s['subject']}</td>";
        $output .= "<td class='fw-bold text-muted'>{$s['room']}</td>";
        $output .= "<td>{$s['instructor']}</td>";
        $output .= "</tr>";
    }
}

$output .= "</tbody></table></div></div></main>";

// --- Footer ---
$output .= "<footer style='background-color: #000d1a; color: white; padding: 30px 0; text-align: center; border-top: 5px solid #ffcc00; margin-top: auto;'>";
$output .= "<p style='color: #ffcc00; font-weight: 900; margin-bottom: 0;'>PANGASINAN STATE UNIVERSITY</p></footer>";

$output .= "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
$output .= "</body></html>";

echo $output;
?>
