<?php

namespace App\Http\Middleware;

use Closure;

class CustomAuth
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
    if($request->route()->uri() == 'login' || $request->route()->uri() == 'register') {
      if(!$request->session()->has('api_token')) {
        return $next($request);
      }
      else {
        return redirect('/');
      }
    }
    if($request->route()->uri() == 'logout') {
      if($request->session()->has('api_token')) {
        return $next($request);
      }
      else {
        return redirect('/');
      }
    }
    //return $next($request);


  }
}
