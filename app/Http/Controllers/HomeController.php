<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Display about page
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Display events page
     */
    public function events()
    {
        return view('events');
    }

    /**
     * Display FAQ/Read First page
     */
    public function readFirst()
    {
        return view('read-first');
    }
}
