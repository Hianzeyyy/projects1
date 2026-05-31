<?php
// save_course.php
$servername = "127.0.0.1";
$username = "root";
$password = ""; 
$dbname = "final_canillo_db";
$port = 3307;

$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = $_POST['year_level'];
    $sem = $_POST['semester'];
    $code = mysqli_real_escape_string($conn, $_POST['course_code']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $units = $_POST['units'];
    $grade = !empty($_POST['grade']) ? $_POST['grade'] : '---';
    
    // Auto-determine status based on grade
    $status = (is_numeric($grade) && $grade <= 3.0) ? 'Passed' : 'In Progress';
    if ($grade == '---') $status = 'In Progress';

    $sql = "INSERT INTO curriculum (year_level, semester, course_code, description, units, grade, status) 
            VALUES ('$year', '$sem', '$code', '$desc', '$units', '$grade', '$status')";

    if (mysqli_query($conn, $sql)) {
        header("Location: syllabus.php?success=1");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>