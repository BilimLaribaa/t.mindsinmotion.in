<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    // /admin/dashboard
    public function dashboard()
    {
        return view('admin.dashboard'); // use dot notation
    }

    // /admin (optional)
    public function index()
    {
        return view('admin.index'); // if you want an admin index page
    }
}
