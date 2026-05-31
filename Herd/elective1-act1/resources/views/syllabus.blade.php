<?php


// 1. Setup Timezone and Assets
date_default_timezone_set('Asia/Manila');
$brandGold  = "#ffcc00";
$deepBlue   = "#001a33";
$navBlue    = "#002b5c";
$logoUrl    = "https://main.psu.edu.ph/wp-content/uploads/2022/07/PSU-LABEL-LOGO.png";

// 2. Start building the single $output variable
$output = "<!DOCTYPE html><html><head><title>Syllabus - " . ($university_name ?? 'PSU') . "</title>";
$output .= "<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap' rel='stylesheet'>";
$output .= "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
$output .= "<style>
    body { font-family: 'Montserrat', sans-serif; background-color: #f4f7f6; color: #333; margin: 0; min-height: 100vh; display: flex; flex-direction: column; }

    /* Navbar & Hero */
    .psu-navbar { background-color: $navBlue; border-bottom: 5px solid $brandGold; padding: 15px 0; }
    .brand-text { color: $brandGold; font-weight: 900; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
    .hero-section { background: linear-gradient(135deg, #003366, #001a33); color: white; padding: 60px 0; text-align: center; border-bottom: 5px solid $brandGold; }

    /* Table Styling */
    .syllabus-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-top: -40px; }
    .table thead th { color: #333; font-weight: 900; border-bottom: 3px solid $brandGold; text-transform: uppercase; font-size: 12px; text-align: center; background: #fff; }
    .table tbody td { padding: 15px 10px; color: #555; font-size: 14px; text-align: center; vertical-align: middle; }

    .course-code { color: $navBlue; font-weight: 900; }
    .description-cell { text-align: left !important; font-weight: 600; color: #222; }

    /* Highlight Status Logic */
    .status-pending { background-color: rgba(255, 204, 0, 0.15) !important; font-weight: bold; }
    .grade-badge { padding: 4px 10px; border-radius: 50px; font-weight: 900; font-size: 12px; }
    .grade-passed { background: #e8f5e9; color: #2e7d32; }
    .grade-none { background: #fff3e0; color: #ef6c00; }
</style></head><body>";

// --- Navbar Section ---
$output .= "<nav class='psu-navbar'><div class='container d-flex justify-content-between align-items-center'>";
$output .= "<div class='d-flex align-items-center'><img src='{$logoUrl}' style='height:50px;' class='me-3'><span class='brand-text'>PSU PORTAL</span></div>";
$output .= "<div class='d-flex align-items-center'>";
$output .= "<ul class='navbar-nav flex-row align-items-center' style='gap: 30px;'>";
$output .= "<li class='nav-item'><a class='nav-link' href='" . url('/') . "' style='color: white; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none;'>HOME</a></li>";
$output .= "<li class='nav-item dropdown'>";
$output .= "<a class='nav-link dropdown-toggle' href='#' id='actDrop' data-bs-toggle='dropdown' style='color: white; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none;'>ACTIVITIES</a>";
$output .= "<ul class='dropdown-menu shadow' style='border-top: 4px solid $brandGold; border-radius: 0; margin-top: 15px;'>";
$output .= "<li><a class='dropdown-item' href='" . url('/syllabus') . "'>Syllabus</a></li>";
$output .= "<li><a class='dropdown-item' href='" . url('/PsuStrategicGoals') . "'>Strategic Goals</a></li>";
$output .= "<li><a class='dropdown-item' href='" . url('/mission') . "'>Mission & Vision</a></li>";
$output .= "<li><a class='dropdown-item' href='" . url('/schedule') . "'>Schedule</a></li>";
$output .= "<li><a class='dropdown-item' href='" . url('/calendar') . "'>University Calendar</a></li>";
$output .= "<li><hr class='dropdown-divider'></li>";
$output .= "<li><a class='dropdown-item' href='http://localhost/Herd/elective1-act1/elctive1-act2/laravel/public' target='_blank'>Activity 2 - Pharmacy System</a></li>";
$output .= "</ul>";
$output .= "</li>";
$output .= "<li class='nav-item dropdown'>";
$output .= "<a class='nav-link dropdown-toggle' href='#' id='projectDrop' data-bs-toggle='dropdown' style='color: white; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none;'>PROJECTS</a>";
$output .= "<ul class='dropdown-menu shadow' style='border-top: 4px solid $brandGold; border-radius: 0; margin-top: 15px;'>";
$output .= "<li><a class='dropdown-item' href='#'>Project 1</a></li>";
$output .= "<li><a class='dropdown-item' href='#'>Project 2</a></li>";

$output .= "</ul>";
$output .= "</li>";
$output .= "</ul>";
$output .= "</div>";
$output .= "</div></nav>";

// --- Hero Section ---
$output .= "<section class='hero-section'><div class='container'>";
$output .= "<h1 class='fw-black' style='font-size: 3rem;'>" . ($hero_welcome ?? 'Course Syllabus') . "</h1>";
$output .= "<p style='color: $brandGold; font-weight: 700;'>" . ($university_name ?? 'Pangasinan State University') . "</p>";
$output .= "<p style='font-size: 12px; opacity: 0.8;'>" . ($motto ?? '') . "</p>";
$output .= "</div></section>";

// --- Table Content ---
$output .= "<main class='container mb-5'><div class='syllabus-card'><div class='table-responsive'>";
$output .= "<table class='table table-hover align-middle'>";
$output .= "<thead><tr><th>GRADE</th><th>COMPLETION</th><th>CODE</th><th>DESCRIPTION</th><th>UNITS</th></tr></thead><tbody>";

if (isset($courses) && is_array($courses)) {
    foreach ($courses as $c) {
        // Highlighting Logic for "In Progress" or "No Grade"
        $isPending = ($c['grade'] === 'NG' || $c['grade'] === '---');
        $rowClass = $isPending ? "class='status-pending'" : "";
        $gradeClass = ($isPending) ? "grade-none" : "grade-passed";

        $output .= "<tr $rowClass>";
        $output .= "<td><span class='grade-badge $gradeClass'>{$c['grade']}</span></td>";
        $output .= "<td><small class='text-muted'>{$c['completion']}</small></td>";
        $output .= "<td class='course-code'>{$c['code']}</td>";
        $output .= "<td class='description-cell'>{$c['description']}</td>";
        $output .= "<td class='fw-bold'>{$c['units']}</td>";
        $output .= "</tr>";
    }
}

$output .= "</tbody></table></div></div></main>";

// --- Footer Section ---
$output .= "<footer style='background-color: $navBlue; color: white; padding: 30px 0; text-align: center; border-top: 5px solid $brandGold; margin-top: auto;'>";
$output .= "<p style='color: $brandGold; font-weight: 900; margin-bottom: 0;'>PANGASINAN STATE UNIVERSITY</p>";
$output .= "<p style='font-size: 10px; opacity: 0.6;'>Syllabus Management System &copy; " . date('Y') . "</p></footer>";

$output .= "</body></html>";

// 3. Final Execution
echo $output;
