<?php
require 'db_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName  = trim($_POST['fullName']);
    $email      = trim($_POST['email']);
    $Program     = trim($_POST['Program']);
    $year_level = trim($_POST['year_level']);

    if ($fullName === "" || $email === "" || $Program === "" || $year_level === "") {
        $error = "Please fill in all fields.";
    } else {
        // Prepared statement para safe sa SQL injection
        $stmt = mysqli_prepare($conn, "INSERT INTO students (fullName, email, Program, year_level) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $fullName, $email, $Program, $year_level);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Error saving student: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        form { max-width: 400px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 6px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 15px; padding: 8px 16px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: #dc3545; }
        a { display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>
    <h1>Add New Student</h1>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="POST" action="create.php">
        <label>Full Name</label>
        <input type="text" name="fullName" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Program</label>
        <input type="text" name="Program" required>

        <label>Year Level</label>
        <select name="year_level" required>
            <option value="">-- Select --</option>
            <option value="1st Year">1st Year</option>
            <option value="2nd Year">2nd Year</option>
            <option value="3rd Year">3rd Year</option>
            <option value="4th Year">4th Year</option>
        </select>

        <button type="submit">Save Student</button>
    </form>
    <a href="index.php">&larr; Back to list</a>
</body>
</html>

