<?php

namespace App\Http\Middleware;

use Closure;

class MallManager
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
        if(auth()->guard('web')->check() && auth()->guard('web')->user()->level === 'mall'){
            return $next($request);
        }
        return redirect('mall-manager/login');
    }
}
