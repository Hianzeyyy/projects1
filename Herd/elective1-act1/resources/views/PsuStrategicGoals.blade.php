<?php
/**
 * PSU Strategic Goals & Quality Policy
 * STRICT PURE PHP RENDER - No raw HTML tags.
 */

// 1. Configuration & Global Assets
$logoUrl     = "https://main.psu.edu.ph/wp-content/uploads/2022/07/PSU-LABEL-LOGO.png";
$brandGold   = "#ffcc00"; // Precise Gold from Portal Branding
$deepBlue    = "#001a33"; // Main Background
$navBlue     = "#002b5c"; // Navbar Background

// 2. Data Arrays
$psu_goals = [
    "SG 1" => "Excellent Student Learning and Development",
    "SG 2" => "Strong Research Culture and Technology Transfer",
    "SG 3" => "Sustainable Extension and Community Engagement",
    "SG 4" => "Efficient and Effective Management of Resources",
    "SG 5" => "Intensified Internationalization and Partnerships"
];

// 3. String Construction (Page Assembly)
$psuPortal = "<html><head>";
$psuPortal .= "<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap' rel='stylesheet'>";
$psuPortal .= "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
$psuPortal .= "<style>
    body { font-family: 'Montserrat', sans-serif; background-color: $deepBlue; color: white; margin: 0; min-height: 100vh; display: flex; flex-direction: column; }

    /* Navbar Styling */
    .psu-logo { height: 50px; width: auto; margin-right: 15px; }
    .brand-text { color: $brandGold; font-weight: 900; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
    .nav-link { color: white !important; font-weight: 700; font-size: 14px; text-transform: uppercase; }
    .nav-link:hover { color: $brandGold !important; }

    /* Section Headers */
    .section-title { color: $brandGold; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 30px; border-bottom: 2px solid rgba(255,204,0,0.3); display: inline-block; padding-bottom: 10px; }

    /* Policy Box */
    .policy-box {
        background: white;
        color: #002b5c;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        border-left: 12px solid $brandGold;
    }

    /* Same-Height Grid Logic */
    .goal-card {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,204,0,0.2);
        padding: 25px;
        border-radius: 15px;
        transition: 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .goal-card:hover { background: rgba(255,204,0,0.1); transform: translateY(-5px); border-color: $brandGold; }
    .goal-number { color: $brandGold; font-weight: 900; font-size: 1.5rem; margin-bottom: 10px; display: block; }
</style></head><body>";

// --- NAVBAR ASSEMBLY ---
$psuPortal .= "<nav class='navbar navbar-expand-lg sticky-top' style='background-color: $navBlue; padding: 20px 0;'>";
$psuPortal .= "<div class='container d-flex justify-content-between align-items-center'>";
$psuPortal .= "<a class='navbar-brand d-flex align-items-center' href='".route('landingPage')."' style='text-decoration: none;'>";
$psuPortal .= "<img src='{$logoUrl}' alt='PSU Logo' class='psu-logo'>";
$psuPortal .= "<span class='brand-text'>PSU PORTAL</span></a>";
$psuPortal .= "<div class='navbar-nav flex-row align-items-center' style='gap: 40px;'>";
$psuPortal .= "<a class='nav-link' href='".route('landingPage')."'>HOME</a>";
$psuPortal .= "<div class='nav-item dropdown'>";
$psuPortal .= "<a class='nav-link dropdown-toggle' href='#' id='actDrop' data-bs-toggle='dropdown' aria-expanded='false'>ACTIVITIES</a>";
$psuPortal .= "<ul class='dropdown-menu dropdown-menu-end shadow' style='border-top: 4px solid $brandGold; border-radius: 0;'>";
$psuPortal .= "<li><a class='dropdown-item' href='".route('syllabus')."'>Syllabus</a></li>";
$psuPortal .= "<li><a class='dropdown-item' href='".route('PsuStrategicGoals')."'>Strategic Goals</a></li>";
$psuPortal .= "<li><a class='dropdown-item' href='".route('mission')."'>Mission & Vision</a></li>";
$psuPortal .= "<li><a class='dropdown-item' href='".route('schedule')."'>Schedule</a></li>";
$psuPortal .= "<li><a class='dropdown-item' href='".route('calendar')."'>University Calendar</a></li>";
$psuPortal .= "<li><hr class='dropdown-divider'></li>";
$psuPortal .= "<li><a class='dropdown-item' href='http://localhost/Herd/elective1-act1/elctive1-act2/laravel/public' target='_blank'>Activity 2 - Pharmacy System</a></li>";
$psuPortal .= "</ul></div>";
$psuPortal .= "<div class='nav-item dropdown'>";
$psuPortal .= "<a class='nav-link dropdown-toggle' href='#' id='projectDrop' data-bs-toggle='dropdown' aria-expanded='false'>PROJECTS</a>";
$psuPortal .= "<ul class='dropdown-menu dropdown-menu-end shadow' style='border-top: 4px solid $brandGold; border-radius: 0;'>";
$psuPortal .= "<li><a class='dropdown-item' href='#'>Project 1</a></li>";
$psuPortal .= "<li><a class='dropdown-item' href='#'>Project 2</a></li>";
$psuPortal .= "<li><a class='dropdown-item' href='#'>Project 3</a></li>";
$psuPortal .= "</ul></div></div></div></nav>";

// Brand Divider
$psuPortal .= "<div style='background-color: $brandGold; height: 5px; width: 100%;'></div>";

// --- MAIN CONTENT ---
$psuPortal .= "<main class='container my-5'>";
$psuPortal .= "<section class='mb-5'>";
$psuPortal .= "<h2 class='section-title'>Quality Policy Standard</h2>";
$psuPortal .= "<div class='policy-box'>";
$psuPortal .= "<h4 class='fw-bold mb-3'>Pangasinan State University Commitment:</h4>";
$psuPortal .= "<p class='lead' style='line-height: 1.8;'>The Pangasinan State University shall be a <strong>Region's Premier University of Choice</strong> providing quality education and satisfactory service to its customers through continuous improvement of the quality management system.</p>";
$psuPortal .= "<p class='mt-4 mb-0 text-muted font-monospace' style='font-size: 0.85rem;'>ISO 9001:2015 Certified | Quality Management System</p>";
$psuPortal .= "</div></section>";

// --- STRATEGIC GOALS GRID ---
$psuPortal .= "<section class='mt-5'>";
$psuPortal .= "<h2 class='section-title'>Strategic Goals (2025-2028)</h2>";
$psuPortal .= "<div class='row g-4 d-flex align-items-stretch'>";
foreach($psu_goals as $num => $goal) {
    $psuPortal .= "<div class='col-md-4'><div class='goal-card'>";
    $psuPortal .= "<span class='goal-number'>$num</span>";
    $psuPortal .= "<h5 class='fw-bold text-white'>$goal</h5>";
    $psuPortal .= "<p class='small opacity-50 mt-auto'>Aligned with the PSU mission to provide top-tier academic excellence and innovation.</p>";
    $psuPortal .= "</div></div>";
}
$psuPortal .= "</div></section></main>";

// --- FOOTER ---
$psuPortal .= "<footer style='background-color: #000d1a; color: white; padding: 40px 0; text-align: center; border-top: 5px solid $brandGold; margin-top: auto;'>";
$psuPortal .= "<p style='color: $brandGold; font-weight: 900; margin-bottom: 5px;'>PANGASINAN STATE UNIVERSITY</p>";
$psuPortal .= "<p class='small opacity-50'>&copy; " . date('Y') . " | Quality Assurance Office | PSU Portal</p></footer>";

$psuPortal .= "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script></body></html>";

// 4. Final Render
echo $psuPortal;
?>
