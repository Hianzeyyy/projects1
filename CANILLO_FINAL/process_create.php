<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $sql = "INSERT INTO my_portfolio (title, category) VALUES ('$title', '$category')";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=success");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>