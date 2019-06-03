<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\RegisterProcess;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        } elseif ($request->only('email') && RegisterProcess::where('email',$request->only('email')['email'])->where('status','ongoing')->count()) {
           return redirect('/waitReview');
        } 

        return $next($request);
    }
}
