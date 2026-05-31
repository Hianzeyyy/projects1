<?php
// Pure PHP landing page (plain-text only)
header('Content-Type: text/plain; charset=utf-8');
echo "PSU Portal - Landing Page\n";
echo "-------------------------\n";
echo "Welcome to the PSU Portal landing page.\n\n";
echo "Available endpoints:\n";
echo "  /                 -> Home\n";
echo "  /PsuStrategicGoals-> Strategic Goals\n";
echo "  /mission          -> Mission & Vision\n";
echo "  /syllabus         -> Syllabus\n";
echo "  /schedule         -> Schedule\n";
echo "  /calendar         -> Calendar\n";
echo "\nNote: This file is intentionally plain-text PHP (no HTML/CSS).\n";
