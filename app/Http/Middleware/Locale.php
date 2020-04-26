<?php

namespace App\Http\Middleware;

use Closure;
use App;

/**
 * Locale class
 * This middleware is used to capture the lang attribute in the request and set
 *
 * @access public
 * @author Crestelito Cuyno <crestelito.cuyno@gmail.com>
 */
class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($locale = $request->get('lang')) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
