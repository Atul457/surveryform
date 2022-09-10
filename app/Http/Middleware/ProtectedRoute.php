<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProtectedRoute
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
        $inactivated = $request->session()->has('inactivated');
        if($request->session()->has('message'))
            $message = $request->session()->get('message');
        else
            $message = "Your account may have been inactivated";

        if($request->session()->has('email') && !($inactivated)) return $next($request);
        $request->session()->flush();

        if($inactivated) $request->session()->flash('error', $message);
        else $request->session()->flash('error', 'Login to access homepage');
        return redirect('login');

        // $request->session()->has('email') && !())
        // echo $inactivated;
        // echo $request->session()->has('email');

        // die();

        // echo "<pre>";

        // print_r(session()->all());
        // die;
        // return $next($request);
    }
}
