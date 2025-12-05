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
    // 1. Validation
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 2. Check in DB
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Invalid email or password!');
    }

    // 3. Save Session
    Session::put('logged_user', $user->id);
    Session::put('logged_email', $user->email);

    // 4. SweetAlert success message
    return back()->with('success', 'Login successful! Redirecting...');
}



    // Logout
   public function logout(Request $request)
{
    Session::forget('logged_user');
    Session::forget('logged_email');

    return redirect('/');
}
}
