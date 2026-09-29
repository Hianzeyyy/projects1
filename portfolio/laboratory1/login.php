<?php

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username == "admin" && $password == "1234") {

        header("Location: index.php");
        exit();

    } else {

        $message = "Invalid username or password.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - De Vera-Mejia Mini Mart</title>
  <link rel="stylesheet" href="sarisaristore.css">
</head>

<body class="login-page">

  <div class="login-box">

    <div class="icon">🏪</div>

    <h1>Welcome Back!</h1>

    <p>Login to enter De Vera-Mejia Mini Mart</p>

    <?php if ($message != ""): ?>
      <div class="error">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <form method="POST">

      <input
        type="text"
        name="username"
        placeholder="Username"
        required
      >

      <input
        type="password"
        name="password"
        placeholder="Password"
        required
      >

      <button type="submit">Login</button>

    </form>

    <a href="index.php">← Back to Landing Page</a>

  </div>

</body>
</html>