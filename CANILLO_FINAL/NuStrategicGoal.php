<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Strategic Goals | NU Portal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { 
            font-family: 'Montserrat', sans-serif; 
            background: #001a33; /* Matching Index Background */
            color: #333;
            min-height: 100vh;
        }
        
        /* --- Navbar --- */
        .custom-navbar { 
            background: #003366 !important; 
            padding: 1.2rem 0; 
            border-bottom: 3px solid #FFCC00; 
        }
        .navbar-brand { font-size: 2rem; color: #FFCC00 !important; font-weight: 800; }
        .nav-link { font-weight: 700; color: white !important; text-transform: uppercase; }
        .dropdown-menu { background: #003366; border: 1px solid #FFCC00; }
        .dropdown-item { color: white !important; }
        .dropdown-item:hover { background: #FFCC00; color: #003366 !important; }

        /* --- Hero Section --- */
        .section-header { padding: 4rem 0 2rem; text-align: center; color: white; }
        .section-title { font-size: 3rem; font-weight: 800; color: #FFCC00; text-transform: uppercase; }

        /* --- Goal Cards (Matching Index Style) --- */
        .goal-card { 
            background: white; 
            border-radius: 25px; 
            padding: 2.5rem; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3); 
            transition: 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            text-align: center;
        }
        .goal-card:hover { transform: translateY(-10px); }
        
        .goal-number { 
            font-size: 3rem; 
            font-weight: 900; 
            color: #003366; 
            opacity: 0.1; 
            margin-bottom: -2rem;
        }

        .goal-card h3 { 
            font-weight: 800; 
            color: #003366; 
            margin-top: 1rem;
            font-size: 1.4rem;
        }
        
        .goal-card p { 
            color: #555; 
            font-size: 0.95rem; 
            line-height: 1.6;
            margin-top: 1rem;
        }

        .goal-footer {
            margin-top: auto;
            padding-top: 1.5rem;
            font-weight: 700;
            color: #003366;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        footer { background: #000c1a; color: white; padding: 2rem 0; border-top: 3px solid #FFCC00; text-align: center; margin-top: 5rem; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg custom-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">NU PORTAL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nuNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="nuNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="actDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">Activities</a>
                        <ul class="dropdown-menu shadow">
                            <li><a class="dropdown-item" href="syllabus.php">Syllabus</a></li>
                            <li><a class="dropdown-item" href="Schedule.php">Strategic Goals</a></li>
                            <li><a class="dropdown-item" href="mission.php">Mission & Vision</a></li>
                            <li><a class="dropdown-item" href="schedule.php">Class Schedule</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="section-header">
        <div class="container">
            <h1 class="section-title">Strategic Goals</h1>
            <p class="lead">Building a brighter future through Education and Technology</p>
        </div>
    </header>

    <main class="container">
        <div class="row g-4">
            
            <div class="col-md-6 col-lg-3">
                <div class="goal-card">
                    <div class="goal-number">01</div>
                    <h3>Academic Excellence</h3>
                    <p>Providing high-quality education that meets international standards for the global workforce.</p>
                    <div class="goal-footer">Quality Education</div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="goal-card">
                    <div class="goal-number">02</div>
                    <h3>Industry Partnership</h3>
                    <p>Strengthening ties with leaders to ensure curriculum relevance and practical experience.</p>
                    <div class="goal-footer">Career Ready</div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="goal-card">
                    <div class="goal-number">03</div>
                    <h3>Innovation & Research</h3>
                    <p>Fostering a culture of innovation among students and faculty in Information Technology.</p>
                    <div class="goal-footer">Tech Driven</div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="goal-card">
                    <div class="goal-number">04</div>
                    <h3>Community Engagement</h3>
                    <p>Utilizing technology to serve the community and address local societal challenges.</p>
                    <div class="goal-footer">Social Impact</div>
                </div>
            </div>

        </div>
    </main>

    <footer>
        <div class="container">
            <p class="mb-0">&copy; 2026 NU Student Portal | Strategic Planning</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>