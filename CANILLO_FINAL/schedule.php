<?php 
// include 'db_connect.php'; 

// Data array (Instructor names removed)
$schedule_data = [
    ['day' => 'Monday', 'time' => '09:00 AM - 12:00 PM', 'subject' => 'Capstone Project 1', 'room' => 'RM6'],
    ['day' => 'Monday', 'time' => '02:00 PM - 05:00 PM', 'subject' => 'Integrative Programming and Tech', 'room' => 'RM15'],
    ['day' => 'Tuesday', 'time' => '08:00 AM - 10:00 AM', 'subject' => 'Information Assurance and Security 1', 'room' => 'RM17'],
    ['day' => 'Tuesday', 'time' => '08:00 AM - 10:00 AM', 'subject' => 'Technopreneurship', 'room' => 'RM15'],
    ['day' => 'Tuesday', 'time' => '10:00 AM - 12:00 PM', 'subject' => 'Mobile Application Development 2', 'room' => 'RM16'],
    ['day' => 'Tuesday', 'time' => '03:00 PM - 05:00 PM', 'subject' => 'Integrative Programming and Tech', 'room' => 'RM17'],
    ['day' => 'Wednesday', 'time' => '10:00 AM - 11:00 AM', 'subject' => 'Technopreneurship', 'room' => 'RM6'],
    ['day' => 'Wednesday', 'time' => '03:00 PM - 05:00 PM', 'subject' => 'Web Systems and Technologies 2', 'room' => 'RM6'],
    ['day' => 'Thursday', 'time' => '08:00 AM - 11:00 AM', 'subject' => 'Information Assurance and Security 1', 'room' => 'RM17'],
    ['day' => 'Thursday', 'time' => '01:00 PM - 04:00 PM', 'subject' => 'Web Systems and Technologies 2', 'room' => 'RM15'],
    ['day' => 'Friday', 'time' => '08:00 AM - 11:00 AM', 'subject' => 'Mobile Application Development 2', 'room' => 'RM6'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule | NU Portal</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background:#003366; 
            min-height: 100vh;
            color: white;
            padding-bottom: 50px;
        }

        /* --- Navbar --- */
        .custom-navbar { 
            background: #003366 !important; 
            padding: 1.2rem 0; 
            border-bottom: 3px solid #FFCC00; 
        }
        .navbar-brand { font-size: 2rem; color: #FFCC00 !important; font-weight: 800; text-decoration: none; }
        .nav-link { font-weight: 700; color: white !important; text-transform: uppercase; }
        .dropdown-menu { background: #003366; border: 1px solid #FFCC00; border-radius: 10px; }
        .dropdown-item { color: white !important; font-weight: 600; padding: 10px 20px; }
        .dropdown-item:hover { background: #FFCC00; color: #003366 !important; }

        .section-title {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 3rem 0;
            text-align: center;
            color: #FFCC00;
        }

        /* --- Filter Buttons --- */
        .filter-container { text-align: center; margin-bottom: 2rem; }
        .filter-btn {
            background: transparent;
            border: 2px solid #FFCC00;
            color: #FFCC00;
            padding: 8px 20px;
            margin: 5px;
            border-radius: 50px;
            font-weight: 700;
            transition: 0.3s;
        }
        .filter-btn.active, .filter-btn:hover {
            background: #FFCC00;
            color: #003366;
        }

        /* --- Schedule Cards --- */
        .schedule-card {
            background: white;
            border-radius: 25px;
            padding: 2.5rem;
            color: #333;
            height: 100%;
            transition: 0.4s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            border: none;
            position: relative;
        }
        
        .schedule-card:hover {
            transform: translateY(-10px);
            outline: 3px solid #FFCC00;
        }

        /* Highlight for current ongoing class */
        .active-now {
            border: 4px solid #28a745 !important;
            background: #f0fff0;
        }
        .active-label {
            position: absolute;
            top: 15px;
            right: 20px;
            background: #28a745;
            color: white;
            font-size: 0.7rem;
            padding: 2px 10px;
            border-radius: 10px;
            font-weight: 800;
        }

        .day-badge {
            display: inline-block;
            background: #f0f2f5;
            color: #003366;
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.75rem;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .course-title {
            font-weight: 800;
            color: #003366;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .info-row {
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 0.5rem;
        }

        .btn-room {
            background: #003366;
            color: #FFCC00;
            border-radius: 50px;
            font-weight: 700;
            padding: 0.7rem;
            margin-top: auto;
            text-align: center;
            width: 100%;
            text-transform: uppercase;
        }

        footer { background: #000c1a; color: white; padding: 2rem 0; border-top: 3px solid #FFCC00; text-align: center; margin-top: 3rem;}
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
                        <a class="nav-link dropdown-toggle" href="#" id="actDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Activities
                        </a>
                        <ul class="dropdown-menu shadow">
                            <li><a class="dropdown-item" href="syllabus.php">Resources</a></li>
                            <li><a class="dropdown-item" href="NuStrategicGoal.php">Strategic Goals</a></li>
                            <li><a class="dropdown-item" href="mission.php">Mission & Vision</a></li>
                            <li><a class="dropdown-item" href="schedule.php">Schedule</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="section-title">Class Schedule</h2>

        <div class="filter-container">
            <button class="filter-btn active" data-day="all">ALL</button>
            <button class="filter-btn" data-day="Monday">MON</button>
            <button class="filter-btn" data-day="Tuesday">TUE</button>
            <button class="filter-btn" data-day="Wednesday">WED</button>
            <button class="filter-btn" data-day="Thursday">THU</button>
            <button class="filter-btn" data-day="Friday">FRI</button>
        </div>

        <div class="row g-4" id="scheduleGrid">
            <?php foreach ($schedule_data as $row): ?>
            <div class="col-md-6 col-lg-4 schedule-item" data-day="<?php echo $row['day']; ?>">
                <div class="schedule-card shadow">
                    <div class="mb-3">
                        <span class="day-badge"><?php echo htmlspecialchars($row['day']); ?></span>
                        <div class="course-title"><?php echo htmlspecialchars($row['subject']); ?></div>
                        <div class="info-row">
                            <strong>🕒 Time:</strong> <span class="time-text"><?php echo htmlspecialchars($row['time']); ?></span>
                        </div>
                    </div>
                    <div class="btn-room">
                        Location: <?php echo htmlspecialchars($row['room']); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p class="mb-0">&copy; 2026 NU Student Portal | Class Schedule</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. DAY FILTER LOGIC ---
        const buttons = document.querySelectorAll('.filter-btn');
        const items = document.querySelectorAll('.schedule-item');

        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                // UI Toggle
                buttons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const selectedDay = this.getAttribute('data-day');

                items.forEach(item => {
                    if (selectedDay === 'all' || item.getAttribute('data-day') === selectedDay) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // --- 2. "ACTIVE CLASS" HIGHLIGHTER ---
        function checkCurrentClass() {
            const now = new Date();
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const currentDay = days[now.getDay()];
            const currentTime = now.getHours() * 100 + now.getMinutes(); // Format: 1430 for 2:30 PM

            items.forEach(item => {
                const card = item.querySelector('.schedule-card');
                const day = item.getAttribute('data-day');
                const timeRange = item.querySelector('.time-text').innerText; // e.g., "09:00 AM - 12:00 PM"
                
                if (day === currentDay) {
                    // Simple parsing for "HH:MM AM/PM"
                    const parts = timeRange.split(' - ');
                    const start = convertTo24(parts[0]);
                    const end = convertTo24(parts[1]);

                    if (currentTime >= start && currentTime <= end) {
                        card.classList.add('active-now');
                        if (!card.querySelector('.active-label')) {
                            const label = document.createElement('span');
                            label.className = 'active-label';
                            label.innerText = '● ONGOING';
                            card.appendChild(label);
                        }
                    }
                }
            });
        }

        function convertTo24(timeStr) {
            let [time, modifier] = timeStr.split(' ');
            let [hours, minutes] = time.split(':');
            hours = parseInt(hours);
            if (hours === 12) hours = 0;
            if (modifier === 'PM') hours += 12;
            return hours * 100 + parseInt(minutes);
        }

        checkCurrentClass();
    });
    </script>
</body>
</html>