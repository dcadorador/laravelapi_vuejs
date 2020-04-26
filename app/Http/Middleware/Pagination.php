<?php

namespace App\Http\Middleware;

use Closure;
use App;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Pagination class
 * This middleware is used to check the presence of page[size] and page[index].
 * It will throw a 400 error if one of the following conditions is true:
 * - page[size] is present but page[index] is missing
 * - page[index] is present but page[size] is missing
 * - the request method is not GET but page options are present
 * - the resource being requested is not index function but the page options are
 *   present
 *
 * @access public
 * @author Ismael Cristal Jr <ismael@devclopan.com>
 */
class Pagination
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
        $page = $request->get('page');

        if (!empty($page)) {
            if (!isset($page['size']) || empty(trim($page['size']))) {
                throw new BadRequestHttpException('missing size');
            }

            if (!isset($page['index']) || empty(trim($page['index']))) {
                throw new BadRequestHttpException('missing index');
            }

            if (strtolower($request->method()) !== 'get') {
                throw new BadRequestHttpException('invalid http method');
            }

            $parts = explode('/', $request->path());

            if (count($parts) !== 2) {
                throw new BadRequestHttpException('invalid url');
            }
        }

        return $next($request);
    }
}
