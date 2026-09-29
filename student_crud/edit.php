<?php
require 'db_connect.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$error = "";

// Handle the update submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName  = trim($_POST['fullName']);
    $email      = trim($_POST['email']);
    $Program     = trim($_POST['Program']);
    $year_level = trim($_POST['year_level']);
    $post_id    = (int) $_POST['id'];

    if ($fullName === "" || $email === "" || $Program === "" || $year_level === "") {
        $error = "Please fill in all fields.";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE students SET fullName = ?, email = ?, Program = ?, year_level = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssssi", $fullName, $email, $course, $year_level, $post_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Error updating student: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch existing record to pre-fill the form
$stmt = mysqli_prepare($conn, "SELECT id, fullName, email, Program, year_level FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$student) {
    die("Student not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        form { max-width: 400px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 6px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 15px; padding: 8px 16px; background: #ffc107; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: #dc3545; }
        a { display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>
    <h1>Edit Student</h1>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']) ?>">

        <label>Full Name</label>
        <input type="text" name="fullName" value="<?= htmlspecialchars($student['fullName']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>

        <label>Program</label>
        <input type="text" name="Program" value="<?= htmlspecialchars($student['Program']) ?>" required>

        <label>Year Level</label>
        <select name="year_level" required>
            <?php foreach (["1st Year", "2nd Year", "3rd Year", "4th Year"] as $level): ?>
                <option value="<?= $level ?>" <?= $student['year_level'] === $level ? 'selected' : '' ?>><?= $level ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Update Student</button>
    </form>
    <a href="index.php">&larr; Back to list</a>
</body>
</html>

