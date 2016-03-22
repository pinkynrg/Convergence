<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Libraries\ActivitiesManager as Activity;

class LogRequests
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
        $filters = ['files','favicon','_debugbar','activities','debug'];
        $log = true;

        foreach ($filters as $filter) {
            $log = strpos($request->path(),$filter) === false ? $log : false;
        }

        if ($log && !$request->ajax() && Auth::user() && $request->path() != "/") {
            Activity::log();
        }
    
        return $next($request);
    }
}
