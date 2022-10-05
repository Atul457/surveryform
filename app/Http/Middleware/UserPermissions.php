<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Resources\RouteResource;
use Illuminate\Http\Response;

class UserPermissions
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
        abort_unless(auth()->user()->can('access',new RouteResource()),Response::HTTP_FORBIDDEN);
        return $next($request);
    }
}
