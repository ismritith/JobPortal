<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
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
        {
         // Check authentication first
         if (!auth()->check()) {
             return redirect()->route('account.login')->with('error', 'Please login first');
         }
     
         // Then check role
         if (auth()->user()->role != 'admin') {
             return redirect()
                    ->route('account.profile')
                    ->with('error', 'You are not authorized to access this page');
         }
     
         return $next($request);
        }
    }
}