<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * IncludeParameter class
 *
 * This middleware is to filter the request if it has the right method
 */
class IncludeParameter
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
        $include = $request->get('include');

        if (!empty($include)) {
            $parts = explode('/', $request->path());

            if (strtolower($request->method()) !== 'get') {
                throw new BadRequestHttpException('invalid http method');
            }
            
            if (count($parts) !== 2) {
                throw new BadRequestHttpException('invalid url');
            }

            //check if every include parameter is available
            if (!$request->route()->controller->checkIncludeParameters($include)) {
                throw new BadRequestHttpException('invalid include parameter');
            }
        }
        return $next($request);
    }
}
