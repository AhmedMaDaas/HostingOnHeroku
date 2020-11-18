<?php

namespace App\Http\Middleware;

use Closure;

class CheckMalls
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
        if(hasMalls()){
            return $next($request);
        }
        session()->flash('error', 'You don\'t have any mall');
        return redirect('mall-manager/home');
    }
}
