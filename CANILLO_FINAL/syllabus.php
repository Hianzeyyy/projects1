<?php 
// 1. DATABASE CONNECTION
$servername = "127.0.0.1";
$username = "root";
$password = ""; // Remember to add this to your .gitignore as we discussed!
$dbname = "final_canillo_db";
$port = 3306; 

$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

if (!$conn) {
    $conn = mysqli_connect($servername, $username, $password, $dbname, 3306);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}

// 2. DATA FETCHING
$query = "SELECT * FROM curriculum ORDER BY year_level ASC, semester ASC";
$result = mysqli_query($conn, $query);

$courseInfo = [
    'code' => 'BSIT',
    'title' => 'Bachelor of Science in Information Technology'
];

$currentSection = ""; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syllabus | NU Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .custom-navbar { background: #003366 !important; padding: 1rem 0; border-bottom: 3px solid #FFCC00; }
        .navbar-brand { font-size: 1.8rem; color: #FFCC00 !important; font-weight: 800; }
        .nav-link { color: white !important; font-weight: 600; text-transform: uppercase; }
        .syllabus-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-bottom: 30px; border-left: 5px solid #003366; }
        .section-header { font-size: 1.5rem; font-weight: 800; color: #003366; border-bottom: 3px solid #FFCC00; display: inline-block; margin-bottom: 20px; }
        
        #manualModal {
            display: none; position: fixed; z-index: 3000; left: 0; top: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);
        }
        .modal-box {
            background: white; margin: 5% auto; padding: 30px; border-radius: 20px;
            width: 90%; max-width: 500px; position: relative; animation: slideDown 0.3s ease;
        }
        @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .fab {
            position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px;
            background: #003366; color: #FFCC00; border-radius: 50%; display: flex;
            justify-content: center; align-items: center; font-size: 30px; cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4); border: 2px solid #FFCC00; transition: 0.3s;
        }
        .fab:hover { transform: scale(1.1) rotate(90deg); background: #FFCC00; color: #003366; }
        .table-secondary { background-color: #f8f9fa !important; }
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
                        <ul class="dropdown-menu shadow" aria-labelledby="actDrop">
                            <li><a class="dropdown-item" href="syllabus.php">Syllabus</a></li>
                            <li><a class="dropdown-item" href="NuStrategicGoal.php">Strategic Goals</a></li>
                            <li><a class="dropdown-item" href="mission.php">Mission & Vision</a></li>
                            <li><a class="dropdown-item" href="schedule.php">Schedule</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-4">
        <div class="syllabus-card">
            <div class="section-header">Course Overview</div>
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Program:</strong> <?php echo $courseInfo['title']; ?></p>
                </div>
                <div class="col-md-4">
                    <input type="text" id="tableSearch" class="form-control" placeholder="🔍 Search subjects...">
                </div>
            </div>
        </div>

        <div class="syllabus-card">
            <div class="section-header">Academic Progress Track</div>
            <div class="table-responsive">
                <table class="table table-hover" id="curriculumTable">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Code</th>
                            <th class="text-start">Description</th>
                            <th>Units</th>
                            <th>Pre-requisites</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): 
                            $sectionLabel = "Year " . $row['year_level'] . " - Semester " . $row['semester'];
                            
                            if ($currentSection !== $sectionLabel): 
                                $currentSection = $sectionLabel; ?>
                                <tr class="table-secondary">
                                    <td colspan="7" class="text-start fw-bold p-3" style="color: #003366; border-left: 5px solid #FFCC00;">
                                        <i class="bi bi-mortarboard-fill me-2"></i> <?php echo strtoupper($currentSection); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <tr class="text-center align-middle">
                                <td class="fw-bold grade-val text-primary">
                                    <?php 
                                        // Handle retaken subjects (e.g., 5.00/2.75) by showing the latest grade
                                        $displayGrade = $row['grade'];
                                        if (strpos($displayGrade, '/') !== false) {
                                            $grades = explode('/', $displayGrade);
                                            $displayGrade = end($grades);
                                        }
                                        echo (!empty($displayGrade) && $displayGrade != '---') ? $displayGrade : '---'; 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $status = $row['status'];
                                        $badgeClass = 'bg-secondary';
                                        if (stripos($status, 'Passed') !== false) $badgeClass = 'bg-success';
                                        elseif (stripos($status, 'Taken') !== false || stripos($status, 'Progress') !== false) $badgeClass = 'bg-primary';
                                        elseif (stripos($status, 'Enroll') !== false) $badgeClass = 'bg-warning text-dark';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                                </td>
                                <td class="fw-bold"><?php echo $row['course_code']; ?></td>
                                <td class="text-start"><?php echo $row['description']; ?></td>
                                <td class="unit-val"><?php echo number_format($row['units'], 1); ?></td>
                                <td class="small"><?php echo $row['pre_requisites']; ?></td>
                                <td class="small italic text-muted"><?php echo $row['remarks']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <div id="summaryBox" class="mt-4 p-3 bg-light rounded-3 text-end border-top"></div>
        </div>
    </div>

    <div id="manualModal">
        <div class="modal-box">
            <h3 class="mb-4" style="color:#003366"><i class="bi bi-plus-circle-fill"></i> Add New Course</h3>
            <form action="save_course.php" method="POST">
                <div class="row">
                    <div class="col-6 mb-2">
                        <label class="small fw-bold">Year Level</label>
                        <input type="number" name="year_level" class="form-control" value="3" required>
                    </div>
                    <div class="col-6 mb-2">
                        <label class="small fw-bold">Semester</label>
                        <input type="number" name="semester" class="form-control" value="2" required>
                    </div>
                </div>
                <input type="text" name="course_code" class="form-control mb-2" placeholder="Course Code" required>
                <input type="text" name="description" class="form-control mb-2" placeholder="Description" required>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="small fw-bold">Units</label>
                        <input type="number" step="0.5" name="units" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="small fw-bold">Grade</label>
                        <input type="text" name="grade" class="form-control" placeholder="1.00">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Save to Curriculum</button>
                <button type="button" id="closeModal" class="btn btn-link w-100 mt-2 text-danger">Cancel</button>
            </form>
        </div>
    </div>

    <div class="fab" id="openModal">+</div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal Logic
        const modal = document.getElementById('manualModal');
        const openBtn = document.getElementById('openModal');
        const closeBtn = document.getElementById('closeModal');

        openBtn.onclick = () => modal.style.display = 'block';
        closeBtn.onclick = () => modal.style.display = 'none';

        // Search Logic
        const searchInput = document.getElementById('tableSearch');
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#curriculumTable tbody tr');
            let lastHeader = null;

            rows.forEach(row => {
                if(row.classList.contains('table-secondary')) {
                    lastHeader = row;
                    row.style.display = 'none';
                    return;
                }
                const text = row.innerText.toLowerCase();
                if(text.includes(filter)) {
                    row.style.display = '';
                    if(lastHeader) lastHeader.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Stats Logic
        function calculateStats() {
            let totalUnitsEarned = 0;
            let totalPoints = 0;
            let gradedUnits = 0;
            const rows = document.querySelectorAll('#curriculumTable tbody tr:not(.table-secondary)');

            rows.forEach(row => {
                const gradeText = row.querySelector('.grade-val').innerText.trim();
                const unitText = row.querySelector('.unit-val').innerText.trim();
                const statusText = row.querySelector('td:nth-child(2)').innerText;
                
                const grade = parseFloat(gradeText);
                const units = parseFloat(unitText);

                if(!isNaN(units)) {
                    // Only count units toward "Earned" if status is Passed
                    if(statusText.includes('Passed')) {
                        totalUnitsEarned += units;
                        
                        // Only add to GWA if there is a valid numeric grade
                        if(!isNaN(grade) && grade > 0) {
                            totalPoints += (grade * units);
                            gradedUnits += units;
                        }
                    }
                }
            });

            const gwa = gradedUnits > 0 ? (totalPoints / gradedUnits).toFixed(2) : "0.00";
            document.getElementById('summaryBox').innerHTML = `
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-calculator me-2"></i> 
                    Total Earned Units: <span class="text-dark">${totalUnitsEarned.toFixed(1)}</span> | 
                    Running GWA: <span class="text-dark">${gwa}</span>
                </h5>
            `;
        }
        calculateStats();
    });
    </script>
</body>
</html>