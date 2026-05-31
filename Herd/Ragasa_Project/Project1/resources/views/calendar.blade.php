<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PSU Collegiate Calendar 2025–2026</title>

<script src="https://cdn.tailwindcss.com"></script>

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


        /* ===== CALENDAR STYLES ===== */
        .calendar-grid{
        display:grid;
        grid-template-columns:repeat(7,1fr);
        font-size:.7rem;
        text-align:center;
        border-left:1px solid #4b5563;
        border-top:1px solid #4b5563
        }

        .calendar-grid div{
        border-right:1px solid #4b5563;
        border-bottom:1px solid #4b5563;
        min-height:20px;
        padding:2px 0
        }

        .calendar-header{
        background:#0927D8;
        color:white;
        font-weight:bold;
        font-size:.75rem;
        padding:4px;
        text-transform:uppercase
        }

        .day-label{background:#e5e7eb;font-weight:bold}
        .holiday{background:#d1d5db;font-weight:bold}
        .class-day{background:#f3f4f6;font-weight:bold}
        .sunday{text-decoration:underline;font-weight:bold}

        .activities-center{
          display:flex;
          justify-content:center;
          margin-top:20px;
        }
        .activities-box{
          width:50%;
          border:1.2px solid #000;
          padding:12px 14px;
          font-size:10.5px;
          font-family:"Times New Roman", serif;
          line-height:1.5;
        }
        .activities-box h4{
          text-align:center;
          font-size:11.5px;
          font-weight:bold;
          margin-bottom:8px;
          text-transform:uppercase;
          border-bottom:1px solid #000;
          padding-bottom:4px;
        }
        .activities-box strong{
          display:block;
          margin-top:6px;
        }
        .activities-box ul{
          margin-left:14px;
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

<!-- ===== MAIN CONTENT ===== -->
<div class="max-w-7xl mx-auto bg-white border-2 border-black p-6 mt-6">
<h1 class="text-center font-black text-2xl border-b-2 border-black pb-2 mb-6">
PANGASINAN STATE UNIVERSITY<br>
<span class="text-lg italic">COLLEGIATE CALENDAR A.Y. 2025–2026</span>
</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- ================= 1ST SEMESTER ================= -->
<div class="space-y-4">
<h3 class="bg-black text-white text-center font-bold py-1 uppercase text-sm">1st Semester</h3>

<!-- AUG -->
<div class="border border-black">
<div class="calendar-header text-center">August 2025</div>
<div class="calendar-grid">
<div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div><div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>
<div></div><div></div><div></div><div></div><div></div><div>1</div><div>2</div>
<div class="sunday">3</div><div>4</div><div>5</div><div>6</div><div>7</div><div>8</div><div>9</div>
<div class="sunday">10</div><div>11</div><div>12</div><div>13</div><div>14</div><div>15</div><div>16</div>
<div class="sunday">17</div><div>18</div><div>19</div><div>20</div><div class="holiday">21</div><div>22</div><div>23</div>
<div class="sunday">24</div><div class="holiday">25</div><div>26</div><div>27</div><div>28</div><div>29</div><div>30</div>
<div class="sunday">31</div>
</div>
</div>

<!-- SEP -->
<div class="border border-black">
<div class="calendar-header text-center">September 2025</div>
<div class="calendar-grid">
<div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div><div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>
<div></div><div class="class-day">1</div><div class="class-day">2</div><div class="class-day">3</div><div class="class-day">4</div><div class="class-day">5</div><div class="class-day">6</div>
<div class="sunday">7</div><div class="class-day">8</div><div class="class-day">9</div><div class="class-day">10</div><div class="class-day">11</div><div class="class-day">12</div><div class="class-day">13</div>
<div class="sunday">14</div><div class="class-day">15</div><div class="class-day">16</div><div class="class-day">17</div><div class="class-day">18</div><div class="class-day">19</div><div class="class-day">20</div>
<div class="sunday">21</div><div class="class-day">22</div><div class="class-day">23</div><div class="class-day">24</div><div class="class-day">25</div><div class="class-day">26</div><div class="class-day">27</div>
<div class="sunday">28</div><div class="class-day">29</div><div class="class-day">30</div>
</div>
</div>

<!-- OCT -->
<div class="border border-black">
<div class="calendar-header text-center">October 2025</div>
<div class="calendar-grid">
<div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div><div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>
<div></div><div></div><div></div><div>1</div><div>2</div><div>3</div><div>4</div>
<div class="sunday">5</div><div>6</div><div>7</div><div>8</div><div>9</div><div>10</div><div>11</div>
<div class="sunday">12</div><div>13</div><div>14</div><div>15</div><div>16</div><div>17</div><div>18</div>
<div class="sunday">19</div><div>20</div><div>21</div><div>22</div><div>23</div><div>24</div><div>25</div>
<div class="sunday">26</div><div>27</div><div>28</div><div>29</div><div>30</div><div class="holiday">31</div>
</div>
</div>

<!-- NOV -->
<div class="border border-black">
<div class="calendar-header text-center">November 2025</div>
<div class="calendar-grid">
<div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div><div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>
<div></div><div></div><div></div><div></div><div></div><div></div><div class="holiday">1</div>
<div class="holiday">2</div><div>3</div><div>4</div><div>5</div><div>6</div><div>7</div><div>8</div>
<div class="sunday">9</div><div>10</div><div>11</div><div>12</div><div class="holiday">13</div><div>14</div><div>15</div>
<div class="sunday">16</div><div>17</div><div>18</div><div>19</div><div>20</div><div>21</div><div>22</div>
<div class="sunday">23</div><div>24</div><div>25</div><div>26</div><div>27</div><div>28</div><div>29</div>
<div class="holiday">30</div>
</div>
</div>

<!-- DEC -->
<div class="border border-black">
<div class="calendar-header text-center">December 2025</div>
<div class="calendar-grid">
<div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div><div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>
<div></div><div>1</div><div>2</div><div>3</div><div>4</div><div>5</div><div>6</div>
<div class="sunday">7</div><div class="holiday">8</div><div>9</div><div>10</div><div>11</div><div>12</div><div>13</div>
<div class="sunday">14</div><div>15</div><div>16</div><div>17</div><div>18</div><div>19</div><div>20</div>
<div class="sunday">21</div><div>22</div><div>23</div><div class="holiday">24</div><div class="holiday">25</div><div>26</div><div>27</div>
<div class="sunday">28</div><div>29</div><div class="holiday">30</div><div class="holiday">31</div>
</div>
</div>
</div>

<!-- ================= MID-YEAR ================= -->
<div class="space-y-4">
<h3 class="text-white text-center font-bold py-1 uppercase text-sm" style="background-color:#0927D8;">
  Mid-Year
</h3>


<!-- JUNE -->
<div class="border border-black">
<div class="calendar-header text-center" style="background-color: #0927D8;">
  June 2026
</div>

<div class="calendar-grid">
<div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div><div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>
<div></div><div class="class-day">1</div><div class="class-day">2</div><div class="class-day">3</div><div class="class-day">4</div><div class="class-day">5</div><div class="class-day">6</div>
<div class="sunday">7</div><div>8</div><div>9</div><div>10</div><div>11</div><div class="holiday">12</div><div>13</div>
<div class="sunday">14</div><div>15</div><div>16</div><div>17</div><div>18</div><div>19</div><div>20</div>
<div class="sunday">21</div><div>22</div><div>23</div><div>24</div><div>25</div><div>26</div><div>27</div>
<div class="sunday">28</div><div>29</div><div>30</div>
</div>
</div>

<!-- JULY -->
<div class="border border-black">
<div class="calendar-header text-center" style="background-color: #0927D8;">
  July 2026
</div>
<div class="calendar-grid">
<div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div><div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>
<div></div><div></div><div></div><div class="class-day">1</div><div class="class-day">2</div><div class="class-day">3</div><div class="class-day">4</div>
<div class="sunday">5</div><div>6</div><div>7</div><div>8</div><div>9</div><div>10</div><div>11</div>
<div class="sunday">12</div><div>13</div><div>14</div><div>15</div><div>16</div><div>17</div><div>18</div>
<div class="sunday">19</div><div>20</div><div>21</div><div>22</div><div>23</div><div>24</div><div class="class-day">25</div>
<div class="sunday">26</div><div>27</div><div>28</div><div>29</div><div>30</div><div>31</div>
</div>
</div>
</div>

<!-- ================= 2ND SEMESTER ================= -->
<div class="space-y-4">
<h3 class="bg-black text-white text-center font-bold py-1 uppercase text-sm">2nd Semester</h3>

<!-- JAN -->
<div class="border border-black">
<div class="calendar-header text-center">January 2026</div>
<div class="calendar-grid">
<div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div><div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>
<div></div><div></div><div></div><div></div><div class="holiday">1</div><div class="class-day">2</div><div class="holiday">3</div>
<div class="sunday">4</div><div>5</div><div>6</div><div>7</div><div>8</div><div>9</div><div>10</div>
<div class="sunday">11</div><div>12</div><div>13</div><div>14</div><div>15</div><div>16</div><div>17</div>
<div class="sunday">18</div><div>19</div><div>20</div><div class="holiday">21</div><div>22</div><div>23</div><div>24</div>
<div class="sunday">25</div><div>26</div><div>27</div><div>28</div><div>29</div><div>30</div><div>31</div>
</div>
</div>

<div class="border border-black mt-4">
  <div class="calendar-header text-center">February '26</div>
  <div class="calendar-grid">
    <div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div>
    <div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>

    <div class="sunday">1</div><div class="border">2</div><div class="border">3</div>
    <div class="border">4</div><div class="border">5</div><div class="border">6</div><div class="border">7</div>

    <div class="sunday">8</div><div class="border">9</div><div class="border">10</div>
    <div class="border">11</div><div class="border">12</div><div class="border">13</div><div class="border">14</div>

    <div class="sunday">15</div><div class="border">16</div><div class="border">17</div>
    <div class="border">18</div><div class="border">19</div><div class="border">20</div><div class="border">21</div>

    <div class="sunday">22</div><div class="border">23</div><div class="border">24</div>
    <div class="border">25</div><div class="border">26</div><div class="border">27</div><div class="border">28</div>
  </div>
</div>

<!-- ===== FEBRUARY 2026 ===== -->
<div class="border border-black mt-4">
  <div class="calendar-header text-center">February '26</div>
  <div class="calendar-grid">
    <div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div>
    <div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>

    <div class="sunday">1</div><div class="border">2</div><div class="border">3</div>
    <div class="border">4</div><div class="border">5</div><div class="border">6</div><div class="border">7</div>

    <div class="sunday">8</div><div class="border">9</div><div class="border">10</div>
    <div class="border">11</div><div class="border">12</div><div class="border">13</div><div class="border">14</div>

    <div class="sunday">15</div><div class="border">16</div><div class="border">17</div>
    <div class="border">18</div><div class="border">19</div><div class="border">20</div><div class="border">21</div>

    <div class="sunday">22</div><div class="border">23</div><div class="border">24</div>
    <div class="border">25</div><div class="border">26</div><div class="border">27</div><div class="border">28</div>
  </div>
</div>

<!-- ===== MARCH 2026 ===== -->
<div class="border border-black mt-4">
  <div class="calendar-header text-center">March '26</div>
  <div class="calendar-grid">
    <div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div>
    <div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>

    <div class="sunday">1</div><div>2</div><div>3</div><div>4</div><div>5</div><div>6</div><div>7</div>
    <div class="sunday">8</div><div>9</div><div>10</div><div>11</div><div>12</div><div>13</div><div>14</div>
    <div class="sunday">15</div><div>16</div><div>17</div><div>18</div><div>19</div><div class="holiday">20</div><div class="holiday">21</div>
    <div class="sunday">22</div><div>23</div><div>24</div><div>25</div><div>26</div><div>27</div><div>28</div>
    <div class="sunday">29</div><div>30</div><div>31</div>
  </div>
</div>

<!-- ===== APRIL 2026 ===== -->
<div class="border border-black mt-4">
  <div class="calendar-header text-center">April '26</div>
  <div class="calendar-grid">
    <div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div>
    <div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>

    <div></div><div></div><div></div><div>1</div><div class="holiday">2</div><div class="holiday">3</div><div class="holiday">4</div>
    <div class="sunday holiday">5</div><div>6</div><div>7</div><div>8</div><div class="holiday">9</div><div>10</div><div>11</div>
    <div class="sunday">12</div><div>13</div><div>14</div><div>15</div><div>16</div><div>17</div><div>18</div>
    <div class="sunday">19</div><div>20</div><div>21</div><div>22</div><div>23</div><div>24</div><div class="holiday">25</div>
    <div class="sunday">26</div><div>27</div><div>28</div><div class="holiday">29</div><div>30</div>
  </div>
</div>

<!-- ===== MAY 2026 ===== -->
<div class="border border-black mt-4">
  <div class="calendar-header text-center">May '26</div>
  <div class="calendar-grid">
    <div class="day-label">Su</div><div class="day-label">M</div><div class="day-label">Tu</div>
    <div class="day-label">W</div><div class="day-label">Th</div><div class="day-label">F</div><div class="day-label">Sa</div>

    <div></div><div></div><div></div><div></div><div></div><div class="holiday">1</div><div>2</div>
    <div class="sunday">3</div><div>4</div><div>5</div><div>6</div><div>7</div><div>8</div><div>9</div>
    <div class="sunday">10</div><div>11</div><div>12</div><div>13</div><div>14</div><div>15</div><div>16</div>
    <div class="sunday">17</div><div>18</div><div>19</div><div>20</div><div>21</div><div>22</div><div>23</div>
    <div class="sunday">24</div><div>25</div><div>26</div><div class="holiday">27</div><div>28</div><div>29</div><div>30</div>
    <div class="sunday">31</div>
  </div>
</div>

<div class="activities-center">
<div class="activities-box">
<h4>University Activities</h4>

<strong>1st Semester</strong>
<ul>
<li>August 2025 – Start of Classes</li>
<li>University Orientation Program</li>
<li>September 2025 – Foundation Week</li>
<li>October 2025 – Midterm Examinations</li>
<li>December 2025 – Final Examinations</li>
</ul>

<strong>2nd Semester</strong>
<ul>
<li>January 2026 – Start of Classes</li>
<li>February 2026 – University Week</li>
<li>March 2026 – Midterm Examinations</li>
<li>May 2026 – Final Examinations</li>
</ul>
</div>
</div>

<footer>
© {{ date('Y') }} Pangasinan State University | BSIT Department
</footer>
</div>
</body>
</html>
