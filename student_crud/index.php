<?php
require 'db_connect.php';

$sql = "SELECT id, fullName, email, Program, year_level FROM students ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Records</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        th { background: #f2f2f2; }
        a.btn { text-decoration: none; padding: 4px 10px; border-radius: 4px; margin-right: 4px; }
        a.edit { background: #ffc107; color: #000; }
        a.delete { background: #dc3545; color: #fff; }
        a.add { background: #28a745; color: #fff; padding: 8px 14px; display: inline-block; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>Student Records</h1>
    <a class="btn add" href="create.php">+ Add New Student</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Year Level</th>
            <th>Actions</th>
        </tr>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['fullName']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['Program']) ?></td>
                    <td><?= htmlspecialchars($row['year_level']) ?></td>
                    <td>
                        <a class="btn edit" href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                        <a class="btn delete" href="delete.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No student records found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

