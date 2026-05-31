<?php


// 1. Data Setup
$accentColor = "#ffcc00";
$navBg = "background-color: #002b5c;";
$heroBg = "background-color: #001a33;";
$logoUrl = "https://main.psu.edu.ph/wp-content/uploads/2022/07/PSU-LABEL-LOGO.png";
$cardStyle = "background: white; border-radius: 15px; border-bottom: 8px solid #ffcc00;";

$psuPortal = "";

// 2. Head and CSS
$psuPortal .= "<html><head>";
$psuPortal .= "<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap' rel='stylesheet'>";
$psuPortal .= "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
$psuPortal .= "<style>
    body { font-family: 'Montserrat', sans-serif; background-color: #001a33; margin: 0; color: white; min-height: 100vh; display: flex; flex-direction: column; }

    /* Navbar Styling */
    .psu-logo { height: 50px; width: auto; margin-right: 15px; }
    .brand-text { color: $accentColor; font-weight: 900; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; transition: 0.2s; }
    .brand-text:hover { opacity: 0.8; }
    .nav-link { color: white !important; font-weight: 700; font-size: 14px; text-transform: uppercase; }
    .nav-link:hover { color: $accentColor !important; }

    /* Equal height cards fix */
    .mission-vision-card {
        $cardStyle
        padding: 40px 30px;
        text-align: center;
        color: #002b5c;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        height: 100%;
    }

    /* Core Values */
    .value-box {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 204, 0, 0.2);
        border-radius: 15px;
        padding: 15px;
        transition: 0.3s;
        height: 100%;
    }
    .value-box:hover { background: $accentColor; color: #002b5c !important; transform: translateY(-5px); }
    .value-letter { font-size: 2rem; font-weight: 900; color: $accentColor; display: block; }
    .value-box:hover .value-letter { color: #002b5c; }
    .value-text { font-size: 11px; font-weight: 700; text-transform: uppercase; margin-top: 5px; }
</style></head><body>";

// 3. Navbar (Logo + Clickable PSU PORTAL on Left)
$psuPortal .= "
<nav class='navbar navbar-expand-lg sticky-top' style='$navBg padding: 20px 0;'>
    <div class='container d-flex justify-content-between align-items-center'>
        <a class='navbar-brand d-flex align-items-center' href='".route('landingPage')."' style='text-decoration: none;'>
            <img src='{$logoUrl}' alt='PSU Logo' class='psu-logo'>
            <span class='brand-text'>PSU PORTAL</span>
        </a>
        <div class='navbar-nav flex-row align-items-center' style='gap: 40px;'>
            <a class='nav-link' href='".route('landingPage')."'>HOME</a>
            <div class='nav-item dropdown'>
                <a class='nav-link dropdown-toggle' href='#' id='actDrop' data-bs-toggle='dropdown' aria-expanded='false'>ACTIVITIES</a>
                <ul class='dropdown-menu dropdown-menu-end shadow' aria-labelledby='actDrop' style='border-top: 4px solid $accentColor; border-radius: 0;'>
                    <li><a class='dropdown-item' href='".route('syllabus')."'>Syllabus</a></li>
                    <li><a class='dropdown-item' href='".route('PsuStrategicGoals')."'>Strategic Goals</a></li>
                    <li><a class='dropdown-item' href='".route('mission')."'>Mission & Vision</a></li>
                    <li><a class='dropdown-item' href='".route('schedule')."'>Schedule</a></li>
                    <li><a class='dropdown-item' href='".route('calendar')."'>University Calendar</a></li>
                    <li><hr class='dropdown-divider'></li>
                    <li><a class='dropdown-item' href='http://localhost/Herd/elective1-act1/elctive1-act2/laravel/public' target='_blank'>Activity 2 - Pharmacy System</a></li>
                </ul>
            </div>
            <div class='nav-item dropdown'>
                <a class='nav-link dropdown-toggle' href='#' id='projectDrop' data-bs-toggle='dropdown' aria-expanded='false'>PROJECTS</a>
                <ul class='dropdown-menu dropdown-menu-end shadow' aria-labelledby='projectDrop' style='border-top: 4px solid $accentColor; border-radius: 0;'>
                    <li><a class='dropdown-item' href='#'>Project 1</a></li>
                    <li><a class='dropdown-item' href='#'>Project 2</a></li>
                    <li><a class='dropdown-item' href='#'>Project 3</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div style='background-color: $accentColor; height: 5px; width: 100%;'></div>";

// 4. Hero Section
$psuPortal .= "
<header style='$heroBg padding: 60px 0; text-align: center;'>
    <div class='container'>
        <h1 style='color: white; font-weight: 900; font-size: 3rem; text-transform: uppercase;'>$hero_welcome</h1>
        <p style='font-size: 1.1rem; opacity: 0.8; font-style: italic;'>$university_name</p>
    </div>
</header>";

// 5. Mission & Vision (Same Height Alignment)
$psuPortal .= "
<main class='container my-5'>
    <div class='row g-4 mb-5 d-flex align-items-stretch'>
        <div class='col-lg-6'>
            <div class='mission-vision-card'>
                <span style='background: $accentColor; padding: 5px 20px; border-radius: 50px; font-weight: 900; font-size: 12px;'>VISION</span>
                <p class='mt-4 fw-bold' style='font-size: 1.4rem; line-height: 1.4;'>$vision</p>
            </div>
        </div>
        <div class='col-lg-6'>
            <div class='mission-vision-card'>
                <span style='background: $accentColor; padding: 5px 20px; border-radius: 50px; font-weight: 900; font-size: 12px;'>MISSION</span>
                <p class='mt-4' style='font-size: 1rem; line-height: 1.8; text-align: justify;'>$mission</p>
            </div>
        </div>
    </div>";

// 6. Core Values
$psuPortal .= "
    <div class='text-center mb-4'><h2 style='color: $accentColor; font-weight: 900; font-size: 1.7rem;'>CORE VALUES</h2></div>
    <div class='row g-2 justify-content-center'>";
    foreach($core_values as $val) {
        $psuPortal .= "
        <div class='col-6 col-md-4 col-lg-2'>
            <div class='value-box text-center'>
                <span class='value-letter'>{$val['letter']}</span>
                <p class='value-text'>{$val['text']}</p>
            </div>
        </div>";
    }
$psuPortal .= "</div></main>";

// 7. Footer
$psuPortal .= "
<footer style='background-color: #000d1a; color: white; padding: 40px 0; text-align: center; border-top: 4px solid $accentColor; margin-top: auto;'>
    <p style='color: $accentColor; font-weight: 900; margin-bottom: 5px;'>$university_name</p>
    <p style='font-size: 14px; opacity: 0.5;'>&copy; " . date('Y') . " | PSU Portal. All Rights Reserved.</p>
</footer>";

$psuPortal .= "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script></body></html>";

echo $psuPortal;
?>
