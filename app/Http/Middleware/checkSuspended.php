<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class checkSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check())
            if (!auth()->user()->is_suspended)
                return $next($request);

        return response()->json([
            'message'   =>  'you are suspended'
        ],403);
    }
}
