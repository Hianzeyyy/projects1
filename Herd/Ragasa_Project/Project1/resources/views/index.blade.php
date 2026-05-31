<?php
// PSU Landing Page - PHP Version
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PSU Landing Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

         body {
            background: url("<?php echo asset('images/BG_PSULOGO.png'); ?>")
                        no-repeat center center fixed;
            background-size: cover;
        }

        /* ===== LOGO FIX ALIGNMENT ===== */
        .logo-area {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }

        .logo-area img {
            width: 90px;
            height: auto;
        }

        .logo-text h1 {
            font-size: 70px;
            font-weight: 900;
            color: #0033cc;
            margin: 0;
            line-height: 1;
        }

        /* ===== HEADER TOP LOGO AREA ===== */
        .top-header {
            background: #fff;
            text-align: center;
            padding: 25px 0;
            border-bottom: 1px solid #ddd;
        }

        .top-header img {
            width: 85px;
            vertical-align: middle;
        }

        .top-header h1 {
            display: inline-block;
            font-size: 70px;
            font-weight: 900;
            color: #0033cc;
            margin-left: 15px;
            vertical-align: middle;
        }

        .top-header p {
            font-size: 14px;
            color: #222;
            margin-top: 5px;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: linear-gradient(to right, #001f80, #0033cc);
            padding: 15px 0;
        }

        .navbar ul {
            list-style: none;
            text-align: center;
        }

        .navbar ul li {
            display: inline-block;
            margin: 0 15px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s;
        }

        .navbar ul li a:hover {
            color: yellow;
        }

        /* ===== HERO SECTION ===== */
        .hero {
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .hero-content {
            background: rgba(0,0,0,0.6);
            padding: 40px;
            border-radius: 12px;
            max-width: 700px;
        }

        .hero-content h2 {
            font-size: 45px;
            margin-bottom: 15px;
        }

        .hero-content p {
            font-size: 18px;
            margin-bottom: 25px;
        }

        .hero-content a {
            display: inline-block;
            padding: 12px 25px;
            background: yellow;
            color: black;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }

        .hero-content a:hover {
            background: orange;
        }

        /* ===== FOOTER ===== */
        footer {
            background: #001f80;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
<div class="top-header">

    <div class="logo-area">
        <!-- PSU Logo -->
        <img src="images/PSU_logo.png" alt="PSU Logo">

        <!-- PSU Text -->
        <div class="logo-text">
            <h1>PSU</h1>
            <p>
                <b>Pangasinan State University</b><br>
                Region’s Premier University of Choice
            </p>
        </div>
    </div>

</div>


    <!-- NAVIGATION BAR -->
    <div class="navbar">
        <ul>
            <li><a href="index">HOME</a></li>
            <li><a href="psu-policy">PSU POLICY</a></li>
            <li><a href="psu-info">MISSION AND VISSION</a></li>
            <li><a href="bsit-syllabus">SYLLABUS</a></li>
            <li><a href="schedule">SCHEDULE</a></li>
            <li><a href="calendar">PSU CALENDAR</a></li>

        </ul>
    </div>

    <!-- HERO SECTION -->
    <section class="hero">
        {{-- <div class="hero-content">
            <h2>Welcome to Pangasinan State University</h2>
            <p>
                A leading institution committed to excellence in instruction,
                research, extension, and innovation for future-ready graduates.
            </p>

            <a href="#">Explore PSU</a>
        </div> --}}
    </section>

    <!-- FOOTER -->
    <footer>
        &copy; <?php echo date("Y"); ?> Pangasinan State University
    </footer>

</body>
</html>
