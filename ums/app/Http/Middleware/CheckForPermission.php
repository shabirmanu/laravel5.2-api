<?php

namespace App\Http\Middleware;

use Closure;

class CheckForPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  Array $perms
     * @return mixed
     */
    public function handle($request, Closure $next, $perms)
    {

 //       dd(auth()->user()->can('administer-permissions'));
        if(is_array($perms)) {
            if(auth()->check() && auth()->user()->can(explode('|' , $perms))) {
                return $next($request);
            }
        } else {
            if(auth()->check() && auth()->user()->can($perms)) {
                return $next($request);
            }
        }
        abort(403, 'You are not authorized to view this page!');
    }

}
