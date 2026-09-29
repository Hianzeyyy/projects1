<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome - De Vera-Mejia Mini Mart</title>
    <link rel="stylesheet" href="sarisaristore.css">
<style>
  div {
  display: flex;
    flex-direction: column;
    align-items: center;
}
.hero {
  text-align: center;
  padding: 2rem;
}
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #c64a8c;
  padding: 1rem 5%;
  color: #ffffff;
}
.navbar .icon {
  font-size: 1.5rem;
  font-weight: bold;
}
.navbar .logo {
  font-size: 1.5rem;
  font-weight: bold;
}
.footer {
  text-align: center;
  padding: 1rem;
  background-color: #c64a8c;
  color: #ffffff;
}
.p {
  font-size: 1.2rem;
  margin-bottom: 1rem;
}


    

</style>



</head>

<body class = "div">

  <nav class="navbar">
    <h2 class = "logo"><span class="icon">🛒</span> De Vera-Mejia Mini Mart</h2>

    <div class="nav-links">
      <a href="about.php">ABOUT</a>
      <a href="contact.php">CONTACT</a>
    </div>
  </nav>

  <section class="hero">


    <h1 class = "store-name" >De Vera-Mejia Mini Mart</h1>

    <p class = "store-description">Your friendly neighborhood sari-sari store</p>

    <span class="welcome">Welcome to Our Store</span>

    <br><br>

    <a href="login.php" class="button">Enter Store</a>

  </section>

  <footer class="footer">
    © 2026 De Vera-Mejia Mini Mart
  </footer>

</body>
</html>