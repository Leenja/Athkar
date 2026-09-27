<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromBrowser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0 , 2);
        app()->setLocale(in_array($browserLocale, ['ar','en']) ? $browserLocale: 'en');
        
        return $next($request);
    }
}
