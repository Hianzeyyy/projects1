<?php

/**
 * PSU Student Portal - Pure PHP Landing Page
 * Developer: Obrero
 * Role: Render terminal-style UI
 */

// 1. HEADER SECTION
echo str_repeat(" ", 40) . "PSU PORTAL" . str_repeat(" ", 30) . "HOME  ACTIVITIES\n";
echo str_repeat("=", 100) . "\n";
echo str_repeat(" ", 32) . "PANGASINAN STATE UNIVERSITY\n";
echo str_repeat(" ", 30) . "Region's Premier University of Choice\n";
echo str_repeat("-", 100) . "\n\n";

// 2. HERO SECTION
echo str_repeat(" ", 33) . "Welcome, PSUnians!\n";
echo str_repeat(" ", 25) . "Education that works. Nation-building through innovation.\n\n";

// 3. STUDENT RESOURCES (Text-Art Cards)
echo str_repeat(" ", 40) . "STUDENT RESOURCES\n\n";

echo "      ______________________       ______________________       ______________________\n";
echo "     |      [ TARGET ]      |     |      [ TEMPLE ]      |     |       [ BOOK ]       |\n";
echo "     |   Strategic Goals    |     |       Identity       |     |      Curriculum      |\n";
echo "     |                      |     |                      |     |                      |\n";
echo "     | Discover our goals   |     | Learn our core       |     | Track your academic  |\n";
echo "     | for quality education|     | values and heritage. |     | progress and units.  |\n";
echo "     |                      |     |                      |     |                      |\n";
echo "     | [ ?page=mission ]    |     | [ ?page=campuses ]   |     | [ ?page=syllabus ]   |\n";
echo "     |______________________|     |______________________|     |______________________|\n\n";

// 4. FOOTER SECTION
echo str_repeat("-", 100) . "\n";
echo str_repeat(" ", 32) . "FACEBOOK  |  GITHUB  |  LINKEDIN\n";
echo str_repeat(" ", 28) . "Pangasinan State University - Education that works.\n";
echo str_repeat(" ", 30) . "© 2026 PSU Student Portal | Project by Canillo\n";
echo str_repeat("=", 100) . "\n";
