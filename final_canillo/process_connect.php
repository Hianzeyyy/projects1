<?php
include 'includes/db_connect.php'; // 1. Open the connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Get data from the HTML form
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $msg = $_POST['message'];

    // 3. CRUD Action: CREATE (INSERT)
    $sql = "INSERT INTO connections (full_name, email, message) 
            VALUES ('$name', '$email', '$msg')";

    if (mysqli_query($conn, $sql)) {
        echo "Success! Data saved to database.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>