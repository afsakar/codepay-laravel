<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SelectCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->session()->has('company_id')) {
            return redirect()->route('company.select');
        }
        return $next($request);
    }
}
