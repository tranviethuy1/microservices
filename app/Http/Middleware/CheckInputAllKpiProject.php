<?php

namespace App\Http\Middleware;

use Closure;

class CheckInputAllKpiProject
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
        $year = $request->year;
        if(!isset($year) || empty($year) || !is_numeric($year)){
            return response()->json(['error' => 'The input type is number'], 400);
        }
        return $next($request);
    }
}
