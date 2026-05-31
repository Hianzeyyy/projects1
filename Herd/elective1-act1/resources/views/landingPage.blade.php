@php

    // 1. Setup Variables
    $logoUrl = 'https://main.psu.edu.ph/wp-content/uploads/2022/07/PSU-LABEL-LOGO.png';
    $psuPortalHtml = '';

    // 2. Build Global CSS
    $psuPortalHtml .=
        "<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap' rel='stylesheet'>";
    $psuPortalHtml .=
        "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
    $psuPortalHtml .= "<style>
    body { font-family: 'Montserrat', sans-serif; background-color: #001a33; margin: 0; }
    .psu-logo { height: 60px; width: auto; margin-right: -30px; margin-left:-8rem;}
    .brand-text { color: #ffcc00; font-weight: 900; font-size: 26px; text-transform: uppercase; text-decoration: none; letter-spacing: 1px; }
    .nav-link:hover { color: #ffcc00 !important; }
    .resource-card {
        background: white; padding: 40px; text-align: center; border-radius: 20px;
        height: 100%; transition: 0.3s; position: relative; border: 3px solid transparent;
    }
    .resource-card:hover { transform: translateY(-10px); border-color: #ffcc00; box-shadow: 0 15px 35px rgba(0,0,0,0.4); }
    .stretched-link::after { position: absolute; top: 0; right: 0; bottom: 0; left: 0; z-index: 1; content: ''; }
</style>";

    // 3. Navigation Items Logic (Array to String)
    $navLinks = [
        ['text' => 'HOME', 'url' => route('landingPage')],
        ['text' => 'ACTIVITIES', 'url' => '#', 'isDropdown' => true],
    ];

    $dropdownItems =
        "
    <li><a class='dropdown-item' href='" .
        route('syllabus') .
        "'>Syllabus</a></li>
    <li><a class='dropdown-item' href='" .
        route('PsuStrategicGoals') .
        "'>Strategic Goals</a></li>
    <li><a class='dropdown-item' href='" .
        route('mission') .
        "'>Mission & Vision</a></li>
    <li><a class='dropdown-item' href='" .
        route('schedule') .
        "'>Schedule</a></li>
    <li><a class='dropdown-item' href='" .
        route('calendar') .
        "'>University Calendar</a></li>
    <li><hr class='dropdown-divider'></li>
    <li><a class='dropdown-item' href='http://localhost/Herd/elective1-act1/pharmacy-system/public/login' target='_blank'>Activity 2 - Pharmacy System</a></li>";

    // 4. Construct Navbar with Logo + Text Brand
    $psuPortalHtml .=
        "
<nav class='navbar navbar-expand-lg sticky-top' style='background-color: #002b5c; padding: 15px 0;'>
    <div class='container d-flex justify-content-between align-items-center'>
        <a class='navbar-brand d-flex align-items-center' href='" .
        route('landingPage') .
        "' style='text-decoration: none;'>
            <img src='{$logoUrl}' alt='PSU Logo' class='psu-logo'>
            <span class='brand-text'>PSU PORTAL</span>
        </a>
        <div class='d-flex align-items-center'>
            <ul class='navbar-nav flex-row align-items-center' style='gap: 50px;'>
                <li class='nav-item'><a class='nav-link' href='" .
        route('landingPage') .
        "' style='color: white; font-weight: 700; font-size: 14px;'>HOME</a></li>
                <li class='nav-item dropdown'>
                    <a class='nav-link dropdown-toggle' href='#' id='actDrop' data-bs-toggle='dropdown' style='color: white; font-weight: 700; font-size: 14px;'>ACTIVITIES</a>
                    <ul class='dropdown-menu shadow' style='border-top: 4px solid #ffcc00; border-radius: 0; margin-top: 15px;'>
                        {$dropdownItems}
                    </ul>
                </li>
                <li class='nav-item dropdown'>
                    <a class='nav-link dropdown-toggle' href='#' id='projectDrop' data-bs-toggle='dropdown' style='color: white; font-weight: 700; font-size: 14px;'>PROJECTS</a>
                    <ul class='dropdown-menu shadow' style='border-top: 4px solid #ffcc00; border-radius: 0; margin-top: 15px;'>
                        <li><a class='dropdown-item' href='#'>Project 1</a></li>
                        <li><a class='dropdown-item' href='#'>Project 2</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div style='background-color: #ffcc00; height: 5px; width: 100%;'></div>";

    // 5. Hero Section Construction
    $psuPortalHtml .= "
<header style='{$design['hero']} padding: 80px 0;'>
    <div class='container text-center text-white'>
        <h1 style='color: #ffcc00; font-weight: 900; font-size: 4rem; text-transform: uppercase;'>{$hero_welcome}</h1>
        <p style='font-size: 1.5rem; opacity: 0.9;'>{$university_name}</p>
        <p class='opacity-50' style='font-style: italic;'>\"{$motto}\"</p>
    </div>
</header>";

    // 6. Resource Grid Logic (Loop)
    $gridHtml = '';
    foreach ($resources as $res) {
        $url = $res['link'] ?? '#';
        $gridHtml .= "
    <div class='col-md-4'>
        <div class='resource-card'>
            <div style='font-size: 3.5rem; margin-bottom: 20px;'>{$res['icon']}</div>
            <h3 style='color: #002b5c; font-weight: 800; text-transform: uppercase;'>{$res['title']}</h3>
            <p style='color: #666; font-size: 0.95rem;'>{$res['desc']}</p>
            <a href='{$url}' class='stretched-link' style='color: #ffcc00; font-weight: 700; text-decoration: none;'>View Details</a>
        </div>
    </div>";
    }

    $psuPortalHtml .= "
<main class='container my-5'>
    <div class='text-center mb-5'>
        <h2 style='color: white; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;'>STUDENT RESOURCES</h2>
    </div>
    <div class='row g-4 justify-content-center'>{$gridHtml}</div>
</main>";

    // 7. Footer & Scripts
    $psuPortalHtml .=
        "
<footer style='background-color: #000d1a; color: white; padding: 50px 0; text-align: center; border-top: 5px solid #ffcc00;'>
    <p style='color: #ffcc00; font-weight: 900; font-size: 1.2rem;'>{$university_name}</p>
    <p style='font-size: 11px; opacity: 0.5;'>&copy; " .
        date('Y') .
        " | PSU Portal | Activity </p>
</footer>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";

@endphp

{{-- Output the final string --}}
{!! $psuPortalHtml !!}
