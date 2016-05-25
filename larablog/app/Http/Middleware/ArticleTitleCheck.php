<?php

namespace App\Http\Middleware;

use Closure;

class ArticleTitleCheck
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
        if($request->session()->has('api_token')) {
            return $next($request);
        }
        abort(403);

    }
}
