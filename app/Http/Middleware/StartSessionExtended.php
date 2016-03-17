<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;

class StartSessionExtended extends StartSession // Extend the base StartSession middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return parent::handle($request, $next); // defer to the right stuff
    }

    /**
     * Store the current URL for the request if necessary.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Session\SessionInterface $session
     * @return void
     */
    protected function storeCurrentUrl(Request $request, $session)
    {
        if (
            $request->method() === 'GET' &&
            $request->route() && !$request->ajax() &&
            $request->route()->getName() != 'files.show' &&
            $request->route()->getName() != '.api' &&
            !str_contains($request->route()->getName(), 'debugbar')
        ) {
            $session->setPreviousUrl($request->fullUrl());
        }
    }
}