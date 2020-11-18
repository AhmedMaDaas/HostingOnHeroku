<?php

namespace App\Http\Middleware;

use Closure;

class Shipping
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
        if(auth()->guard('web')->check() && auth()->guard('web')->user()->level === 'company'){
            return $next($request);
        }
        return redirect('shipping/login');
    }
}
