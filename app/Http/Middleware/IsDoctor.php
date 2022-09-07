<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsDoctor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->user_type == 'doctor') {
            return $next($request);
        }else if (auth()->user()->user_type == 'admin') {
            return redirect()->route('doctors');
        }

        return redirect()->route('login'); // If user is not an admin.
    }
}
