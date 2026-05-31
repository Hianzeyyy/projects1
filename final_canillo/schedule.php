<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSU Class Schedule</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --psu-blue: #003366;
            --psu-gold: #ffcc00;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #62b1f6, #ffffff);
            min-height: 100vh;
            margin: 0;
        }

        /* --- Navbar Style (Matches Mission Page) --- */
        .navbar {
            background: var(--psu-blue) !important;
            border-bottom: 0.5rem solid rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 2rem;
            color: white !important;
            font-weight: 800 !important;
            text-transform: uppercase;
        }

        .nav-link {
            font-weight: 700 !important;
            color: white !important;
            text-transform: uppercase;
            font-size: 0.75rem !important;
            padding: 0.5rem 1rem !important;
        }

        .nav-link:hover {
            color: var(--psu-gold) !important;
        }

        /* --- Glass Card Layout --- */
        .glass-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin-top: 3rem;
            margin-bottom: 3rem;
        }

        .schedule-header {
            color: #2615ad;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }

        /* --- Table Styling --- */
        .table thead {
            background-color: #e8f5e9;
            color: #1b5e20;
        }

        .room-badge {
            background-color: #6c757d;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-5">
            <a class="navbar-brand" href="#">PSU PORTAL</a>
            <div class="collapse navbar-collapse" id="psuNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="activitiesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Activities</a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="activitiesDropdown">
                            <li><a class="dropdown-item" href="/PsuStrategicGoals">PSU Strategic Goal and Quality Policy
                                    Standard</a></li>
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
                <div class="glass-card">
                    <h2 class="schedule-header">BSIT Class Schedule</h2>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr class="text-center">
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Course Title</th>
                                    <th>Room</th>
                                    <th>Instructor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($days as $item)
                                    <tr>
                                        <td class="fw-bold">{{ $item['day'] }}</td>
                                        <td class="text-center">{{ $item['time'] }}</td>
                                        <td>{{ $item['subject'] }}</td>
                                        <td class="text-center">
                                            <span class="room-badge">{{ $item['room'] }}</span>
                                        </td>
                                        <td>{{ $item['instructor'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
