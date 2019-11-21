<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class TokenAuth
 * @package App\Http\Middleware
 */
class TokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return $next($request);
        // $token = $request->header('X-API-TOKEN');
        // if ('SOME_VALUE' != $token) {
        //     abort(401, 'Your token was not found!');
        // };
        return $next($request);
    }
}
