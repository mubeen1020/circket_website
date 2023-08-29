<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\ApiController; // Import the OtherController

use Illuminate\Support\Facades\Response;


use App\Item;

use App\Models\Player;
use App\Models\Tournament;
use App\Models\Fixture;
use App\Models\FixtureScore;
use App\Models\Team;
use App\Models\Ground;
use App\Models\TeamPlayer;
use App\Models\TournamentGroup;
use App\Models\TournamentPlayer;
use App\Models\GalleryImages;
use App\Models\Season;
use App\Models\Sponsor;
use App\Models\Umpire;
use App\Models\Dismissal;
use App\Models\Rulesandregulation;
use App\Models\MatchDismissal;
use App\Models\SeasonSponsor;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use function PHPSTORM_META\type;

class HomeController extends Controller

{

  /**

   * Create a new controller instance.

   *

   * @return void

   */

  public function __construct()

  {


  }


  public function home()
  {
    $tournament = Tournament::query()
      ->where('isActive', 1)
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->selectRaw("name as tournamentname")
      ->selectRaw("isgroup")
      ->selectRaw("id as tournament_id")
      ->get();


    $Season = Season::query()
      ->where('isActive', 1)
      ->where('is_web_display', '=', 1)
      ->selectRaw("name as season_name")
      ->selectRaw("id as season_id")
      ->distinct('id')
      ->get();


    $tournamentArray = [];
    foreach ($tournament->toArray() as $subArray) {
      $subArray['type'] = 'T';
      $tournamentArray[] = $subArray;
    }

    $seasonArray = [];
    foreach ($Season->toArray() as $subArray) {
      $subArray['type'] = 'S';
      $seasonArray[] = $subArray;
    }

    $tournament_season = array_merge($tournamentArray, $seasonArray);

    $ground = Ground::query();
    $ground = $ground->orderBy('id')->get();
    $match_results = Fixture::query()
    ->where('running_inning', '=', 3)
    ->where('isActive', 1)
    ->orderByDesc('id')
    ->limit(10)
    ->get();

    $today = Carbon::now()->toDateString();
    $upcoming_match = Fixture::query()->where('match_startdate', '>=', $today)
      ->where('isActive', 1)
      ->where('running_inning', '=', 0)
      ->orderByDesc('id')
      ->limit(10)
      ->get()
      ->filter(function ($fixture) use ($today) {
        return Carbon::parse($fixture->match_startdate)->greaterThanOrEqualTo($today);
      });
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    $image_slider = GalleryImages::query()
      ->where('is_main_slider', '=', 1)
      ->where('isActive', '=', 1)
      ->get();

    return view('home', compact('tournament', 'tournament_season', 'match_results', 'teams', 'upcoming_match', 'ground', 'image_gallery', 'image_slider'));
  }


  public function balltoballScorecard(int $id)
  {


    $match_results = Fixture::query();
    $match_results->where('id', '=', $id);
    $match_results = $match_results->where('isActive', 1)->orderBy('id')->get();

    $match_data = $match_results->find($id);
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $tournament = Tournament::query()->get()->where('id', '=', $match_results[0]->tournament_id)->pluck(
      'name',
      'id'
    )->first();
    $teams_one = Team::query()->get()->where('id', '=', $match_results[0]->first_inning_team_id)->pluck(
      'name',
      'id'
    )->first();
    $teams_two = Team::query()->get()->where('id', '=', $match_results[0]->second_inning_team_id)->pluck(
      'name',
      'id'
    )->first();

    $teams_oneid = Team::query()->get()->where('id', '=', $match_results[0]->first_inning_team_id)->pluck(
      'id'
    )->first();
    $teams_twoid = Team::query()->get()->where('id', '=', $match_results[0]->second_inning_team_id)->pluck(
      'id'
    )->first();


    $teams_two_player = TeamPlayer::query()
    ->select('player_id', DB::raw('MIN(id) as id'))
    ->where('team_id', $match_results[0]->second_inning_team_id)
    ->groupBy('player_id')
    ->pluck('player_id', 'id');

    $teams_one_player = TeamPlayer::query()
    ->select('player_id', DB::raw('MIN(id) as id'))
        ->where('team_id', $match_results[0]->first_inning_team_id)
        ->groupBy('player_id')
        ->pluck('player_id', 'id');

    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );

    $total_run = FixtureScore::Where('fixture_id', '=', $id)
      ->selectRaw("sum(runs) as total_runs")
      ->selectRaw("inningnumber")
      ->groupBy('inningnumber')
      ->get();

    $total_wickets = FixtureScore::where('fixture_id', '=', $id)
      ->selectRaw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) as total_wickets")
      ->selectRaw("inningnumber")
      ->groupBy('inningnumber')
      ->get();

    $match_total_overs = FixtureScore::where('fixture_id', '=', $id)
      ->selectRaw('max(overnumber) as max_over')
      ->first()
      ->max_over;

    $match_over=Fixture::where('id', '=', $id)
    ->selectRaw('numberofover')
    ->first()
    ->numberofover;

       
    $overs = floor($match_total_overs);
    $balls = ($match_total_overs - $overs) * 10;
    $total_balls = ($overs * 6) + $balls;
    $match_total_overs = $total_balls / 6;
    $match_total_overs = round($match_total_overs, 2);


    $innings = FixtureScore::where('fixture_id', '=', $id)
      ->selectRaw('inningnumber, max(overnumber) as max_over,max(ballnumber) as max_ball')
      ->groupBy('inningnumber')
      ->get();

    $total_overs = array();
    $total_ball=array();

    foreach ($innings as $inning) {
      $overs = $inning->max_over;
      $balls = ($overs - floor($overs)) * 10;
      $total_balls = ($overs * 6) + $balls;
      $total_ball[$inning->inningnumber]=($overs * 6) + $balls;
      $total_over = $total_balls / 6;
      $total_overs[$inning->inningnumber] = round($total_over, 2);
    }


    $match_detail = FixtureScore::Where('fixture_id', '=', $id)
      ->selectRaw("playerId")
      ->selectRaw("balltype")
      ->selectRaw("runs")
      ->selectRaw("overnumber")
      ->selectRaw("ballnumber")
      ->selectRaw("bowlerId")
      ->selectRaw("inningnumber")
      ->get();

      $matchnotouts1 = FixtureScore::where('fixture_id', '=', $id)
      ->where('isout', 0)
      ->where('isActive', 1)
      ->where('inningnumber',1)
      ->select('playerId', \DB::raw('SUM(runs) as total_runs'), \DB::raw('COUNT(id) as total_ball'))
      ->groupBy('playerId')
      ->get();

      $variable1 = 'R';
      $variable2 = 'Wicket';
      $player_balls = FixtureScore::where('fixture_id', '=', $id)
        ->where(function ($query) use ($variable1, $variable2) {
          $query->where('balltype', '=', $variable1)
            ->orWhere('balltype', '=', $variable2);
        })->selectRaw("count(id) as balls")
        ->selectRaw("playerId")->groupBy('playerId')
        ->get()->pluck('balls', 'playerId');

      $matchnotouts2 = FixtureScore::where('fixture_id', '=', $id)
      ->where('isout', 0)
      ->where('isActive', 1)
      ->where('inningnumber',2)
      ->select('playerId', \DB::raw('SUM(runs) as total_runs'), \DB::raw('COUNT(id) as total_ball'))
      ->groupBy('playerId')
      ->get();

      $runnerbowler1 = FixtureScore::where('fixture_id', '=', $id)
      ->where('isActive', 1)
      ->where('inningnumber',1)
      ->select('bowlerid', \DB::raw('SUM(runs) as total_runs'), \DB::raw('max(ballnumber) as max_ball ') ,\DB::raw('COUNT(DISTINCT overnumber) as `total_ball`'),  \DB::raw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) AS total_wickets"))
      ->groupBy('bowlerid')
      ->first();

      $runnerbowler2 = FixtureScore::where('fixture_id', '=', $id)
      ->where('isActive', 1)
      ->where('inningnumber',2)
      ->select('bowlerid', \DB::raw('SUM(runs) as total_runs'),\DB::raw('max(ballnumber) as max_ball ') ,\DB::raw('COUNT(DISTINCT overnumber) as `total_ball`' ), \DB::raw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) AS total_wickets"))
      ->groupBy('bowlerid')
      ->first();

    $team_one_runs = FixtureScore::where('fixture_id', '=', $id)
      ->where('inningnumber', '=', 1)
      ->sum('runs');

    $team_one_overs = $total_overs[1];

    $team_one_run_rate = ($team_one_runs / $team_one_overs);

    $team_two_runs = FixtureScore::where('fixture_id', '=', $id)
      ->where('inningnumber', '=', 2)
      ->sum('runs');

      $team_two_overs = isset($total_overs[2]) ? $total_overs[2] : 0;


      $team_two_run_rate = ($team_two_overs != 0) ? ($team_two_runs / $team_two_overs) : 0;


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();



    return view('ballbyballscorecard', compact('player_balls','innings','team_one_runs','team_two_runs','team_one_run_rate','runnerbowler1','runnerbowler2','match_over','matchnotouts1','matchnotouts2' ,'team_two_run_rate', 'teams_one','teams_oneid','teams_twoid', 'match_total_overs', 'match_data', 'teams_two', 'match_detail', 'match_results', 'teams', 'player', 'total_run', 'total_wickets', 'total_overs', 'tournament', 'teams_two_player', 'teams_one_player', 'image_gallery'));
  }



