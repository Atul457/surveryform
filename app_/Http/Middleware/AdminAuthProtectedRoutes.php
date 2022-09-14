<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuthProtectedRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->session()->has('email') || $request->session()->has('is_admin')) return redirect()->back();
        // print_r($request->session()->has('email') );
        // print_r($request->session()->has('is_admin'));
        // die;

        // echo "adminauthprotected";
        // print_r(session()->all());  
        // die;
        return $next($request);
    }
}
