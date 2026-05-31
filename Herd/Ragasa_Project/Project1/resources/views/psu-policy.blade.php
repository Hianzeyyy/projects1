<?php
// PSU Landing Page + Strategic Goals Page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PSU Strategic Goal & Quality Policy</title>
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

        /* ===== MAIN CONTAINER ===== */
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.80);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        h2 {
            color: #0927D8;
            text-align: center;
            margin-bottom: 15px;
        }

        p {
            text-align: justify;
            color: #333;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .psu-strategic-goals p {
            font-size: 15px;
            margin: 8px 0;
        }

        /* ===== FOOTER ===== */
        footer {
            background: #001f80;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 13px;
            margin-top: 30px;
        }
    </style>
</head>

<body>

<!-- HEADER -->
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

<!-- CONTENT -->
<div class="container">

    <h2>PSU Strategic Goals</h2>
    <div class="psu-strategic-goals">
        <p><strong>SG 1:</strong> Industry-Focused and Innovation-Based Student Learning and Development</p>
        <p><strong>SG 2:</strong> Responsive and Sustainable Research, Community Extension, and Innovative Programs</p>
        <p><strong>SG 3:</strong> Effective and Efficient Governance and Financial Management</p>
        <p><strong>SG 4:</strong> High-Performing and Engaged Human Resource</p>
        <p><strong>SG 5:</strong> Strategic and Functional Internationalization Program</p>
    </div>

    <h2>PSU Quality Policy Standard</h2>
    <p>
        The Pangasinan State University shall be recognized as an ASEAN premier state university that
        provides quality education and satisfactory service delivery through instruction, research,
        extension and production.
    </p>

    <p>
        We commit our expertise and resources to produce professionals who meet the expectations of the industry
        and other interested parties in the national and international community.
    </p>

    <p>
        We shall continuously improve our operations through systems and process innovations guided by ethical,
        intellectual property and technology transfer standards.
    </p>

</div>

<!-- FOOTER -->
<footer>
    &copy; <?php echo date("Y"); ?> Pangasinan State University | All Rights Reserved
</footer>

</body>
</html>
