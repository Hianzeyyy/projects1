<?php

use Illuminate\Support\Facades\Route;

Route::get('/index', function () {
    return view('index');
});


Route::get('/psu-policy', function () {
    return view('psu-policy');
});

Route::get('/psu-info', function () {
    return view('psu-info');
});

Route::get('/bsit-syllabus', function () {
    return view('bsit-syllabus');
});

Route::get('/schedule', function () {
    return view('schedule');
});

Route::get('calendar', function () {
    return view('calendar');
});
