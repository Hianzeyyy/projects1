<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ElectiveController extends Controller
{
    public function PsuStrategicGoals() { return view('PsuStrategicGoals'); }
    
    public function mission() { return view('mission'); }

    public function syllabus() {
        $courses = [
            // First Year
            ['grade' => '---', 'completion' => '---', 'code' => 'A CC 101', 'description' => 'Introduction to Computing', 'units' => 3.00],
            ['grade' => '---', 'completion' => '---', 'code' => 'A CC 102', 'description' => 'Fundamentals of Programming', 'units' => 3.00],
            ['grade' => '---', 'completion' => '---', 'code' => 'A GE 5', 'description' => 'The Contemporary World', 'units' => 3.00],
            ['grade' => '---', 'completion' => '---', 'code' => 'A GE 6', 'description' => 'Science, Technology and Society', 'units' => 3.00],
            ['grade' => '---', 'completion' => '---', 'code' => 'A GE 7', 'description' => 'Mathematics in the Modern World', 'units' => 3.00],
            ['grade' => '---', 'completion' => '---', 'code' => 'A PE 1', 'description' => 'PATH-FIT (Movement Patterns)', 'units' => 2.00],
            // Second Year
            ['grade' => '---', 'completion' => '---', 'code' => 'A CC 104', 'description' => 'Data Structures and Algorithms', 'units' => 3.00],
            ['grade' => '---', 'completion' => '---', 'code' => 'A OOP 101', 'description' => 'Object Oriented Programming', 'units' => 3.00],
            ['grade' => '---', 'completion' => '---', 'code' => 'A NET 101', 'description' => 'Networking 1 (Fundamentals)', 'units' => 3.00],
            ['grade' => '---', 'completion' => '---', 'code' => 'A WD 101', 'description' => 'Web Development', 'units' => 3.00],
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
        return view('syllabus', compact('courses'));
    }

    public function schedule() {
        $days = [
            ['day' => 'Monday', 'time' => '09:00 AM - 12:00 PM', 'subject' => 'Capstone Project 1', 'room' => 'RM6', 'instructor' => 'Jb Doria'],
            ['day' => 'Monday', 'time' => '02:00 PM - 05:00 PM', 'subject' => 'Integrative Programming and Tech', 'room' => 'RM15', 'instructor' => 'Patrick Tarlit'],
            ['day' => 'Tuesday', 'time' => '08:00 AM - 10:00 AM', 'subject' => 'Information Assurance and Security 1', 'room' => 'RM17', 'instructor' => 'Ma Jo Ann Ventura'],
            ['day' => 'Tuesday', 'time' => '08:00 AM - 10:00 AM', 'subject' => 'Technopreneurship', 'room' => 'RM15', 'instructor' => 'ALVIN UMAGA'],
            ['day' => 'Wednesday', 'time' => '03:00 PM - 05:00 PM', 'subject' => 'Web Systems and Technologies 2', 'room' => 'RM6', 'instructor' => 'WILMAR JENNIE MOTEA'],
            ['day' => 'Thursday', 'time' => '08:00 AM - 11:00 AM', 'subject' => 'Information Assurance and Security 1', 'room' => 'RM17', 'instructor' => 'Ma Jo Ann Ventura'],
            ['day' => 'Friday', 'time' => '08:00 AM - 11:00 AM', 'subject' => 'Mobile Application Development 2', 'room' => 'RM6', 'instructor' => 'Patrick Tarlit'],
        ];
        return view('schedule', compact('days'));
    }

    public function calendar() {
        // Full Activity List from PSU-SCHOOL-CALENDAR-SY-2025-2026-CONTENTS-NOTED-1.pdf
        $events = [
            // FIRST SEMESTER
            ['date' => 'Aug 11-15, 2025', 'event' => 'General Registration (Collegiate)'],
            ['date' => 'Aug 18, 2025', 'event' => 'START OF CLASSES (1st Semester)'],
            ['date' => 'Sep 5, 2025', 'event' => 'Last Day of Adding/Changing Subjects'],
            ['date' => 'Oct 14-17, 2025', 'event' => 'Midterm Examination Week'],
            ['date' => 'Nov 5, 2025', 'event' => 'Last Day of Dropping Subjects'],
            ['date' => 'Dec 11-12, 2025', 'event' => 'Final Exam (Graduating Students)'],
            ['date' => 'Dec 16-19, 2025', 'event' => 'Final Exam (Non-Graduating)'],
            ['date' => 'Dec 19, 2025', 'event' => 'END OF 1st SEMESTER'],

            // SECOND SEMESTER
            ['date' => 'Jan 12-16, 2026', 'event' => 'General Registration (2nd Semester)'],
            ['date' => 'Jan 19, 2026', 'event' => 'START OF CLASSES (2nd Semester)'],
            ['date' => 'Mar 16-19, 2026', 'event' => 'Midterm Examination Week'],
            ['date' => 'May 21-22, 2026', 'event' => 'Final Exam (Graduating Students)'],
            ['date' => 'May 25-29, 2026', 'event' => 'Final Exam (Non-Graduating)'],
            ['date' => 'May 29, 2026', 'event' => 'END OF 2nd SEMESTER'],

            // MID-YEAR
            ['date' => 'Jun 15, 2026', 'event' => 'Start of Mid-Year Classes'],
            ['date' => 'Jul 24, 2026', 'event' => 'End of Mid-Year Classes'],
        ];
        return view('calendar', compact('events'));
    }
}