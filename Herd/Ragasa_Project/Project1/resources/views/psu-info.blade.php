<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PSU Mission, Vision & Core Values</title>
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

        /* ===== HEADER ===== */
        .top-header {
        background: white;
        text-align: center;
        padding: 25px 0;
        border-bottom: 1px solid #ddd;
            }

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

        .logo-text p {
            margin: 0;
            font-size: 14px;
            text-align: left;
            color: #222;
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

        /* ===== CONTENT CARD ===== */
        .content-card{
            max-width:850px;
            margin:50px auto;
            background:rgba(255,255,255,0.85);
            backdrop-filter:blur(8px);
            padding:30px;
            border-radius:14px;
            box-shadow:0 4px 10px rgba(0,0,0,0.10);
        }

        h2{
            color:#0927D8;
            margin-top:20px;
            margin-bottom:12px;
        }

        p{
            color:#333;
            line-height:1.6;
            font-size:15px;
            margin:8px 0;
        }

        .blue-letter{
            color:#0927D8;
            font-size:20px;
            font-weight:bold;
        }

        /* ===== FOOTER ===== */
        footer{
            background:#001f80;
            color:white;
            text-align:center;
            padding:15px;
            margin-top:40px;
            font-size:13px;
        }

</style>
</head>

<body>

<div class="top-header">
    <div class="logo-area">
        <img src="images/PSU_logo.png" alt="PSU Logo">

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
        <li><a href="psu-info">MISSION AND VISION</a></li>
        <li><a href="#">SYLLABUS</a></li>
        <li><a href="schedule">SCHEDULE</a></li>
        <li><a href="calendar">PSU CALENDAR</a></li>
    </ul>
</div>


<!-- ===== CONTENT ===== -->
<div class="content-card">

<h2>Vision</h2>
<p>
To become a leading industry-driven State University in the ASEAN region by 2030.
</p>

<h2>Mission</h2>
<p>
The Pangasinan State University shall provide a human-centric, resilient, and sustainable
academic environment to produce dynamic, responsive, and future-ready individuals capable of
meeting the requirements of the local and global communities and industries.
</p>

<h2>Core Values</h2>

<p><span class="blue-letter">A</span>ccountability and Transparency</p>
<p><span class="blue-letter">C</span>redibility and Integrity</p>
<p><span class="blue-letter">C</span>ompetence and Commitment to Achieve</p>
<p><span class="blue-letter">E</span>xcellence in Service Delivery</p>
<p><span class="blue-letter">S</span>ocial and Environmental Responsiveness</p>
<p><span class="blue-letter">S</span>pirituality</p>

</div>

<!-- ===== FOOTER ===== -->
<footer>
© {{ date('Y') }} Pangasinan State University | BSIT Department
</footer>

</body>
</html>
