<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;


use Closure;
use Illuminate\Http\Request;

class header_slider
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
          $sponsor_gallery =DB::table('sponsors')
                                ->where('isActive', '=', 1)
                                ->get();


         $teams = DB::table('teams')->pluck('name', 'id');
         // dd($teams);
          view()->share('sponsor_gallery', $sponsor_gallery);
          view()->share('teams', $teams);

        return $next($request);
    }
}
