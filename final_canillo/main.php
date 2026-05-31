<?php 
// Include the database connection so we can "Read" the data later
include 'includes/db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Portfolio Manager</title>

    <style>
        :root {
            --primary: #4a90e2;
            --success: #27ae60;
            --danger: #e74c3c;
            --dark: #2c3e50;
            --bg: #f4f7f6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: var(--bg); color: var(--dark); line-height: 1.6; }
        .container { max-width: 900px; margin: 40px auto; padding: 20px; }

        header { text-align: center; margin-bottom: 30px; }
        
        /* Responsive Form Design */
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .form-row { display: flex; gap: 20px; margin-bottom: 20px; }
        .input-group { flex: 1; display: flex; flex-direction: column; }
        
        label { font-weight: bold; margin-bottom: 8px; }
        input, select { padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; }

        .btn-primary { background: var(--primary); color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-size: 16px; width: 100%; transition: 0.3s; }
        .btn-primary:hover { background: #357abd; }

        /* Responsive Table Styling */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: var(--dark); color: white; }

        .action-links a { color: var(--primary); text-decoration: none; margin-right: 15px; font-weight: bold; }
        .btn-del { color: var(--danger); background: none; border: none; cursor: pointer; font-weight: bold; font-size: 14px; }

        /* Tablet/Mobile View (Responsive Requirement) */
        @media (max-width: 768px) {
            .form-row { flex-direction: column; }
            .container { margin: 10px auto; }
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>My Personal Project Manager</h1>
        <p>Dynamic CRUD Website using PHP & MySQL</p>
    </header>

    <section class="card">
        <h3>Add New Project</h3>
        <form id="portfolioForm" action="process_create.php" method="POST" onsubmit="return validateForm()">
            <div class="form-row">
                <div class="input-group">
                    <label for="title">Project Name</label>
                    <input type="text" name="title" id="title" placeholder="e.g. Finance Tracker">
                </div>
                <div class="input-group">
                    <label for="category">Category</label>
                    <select name="category" id="category">
                        <option value="">-- Select Category --</option>
                        <option value="Web App">Web App</option>
                        <option value="Mobile App">Mobile App</option>
                        <option value="Design">Design</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary">Add Project</button>
        </form>
    </section>

    <section class="card">
        <h3>Project List</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch data from MySQL
                    $sql = "SELECT * FROM my_portfolio ORDER BY id DESC";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                            echo "<td class='action-links'>
                                    <a href='edit.php?id=" . $row['id'] . "'>Edit</a>
                                    <button class='btn-del' onclick='confirmDelete(" . $row['id'] . ")'>Delete</button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No projects found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    /**
     * Client-side Validation
     * Prevents empty submissions before they hit the server
     */
    function validateForm() {
        const title = document.getElementById('title').value.trim();
        const category = document.getElementById('category').value;

        if (title === "") {
            alert("Please enter a project title.");
            return false;
        }

        if (category === "") {
            alert("Please choose a category from the dropdown.");
            return false;
        }

        return true;
    }

    /**
     * Interactivity: Delete Confirmation
     * Protects against accidental data removal
     */
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this project?")) {
            // Redirect to the PHP delete script
            window.location.href = "process_delete.php?id=" + id;
        }
    }
</script>

</body>
</html>