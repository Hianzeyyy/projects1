<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSU Identity | Mission, Vision & Core Values</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body {
            font-family: 'Montserrat';
             background: linear-gradient(135deg, #62b1f6, #ffffff);
            min-height: 100vh;
            margin: 0;
        }

        /* --- Updated Navbar with Larger Font --- */
        .navbar {
            background-color: #003366 !important;
            padding: 1.2rem 0;
            border-bottom: none;
        }

        .navbar-brand {
            font-size: 2rem; /* Increased size */
            color: white !important;
            font-weight: 800 !important;
            text-transform: uppercase;
        }

        .nav-link {
            font-weight: 700 !important;
            color: white !important;
            text-transform: uppercase;
            font-size: 1 rem !important; /* Increased from 0.65rem */
            padding: 0.5rem 1.2rem !important;
            letter-spacing: 0.8px;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color:  #ffcc00 !important;
        }

        /* --- Content Layout --- */
        .glass-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin: 3rem 0;
        }

        h2 {
            font-weight: 800;
            color: #0033cc;
            text-transform: uppercase;
            margin-bottom: 1rem;
            font-size:3rem;
            font-family: Helvetica;
            text-align: center;
        }

        p{
            font-size:2rem;
            font-family: Helvetica;

        }

        .lead-text {
            margin-bottom: 2.5rem;
            font-size: 1.2rem;
            line-height: 1.7;
            color: #333;
        }

         /* --- ACCESS Core Values Styling --- */
         .value-item {
             margin-bottom: 0.8rem;
             font-size: 1.3rem;
             color: #444;
        }

        .value-letter {
             font-weight: 800;
             color: #0033cc;
             font-size: 1.5rem;
             margin-right: 5px;
        }

             a:hover {
             color: #ffcc00 !important;
             font-weight:bold;
         }
        @media (max-width: 991.98px) {
            .nav-link { font-size: 0.75rem !important; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid px-5">
        <a class="navbar-brand" href="#">PSU PORTAL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#psuNavbar">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="psuNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="activitiesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Activities</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="activitiesDropdown">
                        <li><a class="dropdown-item" href="/PsuStrategicGoals">PSU Strategic Goal and Quality Policy Standard</a></li>
                        <li><a class="dropdown-item" href="mission">Mission, Vision and Core Values</a></li>
                        <li><a class="dropdown-item" href="syllabus">Course Syllabus</a></li>
                        <li><a class="dropdown-item" href="schedule">Class Schedule</a></li>
                        <li><a class="dropdown-item" href="calendar">University Calendar</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="glass-card shadow-sm">
                <section>
                    <h2>Vision</h2>
                    <p class="lead-text">To become a leading industry-driven State University in the ASEAN region by 2030.</p>
                </section>

                <section>
                    <h2>Mission</h2>
                    <p class="lead-text">The Pangasinan State University shall provide a human-centric, resilient, and sustainable academic environment to produce dynamic, responsive, and future-ready individuals capable of meeting the requirements of the local and global communities and industries.</p>
                </section>

                <section>
                    <h2>Core Values</h2>
                    <div class="mt-3">
                        <div class="value-item"><span class="value-letter">A</span>ccountability and Transparency</div>
                        <div class="value-item"><span class="value-letter">C</span>redibility and Integrity</div>
                        <div class="value-item"><span class="value-letter">C</span>ompetence and Commitment to Achieve</div>
                        <div class="value-item"><span class="value-letter">E</span>xcellence in Service Delivery</div>
                        <div class="value-item"><span class="value-letter">S</span>ocial and Environmental Responsiveness</div>
                        <div class="value-item"><span class="value-letter">S</span>pirituality</div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
