<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index()
    {
        // You can load suggestions from the database here if needed
        return view('boses');
    }
}
