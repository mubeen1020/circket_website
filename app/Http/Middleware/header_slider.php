<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;


use Closure;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Rulesandregulation;

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
         $tournament_name = Tournament::query()->where('season_id','=',0)->where('is_web_display','=',1)->get();
         
         $rulesandregulations =Rulesandregulation::query()
         ->get();

          view()->share('header_sponsor_gallery', $sponsor_gallery);
          view()->share('header_tournament_name', $tournament_name);
          view()->share('header_teams', $teams);
          view()->share('headerrulesandregulations', $rulesandregulations);

        return $next($request);
    }
}
