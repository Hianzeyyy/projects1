<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ElectiveController extends Controller
{
    public function landingPage() {
        $university_name = "Pangasinan State University";
        $hero_welcome = "Welcome, PSUnians!";
        $motto = "Region's Premier University of Choice";

        $design = [
            'nav'    => 'background: #003366; border-bottom: 4px solid #FFCC00; padding: 20px;',
            'hero'   => 'background: linear-gradient(135deg, #003366, #001a33); color: white; padding: 80px 0; text-align: center;',
            'card'   => 'background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); height: 100%; border-bottom: 5px solid #003366;',
            'button' => 'background: #003366; color: #FFCC00; border: 2px solid #FFCC00; padding: 10px 25px; border-radius: 50px; text-decoration: none; font-weight: bold; display: inline-block;'
        ];

        $resources = [
            [
                'icon' => '🎯',
                'title' => 'Strategic Goals',
                'desc' => 'Explore PSU\'s commitment to quality education.',
                'link' => route('PsuStrategicGoals'),
                'style' => $design['card']
            ],
            [
                'icon' => '🏛️',
                'title' => 'Identity',
                'desc' => 'Living the PSU core values: Accountability, Quality, and Integrity.',
                'link' => route('mission'),
                'style' => $design['card']
            ],
            [
                'icon' => '📚',
                'title' => 'Curriculum',
                'desc' => 'Access the latest course syllabi and academic requirements.',
                'link' => route('syllabus'),
                'style' => $design['card']
            ],
        ];

        return view('landingPage', compact('university_name', 'hero_welcome', 'resources', 'design', 'motto'));
    }

    public function PsuStrategicGoals() {
        // Official PSU Strategic Goals (8-Point Agenda)
        $goals = [
            ['id' => 1, 'goal' => 'Excellent Student Learning and Development'],
            ['id' => 2, 'goal' => 'Strong Research Culture and Technology Transfer'],
            ['id' => 3, 'goal' => 'Sustainable Extension and Community Engagement'],
            ['id' => 4, 'goal' => 'Enriched Resource Generation and Management'],
            ['id' => 5, 'goal' => 'Efficient and Effective Quality Management System'],
            ['id' => 6, 'goal' => 'Responsive and High-Performing Human Resource'],
            ['id' => 7, 'goal' => 'Modernized Infrastructure and Facilities'],
            ['id' => 8, 'goal' => 'Relevant and Industry-Driven Curriculum'],
        ];

        return view('PsuStrategicGoals', compact('goals'));
    }

    public function mission() {
        // Defining variables to be passed to mission.blade.php
        $vision = "To become a leading industry-driven State University in the ASEAN region by 2030.";

        $mission = "The Pangasinan State University, shall provide a human-centric, resilient, and sustainable academic environment to produce dynamic, responsive, and future-ready individuals capable of meeting the requirements of the local and global communities and industries.";


    $vision = "To be a Region’s Premier University of Choice.";
    $mission = "PSU commits to develop highly principled, morally upright, innovative and globally competent professionals through instruction, research, and extension.";

   $core_values = [
        ['letter' => 'A', 'text' => 'Accountability and Transparency'],
        ['letter' => 'C', 'text' => 'Credibility and Integrity'],
        ['letter' => 'C', 'text' => 'Competence and Commitment to Achieve'],
        ['letter' => 'E', 'text' => 'Excellence in Service Delivery'],
        ['letter' => 'S', 'text' => 'Social and Environmental Responsiveness'],
        ['letter' => 'S', 'text' => 'Spirituality'],
    ];

    $design = [
        'hero' => 'background: linear-gradient(135deg, #003366, #001a33);',
        'nav'  => 'background-color: #002b5c;',
        'accent' => '#ffcc00',
        'card' => 'background: white; border-radius: 20px; border-bottom: 8px solid #ffcc00; box-shadow: 0 15px 35px rgba(0,0,0,0.4);'
    ];

    $university_name = "Pangasinan State University";
    $hero_welcome = "University Identity";

    // LOOK HERE: Added 'hero_welcome' and 'university_name' inside compact()
    return view('mission', compact('vision', 'mission', 'core_values', 'design', 'university_name', 'hero_welcome'));
}

    public function syllabus() {
        $courses = [
            // First Year
            ['grade' => '2.50-', 'completion' => '---', 'code' => 'URD CC 101', 'description' => 'Introduction to Computing', 'units' => 3.00],
            ['grade' => '2.00', 'completion' => '---', 'code' => 'URD CC 102', 'description' => 'Fundamentals of Programming', 'units' => 3.00],
            ['grade' => '1.75', 'completion' => '---', 'code' => 'URD GE 5', 'description' => 'The Contemporary World', 'units' => 3.00],
            ['grade' => '3.00', 'completion' => '---', 'code' => 'URD GE 6', 'description' => 'Science, Technology and Society', 'units' => 3.00],
            ['grade' => '2.75', 'completion' => '---', 'code' => 'URD GE 7', 'description' => 'Mathematics in the Modern World', 'units' => 3.00],
            ['grade' => '1.75', 'completion' => '---', 'code' => 'URD PE 1', 'description' => 'PATH-FIT (Movement Patterns)', 'units' => 2.00],
            // Second Year
            ['grade' => '2.25-', 'completion' => '---', 'code' => 'URD CC 104', 'description' => 'Data Structures and Algorithms', 'units' => 3.00],
            ['grade' => '2.00', 'completion' => '---', 'code' => 'URD OOP 101', 'description' => 'Object Oriented Programming', 'units' => 3.00],
            ['grade' => '2.25', 'completion' => '---', 'code' => 'URD NET 101', 'description' => 'Networking 1 (Fundamentals)', 'units' => 3.00],
            ['grade' => '3.00', 'completion' => '---', 'code' => 'URD WD 101', 'description' => 'Web Development', 'units' => 3.00],
            // Third Year
            ['grade' => '2.25', 'completion' => '1S of 2025', 'code' => 'A CC 106', 'description' => 'Application Development', 'units' => 3.00],
            ['grade' => '2.00', 'completion' => '1S of 2025', 'code' => 'A IM 102', 'description' => 'Information Management 2', 'units' => 3.00],
            ['grade' => '1.75', 'completion' => '1S of 2025', 'code' => 'A MO 101', 'description' => 'Mobile Application Dev 1', 'units' => 3.00],
            ['grade' => '3.00', 'completion' => '1S of 2025', 'code' => 'A SP 101', 'description' => 'Social and Professional issues', 'units' => 3.00],
            ['grade' => '1.50', 'completion' => '1S of 2025', 'code' => 'A WS 101', 'description' => 'Web Systems and Technologies 1', 'units' => 3.00],
            ['grade' => 'NG', 'completion' => '2S of 2025', 'code' => 'A CAP 101', 'description' => 'Capstone Project 1', 'units' => 3.00],
            ['grade' => 'NG', 'completion' => '2S of 2025', 'code' => 'A ELEC 1', 'description' => 'Elective 1 (Web Systems 2)', 'units' => 3.00],
            // Fourth Year
            ['grade' => '---', 'completion' => '1S of 2025', 'code' => 'A OS 101', 'description' => 'Operating System Applications', 'units' => 3.00],
        ];
        // 2. PHP-defined Design System (Pure PHP approach)

    $design = [
        'nav'    => 'background: #003366; border-bottom: 4px solid #FFCC00; padding: 20px;',
        'hero'   => 'background: linear-gradient(135deg, #003366, #001a33); color: white; padding: 80px 0; text-align: center;',
        'card'   => 'background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); border-bottom: 5px solid #003366;',
        'button' => 'background: #003366; color: #FFCC00; border: 2px solid #FFCC00; padding: 10px 25px; border-radius: 50px; text-decoration: none; font-weight: bold; display: inline-block;',
        'body'   => 'background: #f4f7f6; font-family: "Montserrat", sans-serif; margin: 0;'
    ];

    // Menu items for the dropdown
    $menu = [
        ['label' => 'Syllabus', 'route' => 'syllabus'],
        ['label' => 'Strategic Goals', 'route' => 'PsuStrategicGoals'],
        ['label' => 'Mission & Vision', 'route' => 'mission'],
        ['label' => 'Schedule', 'route' => 'schedule'],
    ];

   // Data for the Hero section
    $hero_welcome = "Course Syllabus";
    $university_name = "Pangasinan State University";
    $motto = "Region's Premier University of Choice";

    return view('syllabus', compact('courses', 'design', 'hero_welcome', 'university_name', 'motto'));
}
  public function schedule() {
    // 1. Rename $days to $schedule so compact() can find it
    $schedule = [
        ['day' => 'Monday', 'time' => '09:00 AM - 12:00 PM', 'subject' => 'Capstone Project 1', 'room' => 'RM6', 'instructor' => 'Jb Doria'],
        ['day' => 'Monday', 'time' => '02:00 PM - 05:00 PM', 'subject' => 'Integrative Programming and Tech', 'room' => 'RM15', 'instructor' => 'Patrick Tarlit'],
        ['day' => 'Tuesday', 'time' => '08:00 AM - 10:00 AM', 'subject' => 'Information Assurance and Security 1', 'room' => 'RM17', 'instructor' => 'Ma Jo Ann Ventura'],
        ['day' => 'Tuesday', 'time' => '08:00 AM - 10:00 AM', 'subject' => 'Technopreneurship', 'room' => 'RM15', 'instructor' => 'ALVIN UMAGA'],
        ['day' => 'Wednesday', 'time' => '10:00 AM - 11:00 AM', 'subject' => 'Technopreneurship', 'room' => 'RM6', 'instructor' => 'ALVIN UMAGA'],
        ['day' => 'Wednesday', 'time' => '03:00 PM - 05:00 PM', 'subject' => 'Web Systems and Technologies 2', 'room' => 'RM6', 'instructor' => 'WILMAR JENNIE MOTEA'],
        ['day' => 'Thursday', 'time' => '08:00 AM - 11:00 AM', 'subject' => 'Information Assurance and Security 1', 'room' => 'RM17', 'instructor' => 'Ma Jo Ann Ventura'],
        ['day' => 'Friday', 'time' => '08:00 AM - 11:00 AM', 'subject' => 'Mobile Application Development 2', 'room' => 'RM6', 'instructor' => 'Patrick Tarlit'],
    ];

    $design = ['hero' => 'background: linear-gradient(135deg, #003366, #001a33);'];
    $hero_welcome = "Class Schedule";
    $university_name = "Pangasinan State University";
    $motto = "Region's Premier University of Choice";

    // Now 'schedule' points to the $schedule variable above
    return view('schedule', compact('schedule', 'design', 'hero_welcome', 'university_name', 'motto'));
}

    // ... your other methods (e.g., landingPage, schedule) go here ...

    /**
     * PRIVATE HELPERS
     * These must be INSIDE the class braces for $this to work.
     */
    private function getDesignConfig() {
        return [
            'font_family' => "'Montserrat', sans-serif",
            'glass_mini' => 'background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 10px; padding: 10px; margin-bottom: 20px;',
            'psu_blue' => '#003366',
            'psu_gold' => '#ffcc00',
        ];
    }

    private function renderMiniMonth($monthName, $startDay, $daysInMonth, $highlightDays = []) {
        $html = "<div class='mini-month-box'>";
        $html .= "<div class='month-header'>$monthName</div>";
        $html .= "<table class='mini-table'><thead><tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr></thead><tbody><tr>";
        for ($i = 0; $i < $startDay; $i++) { $html .= "<td></td>"; }
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $class = in_array($day, $highlightDays) ? "class='highlight-day'" : "";
            $html .= "<td $class>$day</td>";
            if (($day + $startDay) % 7 == 0) { $html .= "</tr><tr>"; }
        }
        $html .= "</tr></tbody></table></div>";
        return $html;
    }

    /**
     * MAIN CALENDAR METHOD
     */
    public function calendar() {
        $cfg = $this->getDesignConfig();

        $html = "<!DOCTYPE html><html lang='en'><head>";
        $html .= "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
        $html .= "<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap' rel='stylesheet'>";

        $html .= "<style>
            body {
                background: linear-gradient(135deg, #003366, #001a33);
                background-attachment: fixed;
                font-family: {$cfg['font_family']};
                color: white;
                min-height: 100vh;
                font-size: 18px;
            }
            .navbar {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
                border-bottom: 2px solid {$cfg['psu_gold']};
                padding: 20px 0;
            }
            .navbar-brand { font-weight: 800; font-size: 1.8rem; color: {$cfg['psu_gold']} !important; }
            .nav-link { color: white !important; font-size: 1.2rem; font-weight: 600; margin-left: 20px; }
            .glass-panel { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px); border-radius: 20px; padding: 40px; border: 1px solid rgba(255,255,255,0.1); margin-bottom: 30px; }
            .search-box {
                background: rgba(255, 255, 255, 0.1);
                border: 2px solid {$cfg['psu_gold']};
                color: white;
                border-radius: 12px;
                padding: 20px 25px;
                width: 100%;
                margin-top: 40px;
                margin-bottom: 50px;
                font-weight: bold;
                font-size: 1.4rem;
            }
            .column-label { background: {$cfg['psu_gold']}; color: {$cfg['psu_blue']}; font-weight: 800; text-align: center; padding: 12px; margin-bottom: 20px; border-radius: 8px; font-size: 1.1rem; }
            .detail-section h4 { color: {$cfg['psu_gold']}; border-bottom: 3px solid {$cfg['psu_gold']}; padding-bottom: 15px; margin-top: 40px; font-weight: 800; text-transform: uppercase; font-size: 1.6rem; }
            .detail-row { display: flex; border-bottom: 1px solid rgba(255,255,255,0.1); padding: 18px 0; font-size: 1.2rem; }
            .detail-date { width: 250px; font-weight: 700; color: #62b1f6; flex-shrink: 0; }
            .mini-table { width: 100%; font-size: 0.95rem; color: white; }
            .month-header { background: {$cfg['psu_blue']}; color: {$cfg['psu_gold']}; font-weight: 800; font-size: 1.1rem; padding: 8px; border-radius: 6px; margin-bottom: 8px; }
            .highlight-day { background: {$cfg['psu_gold']}; color: {$cfg['psu_blue']}; font-weight: 800; border-radius: 4px; }
            .mini-month-box { {$cfg['glass_mini']} padding: 15px; }
        </style></head><body>";

        // --- NAVBAR ---
        $html .= "<nav class='navbar navbar-expand-lg mb-5'>
            <div class='container'>
                <a class='navbar-brand' href='#'>PSU PORTAL</a>
                <div class='navbar-nav ms-auto'>
                    <a class='nav-link' href='/'>HOME</a>
                    <a class='nav-link active' href='/calendar' style='color:{$cfg['psu_gold']} !important;'>CALENDAR</a>
                </div>
            </div>
        </nav>";

        $html .= "<div class='container'>";
        $html .= "<h1 class='text-center fw-800 mb-2' style='color:{$cfg['psu_gold']}; font-size: 3.5rem;'>UNIVERSITY CALENDAR</h1>";
        $html .= "<p class='text-center mb-5' style='opacity: 0.8; font-size: 1.3rem;'>Academic Year 2025-2026</p>";
        $html .= "<input type='text' id='calendarSearch' class='search-box' placeholder='Search for exams, holidays, or specific dates...'>";

        // --- GRID ---
        $html .= "<div class='row'>";
        $html .= "<div class='col-md-4'><div class='column-label'>1ST SEMESTER</div>";
        $html .= $this->renderMiniMonth("August '25", 5, 31, [18]);
        $html .= $this->renderMiniMonth("October '25", 3, 31, [14,15,16,17]);
        $html .= $this->renderMiniMonth("December '25", 1, 31, [19]);
        $html .= "</div><div class='col-md-4'><div class='column-label'>MID-YEAR CLASS</div>";
        $html .= $this->renderMiniMonth("June '26", 1, 30, [15]);
        $html .= $this->renderMiniMonth("July '26", 3, 31, [24]);
        $html .= "</div><div class='col-md-4'><div class='column-label'>2ND SEMESTER</div>";
        $html .= $this->renderMiniMonth("January '26", 4, 31, [19]);
        $html .= $this->renderMiniMonth("March '26", 0, 31, [16,17,18,19]);
        $html .= $this->renderMiniMonth("May '26", 5, 31, [29]);
        $html .= "</div></div>";

        // --- DETAILS ---
        $html .= "<div class='glass-panel mt-5 detail-section' id='detailsList'>";
        $html .= "<h4>Academic Term Summary</h4>";

        $data = [
            ['Aug 18 - Dec 19', 'First Semester (100 Days)'],
            ['Jan 19 - May 29', 'Second Semester (100 Days)'],
            ['Jun 15 - Jul 24', 'Mid-Year Class (35 Days)'],
            ['Aug 21, 2025', 'Ninoy Aquino Day (Holiday)'],
            ['Nov 1, 2025', 'All Saints Day (Holiday)'],
            ['Oct 14-17', 'Midterm Examination Week'],
            ['Dec 16-19', 'Final Examination Week']
        ];

        foreach($data as $row) {
            $html .= "<div class='detail-row'><span class='detail-date'>{$row[0]}</span><span>{$row[1]}</span></div>";
        }

        $html .= "</div></div>";

        $html .= "<script>
            document.getElementById('calendarSearch').addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('.detail-row');
                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? 'flex' : 'none';
                });
            });
        </script></body></html>";

        return response($html);
    }
} // End of Class
