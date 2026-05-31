<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSU Strategic Goals & Quality Policy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #62b1f6, #ffffff); min-height: 100vh; font-family: Helvetica, Arial; font-size:1.5rem; margin: 0; }
        .custom-navbar { background: #003366; backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border-bottom: 1rem solid rgba(0, 0, 0, 0.05); padding: 2rem 0; font-size:3rem; color:white; }
        .navbar-brand { font-size: 2rem; color: white !important; letter-spacing: 1px; font-weight: bold !important; }
        .nav-link { font-weight: 600; color: white !important; text-transform: uppercase; font-size:1rem !important; white-space: nowrap; padding: 0.5rem 0.8rem !important; transition: all 0.3s ease; }
        .nav-link:hover, a:hover { color: #ffcc00 !important; font-weight:bold; background: rgba(0, 86, 179, 0.05); border-radius: 5px; }
        .glass-card { background: rgba(253, 251, 255, 0.7); backdrop-filter: blur(2rem); border-radius: 3rem; padding: 3rem; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); margin: 2rem 0 4rem; }
        .psu-blue { color: #003366; }
        .goal-number { font-weight: bold; color: #0056b3; margin-right: 10px; }
        @media (max-width: 1200px) { .nav-link { font-size: 0.62rem !important; padding: 0.5rem 0.4rem !important; } }
        @media (max-width: 991.98px) { .navbar-collapse { background: white; border-radius: 15px; padding: 20px; margin-top: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); } .nav-link { white-space: normal; font-size: 0.85rem !important; border-bottom: 1px solid #f0f0f0; color: #333 !important; } }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top custom-navbar">
    <div class="container-fluid px-lg-5">
        <a class="navbar-brand fw-bold" href="#">PSU PORTAL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#psuNavbar"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="psuNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="activitiesDropdown" data-bs-toggle="dropdown">Activities</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/PsuStrategicGoals">PSU Strategic Goal</a></li>
                        <li><a class="dropdown-item" href="mission">Mission & Vision</a></li>
                        <li><a class="dropdown-item" href="syllabus">Course Syllabus</a></li>
                        <li><a class="dropdown-item" href="schedule">Class Schedule</a></li>
                        <li><a class="dropdown-item" href="calendar">University Calendar</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container"><div class="row justify-content-center"><div class="col-lg-11"><div class="glass-card">
    <section class="mb-5">
        <h2 class="text-center psu-blue fw-bold mb-4">PSU Strategic Goals</h2>
        <div class="ps-md-4">
            <p><span class="goal-number">SG 1:</span> Industry-Focused and Innovation-Based Student Learning</p>
            <p><span class="goal-number">SG 2:</span> Responsive and Sustainable Research and Extension</p>
            <p><span class="goal-number">SG 3:</span> Effective Governance and Financial Management</p>
            <p><span class="goal-number">SG 4:</span> High-Performing and Engaged Human Resource</p>
            <p><span class="goal-number">SG 5:</span> Strategic Internationalization Program</p>
        </div>
    </section>
    <hr class="my-5 opacity-25">
    <section>
        <h2 class="text-center psu-blue fw-bold mb-4">PSU Quality Policy Standard</h2>
        <div class="policy-text px-md-4 text-center">
            <p>The Pangasinan State University shall be recognized as an ASEAN premier state university through instruction, research, extension, and production.</p>
            <p>We commit to produce professionals who meet industry expectations in national and international communities.</p>
            <p>We shall continuously improve our operations in support of the institution’s strategic direction.</p>
        </div>
    </section>
</div></div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
