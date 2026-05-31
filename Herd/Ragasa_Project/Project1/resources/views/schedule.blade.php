<?php // BSIT Class Schedule - PSU Template Version ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>BSIT Class Schedule</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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

    display: flex;
    justify-content: center; /* centers content horizontally */
}

.navbar ul {
    list-style: none;
    display: flex;          /* make items inline-flex */
    justify-content: center;
    align-items: center;
    gap: 30px;              /* equal spacing */
    padding: 0;
    margin: 0;
}

.navbar ul li {
    margin: 0;              /* remove uneven margins */
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
/* SCHEDULE CARD */
.schedule-card{
    max-width:1000px;
    margin:40px auto;
    background:rgba(255,255,255,0.90);
    border-radius:12px;
    padding:30px;
    box-shadow:0 4px 12px rgba(0,0,0,0.10);
}

h2{
    color:#0033cc;
    font-weight:bold;
}

th{
    background:#0033cc !important;
    color:white !important;
    text-align:center;
}

td{
    text-align:center;
    font-size:14px;
}

.lunch{
    background:#fff3cd;
    font-weight:bold;
}

/* FOOTER */
footer{
    background:#001f80;
    color:white;
    text-align:center;
    padding:15px;
    margin-top:50px;
    font-size:13px;
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
<!-- SCHEDULE -->
<div class="schedule-card">

<h2 class="text-center">Bachelor of Science in Information Technology</h2>
<h5 class="text-center">Major in Web and Mobile Technologies</h5>
<p class="text-center">Class Schedule – 3rd Year | 1st Semester</p>

<div class="table-responsive">
<table class="table table-bordered table-hover">

<thead>
<tr>
<th>Time</th>
<th>Monday</th>
<th>Tuesday</th>
<th>Wednesday</th>
<th>Thursday</th>
<th>Friday</th>
</tr>
</thead>

<tbody>

<tr>
<td>8:00 AM – 10:00 AM</td>
<td>Elective 2<br><small>ROOM 16<br>JB Doria</small></td>
<td>Technopreneurship<br><small>ROOM 17<br>Alvin Umaga</small></td>
<td></td>
<td>Elective 1<br><small>ROOM 16<br>Wilmar Motea</small></td>
<td></td>
</tr>

<tr>
<td>10:00 AM – 11:00 AM</td>
<td>Networking<br><small>IT LAB 1<br>Wilmar Motea</small></td>
<td></td>
<td>Technopreneurship<br><small>ROOM 17<br>Alvin Umaga</small></td>
<td></td>
<td>Elective 1<br><small>ROOM 16<br>Wilmar Motea</small></td>
</tr>

<tr>
<td>11:00 AM – 12:00 PM</td>
<td></td>
<td>Elective 2<br><small>ROOM 16<br>JB Doria</small></td>
<td></td>
<td>Networking<br><small>IT LAB 1<br>Wilmar Motea</small></td>
<td></td>
</tr>

<tr class="lunch">
<td>12:00 PM – 1:00 PM</td>
<td colspan="5">Lunch Break</td>
</tr>

<tr>
<td>1:00 PM – 3:00 PM</td>
<td>Networking<br><small>IT LAB 1<br>Wilmar Motea</small></td>
<td>Elective 1<br><small>ROOM 16<br>Wilmar Motea</small></td>
<td></td>
<td>Elective 2<br><small>ROOM 16<br>JB Doria</small></td>
<td>Technopreneurship<br><small>ROOM 17<br>Alvin Umaga</small></td>
</tr>

<tr>
<td>3:00 PM – 5:00 PM</td>
<td></td>
<td></td>
<td>Elective 1<br><small>ROOM 16<br>Wilmar Motea</small></td>
<td></td>
<td></td>
</tr>

</tbody>
</table>
</div>

</div>

<!-- FOOTER -->
<footer>
&copy; <?php echo date("Y"); ?> Pangasinan State University | BSIT Department
</footer>

</body>
</html>
