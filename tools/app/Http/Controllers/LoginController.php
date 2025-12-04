<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('index');
    }

    // Handle login check
    public function checkLogin(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {

        Session::put('logged_user', $user->id);
        Session::put('logged_email', $user->email);

        // Redirect to admin dashboard
        return redirect('/admin/dashboard');
    }

    return back()->withErrors(['Invalid credentials']);
}

    // Logout
   public function logout(Request $request)
{
    Session::forget('logged_user');
    Session::forget('logged_email');

    return redirect('/');
}
}
