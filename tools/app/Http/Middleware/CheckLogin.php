<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckLogin
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('logged_user')) {
            return redirect('/');
        }

        return $next($request);
    }
}