public function fullScorecard_chart(int $id)
  {
    $match_results = Fixture::query();
    $match_results->where('id', '=', $id);
    $match_results = $match_results->orderBy('id')->get();


    $sum_inning_one = FixtureScore::query()
      ->select(DB::raw('SUM(runs) as over_run'))
      ->where('fixture_id', $id)
      ->where('inningnumber', 1)
      ->groupBy('overnumber')
      ->get()->pluck('over_run')->toArray();
    $sum_inning_one = array_map('intval', $sum_inning_one);
    $sum_inning_two = FixtureScore::query()
      ->select(DB::raw('SUM(runs) as over_run'))
      ->where('fixture_id', $id)
      ->where('inningnumber', 2)
      ->groupBy('overnumber')
      ->get()->pluck('over_run')->toArray();



    $sum_inning_two = array_map('intval', $sum_inning_two);
    $over = array_keys($sum_inning_one);


    foreach ($over as &$element) {
      $element += 1;
    }

    $match_results = Fixture::query();
    $match_results->where('id', '=', $id);
    $match_results = $match_results->where('isActive', 1)->orderBy('id')->get();
    $teams_one = Team::query()->get()->where('id', '=', $match_results[0]->first_inning_team_id)->pluck(
      'name'
    )->first();
    // dd($teams_one);
    $teams_two = Team::query()->get()->where('id', '=', $match_results[0]->second_inning_team_id)->pluck(
      'name',
    )->first();

    $cumulativeScores_ining1 = DB::table('fixture_scores')
    ->orderBy('overnumber')
    ->where('fixture_id', $id)
    ->where('inningnumber', 1)
    ->select('overnumber', DB::raw('SUM(runs) as over_run'))
    ->groupBy('overnumber')
    ->get()
    ->reduce(function ($carry, $score) {
        $cumulativeScore = isset($carry[count($carry) - 1]) ? $carry[count($carry) - 1] + $score->over_run : $score->over_run;
        $carry[] = $cumulativeScore;
        return $carry;
    }, []);

    $cumulativeScores_ining2 = DB::table('fixture_scores')
    ->orderBy('overnumber')
    ->where('fixture_id', $id)
    ->where('inningnumber', 2)
    ->select('overnumber', DB::raw('SUM(runs) as over_run'))
    ->groupBy('overnumber')
    ->get()
    ->reduce(function ($carry, $score) {
        $cumulativeScore = isset($carry[count($carry) - 1]) ? $carry[count($carry) - 1] + $score->over_run : $score->over_run;
        $carry[] = $cumulativeScore;
        return $carry;
    }, []);


    $ran_batsman_1 = FixtureScore::where('fixture_id', $id)
    ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut' OR balltype = 'AddRun' THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as total_runs")
    ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'BYES' OR balltype = 'Wicket' OR balltype='RunOut' THEN 1 ELSE 0 END) as balls")
    ->selectRaw('batsman.fullname')
    ->where('inningnumber', '=', 1)
    ->leftJoin('players as batsman', 'batsman.id', '=', 'fixture_scores.playerId')
    ->groupBy('playerId')
    ->get();


    $ran_batsman_2 = FixtureScore::where('fixture_id', $id)
    ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut' OR balltype = 'AddRun' THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as total_runs, batsman.fullname")
    ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'BYES' OR balltype = 'Wicket' OR balltype='RunOut' THEN 1 ELSE 0 END) as balls")
    ->where('inningnumber', '=', 2)
    ->leftJoin('players as batsman', 'batsman.id', '=', 'fixture_scores.playerId')
    ->groupBy('playerId')
    ->get();


    $ran_type_1 = DB::table('fixture_scores')
    ->select(DB::raw('SUM(runs) as total_runs, runs'))
    ->where('fixture_id', '=', $id)
    ->where('inningnumber', '=', 1)
    ->groupBy('runs')
    ->orderBy('runs')
    ->get();

    $ran_type_2 = DB::table('fixture_scores')
    ->select(DB::raw('SUM(runs) as total_runs, runs'))
    ->where('fixture_id', '=', $id)
    ->where('inningnumber', '=', 2)
    ->groupBy('runs')
    ->orderBy('runs')
    ->get();


    $ran_bowler_1 = FixtureScore::where('fixture_id', $id)
    ->selectRaw("SUM(runs) as total_runs")
    ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'BYES' OR balltype = 'Wicket' OR balltype='RunOut' THEN 1 ELSE 0 END) as balls")
    ->selectRaw('bowler.fullname')
    ->where('inningnumber', '=', 1)
    ->leftJoin('players as bowler', 'bowler.id', '=', 'fixture_scores.bowlerid')
    ->groupBy('bowlerid')
    ->get();
    // dd($ran_bowler_1);

    $ran_bowler_2 = FixtureScore::where('fixture_id', $id)
    ->selectRaw("SUM(RUNS) as total_runs")
    ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'BYES' OR balltype = 'Wicket' OR balltype='RunOut' THEN 1 ELSE 0 END) as balls")
    ->selectRaw('bowler.fullname')
    ->where('inningnumber', '=', 2)
    ->leftJoin('players as bowler', 'bowler.id', '=', 'fixture_scores.bowlerid')
    ->groupBy('bowlerid')
    ->get();


    $extra_runs_1 = FixtureScore::where('fixture_id', '=', $id)
    ->selectRaw('inningnumber')
    ->selectRaw("SUM(CASE WHEN balltype IN ('NBP', 'NB', 'NBB','Run Out (WD)','Run Out (NB)') THEN runs ELSE 0 END) AS NoBalls")
    // ->selectRaw("SUM(CASE WHEN balltype = 'NBP' THEN 1 ELSE 0 END) AS nbp_total_runs")
    ->selectRaw("SUM(CASE  WHEN balltype IN ('WBB', 'WD') THEN runs ELSE 0 END) AS Wide")
    ->selectRaw("SUM(CASE WHEN balltype = 'BYES' THEN runs ELSE 0 END) AS Byes")
    ->where('inningnumber','=',1)
    ->groupBy('inningnumber')
    ->get();

    // dd($extra_runs_1);

    $extra_runs_2 = FixtureScore::where('fixture_id', '=', $id)
    ->selectRaw('inningnumber')
    ->selectRaw("SUM(CASE WHEN balltype IN ('NBP', 'NB', 'NBB','Run Out (WD)','Run Out (NB)') THEN runs ELSE 0 END) AS NoBalls")
    // ->selectRaw("SUM(CASE WHEN balltype = 'NBP' THEN 1 ELSE 0 END) AS nbp_total_runs")
    ->selectRaw("SUM(CASE  WHEN balltype IN ('WBB', 'WD') THEN runs ELSE 0 END) AS Wide")
    ->selectRaw("SUM(CASE WHEN balltype = 'BYES' THEN runs ELSE 0 END) AS Byes")
    ->where('inningnumber','=',2)
    ->groupBy('inningnumber')
    ->get();


    return view('fullScorecard_chart', compact('match_results','cumulativeScores_ining2','cumulativeScores_ining1', 'teams_one', 'teams_two', 'sum_inning_one', 'sum_inning_two', 'id', 'over', 'ran_type_1', 'ran_type_2','ran_batsman_1','ran_batsman_2','ran_bowler_2','ran_bowler_1','extra_runs_2','extra_runs_1'));
  }

  public function fullScorecard_overbyover(int $id)
  {
    $match_results = Fixture::query();
    $match_results->where('id', '=', $id);
    $match_results = $match_results->where('isActive', 1)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $teams_one = Team::query()->get()->where('id', '=', $match_results[0]->first_inning_team_id)->pluck(
      'name',
      'id'
    )->first();
    $teams_two = Team::query()->get()->where('id', '=', $match_results[0]->second_inning_team_id)->pluck(
      'name',
      'id'
    )->first();
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $scores = FixtureScore::query()
    ->where('fixture_id', '=', $id)
    ->groupBy('inningnumber', 'id') 
    ->orderBy('id')
    ->get();

    $scores2 = FixtureScore::query()
    ->where('fixture_id', '=', $id)
    ->where('inningnumber', '=', 2)
    ->groupBy('inningnumber', 'id') 
    ->orderBy('id')
    ->get();
// dd($scores);

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

    return view('score_overbyover', compact('scores','scores2', 'match_results', 'teams', 'player', 'teams_one', 'teams_two', 'image_gallery'));
  }

  public function fullScorecard(int $id)
  {

    $ground = Ground::query();
    $ground = $ground->orderBy('id')->get();
    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    $match_results = Fixture::query();
    $match_results->where('id', '=', $id);
    $match_results = $match_results ->where('isActive',1)->orderBy('id')->get();
    $result = [];
    $match_data = $match_results->find($id);
    $tournamentId = $match_results->first()->tournament_id;
    $tournament = Tournament::query()->where('id', '=', $tournamentId)->get()->pluck(
      'name'
    );
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $teams_one = Team::query()->get()->where('id', '=', $match_results[0]->first_inning_team_id)->pluck(
      'name',
      'id'
    )->first();
    $teams_two = Team::query()->get()->where('id', '=', $match_results[0]->second_inning_team_id)->pluck(
      'name',
      'id'
    )->first();

  
    $player_runs = FixtureScore::where('fixture_id', $id)
    ->select('inningnumber')
    ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut' OR balltype = 'AddRun' THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as total_runs")
    ->selectRaw("SUM(isfour = 1) as total_fours")
    ->selectRaw("SUM(issix = 1) as total_six")
    ->selectRaw("playerId, MIN(fixture_scores.id) as min_id")
    ->groupBy('playerId', 'inningnumber')
    ->orderBy('min_id')
    ->distinct('playerId')
    ->get();

    $variable1 = 'R';
    $variable2 = 'Wicket';
    $variable3 = 'RunOut';
    
    
 
    $player_balls = FixtureScore::where('fixture_id', '=', $id)
      ->where(function ($query) use ($variable1, $variable2,$variable3) {
        $query->where('balltype', '=', $variable1)
          ->orWhere('balltype', '=', $variable2)
          ->orWhere('balltype', '=', $variable3);
      })->selectRaw("count(id) as balls")
      ->selectRaw("playerId")->groupBy('playerId')
      ->get()->pluck('balls', 'playerId');


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

    $total_over = Fixture::where('id', '=', $id)
   ->select('numberofover')
    ->get();
    DB::enableQueryLog();
    $extra_runs = FixtureScore::where('fixture_id', '=', $id)
    ->selectRaw('inningnumber')
    ->selectRaw("SUM(CASE WHEN balltype IN ('NB', 'NBB','Run Out (WD)','Run Out (NB)') THEN runs ELSE 0 END) AS noball_total_runs")
    ->selectRaw("SUM(CASE WHEN balltype = 'NBP' THEN 1 ELSE 0 END) AS nbp_total_runs")
    ->selectRaw("SUM(CASE  WHEN balltype IN ('WBB', 'WD') THEN runs ELSE 0 END) AS wideball_total_runs")
    ->selectRaw("SUM(CASE WHEN balltype = 'BYES' THEN runs ELSE 0 END) AS byes_total_runs")
    ->groupBy('inningnumber')
    ->get();
    //  $query = DB::getQueryLog();
    //                 $query = DB::getQueryLog();
    //         dd($query);

    $totalData=FixtureScore::where('fixture_id', '=', $id)
    ->selectRaw('inningnumber')
    ->selectRaw('COUNT(DISTINCT overnumber) as `max_over` ')
    ->selectRaw("COUNT(CASE WHEN balltype = 'Wicket' OR balltype = 'R' OR balltype = 'RunOut' THEN ballnumber ELSE NULL END) AS max_ball ")
    ->selectRaw("SUM(CASE WHEN isout = 1 THEN 1 ELSE 0 END) AS total_wicket")
    ->selectRaw("SUM(runs) AS total_runs")
    ->groupBy('inningnumber')
    ->get();
    
    $bowler_data = FixtureScore::where('fixture_id', '=', $id)
    ->select('inningnumber')
    ->selectRaw('SUM(runs) as total_runs')
    ->selectRaw("COUNT(CASE WHEN balltype = 'Wicket' OR balltype = 'R' OR balltype = 'RunOut' THEN ballnumber ELSE NULL END) AS max_ball")
    ->selectRaw('COUNT(DISTINCT overnumber) as `over`')
    ->selectRaw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) AS total_wicket")
    ->selectRaw('bowlerid, MIN(id) as min_id')
    ->groupBy('bowlerid', 'inningnumber')
    ->orderBy('min_id')
    ->get();

    $match_dissmissal_runout_name= Dismissal::where('dismissals.name', '=', 'Run out')
    ->selectRaw("dismissals.id as dissmissalname")
    ->get()->pluck('dissmissalname');
    $match_dissmissal_retired_name= Dismissal::where('dismissals.name', '=', 'Retired')
    ->selectRaw("dismissals.id as dissmissalname")
    ->get()->pluck('dissmissalname');
    $bowler_wickets= FixtureScore::where('fixture_scores.fixture_id', '=', $id)
    ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
    ->where('match_dismissals.dismissal_id','!=', $match_dissmissal_runout_name)
    ->where('match_dismissals.dismissal_id','!=', $match_dissmissal_retired_name)
    ->selectRaw("COUNT(match_dismissals.id) AS total_wicket")
    ->selectRaw('fixture_scores.bowlerid')
    ->groupBy('fixture_scores.bowlerid')
    ->get()->pluck('total_wicket','bowlerid');

    $maiden_overs = FixtureScore::where('fixture_id', '=', $id)
    ->select('overnumber', 'bowlerid')
    ->selectRaw('COUNT(DISTINCT overnumber) as maiden_count')
    ->groupBy('overnumber', 'bowlerid')
    ->havingRaw('SUM(runs) = 0')
    ->get()->pluck('maiden_count','bowlerid');

      $fallwickets = FixtureScore::where('fixture_id', '=', $id)
      ->get();

      $match_description = FixtureScore::where('fixture_scores.fixture_id', '=', $id)
      ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
      ->leftJoin('players as player_bowler', 'player_bowler.id', '=', 'fixture_scores.bowlerId')
      ->leftJoin('players as player_filder', 'player_filder.id', '=', 'match_dismissals.outbyplayer_id')
      ->join('dismissals', 'dismissals.id', '=', 'match_dismissals.dismissal_id')
      ->where(function($query) {
          $query->where('fixture_scores.balltype', '=', 'Wicket')
              ->orWhere('fixture_scores.balltype', '=', 'RunOut')
              ->orWhere('fixture_scores.balltype', '=', 'RunOut(WD)')
              ->orWhere('fixture_scores.balltype', '=', 'RunOut(NB)')
              ->orWhere('fixture_scores.balltype', '=', 'R');
      })
      ->where('fixture_scores.isout', '=', 1)
      ->select(
          "dismissals.name as out_description",
          "fixture_scores.bowlerid as bowler_Id",
          "match_dismissals.outbyplayer_id as fielder_id",
          "fixture_scores.playerid as batsman_id",
          "player_filder.fullname as fielder_name",
          "player_bowler.fullname as bowler_name",
          "fixture_scores.inningnumber as inningnumber"
      )
      ->get();
  
  
      
    return view('score_card', compact('player_runs','bowler_wickets','match_description','maiden_overs','fallwickets','total_over' ,'bowler_data','totalData','extra_runs','teams_one', 'teams_two', 'player_balls', 'match_results', 'teams', 'player', 'tournament', 'ground', 'match_data', 'image_gallery'));
  }



  public function downloadCSV(int $id)
  {

    $data = [];

    $ground = Ground::query();
    $ground = $ground->orderBy('id')->get();
    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    $match_results = Fixture::query();
    $match_results->where('id', '=', $id);
    $match_results = $match_results->where('isActive', 1)->orderBy('id')->get();
    $result = [];
    $match_data = $match_results->find($id);
    $tournamentId = $match_results->first()->tournament_id;
    $tournament = Tournament::query()->where('id', '=', $tournamentId)->get()->pluck(
      'name'
    );
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $teams_one = Team::query()->get()->where('id', '=', $match_results[0]->first_inning_team_id)->pluck(
      'name',
      'id'
    )->first();
    $teams_two = Team::query()->get()->where('id', '=', $match_results[0]->second_inning_team_id)->pluck(
      'name',
      'id'
    )->first();


    $player_runs = FixtureScore::Where('fixture_id', '=', $id)
      ->selectRaw("sum(runs) as total_runs")
      ->selectRaw("count(isfour) as total_fours")
      ->selectRaw("count(issix) as total_six")
      ->selectRaw("playerId")
      ->selectRaw("inningnumber")
      ->groupBy('playerId')
      ->groupBy('inningnumber')
      ->get();

      $variable1 = 'R';
      $variable2 = 'Wicket';
      $variable3 = 'RunOut';
      
      
      $player_balls = FixtureScore::where('fixture_id', '=', $id)
        ->where(function ($query) use ($variable1, $variable2,$variable3) {
          $query->where('balltype', '=', $variable1)
            ->orWhere('balltype', '=', $variable2)
            ->orWhere('balltype', '=', $variable3)
;
      })->selectRaw("count(id) as balls")
      ->selectRaw("playerId")->groupBy('playerId')
      ->get()->pluck('balls', 'playerId');;




    $TournamentName =    array($tournament[0] . " : " . $match_results[0]->match_result_description . "  (" . $match_data->match_startdate->format('d-m-Y') . ")");
    array_push($data, $TournamentName);
    $empyt = [];

    ///////////////////////////////////  Team One Bating
    $BattingteamOneName =    array(" ", " ",  $teams_one . " Batting");
    array_push($data, $BattingteamOneName);
    $empyt = [];
    array_push($data, $empyt);
    $TeamsBattingHead =    array("BatsMan",  'How Out',  'Fielder',  'Bowler', "Runs", "Balls", "Fours", "Sixex");


    array_push($data, $TeamsBattingHead);
    array_push($data, $empyt);

    foreach ($player_runs as $item) {
      if ($item->inningnumber == 1) {
        $player_detail1 = array($player[$item->playerId], "L Tucker", "C Young", "G Dockrell", $item->total_runs, $player_balls[$item->playerId], $item->total_fours, $item->total_six);
        array_push($data, $player_detail1);
      }
    }

    //////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////  Team two Bowling


    array_push($data, $empyt);
    $BowlingteamTwoName =    array(" ", " ",  $teams_two . " Bowling");
    array_push($data, $BowlingteamTwoName);
    array_push($data, $empyt);
    $TeamsBowlingHead =    array("Bowler",  'Overs',  'Madiens',  'Runs', "Wickets",  'Wides',   'No Balls',   'Hattricks',  'Dot Balls');
    array_push($data, $TeamsBowlingHead);

    //////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////  Team one Fall of wickets

    array_push($data, $empyt);
    $teamOneNFallOfWickets =    array($teams_one . " Fall Of Wickets");
    array_push($data, $teamOneNFallOfWickets);
    array_push($data, $empyt);

    //////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////  Team two Batting



    array_push($data, $empyt);
    array_push($data, $empyt);

    $BattingteamTwoName =    array(" ", " ",  $teams_two . " Batting");
    array_push($data, $BattingteamTwoName);
    $empyt = [];
    array_push($data, $empyt);

    foreach ($player_runs as $item) {
      if ($item->inningnumber == 2) {
        $player_detail2 = array($player[$item->playerId], "L Tucker", "C Young", "G Dockrell", $item->total_runs, $player_balls[$item->playerId], $item->total_fours, $item->total_six);
        array_push($data, $player_detail2);
      }
    }
    //////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////  Team one Bowling

    array_push($data, $empyt);
    $BowlingteamOneName =    array(" ", " ",  $teams_one . " Bowling");
    array_push($data, $BowlingteamOneName);
    array_push($data, $empyt);
    array_push($data, $TeamsBowlingHead);

    //////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////  Team two Fall of wickets
    array_push($data, $empyt);
    $teamTwoNFallOfWickets =    array($teams_two . " Fall Of Wickets");
    array_push($data, $teamTwoNFallOfWickets);
    array_push($data, $empyt);

    //////////////////////////////////////////////////////////////////////////////////////////




    $file = fopen('php://temp', 'w');

    // Write data to the file
    foreach ($data as $row) {
      fputcsv($file, $row);
    }

    // Set the file headers
    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="scoreCard.csv"',
    ];

    // Create the response and return it
    return Response::stream(function () use ($file) {
      rewind($file);
      echo stream_get_contents($file);
      fclose($file);
    }, 200, $headers);

  }


  public function search_player()
  {
    $match_results = Fixture::query();
    $match_results->where('isActive', 1)->where('running_inning', '=', 3);
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );


    $teams_only = Team::query()->where('isclub', '=', 0)->get()->pluck(
      'name',
      'id'
    );


    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'name',
      'id'
    );



    $match_results = $match_results->where('isActive', 1)->orderBy('id')->get();
    $result = [];

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    return view('search_player', compact('result', 'match_results', 'teams', 'image_gallery', 'clubs', 'teams_only'));
  }



  public function team_bowling(int $team_id, int $tournament_id)
  {
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $player = Player::pluck('fullname', 'id');
    $tournamentData = TournamentGroup::where('tournament_id', $tournament_id)->value('tournament_id');
    $playerCount = TournamentPlayer::where('team_id', $team_id)
      ->selectRaw('player_id, COUNT(*) as count')
      ->groupBy('player_id')
      ->get();
    $teamPlayerCount = $playerCount->count();
    $team_resultData = TournamentPlayer::select('tournament_id', 'tournament_players.team_id', 'tournament_players.player_id', 'tournament_players.domain_id', 'team_players.iscaptain')
      ->join('team_players', function ($join) {
        $join->on('tournament_players.team_id', '=', 'team_players.team_id');
        $join->on('tournament_players.player_id', '=', 'team_players.player_id');
      })
      ->where('tournament_players.team_id', $team_id)
      ->get()
      ->groupBy('player_id')
      ->map(function ($group) {
        return $group->first();
      });
    $teamPlayers = TeamPlayer::where('team_id', $team_id)->get();
    $teamData = Team::where('id', '=', $team_id)->selectRaw("name")->get();
    $match_results = Fixture::where('id', '=', $team_id)->where('isActive', 1)->orderBy('id')->get();
    $tournament = Tournament::pluck('name', 'id');
    $team_bowlingdata = TournamentPlayer::where('tournament_players.team_id', $team_id)
      ->where('tournament_players.tournament_id', '=', $tournament_id)
      ->where('fixtures.isActive', '=', 1)
      ->selectRaw('fixture_scores.bowlerId as bowler_id')
      ->selectRaw('team_players.player_id')
      ->selectRaw('fixture_scores.bowlerid')
      ->selectRaw('team_players.team_id')
      ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches')
      ->selectRaw('COUNT(DISTINCT fixture_scores.ballnumber) as total_overs')
      ->selectRaw('SUM(fixture_scores.balltype = "WD") as total_wides')
      ->selectRaw('SUM(fixture_scores.balltype = "NB") as total_noball')
      ->selectRaw('SUM(fixture_scores.runs) as total_runs')
      ->selectRaw('SUM(fixture_scores.isout = 1) as total_wickets')
      ->join('team_players', function ($join) {
        $join->on('team_players.team_id', '=', 'tournament_players.team_id')
          ->on('team_players.player_id', '=', 'tournament_players.player_id');
      })
      ->join('fixture_scores', 'fixture_scores.bowlerId', '=', 'team_players.player_id')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->groupBy('fixture_scores.bowlerid', 'team_players.team_id', 'fixture_scores.bowlerId')
      ->get();
      $hatricks = [];

      foreach($team_bowlingdata as $bowlerData) {
          $total_hat_tricks = DB::table('fixture_scores AS fs1')
              ->join('fixture_scores AS fs2', function ($join) {
                  $join->on('fs2.fixture_id', '=', 'fs1.fixture_id')
                      ->where('fs2.id', '=', DB::raw('(fs1.id + 1)'))
                      ->where('fs2.isout', '=', 1)
                      ->where('fs2.bowlerid', '=', DB::raw('fs1.bowlerid'));
              })
              ->join('fixture_scores AS fs3', function ($join) {
                  $join->on('fs3.fixture_id', '=', 'fs1.fixture_id')
                      ->where('fs3.id', '=', DB::raw('(fs1.id + 2)'))
                      ->where('fs3.isout', '=', 1)
                      ->where('fs3.bowlerid', '=', DB::raw('fs1.bowlerid'));
              })
              ->leftJoin('fixture_scores AS fs4', function ($join) {
                  $join->on('fs4.fixture_id', '=', 'fs1.fixture_id')
                      ->where('fs4.id', '=', DB::raw('(fs1.id + 3)'))
                      ->where('fs4.isout', '=', 1)
                      ->where('fs4.bowlerid', '=', DB::raw('fs1.bowlerid'));
              })
              ->join('fixtures', 'fixtures.id', '=', 'fs1.fixture_id')
              ->where('fixtures.tournament_id', $tournament_id)
              ->where('fs1.bowlerId', $bowlerData->bowler_id)
              ->where('fixtures.isActive', '=', 1)
              ->where('fs1.isout', '=', 1)
              ->whereNull('fs4.id')
              ->select(DB::raw('COUNT(*) as total_hat_tricks'))
              ->pluck('total_hat_tricks')
              ->toArray();
      
          $hatricks[$bowlerData->bowler_id] = $total_hat_tricks;
      }
      
      // Rest of your code...
      // dd($hatricks);
    // $playerballs = FixtureScore::join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    // ->where('fixtures.tournament_id', $tournament_id)
    // ->selectRaw('COUNT(DISTINCT fixture_scores.id) as `over`,fixture_scores.bowlerid')
    // ->groupBy('fixture_scores.bowlerid')
    // ->get()
    // ->pluck('over', 'bowlerid');

    $variable1 = 'R';
    $variable2 = 'Wicket';
    $variable3 = 'RunOut';
    
    $playerballs = FixtureScore::where('fixtures.tournament_id', $tournament_id)
        ->where(function ($query) use ($variable1, $variable2, $variable3) {
            $query->where('balltype', '=', $variable1)
                ->orWhere('balltype', '=', $variable2)
                ->orWhere('balltype', '=', $variable3);
        })
        ->selectRaw("count(fixture_scores.ballnumber) as `over`, bowlerId") // Added backticks for aliasing
        ->where('fixtures.isActive', 1)
        ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
        ->groupBy('bowlerId')
        ->get()
        ->pluck('over', 'bowlerId');
    



    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $playername = TournamentPlayer::where('tournament_id', $tournament_id)
    ->where('team_id', $team_id)
    ->select('tournament_players.player_id', 'players.fullname')
    ->join('players', 'players.id', '=', 'tournament_players.player_id')
    ->pluck('players.fullname', 'tournament_players.player_id');

    $matchcount = Fixture::where('tournament_id', $tournament_id)
    ->where('fixtures.isActive', '=', 1)
    ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
    ->groupBy('fixture_scores.bowlerid')
    ->selectRaw('COUNT(DISTINCT(fixture_scores.fixture_id)) as matches_played, CAST(fixture_scores.bowlerid AS UNSIGNED) as playerId')
    ->pluck('matches_played', 'playerId');

    $team_ids = is_array($team_id) ? $team_id : [$team_id];

    $playermatch = DB::table(function ($query) use ($team_ids, $tournament_id) {
            $query->select('team_id_a AS team_id')
                ->from('fixtures')
                ->whereIn('team_id_a', $team_ids)
                ->where('tournament_id', $tournament_id)
                ->where('fixtures.isActive', '=', 1)
                ->whereIn('running_inning', [ 3,1,2])
                ->unionAll(
                    DB::table('fixtures')
                        ->select('team_id_b AS team_id')
                        ->whereIn('team_id_b', $team_ids)
                        ->where('tournament_id', $tournament_id)
                        ->where('fixtures.isActive', '=', 1)
                        ->whereIn('running_inning', [ 3,1,2])
                );
        }, 'subquery')
            ->select('team_id', DB::raw('COUNT(*) AS count'))
            ->groupBy('team_id')
            ->get()
            ->pluck('count', 'team_id');

     $playerruns =Fixture::where('tournament_id', $tournament_id)
     ->where('fixtures.isActive', '=', 1)
     ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
     ->groupBy('fixture_scores.bowlerid')
     ->selectRaw('SUM(fixture_scores.runs) as playerurns, CAST(fixture_scores.bowlerid AS UNSIGNED) as playerId')
     ->pluck('playerurns', 'playerId');

     $playerteam = TournamentPlayer::where('tournament_players.tournament_id', $tournament_id)
     ->where('tournament_players.team_id', $team_id)
     ->join('fixtures', 'fixtures.tournament_id', '=', 'tournament_players.tournament_id')
     ->groupBy('tournament_players.player_id', 'tournament_players.team_id')
     ->selectRaw('tournament_players.team_id, tournament_players.player_id')
     ->pluck('tournament_players.team_id', 'tournament_players.player_id');

   
     $match_dissmissal_runout_name= Dismissal::where('dismissals.name', '=', 'Run out')
     ->selectRaw("dismissals.id as dissmissalname")
     ->get()->pluck('dissmissalname');
     $match_dissmissal_Retired_name= Dismissal::where('dismissals.name', '=', 'Retired')
     ->selectRaw("dismissals.id as dissmissalname")
     ->get()->pluck('dissmissalname');

     $playerouts =Fixture::where('tournament_id', $tournament_id)
     ->where('fixtures.isActive', '=', 1)
     ->where('match_dismissals.dismissal_id','!=', $match_dissmissal_runout_name)
     ->where('match_dismissals.dismissal_id','!=', $match_dissmissal_Retired_name)
     ->where('fixture_scores.balltype', '=', 'Wicket')
     ->selectRaw('players.fullname, SUM(fixture_scores.isout = 1) as total_wickets, fixture_scores.bowlerid')
     ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
     ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
     ->join('players', 'players.id', '=', 'fixture_scores.bowlerid')
     ->groupBy('fixture_scores.bowlerid', 'fixture_scores.isout') 
     ->orderbyDesc('total_wickets')
     ->pluck('total_wickets', 'fixture_scores.bowlerid');

     $playerwide =Fixture::where('tournament_id', $tournament_id)
     ->where('fixtures.isActive', '=', 1)
     ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
     ->where('fixture_scores.balltype','=','WD')
     ->groupBy('fixture_scores.bowlerid')
     ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, CAST(fixture_scores.bowlerid AS UNSIGNED) as playerId')
     ->pluck('playeouts', 'playerId');

     $playernoball =Fixture::where('tournament_id', $tournament_id)
     ->where('fixtures.isActive', '=', 1)
     ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
     ->where('fixture_scores.balltype','=','NB')
     ->groupBy('fixture_scores.bowlerid')
     ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, CAST(fixture_scores.bowlerid AS UNSIGNED) as playerId')
     ->pluck('playeouts', 'playerId');

    
     $teamid = Team::where('id', '=', $team_id)->select('id')->get();

     $tournamentgrounds=Fixture::where('tournament_id',$tournament_id)
     ->select('ground_id')
     ->distinct('ground_id')
     ->get()->first();

     $ground = Ground::orderBy('id')->get();
     $ground = $ground->pluck('name', 'id');
    return view('team_bowling', compact('playerouts','tournamentgrounds','ground','teamid','hatricks','playernoball','playerwide','matchcount','playerruns','playerballs','playermatch','playerteam','playername','teams', 'tournamentData', 'tournament_ids', 'player', 'teamPlayerCount', 'team_resultData', 'teamPlayers', 'teamData', 'match_results', 'tournament', 'team_id_data', 'team_bowlingdata', 'image_gallery'));
  }

  public function team_fielding(int $team_id, int $tournament_id)
  {
    $teamid = Team::where('id', '=', $team_id)->select('id')->get();
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $player = Player::pluck('fullname', 'id');
    $ground = Ground::orderBy('id')->get();
    $ground = $ground->pluck('name', 'id');
    $tournamentData = TournamentGroup::where('tournament_id', $tournament_id)->value('tournament_id');
    $playerCount = TournamentPlayer::where('team_id', $team_id)
      ->selectRaw('player_id, COUNT(*) as count')
      ->groupBy('player_id')
      ->get();
    $teamPlayerCount = $playerCount->count();
    $team_resultData = TournamentPlayer::select('tournament_id', 'tournament_players.team_id', 'tournament_players.player_id', 'tournament_players.domain_id', 'team_players.iscaptain')
      ->join('team_players', function ($join) {
        $join->on('tournament_players.team_id', '=', 'team_players.team_id');
        $join->on('tournament_players.player_id', '=', 'team_players.player_id');
      })
      ->where('tournament_players.team_id', $team_id)
      ->get()
      ->groupBy('player_id')
      ->map(function ($group) {
        return $group->first();
      });

    $teamPlayers = TeamPlayer::where('team_id', $team_id)->get();
    $teamData = Team::where('id', '=', $team_id)->selectRaw("name")->get();
    $tournament = Tournament::pluck('name', 'id');
  
    $tournamentdata = Tournament::query()
    ->where('isActive', '=', 1)
    ->where('is_web_display' , '=' , 1)
    ->get()
    ->pluck(
      'name',
      'id'
    );
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $match_results = Fixture::query();
    $match_results->whereIN('running_inning',[3,1,2]);
    $match_results = $match_results->orderBy('id')->get();


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    DB::enableQueryLog();

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');
    $getresult = [];
    $match_count_player = collect();
    $player_runs = collect();
    $balls_faced = collect();
    $sixes = collect();
    $fours = [];

    $match_dissmissal_caught = Dismissal::where('dismissals.name', '=', 'Caught')
      ->first();


      $match_dissmissal_stumped = Dismissal::where('dismissals.name', '=', 'Stumped')
      ->first();

      $dismissalIdcatch = $match_dissmissal_caught->id;
     


    $getresult = [];


    $data = TournamentPlayer::query();
   
    $data->selectRaw('tournament_players.player_id')
      ->selectRaw('tournament_players.team_id');
    
    $data->where('tournament_players.team_id', '=', $team_id);
    $data->where('tournament_players.tournament_id', '=', $tournament_id);
    $match_dismissal_name = Dismissal::where('name', 'Caught')
    ->pluck('id');
    $match_dismissal_Stumped = Dismissal::where('name', 'Stumped')
    ->pluck('id');
    
  
    $catchs_data =MatchDismissal::query()
    ->where('match_dismissals.dismissal_id', $match_dismissal_name)
    ->where('fixtures.isActive', '=', 1)
    ->where('fixtures.tournament_id', $tournament_id)
    ->selectRaw("COUNT(match_dismissals.id) as total_catches,outbyplayer_id")
    ->join('fixtures', 'fixtures.id', '=', 'match_dismissals.fixture_id')
    ->groupBy('match_dismissals.outbyplayer_id')
    ->get()->pluck('total_catches', 'outbyplayer_id');


      $stump_data = MatchDismissal::query()
      ->where('match_dismissals.dismissal_id', $match_dismissal_Stumped)
      ->where('fixtures.isActive', '=', 1)
      ->where('fixtures.tournament_id', $tournament_id)
      ->selectRaw("COUNT(match_dismissals.id) as total_catches,outbyplayer_id")
      ->join('fixtures', 'fixtures.id', '=', 'match_dismissals.fixture_id')
      ->groupBy('match_dismissals.outbyplayer_id')
      ->get()->pluck('total_stump', 'outbyplayer_id');


$getresult = $data->get();
  $match_count = DB::table(function ($query) use ($team_id, $tournament_id) {
      $query->select('team_id_a AS team_id')
          ->from('fixtures')
          ->where('team_id_a', $team_id)
          ->where('tournament_id', $tournament_id)
          ->where('fixtures.isActive', '=', 1)
          ->whereIN('running_inning',[ 3,1,2])
          ->unionAll(
              DB::table('fixtures')
                  ->select('team_id_b AS team_id')
                  ->where('team_id_b', $team_id)
                  ->where('tournament_id', $tournament_id)
                  ->where('fixtures.isActive', '=', 1)
                  ->whereIN('running_inning',[ 3,1,2])
          );
  }, 'subquery')
      ->select('team_id', DB::raw('COUNT(*) AS count'))
      ->groupBy('team_id')
      ->get()->pluck('count','team_id');

      $player_id = Player::select('id')
      ->groupBy('id')
      ->get();
  
  $match_dismissal_name = Dismissal::where('name', 'Caught')
      ->select('id')
      ->pluck('id');
  
  $player_cauches = [];
  foreach ($player_id as $data) {
      $total_catches = FixtureScore::join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
          ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
          ->where('fixtures.tournament_id', $tournament_id)
          ->where('match_dismissals.outbyplayer_id', $data->id)
          ->whereIn('match_dismissals.dismissal_id', $match_dismissal_name)
          ->selectRaw('COUNT(match_dismissals.id) as total_catches')
          ->groupBy('match_dismissals.outbyplayer_id')
          ->pluck('total_catches')
          ->first();
  
      $player_cauches[$data->id] = $total_catches ?? 0;
  }
  $tournamentgrounds=Fixture::where('tournament_id',$tournament_id)
     ->select('ground_id')
     ->distinct('ground_id')
     ->get()->first();

    return view('team_fielding', compact('stump_data','tournamentgrounds','ground','teamid', 'player_cauches', 'sixes', 'catchs_data', 'player_runs', 'match_count_player', 'player', 'getresult', 'teams', 'tournamentdata', 'match_count',  'image_gallery', 'years' , 'teamData' , 'tournament' , 'tournamentData' , 'team_resultData' , 'teamPlayerCount', 'team_id_data' , 'tournament_ids'));
  }

  public function searchplayer_form_submit(Request $request)
  {

    $match_results = Fixture::query();
    $match_results->where('isActive', 1)->where('running_inning', '=', 3);
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );


    $teams_only = Team::query()->where('isclub', '=', 0)->get()->pluck(
      'name',
      'id'
    );


    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'name',
      'id'
    );


    $player = Player::query()
    ->select('players.fullname','players.battingstyle','players.bowlingstyle','players.email','players.id','team_players.team_id','teams.name','players.contact')
    ;

    $term = $request;
    if (!empty($term->fullname)) {
      $player->where('fullname', 'like', '%' . $term['fullname'] . '%');
    }

    if (!empty($term->battingStyle)) {
      $player->where('battingstyle', 'like', '%' . $term['battingStyle'] . '%');
    }

    if (!empty($term->bowlingStyle)) {
      $player->where('bowlingstyle', 'like', '%' . $term['bowlingStyle'] . '%');
    }

    if (!empty($term->emailId)) {
      $player->where('email', 'like', '%' . $term['emailId'] . '%');
    }


    if (!empty($term->team_name)) {
      $teamPlayers = TeamPlayer::where('team_id', $term->team_name)->pluck('player_id')->toArray();
      $player->whereIn('players.id', $teamPlayers);
    }

    if (!empty($term->club)) {
      $teamPlayers = TeamPlayer::where('team_id', $term->club)->pluck('player_id')->toArray();
      $player->whereIn('players.id', $teamPlayers);
    }



    if (!empty($term['gender'])) {
      $player->where('gender', '=', $term['gender']);
    }

    $result = $player
      ->leftjoin('team_players', 'team_players.player_id', '=', 'players.id')
      ->leftjoin('teams', 'teams.id', '=', 'team_players.team_id')
      ->distinct('players.id')->get();

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();



    return view('search_player', compact('result', 'match_results', 'image_gallery', 'teams', 'clubs', 'teams_only'));
  }

  public function result()
  {
    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');
    $match_results = Fixture::query()->orderBy('id')->get();
    $data = Fixture::where('running_inning', '=', 3);

    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );

    $results = [];
    // dd($results);
    //         $query = DB::getQueryLog();
    //         $query = DB::getQueryLog();
    // dd($query);
    $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );

    $total_runs = [];
    $total_wicket_fixture = [];
    $total_over_fixture = [];
    $total_ball_fixture = [];
   

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

    return view('result', compact('results', 'clubs', 'teams', 'match_results', 'years', 'tournament', 'total_runs', 'total_wicket_fixture', 'image_gallery'));
  }

  public function result_form_submit(Request $request)
  {
    DB::enableQueryLog();

    if ($request->method() !== 'POST') {
      abort(405, 'Method Not Allowed');
    }
    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

    $match_results = Fixture::query()->orderBy('id')->get();
    $data = Fixture::where('running_inning', '=', 3)->where('fixtures.isActive', '=', 1);
    $term = $request;

    if (!empty($term->created_at) && strtotime($term->created_at)) {
      $startDate = date('Y-m-d', strtotime($term->created_at));
      $data->whereDate('match_startdate', '>=', $startDate);
    }
    
    if (!empty($term->end_at) && strtotime($term->end_at)) {
      $endDate = date('Y-m-d', strtotime($term->end_at));
      $data->whereDate('match_enddate', '<=', $endDate);
    }
    
    if (!empty($term['year'])) {
      $year = $term['year'];
      $data->whereRaw("YEAR(match_startdate) = $year");
    }
    $tournamentsid = $term['tournament'];
    if (!empty($term['tournament'])) {
      $tournaments = $term['tournament'];
      $data->where('tournament_id', '=', $tournaments);
    }
    if (!empty($term['teams'])) {
      $team = $term['teams'];
      $data->where('team_id_a', '=', $team)
        ->oRWhere('team_id_b', '=', $team)
        ->where('fixtures.isActive', '=', 1);
      $data->where('tournament_id', '=', $tournamentsid);
    }
    if (!empty($term->club)) {
      $club = $term->club;
      $data->where('team_id_a', '=', $club)
        ->Where('team_id_b', '=', $club);
    }

    

    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );

    $results = $data ->get();
    // dd($results);
    //         $query = DB::getQueryLog();
    //         $query = DB::getQueryLog();
    // dd($query);
    $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );

    $total_runs = [];
    $total_wicket_fixture = [];
    $total_over_fixture = [];
    $total_ball_fixture = [];
    foreach ($results as $result) {
      $match_summary = FixtureScore::where('fixture_id', '=', $result->id)
        ->selectRaw("SUM(CASE WHEN isout = 1 THEN isout ELSE 0 END) as total_wickets")
        ->selectRaw('inningnumber')
        ->selectRaw('COUNT(DISTINCT overnumber) as `max_over` ')
        ->selectRaw("COUNT(CASE WHEN balltype = 'Wicket' OR balltype = 'R' OR balltype = 'RunOut' THEN ballnumber ELSE NULL END) AS max_ball ")
        ->selectRaw('SUM(runs) as total_runs')
        ->selectRaw("inningnumber")
        ->groupBy('inningnumber')
        ->get();

      if (count($match_summary) == 2) {
        $total_wicket_fixture[$result->id] = [$match_summary[0]['total_wickets'], $match_summary[1]['total_wickets']];
        $total_over_fixture[$result->id] = [$match_summary[0]['max_over'], $match_summary[1]['max_over']];
        $total_ball_fixture[$result->id] = [$match_summary[0]['max_ball'], $match_summary[1]['max_ball']];
        $total_runs[$result->id] = [$match_summary[0]['total_runs'], $match_summary[1]['total_runs']];
      }
    }

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();


    return view('result', compact('results', 'clubs', 'teams', 'match_results', 'years', 'tournament', 'total_over_fixture', 'total_ball_fixture','total_runs', 'total_wicket_fixture', 'image_gallery'));
  }



  public function team_view(int $team_id, int $tournament_id)
  {

    $teamid = Team::where('id', '=', $team_id)->select('id')->get();
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $ground = Ground::orderBy('id')->get();
    $ground = $ground->pluck('name', 'id');
    $tournament = Tournament::pluck('name', 'id');
    $match_results = Fixture::where('id', '=', $team_id)->where('isActive', 1)->orderBy('id')->get();

    $player = Player::pluck('fullname', 'id');
    $team = Team::where('id', '=', $team_id)->orderBy('id')->get();
    $tournamentData = TournamentGroup::where('tournament_id', $tournament_id)->value('tournament_id');
    $playerCount = TournamentPlayer::where('team_id', $team_id)
      ->selectRaw('player_id, COUNT(*) as count')
      ->groupBy('player_id')
      ->get();
    $teamPlayerCount = $playerCount->count();

    $teamPlayers = TeamPlayer::where('team_id', $team_id)->get();
    $teamData = Team::where('id', '=', $team_id)->selectRaw("name")->get();

    $team_resultData = TournamentPlayer::select('tournament_id', 'tournament_players.team_id', 'tournament_players.player_id', 'tournament_players.domain_id', 'team_players.iscaptain')
      ->join('team_players', function ($join) {
        $join->on('tournament_players.team_id', '=', 'team_players.team_id');
        $join->on('tournament_players.player_id', '=', 'team_players.player_id');
      })
      ->where('tournament_players.tournament_id', $tournament_id)
      ->where('tournament_players.team_id', $team_id)
      ->get()
      ->groupBy('player_id')
      ->map(function ($group) {
        return $group->first();
      });

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

      $tournamentgrounds=Fixture::where('tournament_id',$tournament_id)
      ->select('ground_id')
      ->distinct('ground_id')
      ->get()->first();
    return view('team_view', compact('team_id_data', 'teamid','tournament_ids', 'team_resultData', 'teamData', 'playerCount', 'match_results', 'player', 'ground', 'tournamentData', 'tournament', 'teamPlayerCount', 'teamPlayers', "image_gallery",'tournamentgrounds'));
  }

  public function team_result(int $team_id, int $tournament_id)
  {
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $player = Player::pluck('fullname', 'id');
    $tournamentData = TournamentGroup::where('tournament_id', $tournament_id)->value('tournament_id');
    $teamCaptain = TeamPlayer::where('team_id', $team_id)->where('iscaptain', 1)->first();
    $playerCount = TournamentPlayer::where('team_id', $team_id)
      ->selectRaw('player_id, COUNT(*) as count')
      ->groupBy('player_id')
      ->get();
    $teamPlayerCount = $playerCount->count();
    $teamPlayers = TeamPlayer::where('team_id', $team_id)->get();
    $teamData = Team::where('id', '=', $team_id)->selectRaw("name")->get();
    $team_resultData1 = TournamentPlayer::select('tournament_id', 'tournament_players.team_id', 'tournament_players.player_id', 'tournament_players.domain_id', 'team_players.iscaptain')
      ->join('team_players', function ($join) {
        $join->on('tournament_players.team_id', '=', 'team_players.team_id');
        $join->on('tournament_players.player_id', '=', 'team_players.player_id');
      })
      ->where('tournament_players.team_id', $team_id)
      ->get()
      ->groupBy('player_id')
      ->map(function ($group) {
        return $group->first();
      });

    $match_results = Fixture::where('id', '=', $team_id)->where('isActive', 1)->orderBy('id')->get();
    $teams = Team::pluck('name', 'id');
    $tournament = Tournament::where('isActive', '=', 1)->pluck('name', 'id');
    $data = Fixture::query();
    $results = $data->get();

    $team_resultData = TournamentGroup::where('tournament_groups.team_id', $team_id)
        ->where('fixture.isActive', '=', 1)
        ->where('fixture.running_inning', 3)
        ->where(function ($query) use ($team_id) {
            $query->where('fixture.first_inning_team_id', $team_id)
                ->orWhere('fixture.second_inning_team_id', $team_id);
        })
        ->where('tournament_groups.tournament_id', $tournament_id)
        ->selectRaw('DISTINCT fixture.id') 
        ->selectRaw('fixture.created_at')
        ->selectRaw('fixture.second_inning_team_id')
        ->selectRaw('fixture.running_inning')
        ->selectRaw('fixture.numberofover')
        ->selectRaw('fixture.match_result_description')
        ->selectRaw('tournament_groups.tournament_id')
        ->selectRaw('tournament_groups.team_id')
        ->selectRaw('fixture.first_inning_team_id')
        ->join('fixtures as fixture', 'fixture.tournament_id', '=', 'tournament_groups.tournament_id')
        ->orderBy('tournament_groups.tournament_id')
        ->get();

    $total_runs = [];
    $total_wicket_fixture = [];
    $total_over_fixture = [];
    $total_ball_fixture = [];
    foreach ($results as $result) {
      $match_summary = FixtureScore::where('fixture_id', '=', $result->id)
        ->selectRaw("SUM(CASE WHEN isout = 1 THEN isout ELSE 0 END) as total_wickets")
        ->selectRaw('inningnumber')
        ->selectRaw('max(overnumber) as max_over ')
        ->selectRaw("COUNT(CASE WHEN balltype = 'Wicket' OR balltype = 'R' OR balltype = 'RunOut' THEN ballnumber ELSE NULL END) AS max_ball ")
        ->selectRaw('SUM(runs) as total_runs')
        ->selectRaw("inningnumber")
        ->groupBy('inningnumber')
        ->get();

      if (count($match_summary) == 2) {
        $total_wicket_fixture[$result->id] = [$match_summary[0]['total_wickets'], $match_summary[1]['total_wickets']];
        $total_over_fixture[$result->id] = [$match_summary[0]['max_over'], $match_summary[1]['max_over']];
        $total_ball_fixture[$result->id] = [$match_summary[0]['max_ball'], $match_summary[1]['max_ball']];
        $total_runs[$result->id] = [$match_summary[0]['total_runs'], $match_summary[1]['total_runs']];
      }
    }
    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

      $teamid = Team::where('id', '=', $team_id)->select('id')->get();

      $tournamentgrounds=Fixture::where('tournament_id',$tournament_id)
      ->select('ground_id')
      ->distinct('ground_id')
      ->get()->first();

      $ground = Ground::orderBy('id')->get();
      $ground = $ground->pluck('name', 'id');
    return view('team_result', compact('teamid','ground','tournamentgrounds','total_ball_fixture','tournamentData', 'tournament_ids', 'player', 'teamCaptain', 'teamPlayerCount', 'teamPlayers', 'team_resultData', 'team_resultData1', 'teamData', 'match_results',  'tournament', 'total_runs', 'total_wicket_fixture', 'team_id_data', 'image_gallery','total_over_fixture'));
  }


  public function team_schedule(int $team_id, int $tournament_id)
  {
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $tournament = Tournament::where('isActive', '=', 1)->pluck('name', 'id');
    $ground = Ground::orderBy('id')->get();
      $ground = $ground->pluck('name', 'id');
    $player = Player::pluck('fullname', 'id');
    $tournamentData = TournamentGroup::where('tournament_id', $tournament_id)->value('tournament_id');
    $playerCount = TournamentPlayer::where('team_id', $team_id)
      ->selectRaw('player_id, COUNT(*) as count')
      ->groupBy('player_id')
      ->get();
    $teamPlayerCount = $playerCount->count();
    $team_resultData = TournamentPlayer::select('tournament_id', 'tournament_players.team_id', 'tournament_players.player_id', 'tournament_players.domain_id', 'team_players.iscaptain')
      ->join('team_players', function ($join) {
        $join->on('tournament_players.team_id', '=', 'team_players.team_id');
        $join->on('tournament_players.player_id', '=', 'team_players.player_id');
      })
      ->where('tournament_players.team_id', $team_id)
      ->get()
      ->groupBy('player_id')
      ->map(function ($group) {
        return $group->first();
      });
    $teamPlayers = TeamPlayer::where('team_id', $team_id)->get();
    $teamData = Team::where('id', '=', $team_id)->selectRaw("name")->get();
    $today = Carbon::now()->toDateString();
    $match_results = Fixture::where('team_id_a', $team_id)
      ->where('isActive', 1)
      ->orWhere('team_id_b', $team_id)
      ->orderBy('match_startdate')
      ->get();

    $team_scheduleData = TournamentPlayer::where(function ($query) use ($team_id, $today, $tournament_id) {
      $query->where('tournament_players.team_id', $team_id)
        ->where('fixtures.match_startdate', '>=', $today);
    })
    ->where(function ($query) use ($team_id) {
      $query->where('fixtures.team_id_a', $team_id)
          ->orWhere('fixtures.team_id_b', $team_id);
  })
      ->where('tournament_players.tournament_id', '=', $tournament_id)
      ->where('fixtures.running_inning', '=', 0)
      ->where('fixtures.isActive', 1)
      ->distinct('fixtures.id')
      ->selectRaw('fixtures.id, fixtures.team_id_a, fixtures.tournament_id, fixtures.team_id_b, fixtures.running_inning, fixtures.numberofover, fixtures.match_startdate, fixtures.match_result_description')
      ->join('fixtures', 'fixtures.tournament_id', '=', 'tournament_players.tournament_id')
      ->orderBy('fixtures.match_startdate')
      ->get()
      ->filter(function ($fixture) use ($today) {
        return Carbon::parse($fixture->match_startdate)->greaterThanOrEqualTo($today);
      });


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

      $teamid = Team::where('id', '=', $team_id)->select('id')->get();

      $tournamentgrounds=Fixture::where('tournament_id',$tournament_id)
      ->select('ground_id')
      ->distinct('ground_id')
      ->get()->first();
    return view('team_schedule', compact('teamData','tournamentgrounds','teamid', 'tournament_ids', 'match_results', 'player', 'ground', 'tournamentData', 'tournament', 'teamPlayerCount', 'team_resultData', 'teamPlayers', 'team_id_data', 'team_scheduleData', "image_gallery"));
  }

  public function team_batting(int $team_id, int $tournament_id)
  {
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $tournament = Tournament::where('isActive', '=', 1)->pluck('name', 'id');
    $ground = Ground::orderBy('id')->get();
      $ground = $ground->pluck('name', 'id');
    $player = Player::pluck('fullname', 'id');
    $tournamentData = TournamentGroup::where('tournament_id', $tournament_id)->value('tournament_id');
    $playerCount = TournamentPlayer::where('team_id', $team_id)
      ->selectRaw('player_id, COUNT(*) as count')
      ->groupBy('player_id')
      ->get();
    $teamPlayerCount = $playerCount->count();
    $team_resultData = TournamentPlayer::select('tournament_id', 'tournament_players.team_id', 'tournament_players.player_id', 'tournament_players.domain_id', 'team_players.iscaptain')
      ->join('team_players', function ($join) {
        $join->on('tournament_players.team_id', '=', 'team_players.team_id');
        $join->on('tournament_players.player_id', '=', 'team_players.player_id');
      })
      ->where('tournament_players.team_id', $team_id)
      ->get()
      ->groupBy('player_id')
      ->map(function ($group) {
        return $group->first();
      });

      $higest_score=[];
      foreach($team_resultData as $data){
      $higest_score_query = FixtureScore::where('playerId', $data->player_id)
      ->where('fixtures.tournament_id', $tournament_id)
      ->where('fixtures.isActive', 1)
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as total_runs, fixture_id")
      ->groupBy('fixture_id')
      ->orderbydesc('total_runs')
      ->limit(1);
        $higest_score[$data->player_id]= $higest_score_query->value('total_runs');
    }
//  dd($team_resultData);
    $teamPlayers = TeamPlayer::where('team_id', $team_id)->get();
    $teamData = Team::where('id', '=', $team_id)->selectRaw("name")->get();
    $today = Carbon::now()->toDateString();
    $match_results = Fixture::where('team_id_a', $team_id)
      ->where('isActive', 1)
      ->orWhere('team_id_b', $team_id)
      ->orderBy('match_startdate')
      ->get();
  
      $playername = TournamentPlayer::where('tournament_id', $tournament_id)
      ->where('team_id', $team_id)
      ->select('tournament_players.player_id', 'players.fullname')
      ->join('players', 'players.id', '=', 'tournament_players.player_id')
      ->pluck('players.fullname', 'tournament_players.player_id');

    

      $matchcount = Fixture::where('tournament_id', $tournament_id)
      ->where('fixtures.isActive', 1)
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->groupBy('fixture_scores.playerId')
      ->selectRaw('COUNT(DISTINCT(fixture_scores.fixture_id)) as matches_played,fixture_scores.playerId  as playerId')
      ->pluck('matches_played', 'playerId');

      // dd( $matchcount);

      $team_ids = is_array($team_id) ? $team_id : [$team_id];

$playermatch = DB::table(function ($query) use ($team_ids, $tournament_id) {
        $query->select('team_id_a AS team_id')
            ->from('fixtures')
            ->whereIn('team_id_a', $team_ids)
            ->where('tournament_id', $tournament_id)
            ->where('fixtures.isActive', 1)
            ->whereIN('running_inning',[ 3,1,2])
            ->unionAll(
                DB::table('fixtures')
                    ->select('team_id_b AS team_id')
                    ->whereIn('team_id_b', $team_ids)
                    ->where('tournament_id', $tournament_id)
                    ->where('fixtures.isActive', 1)
                    ->whereIN('running_inning',[ 3,1,2])
            );
    }, 'subquery')
        ->select('team_id', DB::raw('COUNT(*) AS count'))
        ->groupBy('team_id')
        ->get()
        ->pluck('count', 'team_id');

  
       $playerruns =Fixture::where('tournament_id', $tournament_id)
       ->where('fixtures.isActive', 1)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->groupBy('fixture_scores.playerId')
       ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END)  as playerurns, CAST(fixture_scores.playerId AS UNSIGNED) as playerId")
       ->pluck('playerurns', 'playerId');

       $playerteam = TournamentPlayer::where('tournament_players.tournament_id', $tournament_id)
      //  ->where('fixtures.isActive', '=', 1)
       ->where('tournament_players.team_id', $team_id)
       ->join('fixtures', 'fixtures.tournament_id', '=', 'tournament_players.tournament_id')
       ->groupBy('tournament_players.player_id', 'tournament_players.team_id')
       ->selectRaw('tournament_players.team_id, tournament_players.player_id')
       ->pluck('tournament_players.team_id', 'tournament_players.player_id');

      //  $playerballs =Fixture::where('tournament_id', $tournament_id)
      //  ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      //  ->groupBy('fixture_scores.playerId')
      //  ->selectRaw('COUNT(fixture_scores.id) as playeballs, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
      //  ->pluck('playeballs', 'playerId');

       $variable1 = 'R';
       $variable2 = 'Wicket';
       $variable3 = 'RunOut';
       
       
       $playerballs = FixtureScore::where('fixtures.tournament_id', $tournament_id)
       ->where(function ($query) use ($variable1, $variable2, $variable3) {
        $query->where('balltype', '=', $variable1)
            ->orWhere('balltype', '=', $variable2)
            ->orWhere('balltype', '=', $variable3);
    })
    ->selectRaw("count(fixture_scores.id) as balls, playerId")
    ->where('fixtures.isActive', 1)
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('playerId')
    ->get()
     ->pluck('balls', 'playerId');


       $playerouts =Fixture::where('tournament_id', $tournament_id)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->where('fixtures.isActive', 1)
       ->where('fixture_scores.balltype','=','Wicket')
       ->groupBy('fixture_scores.playerId')
       ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
       ->pluck('playeouts', 'playerId');

       $playersix =Fixture::where('tournament_id', $tournament_id)
       ->where('fixtures.isActive', '=', 1)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->where('fixture_scores.issix','=',1)
       ->groupBy('fixture_scores.playerId')
       ->selectRaw('COUNT(fixture_scores.issix ) as playesix, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
       ->pluck('playesix', 'playerId');

       $playerfour =Fixture::where('tournament_id', $tournament_id)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->where('fixtures.isActive', 1)
       ->where('fixture_scores.isfour','=',1)
       ->groupBy('fixture_scores.playerId')
       ->selectRaw('COUNT(fixture_scores.isfour ) as playefour, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
       ->pluck('playefour', 'playerId');

       $playerhigestruns = Fixture::where('tournament_id', $tournament_id)
       ->where('fixtures.isActive', 1)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->groupBy('fixture_scores.playerId')
       ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as total_runs, CAST(fixture_scores.playerId AS UNSIGNED) as playerId")
       ->pluck('total_runs', 'playerId');


       $playerHundreds= DB::table(function ($query) use ($tournament_id) {
        $query->select('playerid', DB::raw('SUM(runs) AS hundred'), 'fixture_id')
            ->from('fixture_scores')
            ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
            ->where('fixtures.tournament_id', $tournament_id)
            ->where('fixtures.isActive', 1)
            ->groupBy('playerid', 'fixture_id');
    }, 'subquery')
    ->select('playerid', DB::raw('COUNT(*) AS hundreds_count'))
    ->where('hundred', '>=', 100)
    ->groupBy('playerid')
    ->get()->pluck('hundreds_count','playerid');

           $playerfifty = DB::table(function ($query) use ($tournament_id) {
            $query->select('playerId', DB::raw('SUM(runs) AS fifties'), 'fixture_id')
                ->from('fixture_scores')
                ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
                ->where('fixtures.tournament_id', $tournament_id)
                ->where('fixtures.isActive', 1)
                ->groupBy('playerId', 'fixture_id');
        }, 'subquery')
        ->select('playerId', DB::raw('COUNT(*) AS fifties'))
        ->where('fifties', '>=', 50)
        ->where('fifties', '<', 100)
        ->groupBy('playerId')
        ->get()->pluck('fifties','playerId');

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();


      $teamid = Team::where('id', '=', $team_id)->select('id')->get();

      $tournamentgrounds=Fixture::where('tournament_id',$tournament_id)
      ->select('ground_id')
      ->distinct('ground_id')
      ->get()->first();
    return view('team_batting', compact('teamData','tournamentgrounds','higest_score','teamid','playerfifty','playerHundreds','playerhigestruns','playerfour','playersix','playerouts','playerballs','playerteam','playerruns','playername','playermatch', 'tournament_ids', 'match_results',  'player', 'ground', 'tournamentData', 'tournament', 'teamPlayerCount', 'team_resultData', 'teamPlayers', 'team_id_data',  "image_gallery",'matchcount'));
  }

  public function batting_states()
  {

    $tournamentdata = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $match_results = Fixture::query();
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    DB::enableQueryLog();

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');
    $getresult = [];
    $match_count_player = collect();
    $player_runs = collect();
    $balls_faced = collect();
    $sixes = collect();
    $fours = [];
    $results = array();

    return view('batting_states', compact('fours','results', 'balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_count_player', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years'));
  }

  public function batting_states_submit(Request $request)
  {
    $match_results = Fixture::query()
      ->where('running_inning', 3)
      ->orderBy('id')
      ->get();
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $teams = Team::query()
      ->pluck('name', 'id');

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

    $tournamentdata = Tournament::query()
      ->where('isActive', '=', 1)
      ->pluck('name', 'id');

    $image_gallery = GalleryImages::query()
      ->where('isActive', 1)
      ->get();

      DB::enableQueryLog();
      

      $data = Fixture::query()
      ->where('fixtures.isActive', 1)
      ->selectRaw('DISTINCT tournament_players.player_id')
      ->selectRaw('tournament_players.team_id')
      ->selectRaw('tournament_players.tournament_id')
      ->join('tournament_players', 'tournament_players.tournament_id', '=', 'fixtures.tournament_id');
  
      $term = $request->input();
      $tournament = $term['tournament'];
      $year = $term['year'];
      
      if (!empty($term['year'])) {
        $year = $term['year'];
        $data->whereRaw("YEAR(fixtures.match_startdate) = $year");
      }
      
      if (!empty($term['tournament'])) {
          $tournament = $term['tournament'];
          $data->where('fixtures.tournament_id', '=', $tournament);
      }
  
  $getresult = $data
      ->distinct('tournament_players.player_id')
      ->groupBy('tournament_players.player_id', 'tournament_players.team_id','tournament_players.tournament_id')
      ->get();
  
   

$query = DB::getQueryLog();
                    $query = DB::getQueryLog();
            // dd($query);
  
    
    $hundreds = [];
   
    $higest_score = [];
  

      $teamIds = TournamentGroup::when($tournament, function ($query) use ($tournament) {
        return $query->where('tournament_id', $tournament);
      })
      ->select('team_id')
      ->groupBy('team_id')
      ->pluck('team_id');
  
      $match_count = DB::table(function ($query) use ($teamIds, $tournament, $year) {
        $query->select('team_id_a AS team_id')
            ->from('fixtures')
            ->whereIn('team_id_a', $teamIds)
            ->when($tournament, function ($query) use ($tournament) {
              return $query->where('fixtures.tournament_id', $tournament);
            })
            ->where('fixtures.isActive', 1)
             ->when($year, function ($query) use ($year) {
        return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
      }) // Use whereYear to filter by year
            ->whereIn('running_inning', [3, 1, 2])
            ->unionAll(
                DB::table('fixtures')
                    ->select('team_id_b AS team_id')
                    ->whereIn('team_id_b', $teamIds)
                    ->when($tournament, function ($query) use ($tournament) {
                      return $query->where('fixtures.tournament_id', $tournament);
                    })
                    ->where('fixtures.isActive', 1)
                     ->when($year, function ($query) use ($year) {
        return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
      }) // Use whereYear to filter by year
                    ->whereIn('running_inning', [3, 1, 2])
            );
    }, 'subquery')
        ->select('team_id', DB::raw('COUNT(*) AS count'))
        ->groupBy('team_id')
        ->get()
        ->pluck('count', 'team_id');

    $inningsCount = DB::table('fixture_scores')
      ->selectRaw('COUNT(DISTINCT fixtures.id) as count, fixture_scores.playerId')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->when($tournament, function ($query) use ($tournament) {
        return $query->where('fixtures.tournament_id', $tournament);
      })
      ->when($year, function ($query) use ($year) {
        return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
      })
      // ->whereRaw("YEAR(fixtures.match_startdate) = $year")
      ->where('fixtures.isActive', 1)
      ->groupBy('fixture_scores.playerId')
      ->get()->pluck('count', 'playerId');

      DB::enableQueryLog();

      $player_runs = FixtureScore::when($tournament, function ($query) use ($tournament) {
        return $query->where('fixtures.tournament_id', $tournament);
    })
     ->when($year, function ($query) use ($year) {
        return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
      })
    ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END)  as totalruns, fixture_scores.playerId")
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->where('fixtures.isActive', 1)
    ->groupBy('fixture_scores.playerId')
    ->orderBy('totalruns', 'desc')
    ->distinct()
    ->get()
    ->pluck('totalruns', 'playerId');

//  $query = DB::getQueryLog();
//                     $query = DB::getQueryLog();
//             dd($query);
    $variable1 = 'R';
       $variable2 = 'Wicket';
       $variable3 = 'RunOut';
       
       
       $balls_faced = FixtureScore::when($tournament, function ($query) use ($tournament) {
        return $query->where('fixtures.tournament_id', $tournament);
      })
        ->when($year, function ($query) use ($year) {
        return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
      })
       ->where(function ($query) use ($variable1, $variable2, $variable3) {
        $query->where('balltype', '=', $variable1)
            ->orWhere('balltype', '=', $variable2)
            ->orWhere('balltype', '=', $variable3);
    })
    ->selectRaw("count(fixture_scores.id) as balls, playerId")
    ->where('fixtures.isActive', 1)
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('playerId')
    ->get()
     ->pluck('balls', 'playerId');

    $sixes= FixtureScore::when($tournament, function ($query) use ($tournament) {
      return $query->where('fixtures.tournament_id', $tournament);
    })
     ->when($year, function ($query) use ($year) {
        return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
      })
    ->where('issix', 1)
    ->where('fixtures.isActive', 1)
    ->selectRaw('COUNT(*) as six, fixture_scores.playerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('fixture_scores.playerId')
    ->get()
    ->pluck('six', 'playerId');


  $fours= FixtureScore::when($tournament, function ($query) use ($tournament) {
    return $query->where('fixtures.tournament_id', $tournament);
  })
   ->when($year, function ($query) use ($year) {
        return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
      })
    ->where('isfour', 1)
    ->where('fixtures.isActive', 1)
    ->selectRaw('COUNT(*) as four, fixture_scores.playerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('fixture_scores.playerId')
    ->get()
    ->pluck('four', 'playerId');

  $playerouts =Fixture::when($tournament, function ($query) use ($tournament) {
    return $query->where('fixtures.tournament_id', $tournament);
  })
   ->when($year, function ($query) use ($year) {
        return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
      })
    ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
    ->where('fixtures.isActive', 1)
    ->where('fixture_scores.balltype','=','Wicket')
    ->where('fixture_scores.isout','=',1)
    ->groupBy('fixture_scores.playerId')
    ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, fixture_scores.playerId')
    ->pluck('playeouts', 'playerId');

 
   $fifty=DB::table(function ($query) use ($tournament,$year) {
          $query->select('playerId', DB::raw('SUM(runs) AS fifties'), 'fixture_id')
              ->from('fixture_scores')
              ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
              ->when($tournament, function ($query) use ($tournament) {
                return $query->where('fixtures.tournament_id', $tournament);
              })
               ->when($year, function ($query) use ($year) {
        return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
      })
              ->where('fixtures.isActive', 1)
              ->groupBy('playerId', 'fixture_id');
      }, 'subquery')
      ->select('playerId', DB::raw('COUNT(*) AS fifties'))
      ->where('fifties', '>=', 50)
      ->where('fifties', '<', 100)
      ->groupBy('playerId')
      ->get()->pluck('fifties', 'playerId');

    $hundreds=DB::table(function ($query) use ($tournament,$year) {
      $query->select('playerId', DB::raw('SUM(runs) AS hundred'), 'fixture_id')
          ->from('fixture_scores')
          ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
          ->when($tournament, function ($query) use ($tournament) {
            return $query->where('fixtures.tournament_id', $tournament);
          })
           ->when($year, function ($query) use ($year) {
        return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
      })
          ->where('fixtures.isActive', 1)
          ->groupBy('playerId', 'fixture_id');
      }, 'subquery')
      ->select('playerId', DB::raw('COUNT(*) AS hundred'))
      ->where('hundred', '>=', 100)
      ->groupBy('playerId')
      ->get()->pluck('hundred', 'playerId');
          
   

$results = array();

foreach ($getresult as $teamPlayer) {
  $existingResult = array_search($teamPlayer->player_id, array_column($results, 'player_id'));

  if ($existingResult === false) {
      $higest_score_query = FixtureScore::where('playerId', $teamPlayer->player_id)
          ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as total_runs, fixture_id")
          ->where('fixtures.isActive', 1)
          ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
          ->groupBy('fixture_id')
          ->orderbydesc('total_runs')
          ->limit(1);

      $higest_score[$teamPlayer->player_id] = $higest_score_query->value('total_runs');

      if ($player_runs->has($teamPlayer->player_id)) {
          $results[] = [
              'player_id' => $teamPlayer->player_id,
              'team_id' => $teamPlayer->team_id,
              'player_runs_keys' => $player_runs[$teamPlayer->player_id] ?? '',
          ];
      }
  }
}

usort($results, function ($a, $b) {
  return $b['player_runs_keys'] - $a['player_runs_keys'];
});


      return view('batting_states', compact('fours', 'higest_score', 'fifty', 'hundreds', 'balls_faced', 'sixes', 'tournamentdata', 'balls_faced', 'player_runs', 'match_count', 'player', 'teams', 'match_results', 'image_gallery', 'years', 'getresult','inningsCount','playerouts', 'results'));
  }


  public function comingsoon()
  {
    $match_results = Fixture::query();
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();

    return view('comingsoon', compact('match_results'));
  }

  public function clubs()
  {
    $match_results = Fixture::query();
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();
    $clubs = Team::query()->where('isclub', '=', 1)->get();

    return view('clubs', compact('match_results', 'clubs'));
  }

  public function clubteamsearch()
  {
    $match_results = Fixture::query();
    $match_results = $match_results->orderBy('id')->get();
    $tournament = Tournament::pluck('name', 'id');

    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $data = [];
    $results = $data;
    return view('clubteamsearch', compact('match_results', 'results', 'tournament','player'));
  }


  public function club_team_search_submit(Request $request)
  {
    $match_results = Fixture::query();
    $match_results = $match_results->orderBy('id')->get();
    $tournament = Tournament::pluck('name', 'id');

    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );

    if ($request->method() !== 'POST') {
      abort(405, 'Method Not Allowed');
    }
    $term = $request->input('teamName');

    $query1 = TeamPlayer::query();
    if (!empty($term)) {
      $query1->where('teams.name', 'like', '%' . $term . '%');
    }
    
    $query1->selectRaw('team_players.team_id')
    ->where('tournaments.isActive', 1)
      ->selectRaw('teams.name')
      ->selectRaw('tournament_groups.tournament_id')
      ->selectRaw('MAX(IF(team_players.iscaptain = 1, team_players.player_id, NULL)) AS player_id')
      ->join('teams', 'teams.id', '=', 'team_players.team_id')
      ->join('tournament_groups', 'tournament_groups.team_id', '=', 'team_players.team_id')
      ->join('tournaments', 'tournaments.id', '=', 'tournament_groups.tournament_id')
      ->groupBy('team_players.team_id', 'tournament_groups.tournament_id');
    
    $results = $query1->get();
    


    return view('clubteamsearch', compact('match_results','results','tournament','player'));
  }


  public function schedulesearch()
  {

    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    DB::enableQueryLog();

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

      $match_results = Fixture::query()->where('isActive', 1)->orderBy('id')->get();
    
  
      $teams = Team::query()->get()->pluck(
        'name',
        'id'
      );
  
      $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
        'clubname',
        'id'
      );
  

      $today = Carbon::now()->toDateString();

      $results = [];
      

      $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
        'name',
        'id'
      );
  
      $image_gallery = GalleryImages::query()
        ->where('isActive', '=', 1)
        ->get();
  
  
      $ground2 = Ground::query()->get()->pluck(
        'name',
        'id'
      );

    return view('schedulesearch', compact('results', 'ground2', 'ground', 'clubs', 'match_results', 'years', 'tournament', 'image_gallery'));
  }

  public function schedulesearch_form_submit(Request $request)
  {
    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    DB::enableQueryLog();
    if ($request->method() !== 'POST') {
      abort(405, 'Method Not Allowed');
    }
    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');
    $match_results = Fixture::query()->where('isActive', 1)->orderBy('id')->get();
    $data = Fixture::query()->where('fixtures.isActive', 1);
    $term = $request;

    if (!empty($term->created_at) && strtotime($term->created_at)) {
      $startDate = date('Y-m-d', strtotime($term->created_at));
      $data->whereDate('match_startdate', '>=', $startDate);
    }
    
    if (!empty($term->end_at) && strtotime($term->end_at)) {
      $endDate = date('Y-m-d', strtotime($term->end_at));
      $data->whereDate('match_enddate', '<=', $endDate);
    }

    if (!empty($term['year'])) {
      $year = $term['year'];
      $data->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) {
      $tournaments = $term['tournament'];
      $data->where('tournament_id', '=', $tournaments);
    }

    if (!empty($term['teams'])) {
      $team = $term['teams'];
      $data->where('team_id_a', '=', $team)
        ->oRWhere('team_id_b', '=', $team);
    }

    if (!empty($term->club)) {
      $club = $term->club;
      $data->where('team_id_a', '=', $club)
        ->oRWhere('team_id_b', '=', $club);
    }

    if (!empty($term['grounddata'])) {
      $grounddata = $term['grounddata'];
      $data->where('ground_id', '=', $grounddata);
    }



    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );


    $today = Carbon::now()->toDateString();

    $data->where('running_inning',0)->where('match_startdate', '>=', $today);
    $results = $data->where('isActive', '=', 1)->get();
    
    $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();


    $ground2 = Ground::query()->get()->pluck(
      'name',
      'id'
    );

    return view('schedulesearch', compact('results', 'ground2', 'clubs', 'match_results', 'ground', 'years', 'tournament', 'image_gallery'));
  }

  public function imagegallery()
  {

    $match_results = Fixture::query()->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );
    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

    return view('imagegallery', compact('teams', 'match_results', 'image_gallery'));
  }

  public function seasonresponsers(int $id)
  {
    $match_results = Fixture::query()->where('isActive', 1)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );
    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    $seasonsponser=SeasonSponsor::where('season_sponsors.season_id',$id)
    ->join('sponsors','sponsors.id','=','season_sponsors.sponsor_id')
    ->get();


    return view('seasonresponsers', compact('teams', 'match_results', 'image_gallery','seasonsponser'));
  }

  public function leagueinfo(int $id)
  {
    $match_results = Fixture::where('running_inning', '=', 3) ->where('fixtures.isActive', 1)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );
    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    $rulesandregulations = Rulesandregulation::where('id', '=', $id)
      ->get();

    return view('leagueinfo', compact('teams', 'match_results', 'image_gallery', 'rulesandregulations'));
  }

  public function clubviewteams()
  {
    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

      $ground = Ground::query()->get()->pluck(
        'name',
        'id'
      );
      $match_results = Fixture::query() ->where('fixtures.isActive', 1)->get();
  
      $teamsname = Team::query()->get()->pluck(
        'name',
        'id'
      );
  
      $tournamentname = Tournament::query()->where('isActive', '=', 1)->pluck(
        'name',
        'id'
      );
  
      $playername = Player::query()->get()->pluck(
        'fullname',
        'id'
      );
  
      $tournamentgrounds='';
  
      $TournamentGroupData= [];

      $TeamPlayerData=[];
  
      $Teamdata=[];
  
  
      $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
        'name',
        'id'
      );
  
      $image_gallery = GalleryImages::query()
        ->where('isActive', '=', 1)
        ->get();
  
     
  
  
      return view('viewteams', compact('TournamentGroupData','Teamdata','TeamPlayerData','teamsname','tournamentname' ,'tournamentgrounds','playername', 'match_results', 'ground', 'tournament', 'image_gallery'));
    
  }


  public function clubviewteams_submit(Request $request)
  {
    $years = DB::table('tournaments')
      ->select(DB::raw('YEAR(tournamentstartdate) as year'))
      ->groupBy(DB::raw('YEAR(tournamentstartdate)'))
      ->orderBy(DB::raw('YEAR(tournamentstartdate)'), 'desc')
      ->pluck('year');
      $tournament_id=0;
      $term = $request->all();
      if (!empty($term['tournament'])) {
      $tournament_id = $term['tournament'];
       }
      $ground = Ground::query()->get()->pluck(
        'name',
        'id'
      );
      $match_results = Fixture::query() ->where('fixtures.isActive', 1)->get();
  
      $teamsname = Team::query()->get()->pluck(
        'name',
        'id'
      );
  
      $tournamentname = Tournament::query()->where('isActive', '=', 1)->pluck(
        'name',
        'id'
      );
  
      $playername = Player::query()->get()->pluck(
        'fullname',
        'id'
      );
  
      $tournamentgrounds=Fixture::where('tournament_id',$tournament_id)
      ->where('fixtures.isActive', 1)
      ->select('ground_id')
      ->distinct('ground_id')
      ->get()->pluck('ground_id')->first();
  
      $TournamentGroupData= TournamentGroup::where('tournament_id', '=', $tournament_id)
          ->selectRaw('tournament_id, team_id')
          ->get();
  
      $TeamPlayerData=TeamPlayer::where('iscaptain',1)
      ->selectRaw('player_id, team_id')
      ->get()->pluck('player_id','team_id');
  
      $Teamdata=Team::where('isclub',1)
      ->selectRaw('clubname, id')
      ->get()->pluck('clubname','id');
  
  
      $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
        'name',
        'id'
      );
  
      $image_gallery = GalleryImages::query()
        ->where('isActive', '=', 1)
        ->get();
  
     
  
  
      return view('viewteams', compact('TournamentGroupData','Teamdata','TeamPlayerData','teamsname','tournamentname' ,'tournamentgrounds','playername', 'match_results', 'ground', 'tournament', 'image_gallery'));
    }


  public function  view_tournaments(int $tournament_id)
  {
    $match_results = Fixture::query() ->where('fixtures.isActive', 1);
    $match_results = $match_results->where('isActive', 1)->where('running_inning', '=', 3)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->get();

    $select_tournament_name = Tournament::query()
      ->where('id', '=', $tournament_id)
      ->get();

    $seasons = Season::query()
      ->where('id', '=', $select_tournament_name[0]->season_id)
      ->selectRaw("name as season_name")
      ->selectRaw("id as season_id")
      ->get();

      $tournamentdata = Fixture::where('tournament_id', $tournament_id)
      ->where('fixtures.isActive', 1)
      ->selectRaw('MAX(numberofover) AS maxover')
      ->get()->pluck('maxover')->first();
  
  
    return view('display_tournaments', compact('match_results', 'tournamentdata','teams', "tournament_name", 'select_tournament_name', 'seasons'));
  }

  public function  view_all_tournaments()
  {

    $match_results = Fixture::query() ->where('fixtures.isActive', 1);
    $match_results = $match_results->where('isActive', 1)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('isActive', 1)
      ->where('is_web_display', '=', 1)
      ->get();

    return view('display_all_tournaments', compact('match_results', 'teams', "tournament_name"));
  }


  public function  view_all_grounds()
  {


    $match_results = Fixture::query() ->where('fixtures.isActive', 1);
    $match_results = $match_results->where('isActive', 1)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->get();


    $grounds = Ground::query()
      ->where('isActive', '=', 1)
      ->get();

    return view('grounds_view', compact('match_results', 'teams', "tournament_name", "grounds"));
  }

  public function articals()
  {

    $match_results = Fixture::query() ->where('fixtures.isActive', 1);
    $match_results = $match_results->where('isActive', 1)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->get();


    $grounds = Ground::query()
      ->where('isActive', '=', 1)
      ->get();

    return view('articals', compact('match_results', 'teams', "tournament_name", "grounds"));
  }

  public function newsdata()
  {

    $match_results = Fixture::query() ->where('fixtures.isActive', 1);
    $match_results = $match_results->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->get();


    $grounds = Ground::query()
      ->where('isActive', '=', 1)
      ->get();

    $image_slider = GalleryImages::query()
      ->where('is_main_slider', '=', 1)
      ->where('isActive', '=', 1)
      ->get();


    return view('newsdata', compact('image_slider', 'match_results', 'teams', "tournament_name", "grounds"));
  }

  public function contactus()
  {
    $match_results = Fixture::query() ->where('fixtures.isActive', 1);
    $match_results = $match_results->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->get();


    $grounds = Ground::query()
      ->where('isActive', '=', 1)
      ->get();

    return view('contactus', compact('match_results', 'teams', "tournament_name", "grounds"));
  }


  public function show_team(int $tournament_id)
  {

    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    $match_results = Fixture::query() ->where('fixtures.isActive', 1)->get();

    $teamsname = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $tournamentname = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );

    $playername = Player::query()->get()->pluck(
      'fullname',
      'id'
    );

    $tournamentgrounds=Fixture::where('tournament_id',$tournament_id)
    ->where('fixtures.isActive', 1)
    ->select('ground_id')
    ->distinct('ground_id')
    ->get()->pluck('ground_id')->first();

    $TournamentGroupData= TournamentGroup::where('tournament_id', '=', $tournament_id)
        ->selectRaw('tournament_id, team_id')
        ->get();

    $TeamPlayerData=TeamPlayer::where('iscaptain',1)
    ->selectRaw('player_id, team_id')
    ->get()->pluck('player_id','team_id');

    $Teamdata=Team::where('isclub',1)
    ->selectRaw('clubname, id')
    ->get()->pluck('clubname','id');


    $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

   


    return view('viewteams', compact('TournamentGroupData','Teamdata','TeamPlayerData','teamsname','tournamentname' ,'tournamentgrounds','playername', 'match_results', 'ground', 'tournament', 'image_gallery'));
  }

  public  function viewteams_submit(Request $request)
  {
    $tournament_id=0;
    $term = $request->all();
    if (!empty($term['tournament'])) {
    $tournament_id = $term['tournament'];
     }
    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    $match_results = Fixture::query()->get() ->where('fixtures.isActive', 1);

    $teamsname = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $tournamentname = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );

    $playername = Player::query()->get()->pluck(
      'fullname',
      'id'
    );

    $tournamentgrounds=Fixture::where('tournament_id',$tournament_id)
    ->where('fixtures.isActive', 1)
    ->select('ground_id')
    ->distinct('ground_id')
    ->get()->pluck('ground_id')->first();

    $TournamentGroupData= TournamentGroup::where('tournament_id', '=', $tournament_id)
        ->selectRaw('tournament_id, team_id')
        ->get();

    $TeamPlayerData=TeamPlayer::where('iscaptain',1)
    ->selectRaw('player_id, team_id')
    ->get()->pluck('player_id','team_id');

    $Teamdata=Team::where('isclub',1)
    ->selectRaw('clubname, id')
    ->get()->pluck('clubname','id');


    $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

   


    return view('viewteams', compact('TournamentGroupData','Teamdata','TeamPlayerData','teamsname','tournamentname' ,'tournamentgrounds','playername', 'match_results', 'ground', 'tournament', 'image_gallery'));
  }


  public function matchofficial()
  {
    $match_results = Fixture::query() ->where('fixtures.isActive', 1);
    $match_results = $match_results->where('isActive', '=', 1)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->get();


    $grounds = Ground::query()
      ->where('isActive', '=', 1)
      ->get();

    $umpire_matchoffcial = Umpire::query()
      ->get();


    return view('matchofficial', compact('match_results', 'teams', "tournament_name", "grounds", "umpire_matchoffcial"));
  }

  public function playerview(int $playerid)
  {
    $match_results = Fixture::query() ->where('fixtures.isActive', 1);
    $match_results = $match_results->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->get();


    $grounds = Ground::query()
      ->where('isActive', '=', 1)
      ->get();

    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $player_data = TeamPlayer::where('team_players.player_id', $playerid)
      ->join('players', 'players.id', '=', 'team_players.player_id')
      ->select('team_players.player_id as playername', 'players.bowlingstyle as playerbowlingstyle', 'players.battingstyle as playerbattingstyle')
      ->distinct('team_players.playerid')
      ->get();

    $player_club = FixtureScore::where('fixture_scores.playerid', $playerid)
      ->join('team_players', 'team_players.player_id', '=', 'fixture_scores.playerid')
      ->join('teams', 'teams.id', '=', 'team_players.team_id')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->where('fixtures.isActive', 1)
      ->where('teams.isclub', 1)
      ->select('teams.clubname as playerclub')
      ->distinct('players.playerid')
      ->get();

    $teamIds = FixtureScore::where('fixture_scores.playerid', $playerid)
      ->join('team_players', 'team_players.player_id', '=', 'fixture_scores.playerid')
      ->join('teams', 'teams.id', '=', 'team_players.team_id')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->where('fixtures.isActive', 1)
      ->select('teams.id as playerclub')
      ->distinct('players.playerid')
      ->get()->pluck('playerclub');


    $player_team = TeamPlayer::where('team_players.player_id', $playerid)
    ->join('players', 'players.id', '=', 'team_players.player_id')
    ->select('team_players.player_id as playername', 'team_players.team_id as playerteam')
    ->get();

    $player_team_id = TeamPlayer::where('team_players.player_id', $playerid)
    ->select('team_players.team_id as teamid')
    ->get()->pluck('teamid');

    $player_match = DB::table(function ($query) use ($player_team_id) {
      $query->select('team_id_a AS team_id')
          ->from('fixtures')
          ->whereIn('team_id_a', $player_team_id)
          ->where('isActive', 1)
          ->whereIN('running_inning',[ 3,1,2])
          ->unionAll(
              DB::table('fixtures')
                  ->select('team_id_b AS team_id')
                  ->where('isActive', 1)
                  ->whereIn('team_id_b', $player_team_id)
                  ->whereIN('running_inning',[ 3,1,2])
          );
      }, 'subquery')
      ->select('team_id', DB::raw('COUNT(*) AS count'))
      ->groupBy('team_id')
      ->get()->pluck('count','team_id');


    $player_runs = FixtureScore::where('playerid', $playerid)
    ->where('fixtures.isActive', 1)
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as playerruns")
      ->groupBy('playerid')
      ->get();



      $variable1 = 'R';
    $variable2 = 'Wicket';
    $variable3 = 'RunOut';
    
    
    $player_balls = FixtureScore::where('playerid', $playerid)
    ->where(function ($query) use ($variable1, $variable2, $variable3) {
        $query->where('balltype', '=', $variable1)
            ->orWhere('balltype', '=', $variable2)
            ->orWhere('balltype', '=', $variable3);
    })
    ->selectRaw("count(fixture_scores.id) as balls, playerId")
    ->where('fixtures.isActive', 1)
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('playerId')
    ->get();


        $playerouts =Fixture::where('fixture_scores.playerid', $playerid)
        ->where('fixtures.isActive', 1)
        ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
        ->where('fixture_scores.balltype','=','Wicket')
        ->groupBy('fixture_scores.playerId')
        ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
        ->pluck('playeouts', 'playerId');
        $higest_score=[];
        
        
          $higest_score_query = FixtureScore::where('playerId', $playerid)
          ->where('fixtures.isActive', 1)
          ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
          ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as total_runs, fixture_id")
          ->groupBy('fixture_id')
          ->orderbydesc('total_runs')
          ->limit(1);
            $higest_score[$playerid]= $higest_score_query->value('total_runs');
      



    $match_dissmissal_runout_name= Dismissal::where('dismissals.name', '=', 'Run out')
    ->selectRaw("dismissals.id as dissmissalname")
    ->get()->pluck('dissmissalname');
    $player_wicket= FixtureScore::where('bowlerid', $playerid)
    ->where('fixtures.isActive', 1)
    ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
    ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
    ->where('match_dismissals.dismissal_id','!=', $match_dissmissal_runout_name)
    ->selectRaw("COUNT(match_dismissals.id) AS playerwickets")
    ->selectRaw('fixture_scores.bowlerid')
    ->groupBy('fixture_scores.bowlerid')
    ->get();

    $player_total_fifties =   $player_total_fifties = DB::table(function ($query) {
      $query->select('playerId', DB::raw('SUM(runs) AS fifties'), 'fixture_id')
          ->from('fixture_scores')
          ->where('fixtures.isActive', 1)
          ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
          ->groupBy('playerId', 'fixture_id');
  }, 'subquery')
  ->select('playerId', DB::raw('COUNT(*) AS fifties'))
  ->where('fifties', '>=', 50)
  ->where('fifties', '<', 100)
  ->groupBy('playerId')
  ->get()->pluck('fifties', 'playerId');

    $player_fifties =[];

    $player_hundreds =DB::table(function ($query) {
      $query->select('playerId', DB::raw('SUM(runs) AS hundred'), 'fixture_id')
          ->from('fixture_scores')
          ->where('fixtures.isActive', 1)
          ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
          ->groupBy('playerId', 'fixture_id');
      }, 'subquery')
      ->select('playerId', DB::raw('COUNT(*) AS hundred'))
      ->where('hundred', '>=', 100)
      ->groupBy('playerId')
      ->get()->pluck('hundred', 'playerId');

    $player_runs1 = FixtureScore::where('playerid', $playerid)
    ->where('fixtures.isActive', 1)
    ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
      ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as playerruns")
      ->groupBy('playerid')
      ->first();

    $player_balls1 = FixtureScore::where('playerid', $playerid)
  
      ->where('fixture_scores.balltype', 'R')
      ->selectRaw('COUNT(id) as playerballs')
      ->groupBy('playerid')
      ->first();

    $player_innings = FixtureScore::where('playerid', $playerid)
      ->where('fixture_scores.balltype', 'R')
      ->whereNotNull('isout')
      ->selectRaw('COUNT(DISTINCT fixture_id) as innings')
      ->groupBy('playerid')
      ->first();

  

    $player_six = FixtureScore::where('playerid', $playerid)
    ->where('fixtures.isActive', 1)
    ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
      ->where('fixture_scores.balltype', '=', 'R')
      ->selectRaw("SUM(fixture_scores.issix = 1 AND fixture_scores.balltype = 'R') as total_sixes")
      ->groupBy('playerid')
      ->get();


    $player_inning_score = FixtureScore::where('playerid', $playerid)
    ->where('fixtures.isActive', 1)
    ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
      ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as runs")
      ->selectRaw('fixture_id')
      ->groupBy('fixture_id')
      ->get()->pluck('runs')->toArray();

      $batsman_inning = FixtureScore::where('playerid', $playerid)
      ->where('fixtures.isActive', 1)
      ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
      ->selectRaw("COUNT(DISTINCT fixture_id) as playerinnings, fixture_id")
      ->groupBy('fixture_id')
      ->get()
      ->pluck('playerinnings');
  
      $batsman_inning_length = count($batsman_inning);

      $player_average = 0;

      if ($player_runs1 && $batsman_inning_length) {
        $runs = $player_runs1->playerruns;
        $innings = $player_innings->innings;
  
        if ($innings > 0) {
          $player_average = $runs / $batsman_inning_length;
        }
      }
      $player_average = number_format($player_average, 2);
      $player_strike_rate = 0;
  
      if ($player_runs1 !== null && $player_balls1 !== null && $player_balls1->playerballs > 0) {
        $player_strike_rate = ($player_runs1->playerruns / $player_balls1->playerballs) * 100;
      }
  
      $player_strike_rate = number_format($player_strike_rate, 2);
  
    $bowler_wicket_chart = DB::table('match_dismissals')
      ->select(DB::raw('count(match_dismissals.id) as count, dismissals.name as name'))
      ->join('fixture_scores', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
      ->join('dismissals', 'dismissals.id', '=', 'match_dismissals.dismissal_id')
      ->where('fixture_scores.bowlerId', '=', $playerid)
      ->groupBy('match_dismissals.dismissal_id', 'name')
      ->get()->toArray();


    $batsman_wicket_chart = DB::table('match_dismissals')
      ->select(DB::raw('count(match_dismissals.id) as count, dismissals.name as name'))
      ->join('fixture_scores', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
      ->join('dismissals', 'dismissals.id', '=', 'match_dismissals.dismissal_id')
      ->where('fixture_scores.playerid', '=', $playerid)
      ->groupBy('match_dismissals.dismissal_id', 'name')
      ->get()->toArray();



    // $player_inning_wickets = FixtureScore::where('playerid', $playerid)
    // ->where('fixture_scores.isout','=',1)
    // ->selectRaw('SUM(runs) as runs')
    // ->selectRaw('fixture_id')
    // ->groupBy('fixture_id')
    // ->get()->pluck('runs')->toArray();


    $player_inning_wickets = FixtureScore::where('bowlerid', $playerid)
      ->where('balltype', '=', 'Wicket')
      ->selectRaw('SUM(isout) as playerwickets')
      ->groupBy('fixture_id')
      ->get()->pluck('playerwickets')->toArray();

      $bowler_inning = FixtureScore::where('bowlerid', $playerid)
      ->where('fixtures.isActive', 1)
      ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
      ->selectRaw("COUNT(DISTINCT fixture_id) as playerinnings")
      ->groupBy('fixture_id')
      ->get()
      ->pluck('playerinnings');
      $bowler_inning_length = count($bowler_inning);
    // dd($player_inning_wickets);


    $player_four = FixtureScore::where('playerid', $playerid)
    ->where('fixtures.isActive', 1)
    ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
      ->where('fixture_scores.balltype', '=', 'R')
      ->selectRaw("SUM(fixture_scores.isfour = 1 AND fixture_scores.balltype = 'R') as total_four")
      ->groupBy('playerid')
      ->get();

    // bower state
    $player_matchbowler = FixtureScore::where('bowlerid', $playerid)->count();
    $bower_over = FixtureScore::where('bowlerid', $playerid)
    ->where('fixtures.isActive', 1)
    ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
      ->selectRaw("SUM(fixture_scores.balltype = 'WD') as total_wides")
      ->selectRaw('SUM(runs) as bowler_runs')
      ->selectRaw('COUNT(DISTINCT overnumber) as max_over')
      ->groupBy('bowlerid')
      ->get();

      $variable1 = 'R';
    $variable2 = 'Wicket';
    $variable3 = 'RunOut';
    
    $bowlerballs = FixtureScore::where('bowlerid', $playerid)
        ->where(function ($query) use ($variable1, $variable2, $variable3) {
            $query->where('balltype', '=', $variable1)
                ->orWhere('balltype', '=', $variable2)
                ->orWhere('balltype', '=', $variable3);
        })
        ->selectRaw("count(fixture_scores.ballnumber) as `over`, bowlerId") // Added backticks for aliasing
        ->where('fixtures.isActive', 1)
        ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
        ->groupBy('bowlerId')
        ->get();
    

      
    $match_dissmissal_name = Dismissal::where('dismissals.name', '=', 'Caught')
      ->selectRaw("dismissals.id as dissmissalname")
      ->get()->pluck('dissmissalname');

    $player_cauches = MatchDismissal::where('match_dismissals.outbyplayer_id', $playerid)
      ->where('match_dismissals.dismissal_id', $match_dissmissal_name)
      ->join('fixtures', function ($join) {
        $join->on('fixtures.id', '=', 'match_dismissals.fixture_id')
             ->where('fixtures.isActive', 1);
    })
      ->selectRaw("COUNT(match_dismissals.id) as total_catches")
      ->groupBy('match_dismissals.outbyplayer_id')
      ->get();


    // $player_total_out = FixtureScore::where('fixture_scores.bowlerid', $playerid)
    // ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
    // ->join('dismissals', 'dismissals.id', '=', 'match_dismissals.dismissal_id')
    // ->selectRaw("COUNT(match_dismissals.id) as wicket_count",)
    // ->selectRaw("dismissals.id as out_id")
    // ->groupBy('dismissals.id')
    // ->get()->pluck('out_id, wicket_count');

    // dd($player_total_out);

    $player_balls1 = FixtureScore::where('playerid', $playerid)
      ->where('fixture_scores.balltype', 'R')
      ->selectRaw('COUNT(id) as playerballs')
      ->groupBy('playerid')
      ->first();


    $bowler_strike_rate = 0;

    $bowler_wickets = FixtureScore::where('bowlerid', $playerid)
      ->where('balltype', 'Wicket')
      ->count();

    $bowler_balls = FixtureScore::where('bowlerid', $playerid)
      ->where('balltype', '!=', 'WD')
      ->where('balltype', '!=', 'NB')
      ->count();

    if ($bowler_wickets > 0 && $bowler_balls > 0) {
      $bowler_strike_rate = $bowler_balls / $bowler_wickets;
    }

    $bowler_strike_rate = number_format($bowler_strike_rate, 2);

    $bowler_economy = 0;

    $bowler_runs = FixtureScore::where('bowlerid', $playerid)
      ->where('balltype', '!=', 'WD')
      ->where('balltype', '!=', 'NB')
      ->sum('runs');

    $bowler_balls = FixtureScore::where('bowlerid', $playerid)
      ->where('balltype', '!=', 'WD')
      ->where('balltype', '!=', 'NB')
      ->count();

    if ($bowler_balls > 0) {
      $bowler_economy = ($bowler_runs / $bowler_balls) * 6;
    }

    $bowler_economy = number_format($bowler_economy, 2);



    $chart = (object)[
      'labels' => '',
      'dataset' => '',
    ];
    $chart->labels = (array_keys($player_inning_score));
    $chart->dataset = (array_values($player_inning_score));


    $bowler_inning_wicket = (object)[
      'labels' => '',
      'dataset' => '',
    ];
    $bowler_inning_wicket->labels = (array_keys($player_inning_wickets));
    $bowler_inning_wicket->dataset = (array_values($player_inning_wickets));

    $inningcount_bat = Fixture::where('fixture_scores.playerId', $playerid)
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->groupBy('fixture_scores.playerId')
      ->selectRaw('COUNT(DISTINCT(fixture_scores.fixture_id)) as matches_played,fixture_scores.playerId  as playerId')
      ->pluck('matches_played');
    
    $inningcount_bowl = Fixture::where('fixture_scores.bowlerid', $playerid)
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->groupBy('fixture_scores.bowlerid')
      ->selectRaw('COUNT(DISTINCT(fixture_scores.fixture_id)) as matches_played,fixture_scores.bowlerid  as bowlerid')
      ->pluck('matches_played');

      // dd($inningcount);
      $hatricks = [];

    return view('playerview', compact("bowler_economy",'bowlerballs','hatricks','player_team_id','bowler_inning_length','batsman_inning_length','higest_score', "bowler_balls", "bowler_strike_rate", "player_cauches", "bower_over", 'player_four', 'match_results', 'teams', 'player_team', "tournament_name", "grounds", "player_data", "teams", "player", "player_club", "player_match", "player_runs", "player_wicket", "player_total_fifties", "player_hundreds", "player_balls", "player_average", "player_strike_rate", "player_innings", "player_six", "player_matchbowler", "chart", 'bowler_inning_wicket', 'bowler_wicket_chart', 'batsman_wicket_chart','playerouts','inningcount_bat','inningcount_bowl'));
  }


  public function bowling_state()
  {
    $tournamentdata = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $match_results = Fixture::query()->where('fixtures.isActive', 1);
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    DB::enableQueryLog();

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');
    $getresult = [];
    $results = array();


    return view('bowling_state', compact('player','results', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years'));
  }

  public function bowling_state_submit(Request $request)
  {
    $match_results = Fixture::query()
    ->where('fixtures.isActive', 1)
      ->where('running_inning', 3)
      ->orderBy('id')
      ->get();
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $teams = Team::query()
      ->pluck('name', 'id');

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

    $tournamentdata = Tournament::query()
      ->where('isActive', '=', 1)
      ->pluck('name', 'id');

    $image_gallery = GalleryImages::query()
      ->where('isActive', 1)
      ->get();
      $term = $request->input();
      $year = $term['year'];
      $tournament = $term['tournament'];

      DB::enableQueryLog();
     
    $data = TournamentPlayer::where('fixtures.isActive', 1)
      ->selectRaw('fixture_scores.bowlerId as bowler_id')
      ->selectRaw('tournament_players.tournament_id')
      ->selectRaw('team_players.player_id')
      ->selectRaw('team_players.team_id')
      ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches')
      ->selectRaw('COUNT(DISTINCT fixture_scores.ballnumber) as total_overs')
      ->selectRaw('SUM(fixture_scores.balltype = "WD") as total_wides')
      ->selectRaw('SUM(fixture_scores.balltype = "NB") as total_noball')
      ->selectRaw('SUM(fixture_scores.runs) as total_runs')
      ->selectRaw('SUM(CASE WHEN fixture_scores.isout = 1 THEN 1 ELSE 0 END) as total_wickets')
      ->join('team_players', function ($join) {
        $join->on('team_players.team_id', '=', 'tournament_players.team_id')
          ->on('team_players.player_id', '=', 'tournament_players.player_id');
      })
      ->join('fixture_scores', 'fixture_scores.bowlerId', '=', 'team_players.player_id')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->groupBy('team_players.player_id', 'team_players.team_id')
      ->groupBy('fixture_scores.bowlerId','tournament_players.tournament_id');


    if (!empty($term['year'])) {
      $year = $term['year'];
      $data->whereRaw("YEAR(tournament_players.created_at) = $year");
    }
    if (!empty($term['tournament'])) {
      $tournament = $term['tournament'];
      $data->where('tournament_players.tournament_id', '=', $tournament);
    }

    if (!empty($term['teams'])) {
      $team = $term['teams'];
      $data->where('tournament_players.team_id', '=', $team);
    }
  
    $teamIds = TournamentGroup::when($tournament, function ($query) use ($tournament) {
      return $query->where('tournament_id', $tournament);
  })
    ->select('team_id')
    ->groupBy('team_id')
    ->pluck('team_id');

    $variable1 = 'R';
    $variable2 = 'Wicket';
    $variable3 = 'RunOut';
    
    $bowlerballs = FixtureScore::when($tournament, function ($query) use ($tournament) {
      return $query->where('fixtures.tournament_id', $tournament);
  })
   ->when($year, function ($query) use ($year) {
return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
})
    ->where(function ($query) use ($variable1, $variable2, $variable3) {
     $query->where('balltype', '=', $variable1)
         ->orWhere('balltype', '=', $variable2)
         ->orWhere('balltype', '=', $variable3);
 })
 ->selectRaw("count(fixture_scores.ballnumber) as balls, bowlerId")
 ->where('fixtures.isActive', 1)
 ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
 ->groupBy('bowlerId')
 ->get()
  ->pluck('balls', 'bowlerId');

    $match_count = DB::table(function ($query) use ($teamIds, $tournament,$year) {
    $query->select('team_id_a AS team_id')
        ->from('fixtures')
        ->whereIn('team_id_a', $teamIds)
        ->when($tournament, function ($query) use ($tournament) {
          return $query->where('fixtures.tournament_id', $tournament);
      })
       ->when($year, function ($query) use ($year) {
    return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
    })
        ->where('fixtures.isActive', 1)
        ->whereIn('running_inning', [1,2,3])
        ->unionAll(
            DB::table('fixtures')
                ->select('team_id_b AS team_id')
                ->whereIn('team_id_b', $teamIds)
                ->when($tournament, function ($query) use ($tournament) {
                  return $query->where('fixtures.tournament_id', $tournament);
              })
             ->when($year, function ($query) use ($year) {
                  return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
                  })
                ->where('fixtures.isActive', 1)
                 ->whereIn('running_inning', [1,2,3])
        );
}, 'subquery')
    ->select('team_id', DB::raw('COUNT(*) AS count'))
    ->groupBy('team_id')
    ->get()->pluck('count','team_id');
    $getresult = $data->orderbydesc('total_wickets')->get();

//     $query = DB::getQueryLog();
//     $query = DB::getQueryLog();
// dd($getresult);

    $inningsCount = DB::table('fixture_scores')
      ->selectRaw('COUNT(DISTINCT fixtures.id) as count, fixture_scores.bowlerId')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->when($tournament, function ($query) use ($tournament) {
        return $query->where('fixtures.tournament_id', $tournament);
    })
     ->when($year, function ($query) use ($year) {
  return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
  })
      ->groupBy('fixture_scores.bowlerId')
      ->get()->pluck('count', 'bowlerId');

      $bowlerruns= FixtureScore::when($tournament, function ($query) use ($tournament) {
        return $query->where('fixtures.tournament_id', $tournament);
    })
     ->when($year, function ($query) use ($year) {
  return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
  })
      ->selectRaw('SUM(fixture_scores.runs) as runs, fixture_scores.bowlerId')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->where('fixtures.isActive', 1)
      ->groupBy('fixture_scores.bowlerId')
      ->get()
      ->pluck('runs', 'bowlerId');

      $match_dissmissal_runout_name= Dismissal::where('dismissals.name', '=', 'Run out')
      ->selectRaw("dismissals.id as dissmissalname")
      ->get()->pluck('dissmissalname');
    
      $match_dissmissal_Retired_name= Dismissal::where('dismissals.name', '=', 'Retired')
      ->selectRaw("dismissals.id as dissmissalname")
      ->get()->pluck('dissmissalname');

      $bowlerwickets= FixtureScore::when($tournament, function ($query) use ($tournament) {
        return $query->where('fixtures.tournament_id', $tournament);
    })
     ->when($year, function ($query) use ($year) {
  return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
  })
      ->where('fixtures.isActive', 1)
      ->where('match_dismissals.dismissal_id','!=', $match_dissmissal_runout_name)
      ->where('match_dismissals.dismissal_id','!=', $match_dissmissal_Retired_name)
      ->where('fixture_scores.isout',1)
      ->where('fixture_scores.balltype','=','Wicket')
      ->selectRaw('SUM(fixture_scores.isout) as wicket, fixture_scores.bowlerId')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
      ->groupBy('fixture_scores.bowlerId')
      ->get()
      ->pluck('wicket', 'bowlerId');


    $variable1 = 'R';
    $variable2 = 'Wicket';
    $balls_faced = FixtureScore::when($tournament, function ($query) use ($tournament) {
      return $query->where('fixtures.tournament_id', $tournament);
  })
   ->when($year, function ($query) use ($year) {
return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
})
    ->where(function ($query) use ($variable1, $variable2) {
        $query->where('balltype', $variable1)
            ->orWhere('balltype', $variable2);
    })
    ->selectRaw("count(fixture_scores.id) as balls, playerId")
    ->where('fixtures.isActive', 1)
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('playerId')
    ->get()
     ->pluck('balls', 'playerId');
    

    $results = array();
    $hatricks = [];

//     foreach($getresult as $teamPlayer) {
//         $total_hat_tricks = DB::table('fixture_scores AS fs1')
//             ->join('fixture_scores AS fs2', function ($join) {
//                 $join->on('fs2.fixture_id', '=', 'fs1.fixture_id')
//                     ->where('fs2.id', '=', DB::raw('(fs1.id + 1)'))
//                     ->where('fs2.isout', '=', 1)
//                     ->where('fs2.bowlerid', '=', DB::raw('fs1.bowlerid'));
//             })
//             ->join('fixture_scores AS fs3', function ($join) {
//                 $join->on('fs3.fixture_id', '=', 'fs1.fixture_id')
//                     ->where('fs3.id', '=', DB::raw('(fs1.id + 2)'))
//                     ->where('fs3.isout', '=', 1)
//                     ->where('fs3.bowlerid', '=', DB::raw('fs1.bowlerid'));
//             })
//             ->leftJoin('fixture_scores AS fs4', function ($join) {
//                 $join->on('fs4.fixture_id', '=', 'fs1.fixture_id')
//                     ->where('fs4.id', '=', DB::raw('(fs1.id + 3)'))
//                     ->where('fs4.isout', '=', 1)
//                     ->where('fs4.bowlerid', '=', DB::raw('fs1.bowlerid'));
//             })
//             ->join('fixtures', 'fixtures.id', '=', 'fs1.fixture_id')
//             ->when($tournament, function ($query) use ($tournament) {
//               return $query->where('fixtures.tournament_id', $tournament);
//           })
//            ->when($year, function ($query) use ($year) {
//         return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
//         })
//             ->where('fixtures.isActive', 1)
//             ->where('fs1.bowlerId', $teamPlayer->bowler_id)
//             ->where('fs1.isout', '=', 1)
//             ->whereNull('fs4.id')
//             ->select(DB::raw('COUNT(*) as total_hat_tricks'))
//             ->pluck('total_hat_tricks')
//             ->toArray();
    
//         $hatricks[$teamPlayer->bowler_id] = $total_hat_tricks;
   
//       if ($bowlerwickets->has($teamPlayer->bowler_id)) {
//       $results[] = [
//         'bowler_id' => $teamPlayer->bowler_id,
//         'tournament_id' => $teamPlayer->tournament_id,
//         'player_id' => $teamPlayer->player_id,
//         'team_id' => $teamPlayer->team_id,
//         'bowler_id' => $teamPlayer->bowler_id,
//         'total_matches' => $teamPlayer->total_matches,
//         'total_overs' => $teamPlayer->total_overs/6,
//         'total_wides' => $teamPlayer->total_wides,
//         'total_noball' => $teamPlayer->total_noball,
//         'total_runs' => $teamPlayer->total_runs,
//         'player_wickets_keys' =>$bowlerwickets[$teamPlayer->bowler_id],
//       ];
//     }
      
//     }


// $player_wickets_keys = array_column($results, 'player_wickets_keys');
// array_multisort($player_wickets_keys, SORT_DESC, $results);

foreach ($getresult as $teamPlayer) {
  $existingResult = array_search($teamPlayer->player_id, array_column($results, 'player_id'));

  if ($existingResult === false) {
    $total_hat_tricks = DB::table('fixture_scores AS fs1')
    ->join('fixture_scores AS fs2', function ($join) {
        $join->on('fs2.fixture_id', '=', 'fs1.fixture_id')
            ->where('fs2.id', '=', DB::raw('(fs1.id + 1)'))
            ->where('fs2.isout', '=', 1)
            ->where('fs2.bowlerid', '=', DB::raw('fs1.bowlerid'));
    })
    ->join('fixture_scores AS fs3', function ($join) {
        $join->on('fs3.fixture_id', '=', 'fs1.fixture_id')
            ->where('fs3.id', '=', DB::raw('(fs1.id + 2)'))
            ->where('fs3.isout', '=', 1)
            ->where('fs3.bowlerid', '=', DB::raw('fs1.bowlerid'));
    })
    ->leftJoin('fixture_scores AS fs4', function ($join) {
        $join->on('fs4.fixture_id', '=', 'fs1.fixture_id')
            ->where('fs4.id', '=', DB::raw('(fs1.id + 3)'))
            ->where('fs4.isout', '=', 1)
            ->where('fs4.bowlerid', '=', DB::raw('fs1.bowlerid'));
    })
    ->join('fixtures', 'fixtures.id', '=', 'fs1.fixture_id')
    ->when($tournament, function ($query) use ($tournament) {
      return $query->where('fixtures.tournament_id', $tournament);
  })
   ->when($year, function ($query) use ($year) {
return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
})
    ->where('fixtures.isActive', 1)
    ->where('fs1.bowlerId', $teamPlayer->bowler_id)
    ->where('fs1.isout', '=', 1)
    ->whereNull('fs4.id')
    ->select(DB::raw('COUNT(*) as total_hat_tricks'))
    ->pluck('total_hat_tricks')
    ->toArray();

$hatricks[$teamPlayer->bowler_id] = $total_hat_tricks;

if ($bowlerwickets->has($teamPlayer->bowler_id)) {
$results[] = [
'bowler_id' => $teamPlayer->bowler_id,
'tournament_id' => $teamPlayer->tournament_id,
'player_id' => $teamPlayer->player_id,
'team_id' => $teamPlayer->team_id,
'bowler_id' => $teamPlayer->bowler_id,
'total_matches' => $teamPlayer->total_matches,
'total_overs' => $teamPlayer->total_overs/6,
'total_wides' => $teamPlayer->total_wides,
'total_noball' => $teamPlayer->total_noball,
'total_runs' => $teamPlayer->total_runs,
'player_wickets_keys' =>$bowlerwickets[$teamPlayer->bowler_id],
];
}
  }
}

usort($results, function ($a, $b) {
  return $b['player_wickets_keys'] - $a['player_wickets_keys'];
});

    return view('bowling_state', compact('tournamentdata','bowlerballs','results','bowlerwickets','hatricks','bowlerruns','bowlerballs','inningsCount','match_count' ,'player', 'teams', 'match_results', 'image_gallery', 'years', 'getresult'));
  }


  public function pointtable()
  {
    $match_results = Fixture::query()->where('fixtures.isActive', 1);
    $match_results = $match_results->where('isActive', '=', 1)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->get();


    $grounds = Ground::query()
      ->where('isActive', '=', 1)
      ->get();

    $umpire_matchoffcial = Umpire::query()
      ->get();


    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

    $tournamentdata = Tournament::query()
      ->where('isActive', '=', 1)
      ->pluck('name', 'id');

      $groupdata = Group::query()
      ->where('isActive', '=', 1)
      ->pluck('name', 'id');


    $result = array();

    return view('pointtable', compact('result', 'groupdata','tournamentdata', 'years', 'match_results', 'teams', "tournament_name", "grounds", "umpire_matchoffcial"));
  }

  public function pointtable_submit(Request $request)
  {

    $term = $request->input();

    DB::enableQueryLog();
    $match_results = Fixture::query();
    $match_results = $match_results->where('isActive', '=', 1)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->get();


    $grounds = Ground::query()
      ->where('isActive', '=', 1)
      ->get();

    $umpire_matchoffcial = Umpire::query()
      ->get();


    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

    $tournamentdata = Tournament::query()
      ->where('isActive', '=', 1)
      ->pluck('name', 'id');

    $groupdata = Group::query()
      ->where('isActive', '=', 1)
      ->pluck('name', 'id');


    $get_point_table_data = TournamentGroup::query()
      ->selectRaw("team_name.name as teams_name")
      ->selectRaw("team_name.id as teams_id")
      ->selectRaw("tournament_groups.tournament_id")
      ->selectRaw("tournament_groups.group_id")
      ->Join('teams as team_name', 'team_name.id', '=', 'tournament_groups.team_id');


      $match_query = Fixture::query()->where('fixtures.isActive', 1);
      if (!empty($term['year'])) {
        $year = $term['year'];
        $match_query->whereRaw("YEAR(match_startdate) = $year");
      }
      if (!empty($term['tournament'])) 
      {
          $tournament = $term['tournament'];
          $match_query->where('tournament_id', $tournament);
      }
      if (!empty($term['group'])) 
      {
          $group = $term['group'];
          $match_query->where('group_id', $group);
      }
      $match_query->where('running_inning', 3);
      $match_query->selectRaw("COUNT(id)");
      $match_query->selectRaw("team_id_a");
      $match_query->groupby('team_id_a');
      $match_count_team_a = $match_query->get()->pluck('COUNT(id)', 'team_id_a');


      $match_queryb = Fixture::query()->where('fixtures.isActive', 1);
      if (!empty($term['year'])) {
        $year = $term['year'];
        $match_queryb->whereRaw("YEAR(match_startdate) = $year");
      }
      if (!empty($term['tournament'])) 
      {
          $tournament = $term['tournament'];
          $match_queryb->where('tournament_id', $tournament);
      }
      if (!empty($term['group'])) 
      {
          $group = $term['group'];
          $match_queryb->where('group_id', $group);
      }
      $match_queryb->where('running_inning', 3);
      $match_queryb->selectRaw("COUNT(id)");
      $match_queryb->selectRaw("team_id_b");
      $match_queryb->groupby('team_id_b');
      $match_count_team_b = $match_queryb->get()->pluck('COUNT(id)', 'team_id_b');
  
    $match_count_winning = Fixture::query()->where('fixtures.isActive', 1);
    if (!empty($term['year'])) {
      $year = $term['year'];
      $match_count_winning->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $match_count_winning->where('tournament_id', $tournament);
    }
    if (!empty($term['group'])) 
    {
        $group = $term['group'];
        $match_count_winning->where('group_id', $group);
    }
    $match_count_winning->selectRaw("COUNT(id)");
    $match_count_winning->where('running_inning', 3);
    $match_count_winning->selectRaw("winning_team_id");
    $match_count_winning->groupby('winning_team_id');
    $match_count_winning_team=$match_count_winning->get()->pluck('COUNT(id)', 'winning_team_id');

    $match_count_loss= Fixture::query()->where('fixtures.isActive', 1);
    if (!empty($term['year'])) {
      $year = $term['year'];
      $match_count_loss->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $match_count_loss->where('tournament_id', $tournament);
    }
    if (!empty($term['group'])) 
    {
        $group = $term['group'];
        $match_count_loss->where('group_id', $group);
    }
    $match_count_loss->selectRaw("COUNT(id)");
    $match_count_loss->selectRaw("lossing_team_id");
    $match_count_loss->where('running_inning', 3);
    $match_count_loss->groupby('lossing_team_id');
    $match_count_loss_team=$match_count_loss->get()->pluck('COUNT(id)', 'lossing_team_id');

    $match_count_tiea= Fixture::query()->where('fixtures.isActive', 1);
    if (!empty($term['year'])) {
      $year = $term['year'];
      $match_count_tiea->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $match_count_tiea->where('tournament_id', $tournament);
    }
    if (!empty($term['group'])) 
    {
        $group = $term['group'];
        $match_count_tiea->where('group_id', $group);
    }
    $match_count_tiea->where('is_tie_match', 1);
    $match_count_tiea->selectRaw('team_id_a, COUNT(is_tie_match) as tie');
    $match_count_tiea ->groupBy('team_id_a');
    $match_count_tie_teama=$match_count_tiea->pluck('tie', 'team_id_a');

    $match_count_tieb= Fixture::query()->where('fixtures.isActive', 1);
    if (!empty($term['year'])) {
      $year = $term['year'];
      $match_count_tieb->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $match_count_tieb->where('tournament_id', $tournament);
    }
    if (!empty($term['group'])) 
    {
        $group = $term['group'];
        $match_count_tieb->where('group_id', $group);
    }
    $match_count_tieb->where('is_tie_match', 1);
    $match_count_tieb->selectRaw('team_id_b, COUNT(is_tie_match) as tie');
    $match_count_tieb ->groupBy('team_id_b');
    $match_count_tie_teamb=$match_count_tieb->pluck('tie', 'team_id_b');

    

    $bonusPoints_team_A = Fixture::query()->where('fixtures.isActive', 1);
    if (!empty($term['year'])) {
      $year = $term['year'];
      $bonusPoints_team_A->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $bonusPoints_team_A->where('tournament_id', $tournament);
    }
    if (!empty($term['group'])) 
    {
        $group = $term['group'];
        $bonusPoints_team_A->where('group_id', $group);
    }
    $bonusPoints_team_A->selectRaw('SUM(teamAbonusPoints) as totalBonusPoints');
    $bonusPoints_team_A->selectRaw('team_id_a');
    $bonusPoints_team_A->groupBy('team_id_a');
    $bonusPointsSum_team_A=$bonusPoints_team_A->pluck('totalBonusPoints', 'team_id_a');

    $bonusPoints_team_B = Fixture::query()->where('fixtures.isActive', 1);
    if (!empty($term['year'])) {
      $year = $term['year'];
      $bonusPoints_team_B->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $bonusPoints_team_B->where('tournament_id', $tournament);
    }
    if (!empty($term['group'])) 
    {
        $group = $term['group'];
        $bonusPoints_team_B->where('group_id', $group);
    }
    $bonusPoints_team_B->selectRaw('SUM(teamBbonusPoints) as totalBonusPoints');
    $bonusPoints_team_B->selectRaw('team_id_b');
    $bonusPoints_team_B->groupBy('team_id_b');
    $bonusPointsSum_team_B=$bonusPoints_team_B->pluck('totalBonusPoints', 'team_id_b');

    $tournament_data_id=0;
    if (!empty($term['tournament'])) {
      $tournament_data_id = $term['tournament'];
      
    }
    $ApiController = new ApiController();
    $net_run_rate_result = $ApiController->calculateNetRunRate($tournament_data_id);
    $point_table_net_rr = $net_run_rate_result['point_table_net_rr'];
   




    // dd($term);
    if (!empty($term['year'])) {
      $year = $term['year'];
      $get_point_table_data->whereRaw("YEAR(tournament_groups.created_at) = $year");
    }
    if (!empty($term['tournament'])) {
      $tournament = $term['tournament'];
      $get_point_table_data->where('tournament_groups.tournament_id', '=', $tournament);
    }
    if (!empty($term['group'])) 
    {
        $group = $term['group'];
        $get_point_table_data->where('tournament_groups.group_id', '=', $group);
    }

    $getdata = $get_point_table_data->get()->pluck('teams_name', 'teams_id');
    $result = array();
    foreach ($getdata as $team_id => $team_name) {
      $team_netrr = 0;
      $team_wins = isset($match_count_winning_team[$team_id]) ? $match_count_winning_team[$team_id] : 0;

      $team_losses = isset($match_count_loss_team[$team_id]) ? $match_count_loss_team[$team_id] : 0;
      $team_total_matches = isset($match_count_team_a[$team_id]) ? $match_count_team_a[$team_id] : 0;
      $team_tie_A = isset($match_count_tie_teama[$team_id]) ? $match_count_tie_teama[$team_id] : 0;
      $team_tie_B = isset($match_count_tie_teamb[$team_id]) ? $match_count_tie_teamb[$team_id] : 0;
      $team_tie = $team_tie_A+$team_tie_B;


      $bonus_points_A = isset($bonusPointsSum_team_A[$team_id]) ? $bonusPointsSum_team_A[$team_id] : 0;
      $bonus_points_B = isset($bonusPointsSum_team_B[$team_id]) ? $bonusPointsSum_team_B[$team_id] : 0;


      $total_bonus_points = $bonus_points_A + $bonus_points_B;

      if (isset($match_count_team_b[$team_id])) {
        $team_total_matches += $match_count_team_b[$team_id];
      }

      $team_players_count = isset($team_players[$team_id]) ? $team_players[$team_id] : 0;

      if(count($point_table_net_rr)>0)
      {
          foreach($point_table_net_rr as $netrr_team)
          {
              // dd($netrr_team);
              if( $netrr_team->team_id==$team_id)
              {
                  $team_netrr += $netrr_team->net_rr    ;    
              }
          }
      }

    

      $result[] = [
        'team_id' => $team_id,
        'team_name' => $team_name,
        'total_matches' => $team_total_matches,
        'wins' => $team_wins,
        'losses' => $team_losses,
        'draws' => $team_tie,
        'players_count' => $team_players_count,
        'teambonusPoints' => $total_bonus_points,
        'net_rr' => $team_netrr,
      ];
    }


    return view('pointtable', compact('result','groupdata','tournamentdata', 'years', 'match_results', 'teams', "tournament_name", "grounds", "umpire_matchoffcial"));
  }

  public function fieldingRecords()
  {

    $tournamentdata = Tournament::query()
      ->where('isActive', '=', 1)
      ->where('is_web_display' , '=' , 1)
      ->get()
      ->pluck(
        'name',
        'id'
      );
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $match_results = Fixture::query()->where('fixtures.isActive', 1);
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    DB::enableQueryLog();

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');
    $getresult = [];
    $match_count_player = collect();
    $player_runs = collect();
    $balls_faced = collect();
    $sixes = collect();
    $fours = [];






    $match_dissmissal_name = Dismissal::where('dismissals.name', '=', 'Caught')
      ->selectRaw("dismissals.id as dissmissalname")
      ->groupBy('dismissals.id')
      ->get()->pluck('dissmissalname');

    // dd($match_dissmissal_name);



    $bowlerMatches = DB::table('fixture_scores')->select('bowlerId as id', DB::raw('COUNT(DISTINCT fixture_id) as match_count'))
      ->whereRaw('YEAR(created_at) = ?', [2023])
      ->where('isout', '=', 1)
      ->groupBy('bowlerId')
      ->get();

    // dd( $bowlerMatches);

    // ->leftJoin('match_dismissals','match_dismissals.fixturescores_id','fixture_scores.id')?


    $bowlerdata = FixtureScore::query()
      ->selectRaw('fixture_scores.bowlerId as bowler_id')
      ->selectRaw('COUNT(DISTINCT fixture_scores.fixture_id) as total_matches')
      ->whereRaw('YEAR(fixture_scores.created_at) = ?', [2023])
      ->where('fixture_scores.isout', '=', 1)
      ->groupBy('fixture_scores.bowlerId')
      ->get();
      $fieldingresults=[];

    return view('fieldingRecords', compact('fours', 'fieldingresults','balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_count_player', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years'));
  }



  


    public function fielding_states_submit(Request $request)
  {


    $tournamentdata = Tournament::query()
    ->where('isActive', '=', 1)
    ->where('is_web_display' , '=' , 1)
    ->get()
    ->pluck(
      'name',
      'id'
    );


    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

    $match_dissmissal_caught = Dismissal::where('dismissals.name', '=', 'Caught')
      ->first();


    $match_dissmissal_stumped = Dismissal::where('dismissals.name', '=', 'Stumped')->first();

    $dismissalIdcatch = $match_dissmissal_caught->id;
    $dismissalIdstump = $match_dissmissal_stumped->id;

    $getresult = [];

    $team_players_list = $result = DB::table('teams')
    ->join('team_players', 'team_players.team_id', '=', 'teams.id')
    ->join('players', 'players.id', '=', 'team_players.player_id')
    ->pluck('teams.name', 'players.id');


    $term = $request->input();


    $data = Fixture::query();
    $term = $request->input();
    $tournament = $term['tournament'];
    $year = $term['year'];
   
    $data->selectRaw('tournament_players.player_id')
    ->where('fixtures.isActive', '=', 1)
      ->selectRaw('tournament_players.team_id')
      ->selectRaw('players.fullname as player_name')
      ->join('tournament_players', 'tournament_players.tournament_id', '=', 'fixtures.tournament_id')
      ->join('players', 'players.id', '=', 'tournament_players.player_id');
    

      $match_dismissal_name = Dismissal::where('name', 'Caught')
      ->pluck('id');
      $dismissalIdstump = $match_dissmissal_stumped->id ;
    

    $catch_query = MatchDismissal::query()
    ->where('fixtures.isActive', '=', 1)
    ->where('match_dismissals.dismissal_id', $match_dismissal_name)
    ->selectRaw("COUNT(match_dismissals.id) as catch_count,outbyplayer_id")
    ->join('fixtures', 'fixtures.id', '=', 'match_dismissals.fixture_id')
    ->groupBy('match_dismissals.outbyplayer_id');

    $stump_query = MatchDismissal::query()
    ->where('fixtures.isActive', '=', 1)
    ->where('match_dismissals.dismissal_id', $dismissalIdstump)
    ->selectRaw("COUNT(match_dismissals.id) as stump,outbyplayer_id")
    ->join('fixtures', 'fixtures.id', '=', 'match_dismissals.fixture_id')
    ->groupBy('match_dismissals.outbyplayer_id');  


    if(!empty($term['year'])) {
      $year = $term['year'];
        $catch_query->whereRaw("YEAR(fixtures.match_startdate) = $year");
        $stump_query->whereRaw("YEAR(fixtures.match_startdate) = $year"); 
        $data->whereRaw("YEAR(fixtures.match_startdate) = $year");
    }

    if (!empty($term['teams'])) {
      $team_id = $term['teams'];
      $data->where('tournament_players.team_id', '=', $team_id);


    }
    
    if (!empty($term['tournament'])) {
      $tournament = $term['tournament'];
      $tournament = (int)$tournament;
      $catch_query->where('fixtures.tournament_id', $tournament);
      $stump_query->where('fixtures.tournament_id', $tournament);
      $data->where('fixtures.tournament_id', '=', $tournament);
      // $query_caught .= " AND tournaments.id = $tournament";
    }


    $catchs_data = $catch_query->pluck('catch_count', 'outbyplayer_id');
    $stump_data = $stump_query->pluck('stump', 'outbyplayer_id');


    $getresult = $data
    ->distinct('tournament_players.player_id')
    ->groupby('tournament_players.player_id','tournament_players.team_id')
    ->get();

    
    $teamIds = TournamentGroup::when($tournament, function ($query) use ($tournament) {
      return $query->where('tournament_id', $tournament);
  })
      ->select('team_id')
      ->groupBy('team_id')
      ->pluck('team_id');
  


  $match_count = DB::table(function ($query) use ($teamIds, $tournament,$year) {
      $query->select('team_id_a AS team_id')
          ->from('fixtures')
          ->whereIn('team_id_a', $teamIds)
           ->when($tournament, function ($query) use ($tournament) {
      return $query->where('fixtures.tournament_id', $tournament);
  })
   ->when($year, function ($query) use ($year) {
return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
})
          ->where('fixtures.isActive', 1)
          ->whereIn('running_inning', [1,2,3])
          ->unionAll(
              DB::table('fixtures')
                  ->select('team_id_b AS team_id')
                  ->whereIn('team_id_b', $teamIds)
                   ->when($tournament, function ($query) use ($tournament) {
      return $query->where('fixtures.tournament_id', $tournament);
  })
   ->when($year, function ($query) use ($year) {
return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
})
                  ->where('fixtures.isActive', 1)
                  ->whereIn('running_inning', [1,2,3])
          );
  }, 'subquery')
      ->select('team_id', DB::raw('COUNT(*) AS count'))
      ->groupBy('team_id')
      ->get()->pluck('count','team_id');
      // dd($match_count);

      $fieldingresults = array();

      foreach($getresult as $data) {
        $fieldingresults[] = [
          'player_id' =>$data->player_id,
          'player_name' => $data->player_name,
          'team_players_list' => $team_players_list[$data->player_id],
          'playermatch_countmatch' => $match_count[$data->team_id]??0,
          'catchs_data' => $catchs_data[$data->player_id]??0,
          'stump_data' => $stump_data[$data->player_id]??0,
          'totalfieldingdata' =>($stump_data[$data->player_id] ?? 0) + ($catchs_data[$data->player_id] ?? 0),
        ];
        
      }
  
  
  $totalfieldingdata = array_column($fieldingresults, 'totalfieldingdata');
  array_multisort($totalfieldingdata, SORT_DESC, $fieldingresults);


      if (!isset($stump_data)) {
        $stump_data = [];
    }

    return view('fieldingRecords', compact('getresult', 'fieldingresults','years','tournamentdata','team_players_list', 'catchs_data','stump_data', 'match_count' ));
  }



  public function playerRanking()
  {

    $tournamentdata = Tournament::query()
    ->where('isActive', '=', 1)
    ->where('is_web_display' , '=' , 1)
    ->get()
    ->pluck(
      'name',
      'id'
    );
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $match_results = Fixture::query();
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    DB::enableQueryLog();

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');
    $getresult = [];
    $match_count_player = collect();
    $player_runs = collect();
    $balls_faced = collect();
    $sixes = collect();
    $fours = [];


    $match_dissmissal_name = Dismissal::where('dismissals.name', '=', 'Caught')
      ->selectRaw("dismissals.id as dissmissalname")
      ->groupBy('dismissals.id')
      ->get()->pluck('dissmissalname');


    $bowlerMatches = DB::table('fixture_scores')->select('bowlerId as id', DB::raw('COUNT(DISTINCT fixture_id) as match_count'))
      ->whereRaw('YEAR(created_at) = ?', [2023])
      ->where('isout', '=', 1)
      ->groupBy('bowlerId')
      ->get();


    $bowlerdata = FixtureScore::query()
      ->selectRaw('fixture_scores.bowlerId as bowler_id')
      ->selectRaw('COUNT(DISTINCT fixture_scores.fixture_id) as total_matches')
      ->whereRaw('YEAR(fixture_scores.created_at) = ?', [2023])
      ->where('fixture_scores.isout', '=', 1)
      ->groupBy('fixture_scores.bowlerId')
      ->get();

    // mubeen working on player ranking

  


$Player_Rank_Data = [];


$playermatch='';

$Player_Batting_totalPoints = '';

$Player_Bowling_points = '';

$Player_totalPoints = '';

$Player_MOTM_Points = '';

$results = array();

    return view('playerRanking', compact('results','Player_Rank_Data','fours', 'balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_count_player', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years'));
  }

  public function playerRanking_submit(Request $request)
  {
    $tournamentdata = Tournament::query()
    ->where('isActive', '=', 1)
    ->where('is_web_display' , '=' , 1)
    ->get()
    ->pluck(
      'name',
      'id'
    );
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $match_results = Fixture::query() ->where('fixtures.isActive', 1);
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
   

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');
    $getresult = [];
    $match_count_player = collect();
    $player_runs = collect();
    $balls_faced = collect();
    $sixes = collect();
    $fours = [];


// mubeen working on player ranking

$term = $request->all(); 
$tournamentID = $term['tournament'];
$year = $term['year'];
DB::enableQueryLog();
$PlayerRankingData = Fixture::query()
    ->where('fixtures.isActive', 1)
    ->select('players_contain_points.player_id', 'players_contain_points.tournament_id', 'players_contain_points.team_id')
    ->join('players_contain_points', 'players_contain_points.tournament_id', '=', 'fixtures.tournament_id');




if (!empty($term['year'])) {
  $year = $term['year'];
  $PlayerRankingData->whereRaw("YEAR(fixtures.match_startdate) = $year");
}

if (!empty($term['tournament'])) {
    $PlayerRankingData->where('players_contain_points.tournament_id',$tournamentID );
}

if (!empty($term['teams'])) {
    $PlayerRankingData->where('players_contain_points.team_id', $term['teams']);
    $PlayerRankingData->where('players_contain_points.tournament_id',$tournamentID );
}

$Player_Rank_Data = $PlayerRankingData
->groupBy('players_contain_points.player_id', 'players_contain_points.tournament_id', 'players_contain_points.team_id')
->distinct()
->get();

$tournament_id = $Player_Rank_Data->pluck('tournament_id')->first();
$team_ids = $Player_Rank_Data->pluck('team_id');

$playermatch = DB::table(function ($query) use ($team_ids, $tournamentID,$year) {
  $query->select('team_id_a AS team_id')
      ->from('fixtures')
      ->whereIn('team_id_a', $team_ids)
      ->when($tournamentID, function ($query) use ($tournamentID) {
        return $query->where('fixtures.tournament_id', $tournamentID);
    })
     ->when($year, function ($query) use ($year) {
  return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
  })
      ->where('fixtures.isActive', 1)
      ->whereIN('running_inning',[ 3,1,2])
      ->unionAll(
          DB::table('fixtures')
              ->select('team_id_b AS team_id')
              ->whereIn('team_id_b', $team_ids)
              ->when($tournamentID, function ($query) use ($tournamentID) {
                return $query->where('fixtures.tournament_id', $tournamentID);
            })
             ->when($year, function ($query) use ($year) {
          return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
          })
              ->where('fixtures.isActive', 1)
              ->whereIN('running_inning',[ 3,1,2])
      );
}, 'subquery')
  ->select('team_id', DB::raw('COUNT(*) AS count'))
  ->groupBy('team_id')
  ->get()
  ->pluck('count', 'team_id');

  $Player_Batting_points = DB::table('players_contain_points')
  ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
  ->when($tournamentID, function ($query) use ($tournamentID) {
    return $query->where('players_contain_points.tournament_id', $tournamentID);
})
  ->where('player_type', 'batsmen')
  ->join('fixtures', function ($join) use ($year) {
        $join->on('fixtures.id', '=', 'players_contain_points.fixture_id')
            ->where('fixtures.isActive', 1)
            ->when($year, function ($query) use ($year) {
                return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
            });
    })
  ->groupBy('player_id')
  ->get();

$Player_Batting_totalPoints = $Player_Batting_points->pluck('totalpoints', 'player_id');

$Player_Bowling_points = DB::table('players_contain_points')
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->when($tournamentID, function ($query) use ($tournamentID) {
      return $query->where('players_contain_points.tournament_id', $tournamentID);
  })
    ->where('player_type', 'Bowler')
    ->join('fixtures', function ($join) use ($year) {
        $join->on('fixtures.id', '=', 'players_contain_points.fixture_id')
            ->where('fixtures.isActive', 1)
            ->when($year, function ($query) use ($year) {
                return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
            });
    })
    ->groupBy('player_id')
    ->get();

$Player_Bowling_totalPoints = $Player_Bowling_points->pluck('totalpoints', 'player_id');

$Player_Fielder_points = DB::table('players_contain_points')
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->when($tournamentID, function ($query) use ($tournamentID) {
      return $query->where('players_contain_points.tournament_id', $tournamentID);
  })
    ->where('player_type', 'Fielder')
    ->join('fixtures', function ($join) use ($year) {
        $join->on('fixtures.id', '=', 'players_contain_points.fixture_id')
            ->where('fixtures.isActive', 1)
            ->when($year, function ($query) use ($year) {
                return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
            });
    })
    ->groupBy('player_id')
    ->get();

$Player_Fielder_totalPoints = $Player_Fielder_points->pluck('totalpoints', 'player_id');

$Player_totalpoints = DB::table('players_contain_points')
->when($tournamentID, function ($query) use ($tournamentID) {
  return $query->where('players_contain_points.tournament_id', $tournamentID);
})
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->join('fixtures', function ($join) use ($year) {
        $join->on('fixtures.id', '=', 'players_contain_points.fixture_id')
            ->where('fixtures.isActive', 1)
            ->when($year, function ($query) use ($year) {
                return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
            });
    })
    ->groupBy('player_id')
    ->orderbydesc('totalpoints')
    ->get();

$Player_totalPoints = $Player_totalpoints->pluck('totalpoints', 'player_id');

$points = DB::table('players_points_types')
    ->where('code', 'MOTM')
    ->pluck('points')
    ->first(); 

$player_ids = $Player_Rank_Data->pluck('player_id');
$Player_MOTM_Points = Fixture::whereIn('manofmatch_player_id', $player_ids)
->when($tournamentID, function ($query) use ($tournamentID) {
  return $query->where('fixtures.tournament_id', $tournamentID);
})
->when($year, function ($query) use ($year) {
  return $query->whereRaw("YEAR(fixtures.match_startdate) = $year");
})
    ->where('fixtures.isActive', 1)
    ->select(DB::raw("SUM($points) as playermompoints, manofmatch_player_id"))
    ->groupBy('manofmatch_player_id')
    ->get()
    ->pluck('playermompoints', 'manofmatch_player_id');

    $results = array();

//     foreach($Player_Rank_Data as $data) {
//       $results[] = [
//         'player_id' => $data->player_id,
//         'team_id' => $data->team_id,
//         'playermatch' => $playermatch[$data->team_id]??0,
//         'Player_Batting_totalPoints' => $Player_Batting_totalPoints[$data->player_id]??0,
//         'Player_Bowling_totalPoints' => $Player_Bowling_totalPoints[$data->player_id]??0,
//         'Player_Fielder_totalPoints' => $Player_Fielder_totalPoints[$data->player_id]??0,
//         'Player_MOTM_Points' => $Player_MOTM_Points[$data->player_id]??0,
//         'total_point' =>($Player_Fielder_totalPoints[$data->player_id] ?? 0) +($Player_Bowling_totalPoints[$data->player_id] ?? 0) +  ($Player_Batting_totalPoints[$data->player_id] ?? 0) + ($Player_MOTM_Points[$data->player_id] ?? 0),
//       ];
      
//     }


// $total_point = array_column($results, 'total_point');
// array_multisort($total_point, SORT_DESC, $results);

foreach ($Player_Rank_Data as $data) {
  $existingResult = array_search($data->player_id, array_column($results, 'player_id'));

  if ($existingResult === false) {
    $results[] = [
      'player_id' => $data->player_id,
      'team_id' => $data->team_id,
      'playermatch' => $playermatch[$data->team_id]??0,
      'Player_Batting_totalPoints' => $Player_Batting_totalPoints[$data->player_id]??0,
      'Player_Bowling_totalPoints' => $Player_Bowling_totalPoints[$data->player_id]??0,
      'Player_Fielder_totalPoints' => $Player_Fielder_totalPoints[$data->player_id]??0,
      'Player_MOTM_Points' => $Player_MOTM_Points[$data->player_id]??0,
      'total_point' =>($Player_Fielder_totalPoints[$data->player_id] ?? 0) +($Player_Bowling_totalPoints[$data->player_id] ?? 0) +  ($Player_Batting_totalPoints[$data->player_id] ?? 0) + ($Player_MOTM_Points[$data->player_id] ?? 0),
    ];
  }
}

usort($results, function ($a, $b) {
  return $b['total_point'] - $a['total_point'];
});

return view('playerRanking', compact('results','Player_Rank_Data','fours','balls_faced', 'sixes', 'balls_faced', 'player_runs', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years' ));
  }

  public function show_point_table(int $tournament_id)
  {

    $match_results = Fixture::query() ->where('fixtures.isActive', 1);
    $match_results = $match_results->where('isActive', '=', 1)->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $sponsor_gallery = Sponsor::query()
      ->where('isActive', '=', 1)
      ->get();

    $tournament_name = Tournament::query()
      ->where('season_id', '=', 0)
      ->where('is_web_display', '=', 1)
      ->get();


    $grounds = Ground::query()
      ->where('isActive', '=', 1)
      ->get();

    $umpire_matchoffcial = Umpire::query()
      ->get();


    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

    $tournamentdata = Tournament::query()
      ->where('isActive', '=', 1)
      ->pluck('name', 'id');

      $groupdata = Group::query()
      ->where('isActive', '=', 1)
      ->pluck('name', 'id');

      $get_point_table_data = TournamentGroup::where('tournament_id', '=', $tournament_id)
      ->selectRaw("team_name.name as teams_name")
      ->selectRaw("team_name.id as teams_id")
      ->Join('teams as team_name', 'team_name.id', '=', 'tournament_groups.team_id')
      ->get()->pluck('teams_name', 'teams_id');

  $match_count_team_a = Fixture::where('tournament_id', '=', $tournament_id)
     ->whereIN('running_inning',[1,2,3])
     ->where('fixtures.isActive', 1)
      ->selectRaw("COUNT(id)")
      ->selectRaw("team_id_a")
      ->groupby('team_id_a')
      ->get()->pluck('COUNT(id)', 'team_id_a');

  $match_count_team_b = Fixture::where('tournament_id', '=', $tournament_id)
  ->where('fixtures.isActive', 1)
  ->whereIn('running_inning', [1,2,3])
      ->selectRaw("COUNT(id)")
      ->selectRaw("team_id_b")
      ->groupby('team_id_b')
      ->get()->pluck('COUNT(id)', 'team_id_b');

  $match_count_winning_team = Fixture::where('tournament_id', '=', $tournament_id)
  ->where('fixtures.isActive', 1)
      ->selectRaw("COUNT(id)")
      ->selectRaw("winning_team_id")
      ->groupby('winning_team_id')
      ->get()->pluck('COUNT(id)', 'winning_team_id');

  $match_count_loss_team = Fixture::where('tournament_id', '=', $tournament_id)
  ->where('fixtures.isActive', 1)
      ->selectRaw("COUNT(id)")
      ->selectRaw("lossing_team_id")
      ->groupby('lossing_team_id')
      ->get()->pluck('COUNT(id)', 'lossing_team_id');
      
      $match_count_tie_team_b = Fixture::where('tournament_id', $tournament_id)
      ->where('fixtures.isActive', 1)
      ->where('is_tie_match', 1)
      ->selectRaw('team_id_b, COUNT(is_tie_match) as tie')
      ->groupBy('team_id_b')
      ->pluck('tie', 'team_id_b');

      $match_count_tie_team_a = Fixture::where('tournament_id', $tournament_id)
      ->where('fixtures.isActive', 1)
      ->where('is_tie_match', 1)
      ->selectRaw('team_id_a, COUNT(is_tie_match) as tie')
      ->groupBy('team_id_a')
      ->pluck('tie', 'team_id_a');
  
      $ApiController = new ApiController();
      $net_run_rate_result = $ApiController->calculateNetRunRate($tournament_id);
      $point_table_net_rr = $net_run_rate_result['point_table_net_rr'];
      
  $result = array();
  
  foreach ($get_point_table_data as $team_id => $team_name) {
      $team_netrr = 0;
      $team_wins = isset($match_count_winning_team[$team_id]) ? $match_count_winning_team[$team_id] : 0;
      $team_losses = isset($match_count_loss_team[$team_id]) ? $match_count_loss_team[$team_id] : 0;
      $team_total_matches = isset($match_count_team_a[$team_id]) ? $match_count_team_a[$team_id] : 0;
      
      if(count($point_table_net_rr)>0)
{
    foreach($point_table_net_rr as $netrr_team)
    {
        if( $netrr_team->team_id==$team_id)
        {
            $team_netrr += $netrr_team->net_rr    ;    
        }
    }
}


  if (isset($match_count_team_b[$team_id])) {
      $team_total_matches += $match_count_team_b[$team_id];
  }

  $team_players_count = isset($team_players[$team_id]) ? $team_players[$team_id] : 0;
  $bonus_points_A = isset($bonusPointsSum_team_A[$team_id]) ? $bonusPointsSum_team_A[$team_id] : 0;
  $bonus_points_B = isset($bonusPointsSum_team_B[$team_id]) ? $bonusPointsSum_team_B[$team_id] : 0;
  $team_tie__A = isset($match_count_tie_team_a[$team_id]) ? $match_count_tie_team_a[$team_id] : 0;
        $team_tie__B = isset($match_count_tie_team_b[$team_id]) ? $match_count_tie_team_b[$team_id] : 0;
        $team_tie = $team_tie__A+$team_tie__B;
  $total_bonus_points = $bonus_points_A + $bonus_points_B;

  $result[] = [
      'tournament_id' => $tournament_id,
      'team_id' => $team_id,
      'team_name' => $team_name,
      'total_matches' => $team_total_matches,
      'wins' => $team_wins,
      'losses' => $team_losses,
      'draws' => $team_tie,
      'players_count' => $team_players_count,
      'teambonusPoints' => $total_bonus_points,
      'net_rr' => $team_netrr,
  ];
}


    return view('pointtable', compact('result','groupdata', 'tournamentdata', 'years', 'match_results', 'teams', "tournament_name", "grounds", "umpire_matchoffcial"));
  }

  public function show_batting_records(int $tournament_id)
  {
    $match_results = Fixture::query()
    ->where('fixtures.isActive', 1)
    ->where('running_inning', 3)
    ->orderBy('id')
    ->get();
  $player = Player::query()->get()->pluck(
    'fullname',
    'id'
  );
  $teams = Team::query()
    ->pluck('name', 'id');

  $years = DB::table('fixtures')
    ->select(DB::raw('YEAR(created_at) as year'))
    ->groupBy(DB::raw('YEAR(created_at)'))
    ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
    ->pluck('year');

  $tournamentdata = Tournament::query()
    ->where('isActive', '=', 1)->where('isActive', '=', 1)
    ->pluck('name', 'id');

  $image_gallery = GalleryImages::query()
    ->where('isActive', 1)
    ->get();

  $data = Fixture::query()->where('fixtures.isActive', 1);
 
  $data->selectRaw('tournament_players.player_id')
    ->selectRaw('tournament_players.team_id')
    ->join('tournament_players', 'tournament_players.tournament_id', '=', 'fixtures.tournament_id');
  
  $getresult = $data
  ->distinct('tournament_players.player_id')
  ->groupby('tournament_players.player_id','tournament_players.team_id')
  ->get();

  
  $hundreds = [];
 
  $higest_score = [];
  DB::enableQueryLog();

    $teamIds = TournamentGroup::where('tournament_id', $tournament_id)
    ->select('team_id')
    ->groupBy('team_id')
    ->pluck('team_id');

    $match_count = DB::table(function ($query) use ($teamIds, $tournament_id) {
      $query->select('team_id_a AS team_id')
          ->from('fixtures')
          ->whereIn('team_id_a', $teamIds)
          ->where('tournament_id', $tournament_id)
          ->whereIN('running_inning',[ 3,1,2])
          ->where('fixtures.isActive', 1)
          
          ->unionAll(
              DB::table('fixtures')
                  ->select('team_id_b AS team_id')
                  ->whereIn('team_id_b', $teamIds)
                  ->where('tournament_id', $tournament_id)
                  ->whereIN('running_inning',[ 3,1,2])
                  ->where('fixtures.isActive', 1)
          );
      }, 'subquery')
      ->select('team_id', DB::raw('COUNT(*) AS count'))
      ->groupBy('team_id')
      ->get()->pluck('count','team_id');

  $inningsCount = DB::table('fixture_scores')
    ->selectRaw('COUNT(DISTINCT fixtures.id) as count, fixture_scores.playerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->where('fixtures.isActive', 1)
    ->where('fixtures.tournament_id', $tournament_id)
    ->groupBy('fixture_scores.playerId')
    ->get()->pluck('count', 'playerId');

  $player_runs= FixtureScore::where('fixtures.tournament_id',$tournament_id)
  ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END)  as totalruns, fixture_scores.playerId")
    ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
    ->where('fixtures.isActive', 1)
    ->orderBy('totalruns', 'desc')
    ->groupBy('fixture_scores.playerId')
    ->get()->pluck('totalruns', 'playerId');


   

    $variable1 = 'R';
    $variable2 = 'Wicket';
    $variable3 = 'RunOut';
    
    
    $balls_faced = FixtureScore::where('fixtures.tournament_id', $tournament_id)
      ->where(function ($query) use ($variable1, $variable2,$variable3) {
        $query->where('balltype', '=', $variable1)
          ->orWhere('balltype', '=', $variable2)
          ->orWhere('balltype', '=', $variable3)
;
  })
  ->selectRaw("count(fixture_scores.id) as balls, playerId")
  ->where('fixtures.isActive', 1)
  ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
  ->groupBy('playerId')
  ->get()
   ->pluck('balls', 'playerId');

  $sixes= FixtureScore::where('fixtures.tournament_id', $tournament_id)
  ->where('issix', 1)
  ->where('fixtures.isActive', 1)
  ->selectRaw('COUNT(*) as six, fixture_scores.playerId')
  ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
  ->groupBy('fixture_scores.playerId')
  ->get()
  ->pluck('six', 'playerId');


$fours= FixtureScore::where('fixtures.tournament_id', $tournament_id)
  ->where('isfour', 1)
  ->where('fixtures.isActive', 1)
  ->selectRaw('COUNT(*) as four, fixture_scores.playerId')
  ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
  ->groupBy('fixture_scores.playerId')
  ->get()
  ->pluck('four', 'playerId');

$playerouts =Fixture::where('tournament_id', $tournament_id)
  ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
  ->where('fixtures.isActive', 1)
  ->where('fixture_scores.balltype','=','Wicket')
  ->where('fixture_scores.isout','=',1)
  ->groupBy('fixture_scores.playerId')
  ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, fixture_scores.playerId')
  ->pluck('playeouts', 'playerId');


 $fifty=DB::table(function ($query) use ($tournament_id) {
        $query->select('playerId', DB::raw('SUM(runs) AS fifties'), 'fixture_id')
            ->from('fixture_scores')
            ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
            ->where('fixtures.tournament_id', $tournament_id)
            ->where('fixtures.isActive', 1)
            ->groupBy('playerId', 'fixture_id');
    }, 'subquery')
    ->select('playerId', DB::raw('COUNT(*) AS fifties'))
    ->where('fifties', '>=', 50)
    ->where('fifties', '<', 100)
    ->groupBy('playerId')
    ->get()->pluck('fifties', 'playerId');

  $hundreds=DB::table(function ($query) use ($tournament_id) {
    $query->select('playerId', DB::raw('SUM(runs) AS hundred'), 'fixture_id')
        ->from('fixture_scores')
        ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
        ->where('fixtures.tournament_id', $tournament_id)
        ->where('fixtures.isActive', 1)
        ->groupBy('playerId', 'fixture_id');
    }, 'subquery')
    ->select('playerId', DB::raw('COUNT(*) AS hundred'))
    ->where('hundred', '>=', 100)
    ->groupBy('playerId')
    ->get()->pluck('hundred', 'playerId');
        
      
    $results = array();
    foreach ($getresult as $teamPlayer) {
      $higest_score_query = FixtureScore::where('playerId', $teamPlayer->player_id)
      ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as total_runs, fixture_id")
      ->where('fixtures.isActive', 1)
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->groupBy('fixture_id')
      ->orderbydesc('total_runs')
      ->limit(1);
        $higest_score[$teamPlayer->player_id] = $higest_score_query->value('total_runs');
      
        if ($player_runs->has($teamPlayer->player_id)) {

      $results[] = [
        'player_id' => $teamPlayer->player_id,
        'team_id' => $teamPlayer->team_id,
        'player_runs_keys' =>$player_runs[$teamPlayer->player_id],
      ];
      }
    }

$player_runs_keys = array_column($results, 'player_runs_keys');
array_multisort($player_runs_keys, SORT_DESC, $results);

    return view('batting_states', compact('fours', 'higest_score', 'fifty', 'hundreds', 'balls_faced', 'sixes', 'tournamentdata', 'balls_faced', 'player_runs', 'match_count', 'player', 'teams', 'match_results', 'image_gallery', 'years', 'getresult','inningsCount','playerouts', 'results'));
  }


  public function show_bowling_records(int $tournament_id)
  {

    $match_results = Fixture::query()  ->where('fixtures.isActive', 1)
    ->where('running_inning', 3)
    ->orderBy('id')
    ->get();
  $player = Player::query()->get()->pluck(
    'fullname',
    'id'
  );
  $teams = Team::query()
    ->pluck('name', 'id');

  $years = DB::table('fixtures')
    ->select(DB::raw('YEAR(created_at) as year'))
    ->groupBy(DB::raw('YEAR(created_at)'))
    ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
    ->pluck('year');

  $tournamentdata = Tournament::query()
    ->where('isActive', '=', 1)
    ->pluck('name', 'id');

  $image_gallery = GalleryImages::query()
    ->where('isActive', 1)
    ->get();


 
  $data = TournamentPlayer::where('tournament_players.tournament_id', $tournament_id)
  ->where('fixtures.isActive', 1)
    ->selectRaw('fixture_scores.bowlerId as bowler_id')
    ->selectRaw('tournament_players.tournament_id')
    ->selectRaw('team_players.player_id')
    ->selectRaw('team_players.team_id')
    ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches')
    ->selectRaw('COUNT(DISTINCT fixture_scores.ballnumber) as total_overs')
    ->selectRaw('SUM(fixture_scores.balltype = "WD") as total_wides')
    ->selectRaw('SUM(fixture_scores.balltype = "NB") as total_noball')
    ->selectRaw('SUM(fixture_scores.runs) as total_runs')
    ->selectRaw('SUM(CASE WHEN fixture_scores.isout = 1 THEN 1 ELSE 0 END) as total_wickets')
    ->join('team_players', function ($join) {
      $join->on('team_players.team_id', '=', 'tournament_players.team_id')
        ->on('team_players.player_id', '=', 'tournament_players.player_id');
    })
    ->join('fixture_scores', 'fixture_scores.bowlerId', '=', 'team_players.player_id')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('team_players.player_id', 'team_players.team_id')
    ->groupBy('fixture_scores.bowlerId','tournament_players.tournament_id');




  $teamIds = TournamentGroup::where('tournament_id', $tournament_id)
  ->select('team_id')
  ->groupBy('team_id')
  ->pluck('team_id');

  

  $variable1 = 'R';
  $variable2 = 'Wicket';
  $bowlerballs = FixtureScore::where('fixtures.tournament_id', $tournament_id)
  ->where(function ($query) use ($variable1, $variable2) {
      $query->where('balltype', $variable1)
          ->orWhere('balltype', $variable2);
  })
  ->selectRaw("count(fixture_scores.id) as balls, bowlerId")
  ->where('fixtures.isActive', 1)
  ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
  ->groupBy('bowlerId')
  ->get()
   ->pluck('balls', 'bowlerId');

  $match_count = DB::table(function ($query) use ($teamIds, $tournament_id) {
  $query->select('team_id_a AS team_id')
      ->from('fixtures')
      ->whereIn('team_id_a', $teamIds)
      ->where('tournament_id', $tournament_id)
      ->where('fixtures.isActive', 1)
      ->where('running_inning', 3)
      ->unionAll(
          DB::table('fixtures')
              ->select('team_id_b AS team_id')
              ->whereIn('team_id_b', $teamIds)
              ->where('tournament_id', $tournament_id)
              ->where('fixtures.isActive', 1)
               ->where('running_inning', 3)
      );
}, 'subquery')
  ->select('team_id', DB::raw('COUNT(*) AS count'))
  ->groupBy('team_id')
  ->get()->pluck('count','team_id');
  $getresult = $data->orderbydesc('total_wickets')->get();

  $inningsCount = DB::table('fixture_scores')
    ->selectRaw('COUNT(DISTINCT fixtures.id) as count, fixture_scores.bowlerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->where('fixtures.isActive', 1)
    ->where('fixtures.tournament_id', $tournament_id)
    ->groupBy('fixture_scores.bowlerId')
    ->get()->pluck('count', 'bowlerId');

    $bowlerruns= FixtureScore::where('fixtures.tournament_id', $tournament_id)
    ->selectRaw('SUM(fixture_scores.runs) as runs, fixture_scores.bowlerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->where('fixtures.isActive', 1)
    ->groupBy('fixture_scores.bowlerId')
    ->get()
    ->pluck('runs', 'bowlerId');

    $match_dissmissal_runout_name= Dismissal::where('dismissals.name', '=', 'Run out')
    ->selectRaw("dismissals.id as dissmissalname")
    ->get()->pluck('dissmissalname');
  

    $bowlerwickets= FixtureScore::where('fixtures.tournament_id', $tournament_id)
    ->where('fixtures.isActive', 1)
    ->where('match_dismissals.dismissal_id','!=', $match_dissmissal_runout_name)
    ->where('fixture_scores.isout',1)
    ->where('fixture_scores.balltype','=','Wicket')
    ->selectRaw('SUM(fixture_scores.isout) as wicket, fixture_scores.bowlerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
    ->groupBy('fixture_scores.bowlerId')
    ->get()
    ->pluck('wicket', 'bowlerId');


  $variable1 = 'R';
  $variable2 = 'Wicket';
  $balls_faced = FixtureScore::where('fixtures.tournament_id', $tournament_id)
  ->where(function ($query) use ($variable1, $variable2) {
      $query->where('balltype', $variable1)
          ->orWhere('balltype', $variable2);
  })
  ->selectRaw("count(fixture_scores.id) as balls, playerId")
  ->where('fixtures.isActive', 1)
  ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
  ->groupBy('playerId')
  ->get()
   ->pluck('balls', 'playerId');

  

  

  $results = array();
  $hatricks = [];

  foreach($getresult as $teamPlayer) {
      $total_hat_tricks = DB::table('fixture_scores AS fs1')
          ->join('fixture_scores AS fs2', function ($join) {
              $join->on('fs2.fixture_id', '=', 'fs1.fixture_id')
                  ->where('fs2.id', '=', DB::raw('(fs1.id + 1)'))
                  ->where('fs2.isout', '=', 1)
                  ->where('fs2.bowlerid', '=', DB::raw('fs1.bowlerid'));
          })
          ->join('fixture_scores AS fs3', function ($join) {
              $join->on('fs3.fixture_id', '=', 'fs1.fixture_id')
                  ->where('fs3.id', '=', DB::raw('(fs1.id + 2)'))
                  ->where('fs3.isout', '=', 1)
                  ->where('fs3.bowlerid', '=', DB::raw('fs1.bowlerid'));
          })
          ->leftJoin('fixture_scores AS fs4', function ($join) {
              $join->on('fs4.fixture_id', '=', 'fs1.fixture_id')
                  ->where('fs4.id', '=', DB::raw('(fs1.id + 3)'))
                  ->where('fs4.isout', '=', 1)
                  ->where('fs4.bowlerid', '=', DB::raw('fs1.bowlerid'));
          })
          ->join('fixtures', 'fixtures.id', '=', 'fs1.fixture_id')
          ->where('fixtures.tournament_id', $tournament_id)
          ->where('fixtures.isActive', 1)
          ->where('fs1.bowlerId', $teamPlayer->bowler_id)
          ->where('fs1.isout', '=', 1)
          ->whereNull('fs4.id')
          ->select(DB::raw('COUNT(*) as total_hat_tricks'))
          ->pluck('total_hat_tricks')
          ->toArray();
  
      $hatricks[$teamPlayer->bowler_id] = $total_hat_tricks;
 
    if ($bowlerwickets->has($teamPlayer->bowler_id)) {
    $results[] = [
      'bowler_id' => $teamPlayer->bowler_id,
      'tournament_id' => $teamPlayer->tournament_id,
      'player_id' => $teamPlayer->player_id,
      'team_id' => $teamPlayer->team_id,
      'bowler_id' => $teamPlayer->bowler_id,
      'total_matches' => $teamPlayer->total_matches,
      'total_overs' => $teamPlayer->total_overs/6,
      'total_wides' => $teamPlayer->total_wides,
      'total_noball' => $teamPlayer->total_noball,
      'total_runs' => $teamPlayer->total_runs,
      'player_wickets_keys' =>$bowlerwickets[$teamPlayer->bowler_id],
    ];
  }
    
  }


$player_wickets_keys = array_column($results, 'player_wickets_keys');
array_multisort($player_wickets_keys, SORT_DESC, $results);

  return view('bowling_state', compact('tournamentdata','results','bowlerwickets','hatricks','bowlerruns','bowlerballs','inningsCount','match_count' ,'player', 'teams', 'match_results', 'image_gallery', 'years', 'getresult'));
  }
  
  public function show_fielding_records(int $tournament_id)
  {


    $tournamentdata = Tournament::query()
    ->where('isActive', '=', 1)
    ->where('is_web_display' , '=' , 1)
    ->get()
    ->pluck(
      'name',
      'id'
    );


    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

    $match_dissmissal_caught = Dismissal::where('dismissals.name', '=', 'Caught')
      ->first();


    $match_dissmissal_stumped = Dismissal::where('dismissals.name', '=', 'Stumped')->first();

    $dismissalIdcatch = $match_dissmissal_caught->id;
    $dismissalIdstump = $match_dissmissal_stumped->id;

    $getresult = [];

    $team_players_list = $result = DB::table('teams')
    ->join('team_players', 'team_players.team_id', '=', 'teams.id')
    ->join('players', 'players.id', '=', 'team_players.player_id')
    ->pluck('teams.name', 'players.id');


  


    $data = Fixture::query()  ->where('fixtures.isActive', 1);
   
   
    $data->selectRaw('tournament_players.player_id')
    ->where('fixtures.tournament_id', $tournament_id)
    ->where('fixtures.isActive', 1)
      ->selectRaw('tournament_players.team_id')
      ->selectRaw('players.fullname as player_name')
      ->join('tournament_players', 'tournament_players.tournament_id', '=', 'fixtures.tournament_id')
      ->join('players', 'players.id', '=', 'tournament_players.player_id');
    

      $match_dismissal_name = Dismissal::where('name', 'Caught')
      ->pluck('id');
      $dismissalIdstump = $match_dissmissal_stumped->id ;
    

    $catch_query = MatchDismissal::query()
    ->where('fixtures.tournament_id', $tournament_id)
    ->where('fixtures.isActive', 1)
    ->where('match_dismissals.dismissal_id', $match_dismissal_name)
    ->selectRaw("COUNT(match_dismissals.id) as catch_count,outbyplayer_id")
    ->join('fixtures', 'fixtures.id', '=', 'match_dismissals.fixture_id')
    ->groupBy('match_dismissals.outbyplayer_id');

    $stump_query = MatchDismissal::query()
    ->where('fixtures.tournament_id', $tournament_id)
    ->where('fixtures.isActive', 1)
    ->where('match_dismissals.dismissal_id', $dismissalIdstump)
    ->selectRaw("COUNT(match_dismissals.id) as stump,outbyplayer_id")
    ->join('fixtures', 'fixtures.id', '=', 'match_dismissals.fixture_id')
    ->groupBy('match_dismissals.outbyplayer_id');  


 


    $catchs_data = $catch_query->pluck('catch_count', 'outbyplayer_id');
    $stump_data = $stump_query->pluck('stump', 'outbyplayer_id');


    $getresult = $data
    ->distinct('tournament_players.player_id')
    ->groupby('tournament_players.player_id','tournament_players.team_id')
    ->get();

    
    $teamIds = TournamentGroup::where('tournament_id', $tournament_id)
      ->select('team_id')
      ->groupBy('team_id')
      ->pluck('team_id');
  


  $match_count = DB::table(function ($query) use ($teamIds, $tournament_id) {
      $query->select('team_id_a AS team_id')
          ->from('fixtures')
          ->whereIn('team_id_a', $teamIds)
          ->where('tournament_id', $tournament_id)
          ->where('fixtures.isActive', 1)
          ->whereIn('running_inning', [1,2,3])
          ->unionAll(
              DB::table('fixtures')
                  ->select('team_id_b AS team_id')
                  ->whereIn('team_id_b', $teamIds)
                  ->where('tournament_id', $tournament_id)
                  ->where('fixtures.isActive', 1)
                  ->whereIn('running_inning', [1,2,3])
          );
  }, 'subquery')
      ->select('team_id', DB::raw('COUNT(*) AS count'))
      ->groupBy('team_id')
      ->get()->pluck('count','team_id');
      // dd($match_count);

      if (!isset($stump_data)) {
        $stump_data = [];
    }
    $fieldingresults = array();

    foreach($getresult as $data) {
      $fieldingresults[] = [
        'player_id' =>$data->player_id,
        'player_name' => $data->player_name,
        'team_players_list' => $team_players_list[$data->player_id],
        'playermatch_countmatch' => $match_count[$data->team_id]??0,
        'catchs_data' => $catchs_data[$data->player_id]??0,
        'stump_data' => $stump_data[$data->player_id]??0,
        'totalfieldingdata' =>($stump_data[$data->player_id] ?? 0) + ($catchs_data[$data->player_id] ?? 0),
      ];
      
    }


$totalfieldingdata = array_column($fieldingresults, 'totalfieldingdata');
array_multisort($totalfieldingdata, SORT_DESC, $fieldingresults);


    return view('fieldingRecords', compact('getresult','fieldingresults', 'years','tournamentdata','team_players_list', 'catchs_data','stump_data', 'match_count' ));
  }

  public function show_player_ranking(int $tournament_id)
  {

    $tournamentdata = Tournament::query()
    ->where('isActive', '=', 1)
    ->where('is_web_display' , '=' , 1)
    ->get()
    ->pluck(
      'name',
      'id'
    );
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $match_results = Fixture::query()->where('fixtures.isActive', 1);
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    DB::enableQueryLog();

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');
    $getresult = [];
    $match_count_player = collect();
    $player_runs = collect();
    $balls_faced = collect();
    $sixes = collect();
    $fours = [];


// mubeen working on player ranking
$PlayerRankingData = Fixture::query()
->where('fixtures.isActive', 1)
    ->where('players_contain_points.tournament_id', $tournament_id)
    ->select('players_contain_points.player_id', 'players_contain_points.tournament_id', 'players_contain_points.team_id')
    ->join('players_contain_points', 'players_contain_points.tournament_id', '=', 'fixtures.tournament_id')
    ->groupBy('players_contain_points.player_id', 'players_contain_points.tournament_id', 'players_contain_points.team_id')
    ->distinct();



$Player_Rank_Data = $PlayerRankingData->get();


$team_ids = $Player_Rank_Data->pluck('team_id');

$playermatch = DB::table(function ($query) use ($team_ids, $tournament_id) {
  $query->select('team_id_a AS team_id')
      ->from('fixtures')
      ->whereIn('team_id_a', $team_ids)
      ->where('tournament_id', $tournament_id)
      ->where('fixtures.isActive', 1)
      ->whereIN('running_inning',[ 3,1,2])
      ->unionAll(
          DB::table('fixtures')
              ->select('team_id_b AS team_id')
              ->whereIn('team_id_b', $team_ids)
              ->where('tournament_id', $tournament_id)
              ->where('fixtures.isActive', 1)
              ->whereIN('running_inning',[ 3,1,2])
      );
}, 'subquery')
  ->select('team_id', DB::raw('COUNT(*) AS count'))
  ->groupBy('team_id')
  ->get()
  ->pluck('count', 'team_id');

  $Player_Batting_points = DB::table('players_contain_points')
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->where('player_type', 'batsmen')
    ->groupBy('player_id')
    ->get();

$Player_Batting_totalPoints = $Player_Batting_points->pluck('totalpoints', 'player_id');

$Player_Bowling_points = DB::table('players_contain_points')
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->where('player_type', 'Bowler')
    ->groupBy('player_id')
    ->get();

$Player_Bowling_totalPoints = $Player_Bowling_points->pluck('totalpoints', 'player_id');

$Player_totalpoints = DB::table('players_contain_points')
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->groupBy('player_id')
    ->orderbydesc('totalpoints')
    ->get();

$Player_totalPoints = $Player_totalpoints->pluck('totalpoints', 'player_id');

$Player_Fielder_points = DB::table('players_contain_points')
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->where('player_type', 'Fielder')
    ->groupBy('player_id')
    ->get();

$Player_Fielder_totalPoints = $Player_Fielder_points->pluck('totalpoints', 'player_id');

$points = DB::table('players_points_types')
    ->where('code', 'MOTM')
    ->pluck('points')
    ->first(); 

$player_ids = $Player_Rank_Data->pluck('player_id');
$Player_MOTM_Points = Fixture::whereIn('manofmatch_player_id', $player_ids)
    ->where('tournament_id', $tournament_id)
    ->select(DB::raw("SUM($points) as playermompoints, manofmatch_player_id"))
    ->groupBy('manofmatch_player_id')
    ->get()
    ->pluck('playermompoints', 'manofmatch_player_id');

    $results = array();

    foreach($Player_Rank_Data as $data) {
      $results[] = [
        'player_id' => $data->player_id,
        'team_id' => $data->team_id,
        'playermatch' => $playermatch[$data->team_id]??0,
        'Player_Batting_totalPoints' => $Player_Batting_totalPoints[$data->player_id]??0,
        'Player_Fielder_totalPoints' => $Player_Fielder_totalPoints[$data->player_id]??0,
        'Player_Bowling_totalPoints' => $Player_Bowling_totalPoints[$data->player_id]??0,
        'Player_MOTM_Points' => $Player_MOTM_Points[$data->player_id]??0,
        'total_point' => ($Player_Fielder_totalPoints[$data->player_id] ?? 0)+($Player_Batting_totalPoints[$data->player_id] ?? 0)+($Player_Bowling_totalPoints[$data->player_id] ?? 0) + ($Player_MOTM_Points[$data->player_id] ?? 0),
      ];
      
    }


$total_point = array_column($results, 'total_point');
array_multisort($total_point, SORT_DESC, $results);

return view('playerRanking', compact('results','Player_Rank_Data','fours','balls_faced', 'sixes', 'balls_faced', 'player_runs', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years' ));
  }

public function playermatchcount(){
  $teams = Team::query()->get()->pluck(
    'name',
    'id'
  );
  $player = Player::query()->get()->pluck(
    'fullname',
    'id'
  );
  $match_results = Fixture::query();
  $match_results->where('running_inning', '=', 3);
  $match_results = $match_results->orderBy('id')->get();
  $image_gallery = GalleryImages::query()
  ->where('isActive', 1)
  ->get();

  $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
    'name',
    'id'
  );

  $tournamentname = Tournament::query()->where('isActive', '=', 1)->pluck(
    'name',
    'id'
  );
  $playermatch = [];
  // TournamentPlayer::query()
  // ->selectRaw('DISTINCT tournament_players.tournament_id, tournament_players.team_id, tournament_players.player_id, teams.clubname')
  // ->join('teams', 'teams.id', '=', 'tournament_players.team_id')
  // ->get();


  $result=$playermatch;
  $teamIds = [];
  $tournamentid = [];
foreach ($result as $item) {
    $teamIds[] = $item->team_id;
    $tournamentid[] = $item->tournament_id;
}

  $match_count = [];
//   DB::table(function ($query) use ($teamIds, $tournamentid) {
//     $query->select('team_id_a AS team_id')
//         ->from('fixtures')
//         ->whereIn('team_id_a', $teamIds)
//         ->whereIn('tournament_id', $tournamentid)
//         ->whereIn('running_inning', [1,2,3])
//         ->where('isActive', '=', 1)
//         ->unionAll(
//             DB::table('fixtures')
//                 ->select('team_id_b AS team_id')
//                 ->whereIn('running_inning', [1,2,3])
//                 ->where('isActive', '=', 1)
//                 ->whereIn('team_id_b', $teamIds)
//                 ->where('tournament_id', $tournamentid)
              
//         );
// }, 'subquery')
//     ->select('team_id', DB::raw('COUNT(*) AS count'))
//     ->groupBy('team_id')
//     ->get()->pluck('count','team_id');

  return view ('playermatchcount', compact('teams','tournament','tournamentname','image_gallery','match_results','result','player','match_count'));
}

public function playermatchcount_submit(Request $request){
  $teams = Team::query()->get()->pluck(
    'name',
    'id'
  );
  $player = Player::query()->get()->pluck(
    'fullname',
    'id'
  );
  $match_results = Fixture::query();
  $match_results->where('running_inning', '=', 3);
  $match_results = $match_results->orderBy('id')->get();
  $image_gallery = GalleryImages::query()
  ->where('isActive', 1)
  ->get();
  $tournamentname = Tournament::query()->where('isActive', '=', 1)->pluck(
    'name',
    'id'
  );
  // dd($tournament);

  $playermatch=TournamentPlayer::query()
  ->selectRaw('tournament_players.tournament_id')
  ->selectRaw('tournament_players.team_id')
  ->selectRaw('tournament_players.player_id')
  ->selectRaw('teams.clubname')
  ->selectRaw('tournaments.name')
  ->join('teams','teams.id','tournament_players.team_id')
  ->join('tournaments','tournaments.id','tournament_players.tournament_id');
  

  $term = $request;
  if (!empty($term['tournament'])) {
    $tournament = $term['tournament'];
    $playermatch->where('tournament_players.tournament_id', '=', $tournament);
  }

$result=$playermatch->get();
  $teamIds = [];
  $tournamentid = [];
foreach ($result as $item) {
    $teamIds[] = $item->team_id;
    $tournamentid[] = $item->tournament_id;
}
  $match_count = DB::table(function ($query) use ($teamIds, $tournamentid) {
    $query->select('team_id_a AS team_id')
        ->from('fixtures')
        ->whereIn('team_id_a', $teamIds)
        ->where('tournament_id', $tournamentid)
        ->whereIn('running_inning',[1,2,3])
        ->where('isActive', '=', 1)
        ->unionAll(
            DB::table('fixtures')
                ->select('team_id_b AS team_id')
                ->whereIn('team_id_b', $teamIds)
                ->whereIn('running_inning',[1,2,3])
                ->where('isActive', '=', 1)
                ->where('tournament_id', $tournamentid)
        );
}, 'subquery')
    ->select('team_id', DB::raw('COUNT(*) AS count'))
    ->groupBy('team_id')
    ->get()->pluck('count','team_id');


  return view ('playermatchcount', compact('teams','tournamentname','tournament','image_gallery','match_results','result','player','match_count'));
}

public function team_ranking(int $team_id, int $tournament_id)
{
  $teamid = Team::where('id', '=', $team_id)->select('id')->get();
  $team_id_data = $team_id;
  $tournament_ids = $tournament_id;
  $ground = Ground::orderBy('id')->get();
  $ground = $ground->pluck('name', 'id');
  $player = Player::pluck('fullname', 'id');
  $tournamentData = TournamentGroup::where('tournament_id', $tournament_id)->value('tournament_id');
  $playerCount = TournamentPlayer::where('team_id', $team_id)
    ->selectRaw('player_id, COUNT(*) as count')
    ->groupBy('player_id')
    ->get();
  $teamPlayerCount = $playerCount->count();
  $team_resultData = TournamentPlayer::select('tournament_id', 'tournament_players.team_id', 'tournament_players.player_id', 'tournament_players.domain_id', 'team_players.iscaptain')
    ->join('team_players', function ($join) {
      $join->on('tournament_players.team_id', '=', 'team_players.team_id');
      $join->on('tournament_players.player_id', '=', 'team_players.player_id');
    })
    ->where('tournament_players.team_id', $team_id)
    ->get()
    ->groupBy('player_id')
    ->map(function ($group) {
      return $group->first();
    });
  $teamPlayers = TeamPlayer::where('team_id', $team_id)->get();
  $teamData = Team::where('id', '=', $team_id)->selectRaw("name")->get();
  $match_results = Fixture::where('id', '=', $team_id)->where('isActive', 1)->orderBy('id')->get();
  $tournament = Tournament::pluck('name', 'id');
  $tournamentdata = Tournament::query()
    ->where('isActive', '=', 1)
    ->where('is_web_display' , '=' , 1)
    ->get()
    ->pluck(
      'name',
      'id'
    );
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
    $match_results = Fixture::query();
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
    DB::enableQueryLog();

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');
    $getresult = [];
    $match_count_player = collect();
    $player_runs = collect();
    $balls_faced = collect();
    $sixes = collect();
    $fours = [];


// mubeen working on player ranking
$PlayerRankingData = Fixture::query()
    ->where('players_contain_points.tournament_id', $tournament_id)
    ->where('fixtures.isActive', '=', 1)
    ->where('players_contain_points.team_id', $team_id)
    ->select('players_contain_points.player_id', 'players_contain_points.tournament_id', 'players_contain_points.team_id')
    ->join('players_contain_points', 'players_contain_points.tournament_id', '=', 'fixtures.tournament_id')
    ->groupBy('players_contain_points.player_id', 'players_contain_points.tournament_id', 'players_contain_points.team_id')
    ->distinct();



$Player_Rank_Data = $PlayerRankingData->get();


$team_ids = $Player_Rank_Data->pluck('team_id');

$playermatch = DB::table(function ($query) use ($team_ids, $tournament_id) {
  $query->select('team_id_a AS team_id')
      ->from('fixtures')
      ->whereIn('team_id_a', $team_ids)
      ->where('tournament_id', $tournament_id)
      ->where('fixtures.isActive', '=', 1)
      ->whereIN('running_inning',[ 3,1,2])
      ->unionAll(
          DB::table('fixtures')
              ->select('team_id_b AS team_id')
              ->whereIn('team_id_b', $team_ids)
              ->where('tournament_id', $tournament_id)
              ->where('fixtures.isActive', '=', 1)
              ->whereIN('running_inning',[ 3,1,2])
      );
}, 'subquery')
  ->select('team_id', DB::raw('COUNT(*) AS count'))
  ->groupBy('team_id')
  ->get()
  ->pluck('count', 'team_id');

  $Player_Batting_points = DB::table('players_contain_points')
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->where('player_type', 'batsmen')
    ->groupBy('player_id')
    ->get();

$Player_Batting_totalPoints = $Player_Batting_points->pluck('totalpoints', 'player_id');

$Player_Bowling_points = DB::table('players_contain_points')
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->where('player_type', 'Bowler')
    ->groupBy('player_id')
    ->get();

$Player_Bowling_totalPoints = $Player_Bowling_points->pluck('totalpoints', 'player_id');

$Player_totalpoints = DB::table('players_contain_points')
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->groupBy('player_id')
    ->orderbydesc('totalpoints')
    ->get();

$Player_totalPoints = $Player_totalpoints->pluck('totalpoints', 'player_id');

$Player_Fielder_points = DB::table('players_contain_points')
    ->select(DB::raw('SUM(points) AS totalpoints'), 'player_id')
    ->where('player_type', 'Fielder')
    ->groupBy('player_id')
    ->get();

$Player_Fielder_totalPoints = $Player_Fielder_points->pluck('totalpoints', 'player_id');

$points = DB::table('players_points_types')
    ->where('code', 'MOTM')
    ->pluck('points')
    ->first(); 

$player_ids = $Player_Rank_Data->pluck('player_id');
$Player_MOTM_Points = Fixture::whereIn('manofmatch_player_id', $player_ids)
    ->where('fixtures.isActive', '=', 1)
    ->where('tournament_id', $tournament_id)
    ->select(DB::raw("SUM($points) as playermompoints, manofmatch_player_id"))
    ->groupBy('manofmatch_player_id')
    ->get()
    ->pluck('playermompoints', 'manofmatch_player_id');

    $results = array();

    foreach($Player_Rank_Data as $data) {
      $results[] = [
        'player_id' => $data->player_id,
        'team_id' => $data->team_id,
        'playermatch' => $playermatch[$data->team_id]??0,
        'Player_Batting_totalPoints' => $Player_Batting_totalPoints[$data->player_id]??0,
        'Player_Bowling_totalPoints' => $Player_Bowling_totalPoints[$data->player_id]??0,
        'Player_Fielder_totalPoints' => $Player_Fielder_totalPoints[$data->player_id]??0,
        'Player_MOTM_Points' => $Player_MOTM_Points[$data->player_id]??0,
        'total_point' =>($Player_Fielder_totalPoints[$data->player_id] ?? 0) +($Player_Bowling_totalPoints[$data->player_id] ?? 0) +  ($Player_Batting_totalPoints[$data->player_id] ?? 0) + ($Player_MOTM_Points[$data->player_id] ?? 0),
      ];
      
    }


$total_point = array_column($results, 'total_point');
array_multisort($total_point, SORT_DESC, $results);

$tournamentgrounds=Fixture::where('tournament_id',$tournament_id)
->select('ground_id')
->distinct('ground_id')
->get()->first();


return view('team_ranking', compact('results','tournamentgrounds','teamid','teamData','tournamentData','team_resultData','teamPlayerCount','team_id_data','tournament_ids','ground','Player_Rank_Data','fours','balls_faced', 'sixes', 'balls_faced', 'player_runs', 'player', 'getresult', 'teams', 'tournament', 'match_results',  'image_gallery', 'years' ));
  
}

public function fixturesCalendar()
{


    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    DB::enableQueryLog();

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

    $match_results = Fixture::query()->orderBy('id')->get();
    $data = Fixture::where('running_inning', '=', 0)->where('fixtures.isActive', '=', 1);


    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );

    $results = $data->get();
    $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );
    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

    $ground2 = Ground::query()->get()->pluck(
      'name',
      'id'
    );


  $today = Carbon::now()->toDateString();
    $upcoming_match = Fixture::query()->where('match_startdate', '>=', $today)
      ->where('isActive', 1)
      ->orderBy('id')
      ->selectRaw('match_description')
      ->selectRaw('match_startdate')
      ->selectRaw('match_enddate')
      ->selectRaw('match_starttime')
      ->selectRaw('match_endtime')
      ->get()
      ->filter(function ($fixture) use ($today) {
        return Carbon::parse($fixture->match_startdate)->greaterThanOrEqualTo($today);
      });
      
  $jsObjects = $upcoming_match->map(function ($fixture) {
    $startDate = Carbon::parse($fixture->match_startdate);
    $startTime = Carbon::parse($fixture->match_starttime);
    $startDateTime = $startDate->format('Y-m-d') . 'T' . $startTime->format('H:i:s');

    $jsObject = [
        'title' => $fixture->match_description,
        'start' => $startDateTime,
    ];

    if ($fixture->match_enddate && $fixture->match_endtime) {
        $endDate = Carbon::parse($fixture->match_enddate);
        $endTime = Carbon::parse($fixture->match_endtime);
        $endDateTime = $endDate->format('Y-m-d') . 'T' . $endTime->format('H:i:s');
        $jsObject['end'] = $endDateTime;
    }

    return $jsObject;
})->toArray();

   return view ('calendar', compact('jsObjects','ground2', 'ground', 'clubs', 'match_results', 'years', 'tournament', 'image_gallery')); 

}
  //////////////////////////////////////////////////////////////////////////////////////



public function fixturesCalendar_post(Request $request)
{


    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    DB::enableQueryLog();

    $years = DB::table('fixtures')
      ->select(DB::raw('YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at)'))
      ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
      ->pluck('year');

    // $match_results = Fixture::query()->orderBy('id')->get();
    $data = Fixture::where('running_inning', '=', 0)->where('fixtures.isActive', '=', 1);


    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );

    // $results = $data->get();
    $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
      'name',
      'id'
    );
    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

    $ground2 = Ground::query()->get()->pluck(
      'name',
      'id'
    );


$term = $request;
    if (!empty($term->created_at)) {
      $convertedDate = Carbon::createFromFormat('m/d/Y', $term->created_at)->format('Y-m-d');
      $data->whereRaw("DATE(match_startdate) >= Date('$convertedDate')");
    }
    if (!empty($term->end_at)) {
      $convertedDate = Carbon::createFromFormat('m/d/Y', $term->end_at)->format('Y-m-d');
      $data->whereRaw("DATE(match_startdate) <= Date('$convertedDate')");
    }

    if (!empty($term['year'])) {
      $year = $term['year'];
      $data->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) {
      $tournaments = $term['tournament'];
      $data->where('tournament_id', '=', $tournaments);
    }

    if (!empty($term['teams'])) {
      $team = $term['teams'];
      $data->where('team_id_a', '=', $team)
        ->oRWhere('team_id_b', '=', $team);
    }

    if (!empty($term->club)) {
      $club = $term->club;
      $data->where('team_id_a', '=', $club)
        ->oRWhere('team_id_b', '=', $club);
    }

    if (!empty($term['grounddata'])) {
      $grounddata = $term['grounddata'];
      $data->where('ground_id', '=', $grounddata);
    }



    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );


    DB::enableQueryLog();
    $results = $data->selectRaw('match_description')
      ->selectRaw('match_startdate')
      ->selectRaw('match_enddate')
      ->selectRaw('match_starttime')
      ->selectRaw('match_endtime')->get();
      // dd($results);
 $query = DB::getQueryLog();
                    $query = DB::getQueryLog();
            // dd($query);

  $today = Carbon::now()->toDateString();
    $upcoming_match = Fixture::query()->where('match_startdate', '>=', $today)
      ->where('isActive', 1)
      ->orderBy('id')
      ->selectRaw('match_description')
      ->selectRaw('match_startdate')
      ->selectRaw('match_enddate')
      ->selectRaw('match_starttime')
      ->selectRaw('match_endtime')
      ->get()
      ->filter(function ($fixture) use ($today) {
        return Carbon::parse($fixture->match_startdate)->greaterThanOrEqualTo($today);
      });
      
  $jsObjects = $results->map(function ($fixture) {
    $startDate = Carbon::parse($fixture->match_startdate);
    $startTime = Carbon::parse($fixture->match_starttime);
    $startDateTime = $startDate->format('Y-m-d') . 'T' . $startTime->format('H:i:s');

    $jsObject = [
        'title' => $fixture->match_description,
        'start' => $startDateTime,
    ];

    if ($fixture->match_enddate && $fixture->match_endtime) {
        $endDate = Carbon::parse($fixture->match_enddate);
        $endTime = Carbon::parse($fixture->match_endtime);
        $endDateTime = $endDate->format('Y-m-d') . 'T' . $endTime->format('H:i:s');
        $jsObject['end'] = $endDateTime;
    }

    return $jsObject;
})->toArray();

   return view ('calendar', compact('jsObjects','ground2', 'ground', 'clubs', 'years', 'tournament', 'image_gallery')); 

}



}
