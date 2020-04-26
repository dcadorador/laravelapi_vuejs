<?php

namespace App\Http\Middleware;

use Closure;
use \Illuminate\Http\Request;

class AddAuthTokenMiddleware
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
        $token = session('authtoken');
        $request->headers->set('Authorization', "Bearer $token");
        return $next($request);
    }
}
