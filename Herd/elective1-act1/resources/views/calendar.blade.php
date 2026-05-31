<?php
echo "<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap' rel='stylesheet'>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "<style>
    body { font-family: 'Montserrat', sans-serif; background-color: #001a33; margin: 0; }
    .nav-link:hover { color: #ffcc00 !important; }
    .dropdown-item:hover { background-color: #002b5c; color: #ffcc00; }
    .calendar-card {
        background: white;
        padding: 40px;
        border-radius: 20px;
        border-bottom: 5px solid #ffcc00;
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
    }
    .table thead { background-color: #002b5c; color: #ffcc00; border-bottom: 3px solid #ffcc00; }
</style>";

// --- NAVBAR ---
echo "<nav class='navbar navbar-expand-lg sticky-top' style='background-color: #002b5c; padding: 25px 0;'>";
    echo "<div class='container d-flex justify-content-between align-items-center'>";
        echo "<a class='navbar-brand' href='".route('landingPage')."' style='color: #ffcc00; font-weight: 900; font-size: 28px; text-transform: uppercase; text-decoration: none;'>PSU PORTAL</a>";
        echo "<div class='d-flex align-items-center'>";
            echo "<ul class='navbar-nav flex-row align-items-center' style='gap: 35px;'>";
                echo "<li class='nav-item'><a class='nav-link' href='".route('landingPage')."' style='color: white; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none;'>HOME</a></li>";
                echo "<li class='nav-item dropdown'>";
                    echo "<a class='nav-link dropdown-toggle' href='#' id='actDrop' data-bs-toggle='dropdown' style='color: white; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none;'>ACTIVITIES</a>";
                    echo "<ul class='dropdown-menu shadow' style='border-top: 4px solid #ffcc00; border-radius: 0; margin-top: 15px;'>";
                        echo "<li><a class='dropdown-item' href='".route('syllabus')."'>Syllabus</a></li>";
                        echo "<li><a class='dropdown-item' href='".route('PsuStrategicGoals')."'>Strategic Goals</a></li>";
                        echo "<li><a class='dropdown-item' href='".route('mission')."'>Mission & Vision</a></li>";
                        echo "<li><a class='dropdown-item' href='".route('schedule')."'>Schedule</a></li>";
                        echo "<li><a class='dropdown-item' href='".route('calendar')."'>University Calendar</a></li>";
                        echo "<li><hr class='dropdown-divider'></li>";
                        echo "<li><a class='dropdown-item' href='http://localhost/Herd/elective1-act1/elctive1-act2/laravel/public' target='_blank'>Activity 2 - Pharmacy System</a></li>";
                    echo "</ul>";
                echo "</li>";
                echo "<li class='nav-item dropdown'>";
                    echo "<a class='nav-link dropdown-toggle' href='#' id='projectDrop' data-bs-toggle='dropdown' style='color: white; font-weight: 700; text-transform: uppercase; font-size: 14px; text-decoration: none;'>PROJECTS</a>";
                    echo "<ul class='dropdown-menu shadow' style='border-top: 4px solid #ffcc00; border-radius: 0; margin-top: 15px;'>";
                        echo "<li><a class='dropdown-item' href='#'>Project 1</a></li>";
                        echo "<li><a class='dropdown-item' href='#'>Project 2</a></li>";

                    echo "</ul>";
                echo "</li>";
            echo "</ul>";
        echo "</div>";
    echo "</div>";
echo "</nav>";

echo "<div style='background-color: #ffcc00; height: 5px; width: 100%;'></div>";

// --- HERO ---
echo "<header style='{$design['hero']} padding: 80px 0;'>";
    echo "<div class='container text-center text-white'>";
        echo "<h1 style='color: #ffcc00; font-weight: 900; font-size: 4rem; text-transform: uppercase;'>$hero_welcome</h1>";
        echo "<p style='font-size: 1.5rem; opacity: 0.9;'>$university_name</p>";
    echo "</div>";
echo "</header>";

// --- MAIN CONTENT ---
echo "<main class='container my-5'>";
    echo "<div class='calendar-card'>";
        echo "<div class='table-responsive'>";
            echo "<table class='table table-hover align-middle table-bordered'>";
                echo "<thead>";
                    echo "<tr class='text-center'>";
                        echo "<th style='padding: 15px;'>Scheduled Date</th><th>Institutional Event</th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach($events as $event) {
                    echo "<tr>";
                        echo "<td class='text-center fw-bold' style='color: #002b5c; width: 30%;'>{$event['date']}</td>";
                        echo "<td class='ps-4 fw-bold text-uppercase'>{$event['event']}</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
        echo "<div class='text-center mt-4'>";
        echo "</div>";
    echo "</div>";
echo "</main>";

// --- FOOTER ---
echo "<footer style='background-color: #000d1a; color: white; padding: 50px 0; text-align: center; border-top: 5px solid #ffcc00;'>";
    echo "<p style='color: #ffcc00; font-weight: 900; font-size: 1.2rem;'>$university_name</p>";
    echo "<p style='font-size: 11px; opacity: 0.5;'>&copy; " . date('Y') . " | PSU Portal | Project by Obrero</p>";
echo "</footer>";

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
?>
