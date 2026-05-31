<?php 
// 1. Database Connection
include 'includes/db_connect.php'; 

// 2. Data Arrays (Replacing Laravel Controller data)
$courseInfo = [
    'code' => 'CC106',
    'title' => 'Applications Development and Emerging Technologies',
    'credit' => '3 Units',
    'hours' => '5 Hours / Week'
];

$courses = [
    ['grade' => '1.25', 'completion' => 'Passed', 'code' => 'CC101', 'description' => 'Introduction to Computing', 'units' => 3.00],
    ['grade' => '1.50', 'completion' => 'Passed', 'code' => 'CC102', 'description' => 'Fundamentals of Programming', 'units' => 3.00],
    ['grade' => '1.75', 'completion' => 'Passed', 'code' => 'CC103', 'description' => 'Intermediate Programming', 'units' => 3.00],
    ['grade' => '---',  'completion' => 'Ongoing', 'code' => 'CC106', 'description' => 'Applications Development', 'units' => 3.00],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSU IT Syllabus | Native PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --psu-blue: #003366; --psu-gold: #ffcc00; }
        body { background: linear-gradient(135deg, #e0f0ff, #ffffff); min-height: 100vh; font-family: 'Montserrat', sans-serif; margin: 0; }
        
        /* Navbar */
        .navbar { background-color: var(--psu-blue) !important; padding: 1rem 0; }
        .navbar-brand { font-size: 1.8rem; color: var(--psu-gold) !important; font-weight: 800; }
        .nav-link { color: white !important; font-weight: 600; text-transform: uppercase; }
        .nav-link:hover { color: var(--psu-gold) !important; }

        /* PSU Header Branding */
        .psu-header { margin-top: 1rem; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background: white; }
        .psu-header .topbar { background: #bfe4ff; height: 12px; }
        .psu-header .goldbar { background: var(--psu-gold); padding: 15px 25px; display: flex; align-items: center; }
        .psu-header .seal { width: 55px; height: 55px; border-radius: 50%; background: #fff; margin-right: 15px; border: 2px solid var(--psu-blue); }
        .psu-header .bluebar { background: var(--psu-blue); padding: 8px; text-align: center; color: #fff; font-weight: 800; font-size: 0.8rem; }

        /* Cards and Tables */
        .syllabus-card { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08); margin-bottom: 2rem; border-top: 5px solid var(--psu-blue); }
        .section-header { background: var(--psu-blue); color: #fff; padding: 10px; font-weight: 800; font-size: 0.9rem; text-transform: uppercase; border-radius: 4px; margin-bottom: 10px; }
        .table-custom td { padding: 10px; border-bottom: 1px solid #eee; }
        .table-custom td.label { width: 35%; font-weight: 800; color: var(--psu-blue); background: #f9f9f9; }

        /* Small Text Footer Styling */
        footer { background: var(--psu-blue); color: white; padding: 30px 0; margin-top: 50px; text-align: center; }
        footer p { font-size: 0.75rem !important; opacity: 0.8; margin-bottom: 5px; }
        .social-links { list-style: none; display: flex; justify-content: center; gap: 20px; padding: 0; margin-bottom: 15px; }
        .social-links a { color: var(--psu-gold); text-decoration: none; font-weight: bold; font-size: 0.8rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand" href="index.php">PSU PORTAL</a>
        <div class="collapse navbar-collapse" id="psuNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#resources">RESOURCES</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="psu-header">
        <div class="topbar"></div>
        <div class="goldbar">
            <div class="seal"></div>
            <div>
                <div style="font-size: 1.3rem; font-weight:800; color:#000;">PANGASINAN STATE UNIVERSITY</div>
                <div style="font-weight:700; color:#000; font-size: 0.9rem;">Asingan Campus | IT Department</div>
            </div>
        </div>
        <div class="bluebar">BS INFORMATION TECHNOLOGY CURRICULUM</div>
    </div>

    <div class="text-center my-4">
        <h4 class="fw-800 text-primary border-bottom border-3 border-warning d-inline-block pb-2">COURSE SYLLABUS</h4>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="syllabus-card">
                <div class="section-header">Course Overview</div>
                <table class="w-100 table-custom">
                    <tr><td class="label">Course Code</td><td><?php echo $courseInfo['code']; ?></td></tr>
                    <tr><td class="label">Course Title</td><td><?php echo $courseInfo['title']; ?></td></tr>
                    <tr><td class="label">Credit Units</td><td><?php echo $courseInfo['credit']; ?></td></tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="syllabus-card">
                <div class="section-header">University Vision</div>
                <p class="small p-2">To be a leading industry-driven State University in the ASEAN region by 2030.</p>
            </div>
        </div>
    </div>

    <div class="syllabus-card">
        <div class="section-header mb-3">Academic Progress Track</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Grade</th>
                        <th>Status</th>
                        <th>Code</th>
                        <th class="text-start">Description</th>
                        <th>Units</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($courses as $course): ?>
                    <tr class="text-center">
                        <td class="fw-bold"><?php echo $course['grade']; ?></td>
                        <td><span class="badge bg-secondary"><?php echo $course['completion']; ?></span></td>
                        <td class="text-primary fw-bold"><?php echo $course['code']; ?></td>
                        <td class="text-start"><?php echo $course['description']; ?></td>
                        <td><?php echo number_format($course['units'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <ul class="social-links">
            <li><a href="https://www.facebook.com/itsmeyyycccc">FB</a></li>
            <li><a href="#">GH</a></li>
            <li><a href="#">LI</a></li>
            <li><a href="#">EMAIL</a></li>
        </ul>
        <p>Mastery of HTML, CSS, JS, PHP, and MySQL</p>
        <p>&copy; 2026 NU Student Portal | FAIRVIEW Campus</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>