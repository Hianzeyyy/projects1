<?php
include 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO connections (full_name, email, message) 
            VALUES ('$name', '$email', '$message')";

    if (mysqli_query($conn, $sql)) {
        $to = "sumaoemilhene@gmail.com"; 
        $subject = "Inquiry from " . $name;
        $body = "Name: $name\nEmail: $email\nMessage: $message";
        $headers = "From: portal@canillo.com";
        
        @mail($to, $subject, $body, $headers);

        echo "<script>alert('Sent to Database & sumaoemilhene@gmail.com'); window.location.href='index.php';</script>";
    }
}
?>