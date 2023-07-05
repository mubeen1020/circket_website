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

    //

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


    // $tournament_name = Tournament::query()
    // ->where('season_id','=',0)
    // ->where('is_web_display','=',1)
    // ->get();

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


    // dd($tournament_season);

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






    //  dd($image_slider);

    return view('home', compact('tournament', 'tournament_season', 'match_results', 'teams', 'upcoming_match', 'ground', 'image_gallery', 'image_slider'));
  }


  public function balltoballScorecard(int $id)
  {


    $match_results = Fixture::query();
    $match_results->where('id', '=', $id);
    $match_results = $match_results->where('isActive', 1)->orderBy('id')->get();

    $match_data = $match_results->find($id);
    // dd($match_data[0]->match_result_description);
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

    // dd($runnerbowler2);

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
    // dd();

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
    // $teams = Team::query()->get()pluck('name')->toArray();
    //   'name',
    //   'id'
    // );
    $teams_one = Team::query()->get()->where('id', '=', $match_results[0]->first_inning_team_id)->pluck(
      'name'
    )->first();
    // dd($teams_one);
    $teams_two = Team::query()->get()->where('id', '=', $match_results[0]->second_inning_team_id)->pluck(
      'name',
    )->first();


    return view('fullScorecard_chart', compact('match_results', 'teams_one', 'teams_two', 'sum_inning_one', 'sum_inning_two', 'id', 'over'));
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
    $scores = FixtureScore::query();
    $scores->where('fixture_id', '=', $id);
    $scores = $scores->orderBy('id')->get();


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();





    return view('score_overbyover', compact('scores', 'match_results', 'teams', 'player', 'teams_one', 'teams_two', 'image_gallery'));
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
    // dd($match_data->manofmatch_player_id);
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
    ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'NBP' OR balltype = 'Wicket' THEN runs ELSE 0 END) as total_runs")
    ->selectRaw("SUM(isfour = 1) as total_fours")
    ->selectRaw("SUM(issix = 1) as total_six")
    ->selectRaw("playerId, MIN(fixture_scores.id) as min_id") 
    ->groupBy('playerId', 'inningnumber')
    ->orderBy('min_id')
    ->distinct('playerId')
    ->get();


    $variable1 = 'R';
    $variable2 = 'Wicket';
    $variable3 = 'BYES';
    
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

    $extra_runs = FixtureScore::where('fixture_id', '=', $id)
    ->selectRaw('inningnumber')
    ->selectRaw("SUM(CASE WHEN balltype IN ('NB', 'NBB', 'NBP') THEN runs ELSE 0 END) AS noball_total_runs")
    ->selectRaw("SUM(CASE WHEN balltype = 'WD' THEN runs ELSE 0 END) AS wideball_total_runs")
    ->selectRaw("SUM(CASE WHEN balltype = 'BYES' THEN runs ELSE 0 END) AS byes_total_runs")
    ->groupBy('inningnumber')
    ->get();

    $totalData=FixtureScore::where('fixture_id', '=', $id)
    ->selectRaw('inningnumber')
    ->selectRaw('max(overnumber) as max_over ')
    ->selectRaw('max(ballnumber) as max_ball ')
    ->selectRaw("SUM(CASE WHEN isout = 1 THEN 1 ELSE 0 END) AS total_wicket")
    ->selectRaw("SUM(runs) AS total_runs")
    ->groupBy('inningnumber')
    ->get();


    DB::enableQueryLog();
    $bowler_data = FixtureScore::where('fixture_id', '=', $id)
    ->select('inningnumber')
    ->selectRaw('SUM(runs) as total_runs')
    ->selectRaw("COUNT(CASE WHEN balltype = 'Wicket' OR balltype = 'R' THEN id ELSE NULL END) AS max_ball")
    ->selectRaw('COUNT(DISTINCT overnumber) as `over`')
    ->selectRaw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) AS total_wicket")
    ->selectRaw('bowlerid, MIN(id) as min_id')
    ->groupBy('bowlerid', 'inningnumber')
    ->orderBy('min_id')
    ->get();

    // $query = DB::getQueryLog();
    //                 $query = DB::getQueryLog();
    //         dd($query);

    

    $match_dissmissal_runout_name= Dismissal::where('dismissals.name', '=', 'Run out')
    ->selectRaw("dismissals.id as dissmissalname")
    ->get()->pluck('dissmissalname');
    $bowler_wickets= FixtureScore::where('fixture_scores.fixture_id', '=', $id)
    ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
    ->where('match_dismissals.dismissal_id','!=', $match_dissmissal_runout_name)
    ->selectRaw("COUNT(match_dismissals.id) AS total_wicket")
    ->selectRaw('fixture_scores.bowlerid')
    ->groupBy('fixture_scores.bowlerid')
    ->get()->pluck('total_wicket','bowlerid');

    // dd($bowler_wickets);

    $maiden_overs = FixtureScore::where('fixture_id', '=', $id)
    ->select('overnumber', 'bowlerid')
    ->selectRaw('COUNT(DISTINCT overnumber) as maiden_count')
    ->groupBy('overnumber', 'bowlerid')
    ->havingRaw('SUM(runs) = 0')
    ->get()->pluck('maiden_count','bowlerid');

// dd($maiden_overs);
   

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
  
  
  // dd($match_description);
      
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
    $player_balls = FixtureScore::where('fixture_id', '=', $id)
      ->where(function ($query) use ($variable1, $variable2) {
        $query->where('balltype', '=', $variable1)
          ->orWhere('balltype', '=', $variable2);
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
    // $TeamsBowlingHead =    array("Bowler"  ,  'Overs' ,  'Madiens'	,  'Runs' , "Wickets"  ,  'Wides' , 	'No Balls' , 	'Hattricks' ,	'Dot Balls');
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

    // return view('score_card',compact('player_runs', 'teams_one','teams_two','player_balls','match_results','teams','player','tournament','ground','match_data'));
  }

  //     public function result_form_submit(Request $request)
  //     {
  //       DB::enableQueryLog();

  //         if ($request->method() !== 'POST') {
  //             abort(405, 'Method Not Allowed');
  //         }
  //         $years = DB::table('fixtures')
  //             ->select(DB::raw('YEAR(created_at) as year'))
  //             ->groupBy(DB::raw('YEAR(created_at)'))
  //             ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
  //             ->pluck('year');

  //         $match_results = Fixture::query()->orderBy('id')->get();
  //         $data = Fixture::where('running_inning', '=', 3);
  //         $term = $request;
  //         if (!empty($term->created_at)) {
  //           $data->whereRaw("DATE(match_startdate) >= Date('$term->created_at')");
  //         }
  //         if (!empty($term->end_at)) {
  //           $data->whereRaw("DATE(match_enddate) <= Date('$term->end_at')");
  //         }

  //         if (!empty($term['year'])) {
  //             $year = $term['year'];
  //             $data->whereRaw("YEAR(created_at) = $year");
  //         }
  //         if (!empty($term['tournament'])) {
  //           $tournaments = $term['tournament'];
  //           $data->where('tournament_id', '=', $tournaments);
  //       }

  //         if (!empty($term['teams'])) {
  //             $team = $term['teams'];
  //             $data->where('team_id_a', '=', $team)
  //             ->oRWhere('team_id_b', '=', $team);
  //         }
  //         if(!empty($term->club)){
  //           $club = $term->club;
  //           $data->where('team_id_a', '=', $club)
  //           ->Where('team_id_b', '=', $club);
  //         }



  //         $teams = Team::query()->get()->pluck(
  //             'name',
  //             'id'
  //         );

  //         $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
  //           'clubname',
  //           'id'
  //         );

  //         $results = $data->get();
  //         // dd($results);
  // //         $query = DB::getQueryLog();
  // //         $query = DB::getQueryLog();
  // // dd($query);
  //         $tournament = Tournament::query()->pluck(
  //                 'name',
  //                 'id'
  //             );

  //         $total_runs = [];
  //          $total_wicket_fixture = [];
  //          $total_run_fixture = [];
  //          foreach ($results as $result) {
  //           $match_summary = FixtureScore::where('fixture_id', '=', $result->id)
  //           ->selectRaw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) as total_wickets")
  //           ->selectRaw('inningnumber, max(overnumber) as max_over')
  //           ->selectRaw('SUM(runs) as total_runs')
  //           ->selectRaw("inningnumber")
  //           ->groupBy('inningnumber')
  //           ->get();

  //           if(count($match_summary)== 2)
  //           {
  //               $total_wicket_fixture[$result->id] = [$match_summary[0]['total_wickets'], $match_summary[1]['total_wickets']];
  //               $total_run_fixture[$result->id] = [$match_summary[0]['max_over'], $match_summary[1]['max_over'] ] ;
  //               $total_runs[$result->id] = [$match_summary[0]['total_runs'], $match_summary[1]['total_runs']];

  //              }   
  //         } 

  //         $image_gallery =GalleryImages::query()
  //         ->where('isActive','=',1)
  //         ->get();

  //   }

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
    // $teams = Team::pluck('name', 'id');
    $tournament = Tournament::pluck('name', 'id');
    $team_bowlingdata = TournamentPlayer::where('tournament_players.team_id', $team_id)
      ->where('tournament_players.tournament_id', '=', $tournament_id)
      ->selectRaw('fixture_scores.bowlerId as bowler_id')
      ->selectRaw('team_players.player_id')
      ->selectRaw('fixture_scores.bowlerid')
      ->selectRaw('team_players.team_id')
      ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches')
      ->selectRaw('COUNT(DISTINCT fixture_scores.overnumber) as total_overs')
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
      $hatricks ;

    
    foreach($team_bowlingdata as $bowlerData){
    

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
      ->where('fs1.isout', '=', 1)
      ->whereNull('fs4.id')
      ->select(DB::raw('COUNT(*) as total_hat_tricks'))
      ->pluck('total_hat_tricks')
      ->toArray();
  
 
  
  $hatricks = $total_hat_tricks;
  
  
    }

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
    ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
    ->groupBy('fixture_scores.bowlerid')
    ->selectRaw('COUNT(DISTINCT(fixture_scores.fixture_id)) as matches_played, CAST(fixture_scores.bowlerid AS UNSIGNED) as playerId')
    ->pluck('matches_played', 'playerId');

    // $playermatch = TournamentPlayer::where('tournament_players.tournament_id', $tournament_id)
    // ->where('tournament_players.team_id', $team_id)
    // ->where('fixtures.running_inning',3)
    // ->join('fixtures', 'fixtures.tournament_id', '=', 'tournament_players.tournament_id')
    // ->groupBy('tournament_players.player_id')
    // ->selectRaw('COUNT(DISTINCT(fixtures.id)) as `match`, tournament_players.player_id')
    // ->pluck('match', 'tournament_players.player_id');

    $team_ids = is_array($team_id) ? $team_id : [$team_id];

    $playermatch = DB::table(function ($query) use ($team_ids, $tournament_id) {
            $query->select('team_id_a AS team_id')
                ->from('fixtures')
                ->whereIn('team_id_a', $team_ids)
                ->where('tournament_id', $tournament_id)
                ->where('running_inning', 3)
                ->unionAll(
                    DB::table('fixtures')
                        ->select('team_id_b AS team_id')
                        ->whereIn('team_id_b', $team_ids)
                        ->where('tournament_id', $tournament_id)
                        ->where('running_inning', 3)
                );
        }, 'subquery')
            ->select('team_id', DB::raw('COUNT(*) AS count'))
            ->groupBy('team_id')
            ->get()
            ->pluck('count', 'team_id');

     $playerruns =Fixture::where('tournament_id', $tournament_id)
     ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
     ->groupBy('fixture_scores.bowlerid')
     ->selectRaw('SUM(fixture_scores.runs) as playerurns, CAST(fixture_scores.bowlerid AS UNSIGNED) as playerId')
     ->pluck('playerurns', 'playerId');

    //  dd($playerruns);

     $playerteam = TournamentPlayer::where('tournament_players.tournament_id', $tournament_id)
     ->where('tournament_players.team_id', $team_id)
     ->join('fixtures', 'fixtures.tournament_id', '=', 'tournament_players.tournament_id')
     ->groupBy('tournament_players.player_id', 'tournament_players.team_id')
     ->selectRaw('tournament_players.team_id, tournament_players.player_id')
     ->pluck('tournament_players.team_id', 'tournament_players.player_id');

     $playerballs =Fixture::where('tournament_id', $tournament_id)
     ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
     ->groupBy('fixture_scores.bowlerid')
     ->selectRaw('COUNT(DISTINCT overnumber) as `over`, CAST(fixture_scores.bowlerid AS UNSIGNED) as playerId')
     ->pluck('over', 'playerId');

     $playerouts =Fixture::where('tournament_id', $tournament_id)
     ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
     ->where('fixture_scores.balltype','=','Wicket')
     ->where('fixture_scores.isout','=',1)
     ->groupBy('fixture_scores.bowlerid')
     ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, CAST(fixture_scores.bowlerid AS UNSIGNED) as playerId')
     ->pluck('playeouts', 'playerId');

     $playerwide =Fixture::where('tournament_id', $tournament_id)
     ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
     ->where('fixture_scores.balltype','=','WD')
     ->groupBy('fixture_scores.bowlerid')
     ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, CAST(fixture_scores.bowlerid AS UNSIGNED) as playerId')
     ->pluck('playeouts', 'playerId');

     $playernoball =Fixture::where('tournament_id', $tournament_id)
     ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
     ->where('fixture_scores.balltype','=','NB')
     ->groupBy('fixture_scores.bowlerid')
     ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, CAST(fixture_scores.bowlerid AS UNSIGNED) as playerId')
     ->pluck('playeouts', 'playerId');

    
     $teamid = Team::where('id', '=', $team_id)->select('id')->get();
    return view('team_bowling', compact('playerouts','teamid','hatricks','playernoball','playerwide','matchcount','playerruns','playerballs','playermatch','playerteam','playername','teams', 'tournamentData', 'tournament_ids', 'player', 'teamPlayerCount', 'team_resultData', 'teamPlayers', 'teamData', 'match_results', 'tournament', 'team_id_data', 'team_bowlingdata', 'image_gallery'));
  }

  public function team_fielding(int $team_id, int $tournament_id)
  {
    $teamid = Team::where('id', '=', $team_id)->select('id')->get();
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
    // $match_results = Fixture::where('id', '=', $team_id)->where('isActive', 1)->where('isActive', 1)->orderBy('id')->get();
    // // $teams = Team::pluck('name', 'id');
    $tournament = Tournament::pluck('name', 'id');
    // $team_bowlingdata = TournamentPlayer::where('tournament_players.team_id', $team_id)
    //   ->where('tournament_players.tournament_id', '=', $tournament_id)
    //   ->selectRaw('fixture_scores.bowlerId as bowler_id')
    //   ->selectRaw('team_players.player_id')
    //   ->selectRaw('team_players.team_id')
    //   ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches')
    //   ->selectRaw('COUNT(DISTINCT fixture_scores.overnumber) as total_overs')
    //   ->selectRaw('SUM(fixture_scores.balltype = "WD") as total_wides')
    //   ->selectRaw('SUM(fixture_scores.balltype = "NB") as total_noball')
    //   ->selectRaw('SUM(fixture_scores.runs) as total_runs')
    //   ->selectRaw('SUM(fixture_scores.isout) as total_wickets')
    //   ->join('team_players', function ($join) {
    //     $join->on('team_players.team_id', '=', 'tournament_players.team_id')
    //       ->on('team_players.player_id', '=', 'tournament_players.player_id');
    //   })
    //   ->join('fixture_scores', 'fixture_scores.bowlerId', '=', 'team_players.player_id')
    //   ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    //   ->groupBy('team_players.player_id', 'team_players.team_id', 'fixture_scores.bowlerId')
    //   ->get();



    // $team_bowlingdata = TournamentPlayer::where('tournament_players.team_id', $team_id)
    //   ->where('tournament_players.tournament_id', '=', $tournament_id)
    //   ->selectRaw('fixture_scores.bowlerId as bowler_id')
    //   ->selectRaw('team_players.player_id')
    //   ->selectRaw('team_players.team_id')
    //   ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches')
    //   ->selectRaw('COUNT(DISTINCT fixture_scores.overnumber) as total_overs')
    //   ->selectRaw('SUM(fixture_scores.balltype = "WD") as total_wides')
    //   ->selectRaw('SUM(fixture_scores.balltype = "NB") as total_noball')
    //   ->selectRaw('SUM(fixture_scores.runs) as total_runs')
    //   ->selectRaw('SUM(fixture_scores.isout) as total_wickets')
    //   ->join('team_players', function ($join) {
    //     $join->on('team_players.team_id', '=', 'tournament_players.team_id')
    //       ->on('team_players.player_id', '=', 'tournament_players.player_id');
    //   })
    //   ->join('fixture_scores', 'fixture_scores.bowlerId', '=', 'team_players.player_id')
    //   ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    //   ->groupBy('team_players.player_id', 'team_players.team_id', 'fixture_scores.bowlerId')
    //   ->get();

    // $image_gallery = GalleryImages::query()
    //   ->where('isActive', '=', 1)
    //   ->get();
    // $teams = Team::query()->get()->pluck(
    //   'name',
    //   'id'
    // );
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


    $match_dissmissal_caught = Dismissal::where('dismissals.name', '=', 'Caught')
      ->first();


      $match_dissmissal_stumped = Dismissal::where('dismissals.name', '=', 'Stumped')
      ->first();

      $dismissalIdcatch = $match_dissmissal_caught->id;
      $dismissalIdstump = $match_dissmissal_stumped->id ;

    $getresult = [];

    // $term = $request->input();
    $query = "
SELECT 
    bowler_id,
    team_id,
    MAX(total_matches_catch) AS total_matches,
    SUM(catch) AS catch,
    SUM(stump) AS stump
FROM 
    (SELECT 
        tournament_players.player_id AS bowler_id,
        tournament_players.team_id AS team_id,
        COUNT(DISTINCT fixture_scores.fixture_id) AS total_matches_catch,
        COUNT(DISTINCT CASE WHEN match_dismissals.dismissal_id = $dismissalIdcatch THEN match_dismissals.fixturescores_id END) AS catch,
        0 AS stump
    FROM 
        tournaments 
    JOIN 
        fixtures ON fixtures.tournament_id = tournaments.id 
    JOIN 
        fixture_scores ON fixture_scores.fixture_id = fixtures.id
    JOIN 
        match_dismissals ON match_dismissals.outbyplayer_id = fixture_scores.bowlerId
    JOIN 
        tournament_players ON tournament_players.player_id = fixture_scores.bowlerId
    WHERE 
        fixture_scores.isout = 1
        AND match_dismissals.dismissal_id = $dismissalIdcatch";

// if (!empty($term['year'])) {
//   $year = $term['year'];
//     $query .= " AND YEAR(fixture_scores.created_at) = $year";
// }


// if (!empty($term['tournament'])) {
//   $tournament = $term['tournament'];
//   $tournament = (int)$tournament;
  $query .= " AND tournaments.id = $tournament_id";
// }

// if (!empty($term['teams'])) {
//   $team = $term['teams'];
//   $team = (int)$team;
  $query .= " AND  tournament_players.team_id = $team_id";
// }

$query .= " GROUP BY tournament_players.player_id, tournament_players.team_id
    UNION ALL
    SELECT 
        tournament_players.player_id AS bowler_id,
        tournament_players.team_id AS team_id,
        COUNT(DISTINCT fixture_scores.fixture_id) AS total_matches_stump,
        0 AS catch,
        COUNT(DISTINCT CASE WHEN match_dismissals.dismissal_id = $dismissalIdstump THEN match_dismissals.fixturescores_id END) AS stump
    FROM 
        tournaments 
    JOIN 
        fixtures ON fixtures.tournament_id = tournaments.id
    JOIN 
        fixture_scores ON fixture_scores.fixture_id = fixtures.id
    JOIN 
        match_dismissals ON match_dismissals.outbyplayer_id = fixture_scores.bowlerId
    JOIN 
        tournament_players ON tournament_players.player_id = fixture_scores.bowlerId
    WHERE 
        fixture_scores.isout = 1
        AND match_dismissals.dismissal_id = $dismissalIdstump";


// if (!empty($term['year'])) {
//   $year = $term['year'];
//     $query .= " AND YEAR(fixture_scores.created_at) = $year";
// }


// if (!empty($term['tournament'])) {
//   $tournament = $term['tournament'];
//   $tournament = (int)$tournament;
  $query .= " AND tournaments.id = $tournament_id";
// }

// if (!empty($term['teams'])) {
//   $team = $term['teams'];
//   $team = (int)$team;
  $query .= " AND  tournament_players.team_id = $team_id";
// }

$query .= " GROUP BY tournament_players.player_id, tournament_players.team_id) AS combined
";

$query .= " GROUP BY bowler_id, team_id";

$result = DB::select($query) ;
// dd($result) ;
$getresult = $result;

////////////////////////////////////////////////////////////////////
    // dd($term)

    return view('team_fielding', compact('fours','teamid', 'balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_count_player', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years' , 'teamData' , 'tournament' , 'tournamentData' , 'team_resultData' , 'teamPlayerCount', 'team_id_data' , 'tournament_ids'));
    // return view('team_fielding', compact('teams', 'tournamentData', 'player', 'teamPlayers', 'teamData', 'match_results', 'tournament', 'team_id_data', 'team_bowlingdata', 'teamPlayerCount', 'team_resultData', 'tournament_ids', 'image_gallery'));
  
  }

  // public function team_fielding(int $team_id, int $tournament_id)
  // {
  //   $team_id_data = $team_id;
  //   $tournament_ids = $tournament_id;
  //   $player = Player::pluck('fullname', 'id');
  //   $tournamentData = TournamentGroup::where('tournament_id', $tournament_id)->value('tournament_id');
  //   $playerCount = TournamentPlayer::where('team_id', $team_id)
  //     ->selectRaw('player_id, COUNT(*) as count')
  //     ->groupBy('player_id')
  //     ->get();
  //   $teamPlayerCount = $playerCount->count();
  //   $team_resultData = TournamentPlayer::select('tournament_id', 'tournament_players.team_id', 'tournament_players.player_id', 'tournament_players.domain_id', 'team_players.iscaptain')
  //     ->join('team_players', function ($join) {
  //       $join->on('tournament_players.team_id', '=', 'team_players.team_id');
  //       $join->on('tournament_players.player_id', '=', 'team_players.player_id');
  //     })
  //     ->where('tournament_players.team_id', $team_id)
  //     ->get()
  //     ->groupBy('player_id')
  //     ->map(function ($group) {
  //       return $group->first();
  //     });
  //   $teamPlayers = TeamPlayer::where('team_id', $team_id)->get();
  //   $teamData = Team::where('id', '=', $team_id)->selectRaw("name")->get();
  //   $match_results = Fixture::where('id', '=', $team_id)->where('isActive', 1)->where('isActive', 1)->orderBy('id')->get();
  //   // $teams = Team::pluck('name', 'id');
  //   $tournament = Tournament::pluck('name', 'id');
  //   $team_bowlingdata = TournamentPlayer::where('tournament_players.team_id', $team_id)
  //     ->where('tournament_players.tournament_id', '=', $tournament_id)
  //     ->selectRaw('fixture_scores.bowlerId as bowler_id')
  //     ->selectRaw('team_players.player_id')
  //     ->selectRaw('team_players.team_id')
  //     ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches')
  //     ->selectRaw('COUNT(DISTINCT fixture_scores.overnumber) as total_overs')
  //     ->selectRaw('SUM(fixture_scores.balltype = "WD") as total_wides')
  //     ->selectRaw('SUM(fixture_scores.balltype = "NB") as total_noball')
  //     ->selectRaw('SUM(fixture_scores.runs) as total_runs')
  //     ->selectRaw('SUM(fixture_scores.isout) as total_wickets')
  //     ->join('team_players', function ($join) {
  //       $join->on('team_players.team_id', '=', 'tournament_players.team_id')
  //         ->on('team_players.player_id', '=', 'tournament_players.player_id');
  //     })
  //     ->join('fixture_scores', 'fixture_scores.bowlerId', '=', 'team_players.player_id')
  //     ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
  //     ->groupBy('team_players.player_id', 'team_players.team_id', 'fixture_scores.bowlerId')
  //     ->get();


  //   $image_gallery = GalleryImages::query()
  //     ->where('isActive', '=', 1)
  //     ->get();

  //   $teams = Team::query()->get()->pluck(
  //     'name',
  //     'id'
  //   );
  //   return view('team_fielding', compact('teams', 'tournamentData', 'player', 'teamPlayers', 'teamData', 'match_results', 'tournament', 'team_id_data', 'team_bowlingdata', 'teamPlayerCount', 'team_resultData', 'tournament_ids', 'image_gallery'));
  // }



  public function searchplayer_form_submit(Request $request)
  {

    // dd($request);

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
    // dd($term);
    if (!empty($term->fullname)) {
      $player->where('fullname', 'like', '%' . $term['fullname'] . '%');
    }



    if (!empty($term->battingStyle)) {
      $player->where('battingstyle', 'like', '%' . $term['battingStyle'] . '%');
    }


    if (!empty($term->bowlingStyle)) {
      // dd($term->bowlingStyle);
      $player->where('bowlingstyle', 'like', '%' . $term['bowlingStyle'] . '%');
      // dd($player->get());
    }

    if (!empty($term->emailId)) {
      $player->where('email', 'like', '%' . $term['emailId'] . '%');
    }


    if (!empty($term->team_name)) {
      $teamPlayers = TeamPlayer::where('team_id', $term->team_name)->pluck('player_id')->toArray();
      // dd($teamPlayers);
      $player->whereIn('players.id', $teamPlayers);
    }

    if (!empty($term->club)) {
      $teamPlayers = TeamPlayer::where('team_id', $term->club)->pluck('player_id')->toArray();
      // dd($teamPlayers);
      $player->whereIn('players.id', $teamPlayers);
    }



    if (!empty($term['gender'])) {
      $player->where('gender', '=', $term['gender']);
    }

    // dd($player);
    // $result = $player->orderBy('players.id')
    //   ->join('team_players as team_players', 'team_players.player_id', '=', 'players.id')
    //   ->join('teams as teams', 'teams.id', '=', 'team_players.team_id')
    //   ->get(['name as team_name', 'player_id', 'fullname', 'players.contact', 'players.email']);


    $result = $player
      ->leftjoin('team_players', 'team_players.player_id', '=', 'players.id')
      ->leftjoin('teams', 'teams.id', '=', 'team_players.team_id')
      ->distinct('players.id')->get();

    //  dd($result);

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

    $results = $data->get();
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
    $total_run_fixture = [];
    foreach ($results as $result) {
      $match_summary = FixtureScore::where('fixture_id', '=', $result->id)
        ->selectRaw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) as total_wickets")
        ->selectRaw('inningnumber, max(overnumber) as max_over')
        ->selectRaw('SUM(runs) as total_runs')
        ->selectRaw("inningnumber")
        ->groupBy('inningnumber')
        ->get();

      if (count($match_summary) == 2) {
        $total_wicket_fixture[$result->id] = [$match_summary[0]['total_wickets'], $match_summary[1]['total_wickets']];
        $total_run_fixture[$result->id] = [$match_summary[0]['max_over'], $match_summary[1]['max_over']];
        $total_runs[$result->id] = [$match_summary[0]['total_runs'], $match_summary[1]['total_runs']];
      }
    }

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

    return view('result', compact('results', 'clubs', 'teams', 'match_results', 'years', 'tournament', 'total_run_fixture', 'total_runs', 'total_wicket_fixture', 'image_gallery'));
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
    $data = Fixture::where('running_inning', '=', 3);
    $term = $request;
    if (!empty($term->created_at)) {
      $data->whereRaw("DATE(match_startdate) >= Date('$term->created_at')");
    }
    if (!empty($term->end_at)) {
      $data->whereRaw("DATE(match_enddate) <= Date('$term->end_at')");
    }

    if (!empty($term['year'])) {
      $year = $term['year'];
      $data->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['teams'])) {
      $team = $term['teams'];
      $data->where('team_id_a', '=', $team)
        ->oRWhere('team_id_b', '=', $team);
    }
    if (!empty($term->club)) {
      $club = $term->club;
      $data->where('team_id_a', '=', $club)
        ->Where('team_id_b', '=', $club);
    }

    if (!empty($term['tournament'])) {
      $tournaments = $term['tournament'];
      $data->where('tournament_id', '=', $tournaments);
    }

    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );

    $results = $data->get();
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
    $total_run_fixture = [];
    foreach ($results as $result) {
      $match_summary = FixtureScore::where('fixture_id', '=', $result->id)
        ->selectRaw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) as total_wickets")
        ->selectRaw('inningnumber, max(overnumber) as max_over')
        ->selectRaw('SUM(runs) as total_runs')
        ->selectRaw("inningnumber")
        ->groupBy('inningnumber')
        ->get();

      if (count($match_summary) == 2) {
        $total_wicket_fixture[$result->id] = [$match_summary[0]['total_wickets'], $match_summary[1]['total_wickets']];
        $total_run_fixture[$result->id] = [$match_summary[0]['max_over'], $match_summary[1]['max_over']];
        $total_runs[$result->id] = [$match_summary[0]['total_runs'], $match_summary[1]['total_runs']];
      }
    }

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();


    return view('result', compact('results', 'clubs', 'teams', 'match_results', 'years', 'tournament', 'total_run_fixture', 'total_runs', 'total_wicket_fixture', 'image_gallery'));
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
      ->get();
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
    $total_run_fixture = [];
    foreach ($team_resultData as $result) {
      $match_summary = FixtureScore::where('fixture_id', '=', $result->id)
        ->selectRaw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) as total_wickets")
        ->selectRaw('inningnumber, max(overnumber) as max_over')
        ->selectRaw('SUM(runs) as total_runs')
        ->selectRaw("inningnumber")
        ->groupBy('inningnumber')
        ->get();

      if (count($match_summary) == 2) {
        $total_wicket_fixture[$result->id] = [$match_summary[0]['total_wickets'], $match_summary[1]['total_wickets']];
        $total_run_fixture[$result->id] = [$match_summary[0]['max_over'], $match_summary[1]['max_over']];
        $total_runs[$result->id] = [$match_summary[0]['total_runs'], $match_summary[1]['total_runs']];
      }
    }

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

      $teamid = Team::where('id', '=', $team_id)->select('id')->get();
    return view('team_result', compact('teamid','tournamentData', 'tournament_ids', 'player', 'teamCaptain', 'teamPlayerCount', 'teamPlayers', 'team_resultData', 'team_resultData1', 'teamData', 'match_results',  'tournament', 'total_run_fixture', 'total_runs', 'total_wicket_fixture', 'team_id_data', 'image_gallery'));
  }


  public function team_schedule(int $team_id, int $tournament_id)
  {
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $tournament = Tournament::where('isActive', '=', 1)->pluck('name', 'id');
    $ground = Ground::orderBy('id')->get();
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
    // $teams_data = Team::pluck('name', 'id');



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
    return view('team_schedule', compact('teamData','teamid', 'tournament_ids', 'match_results', 'player', 'ground', 'tournamentData', 'tournament', 'teamPlayerCount', 'team_resultData', 'teamPlayers', 'team_id_data', 'team_scheduleData', "image_gallery"));
  }

  public function team_batting(int $team_id, int $tournament_id)
  {
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $tournament = Tournament::where('isActive', '=', 1)->pluck('name', 'id');
    $ground = Ground::orderBy('id')->get();
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
  
      $playername = TournamentPlayer::where('tournament_id', $tournament_id)
      ->where('team_id', $team_id)
      ->select('tournament_players.player_id', 'players.fullname')
      ->join('players', 'players.id', '=', 'tournament_players.player_id')
      ->pluck('players.fullname', 'tournament_players.player_id');

      $matchcount = Fixture::where('tournament_id', $tournament_id)
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->groupBy('fixture_scores.playerId')
      ->selectRaw('COUNT(DISTINCT(fixture_scores.fixture_id)) as matches_played,fixture_scores.playerId  as playerId')
      ->pluck('matches_played', 'playerId');

      // $playermatch = TournamentPlayer::where('tournament_players.tournament_id', $tournament_id)
      // ->where('tournament_players.team_id', $team_id)
      // ->where('fixtures.running_inning',3)
      // ->join('fixtures', 'fixtures.tournament_id', '=', 'tournament_players.tournament_id')
      // ->groupBy('tournament_players.player_id')
      // ->selectRaw('COUNT(DISTINCT(fixtures.id)) as `match`, tournament_players.player_id')
      // ->pluck('match', 'tournament_players.player_id');

      $team_ids = is_array($team_id) ? $team_id : [$team_id];

$playermatch = DB::table(function ($query) use ($team_ids, $tournament_id) {
        $query->select('team_id_a AS team_id')
            ->from('fixtures')
            ->whereIn('team_id_a', $team_ids)
            ->where('tournament_id', $tournament_id)
            ->where('running_inning', 3)
            ->unionAll(
                DB::table('fixtures')
                    ->select('team_id_b AS team_id')
                    ->whereIn('team_id_b', $team_ids)
                    ->where('tournament_id', $tournament_id)
                    ->where('running_inning', 3)
            );
    }, 'subquery')
        ->select('team_id', DB::raw('COUNT(*) AS count'))
        ->groupBy('team_id')
        ->get()
        ->pluck('count', 'team_id');

  
       $playerruns =Fixture::where('tournament_id', $tournament_id)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->groupBy('fixture_scores.playerId')
       ->selectRaw('SUM(fixture_scores.runs) as playerurns, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
       ->pluck('playerurns', 'playerId');

       $playerteam = TournamentPlayer::where('tournament_players.tournament_id', $tournament_id)
       ->where('tournament_players.team_id', $team_id)
       ->join('fixtures', 'fixtures.tournament_id', '=', 'tournament_players.tournament_id')
       ->groupBy('tournament_players.player_id', 'tournament_players.team_id')
       ->selectRaw('tournament_players.team_id, tournament_players.player_id')
       ->pluck('tournament_players.team_id', 'tournament_players.player_id');

       $playerballs =Fixture::where('tournament_id', $tournament_id)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->groupBy('fixture_scores.playerId')
       ->selectRaw('COUNT(fixture_scores.id) as playeballs, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
       ->pluck('playeballs', 'playerId');

       $playerouts =Fixture::where('tournament_id', $tournament_id)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->where('fixture_scores.balltype','=','Wicket')
       ->groupBy('fixture_scores.playerId')
       ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
       ->pluck('playeouts', 'playerId');

       $playersix =Fixture::where('tournament_id', $tournament_id)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->where('fixture_scores.issix','=',1)
       ->groupBy('fixture_scores.playerId')
       ->selectRaw('COUNT(fixture_scores.issix ) as playesix, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
       ->pluck('playesix', 'playerId');

       $playerfour =Fixture::where('tournament_id', $tournament_id)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->where('fixture_scores.isfour','=',1)
       ->groupBy('fixture_scores.playerId')
       ->selectRaw('COUNT(fixture_scores.isfour ) as playefour, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
       ->pluck('playefour', 'playerId');

       $playerhigestruns = Fixture::where('tournament_id', $tournament_id)
       ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
       ->groupBy('fixture_scores.playerId')
       ->selectRaw('SUM(fixture_scores.runs) as total_runs, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
       ->pluck('total_runs', 'playerId');


       $playerHundreds= DB::table(function ($query) use ($tournament_id) {
        $query->select('playerid', DB::raw('SUM(runs) AS hundred'), 'fixture_id')
            ->from('fixture_scores')
            ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
            ->where('fixtures.tournament_id', $tournament_id)
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
                ->groupBy('playerId', 'fixture_id');
        }, 'subquery')
        ->select('playerId', DB::raw('COUNT(*) AS fifties'))
        ->where('fifties', '>=', 50)
        ->where('fifties', '<', 100)
        ->groupBy('playerId')
        ->get()->pluck('fifties','playerId');



  //          $subquery = DB::table(function ($query) {
  //           $query->select(DB::raw('MAX(playerId) AS playerId'))
  //               ->from('fixture_scores AS fs')
  //               ->join('fixtures AS f', 'fs.fixture_id', '=', 'f.id')
  //               ->where('f.tournament_id', 50)
  //               ->groupBy('fs.playerId')
  //               ->havingRaw('SUM(fs.runs) >= 100');
  //       }, 'subquery');
        
  //       $hundredCounts = DB::table($subquery)
  //           ->select('playerId')
  //           ->selectRaw('COUNT(*) AS hundred_count')
  //           ->groupBy('playerId')
  //           ->pluck('hundred_count', 'playerId');
        
        
        
    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();


      $teamid = Team::where('id', '=', $team_id)->select('id')->get();
    return view('team_batting', compact('teamData','teamid','playerfifty','playerHundreds','playerhigestruns','playerfour','playersix','playerouts','playerballs','playerteam','playerruns','playername','playermatch', 'tournament_ids', 'match_results',  'player', 'ground', 'tournamentData', 'tournament', 'teamPlayerCount', 'team_resultData', 'teamPlayers', 'team_id_data',  "image_gallery",'matchcount'));
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
    $results = [];

    return view('batting_states', compact('fours', 'balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_count_player', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years', 'results'));
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
      ->where('isActive', '=', 1)->where('isActive', '=', 1)
      ->pluck('name', 'id');

    $image_gallery = GalleryImages::query()
      ->where('isActive', 1)
      ->get();

    $data = Fixture::query();
    $term = $request->input();
    // dd($term);
    if (!empty($term['year'])) {
      $year = $term['year'];
      $data->whereRaw("YEAR(fixtures.created_at) = $year");
    }
    $tournament = $term['tournament'];
    if (!empty($term['tournament'])) {
      $tournament = $term['tournament'];
      $data->where('fixtures.tournament_id', '=', $tournament);
    }
   
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
    // $match_count = DB::table('fixtures')
    //   ->selectRaw('COUNT(DISTINCT fixtures.id) as match_count, tournament_players.player_Id')
    //   ->join('tournament_players', 'tournament_players.tournament_id', '=', 'fixtures.tournament_id')
    //   ->where('fixtures.tournament_id', $tournament)
    //   ->groupBy('tournament_players.player_Id')
    //   ->get()
    //   ->pluck('match_count', 'player_Id');

      $teamIds = TournamentGroup::where('tournament_id', $tournament)
      ->select('team_id')
      ->groupBy('team_id')
      ->pluck('team_id');
  
  $match_count = DB::table(function ($query) use ($teamIds, $tournament) {
      $query->select('team_id_a AS team_id')
          ->from('fixtures')
          ->whereIn('team_id_a', $teamIds)
          ->where('tournament_id', $tournament)
          ->unionAll(
              DB::table('fixtures')
                  ->select('team_id_b AS team_id')
                  ->whereIn('team_id_b', $teamIds)
                  ->where('tournament_id', $tournament)
          );
  }, 'subquery')
      ->select('team_id', DB::raw('COUNT(*) AS count'))
      ->groupBy('team_id')
      ->get()->pluck('count','team_id');
  
//  $query = DB::getQueryLog();
//                     $query = DB::getQueryLog();
//             dd($query);
    $inningsCount = DB::table('fixture_scores')
      ->selectRaw('COUNT(DISTINCT fixtures.id) as count, fixture_scores.playerId')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->where('fixtures.tournament_id', $tournament)
      ->groupBy('fixture_scores.playerId')
      ->get()->pluck('count', 'playerId');

    $player_runs= FixtureScore::where('fixtures.tournament_id',$tournament)
    ->selectRaw('SUM(runs) as totalruns, fixture_scores.playerId')
      ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
      ->orderBy('totalruns', 'desc')
      ->groupBy('fixture_scores.playerId')
      ->get()->pluck('totalruns', 'playerId');

    $variable1 = 'R';
    $variable2 = 'Wicket';
    $balls_faced = FixtureScore::where('fixtures.tournament_id', $tournament)
    ->where(function ($query) use ($variable1, $variable2) {
        $query->where('balltype', $variable1)
            ->orWhere('balltype', $variable2);
    })
    ->selectRaw('COUNT(fixture_scores.id) as balls, fixture_scores.playerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('fixture_scores.playerId')
    ->get()
    ->pluck('balls', 'playerId');

    $sixes= FixtureScore::where('fixtures.tournament_id', $tournament)
    ->where('issix', 1)
    ->selectRaw('COUNT(*) as six, fixture_scores.playerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('fixture_scores.playerId')
    ->get()
    ->pluck('six', 'playerId');

  $fours= FixtureScore::where('fixtures.tournament_id', $tournament)
    ->where('isfour', 1)
    ->selectRaw('COUNT(*) as four, fixture_scores.playerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('fixture_scores.playerId')
    ->get()
    ->pluck('four', 'playerId');

  $playerouts =Fixture::where('tournament_id', $tournament)
    ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
    ->where('fixture_scores.balltype','=','Wicket')
    ->where('fixture_scores.isout','=',1)
    ->groupBy('fixture_scores.playerId')
    ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, fixture_scores.playerId')
    ->pluck('playeouts', 'playerId');

 
   $fifty=DB::table(function ($query) use ($tournament) {
          $query->select('playerId', DB::raw('SUM(runs) AS fifties'), 'fixture_id')
              ->from('fixture_scores')
              ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
              ->where('fixtures.tournament_id', $tournament)
              ->groupBy('playerId', 'fixture_id');
      }, 'subquery')
      ->select('playerId', DB::raw('COUNT(*) AS fifties'))
      ->where('fifties', '>=', 50)
      ->where('fifties', '<', 100)
      ->groupBy('playerId')
      ->get()->pluck('fifties', 'playerId');

    $hundreds=DB::table(function ($query) use ($tournament) {
      $query->select('playerId', DB::raw('SUM(runs) AS hundred'), 'fixture_id')
          ->from('fixture_scores')
          ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
          ->where('fixtures.tournament_id', $tournament)
          ->groupBy('playerId', 'fixture_id');
      }, 'subquery')
      ->select('playerId', DB::raw('COUNT(*) AS hundred'))
      ->where('hundred', '>=', 100)
      ->groupBy('playerId')
      ->get()->pluck('hundred', 'playerId');
          

    $results = array();
    foreach ($getresult as $teamPlayer) {
      $higest_score_query = FixtureScore::where('playerId', $teamPlayer->player_id)
        ->selectRaw('SUM(runs) as total_runs, fixture_id')
        ->where('fixtures.tournament_id',$tournament)
        ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
        ->groupBy('fixture_id')
        ->orderByDesc('total_runs')
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

    // dd($results);

   
    return view('batting_states', compact('fours', 'higest_score', 'fifty', 'hundreds', 'balls_faced', 'sixes', 'tournamentdata', 'balls_faced', 'player_runs', 'match_count', 'player', 'teams', 'match_results', 'image_gallery', 'years', 'getresult','inningsCount','playerouts', 'results'));
  }


  public function comingsoon()
  {
    $match_results = Fixture::query();
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();
    // $teams = Team::pluck('name', 'id');


    return view('comingsoon', compact('match_results'));
  }

  public function clubs()
  {
    $match_results = Fixture::query();
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results->orderBy('id')->get();
    // $teams = Team::pluck('name', 'id');


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
      ->selectRaw('teams.name')
      ->selectRaw('tournament_groups.tournament_id')
      ->selectRaw('MAX(IF(team_players.iscaptain = 1, team_players.player_id, NULL)) AS player_id')
      ->join('teams', 'teams.id', '=', 'team_players.team_id')
      ->join('tournament_groups', 'tournament_groups.team_id', '=', 'team_players.team_id')
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
      $data = Fixture::query();
   
  
  
  
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
    $data = Fixture::query();
    $term = $request;
    if (!empty($term->created_at)) {
      $data->whereRaw("DATE(match_startdate) >= Date('$term->created_at')");
    }
    if (!empty($term->end_at)) {
      $data->whereRaw("DATE(match_enddate) <= Date('$term->end_at')");
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

    // dd($ground);
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

  public function seasonresponsers()
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


    return view('seasonresponsers', compact('teams', 'match_results', 'image_gallery'));
  }

  public function leagueinfo(int $id)
  {
    $match_results = Fixture::where('running_inning', '=', 3)->orderBy('id')->get();
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
    $tournament_name = Tournament::query()->where('season_id', '=', 0)->where('is_web_display', '=', 1)->get();
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

    // dd($ground);

    return view('clubviewteams', compact('tournament_name', 'results', 'match_results', 'ground2', 'clubs', 'ground', 'years', 'tournament', 'image_gallery'));
  }


  public function clubviewteams_submit(Request $request)
  {
    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    DB::enableQueryLog();

    $years = DB::table('tournaments')
      ->select(DB::raw('YEAR(tournamentstartdate) as year'))
      ->groupBy(DB::raw('YEAR(tournamentstartdate)'))
      ->orderBy(DB::raw('YEAR(tournamentstartdate)'), 'desc')
      ->pluck('year');
    $match_results = Fixture::query()->where('isActive', 1)->get();

    $data = TournamentGroup::query()
      ->selectRaw('players.fullname, teams.id, teams.clubname, teams.name, tournament_groups.tournament_id, tournaments.tournamentstartdate,tournaments.name as tournamentname')
      ->where('teams.isclub', 1)
      ->where('team_players.iscaptain', 1)
      ->join('tournaments', 'tournaments.id', '=', 'tournament_groups.tournament_id')
      ->join('teams', function ($join) {
        $join->on('teams.id', '=', 'tournament_groups.team_id');
      })->join('team_players', 'team_players.team_id', '=', 'teams.id')
      ->join('players', 'players.id', '=', 'team_players.player_id');


    $term = $request;
    if (!empty($term['year'])) {
      $year = $term['year'];
      $data->whereRaw("YEAR(tournaments.tournamentstartdate) = $year");
    }

    if (!empty($term['tournament'])) {
      $tournaments = $term['tournament'];
      $data->where('tournament_groups.tournament_id', '=', $tournaments);
    }

    $results = $data->orderby('tournament_groups.team_id')
      ->get();
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
    DB::enableQueryLog();

    $years = DB::table('tournaments')
      ->select(DB::raw('YEAR(tournamentstartdate) as year'))
      ->groupBy(DB::raw('YEAR(tournamentstartdate)'))
      ->orderBy(DB::raw('YEAR(tournamentstartdate)'), 'desc')
      ->pluck('year');
    $match_results = Fixture::query()->where('isActive', 1)->get();

    $data = TournamentGroup::query()
      ->selectRaw('players.fullname, teams.id, teams.clubname, teams.name, tournament_groups.tournament_id, tournaments.tournamentstartdate,tournaments.name as tournamentname')
      ->where('teams.isclub', 1)
      ->where('team_players.iscaptain', 1)
      ->join('tournaments', 'tournaments.id', '=', 'tournament_groups.tournament_id')
      ->join('teams', function ($join) {
        $join->on('teams.id', '=', 'tournament_groups.team_id');
      })->join('team_players', 'team_players.team_id', '=', 'teams.id')
      ->join('players', 'players.id', '=', 'team_players.player_id');


    $term = $request;
    if (!empty($term['year'])) {
      $year = $term['year'];
      $data->whereRaw("YEAR(tournaments.tournamentstartdate) = $year");
    }

    if (!empty($term['tournament'])) {
      $tournaments = $term['tournament'];
      $data->where('tournament_groups.tournament_id', '=', $tournaments);
    }

    $results = $data->orderby('tournament_groups.team_id')
      ->get();
    $tournament = Tournament::query()->pluck(
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

    return view('clubviewteams', compact('results', 'ground2', 'match_results', 'ground', 'years', 'tournament', 'image_gallery'));
  }


  public function  view_tournaments(int $tournament_id)
  {


    $match_results = Fixture::query();
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


    return view('display_tournaments', compact('match_results', 'teams', "tournament_name", 'select_tournament_name', 'seasons'));
  }





  public function  view_all_tournaments()
  {

    $match_results = Fixture::query();
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




    return view('display_all_tournaments', compact('match_results', 'teams', "tournament_name"));
  }


  public function  view_all_grounds()
  {


    $match_results = Fixture::query();
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

    $match_results = Fixture::query();
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

    $match_results = Fixture::query();
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
    $match_results = Fixture::query();
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
    DB::enableQueryLog();

    $years = DB::table('tournaments')
      ->select(DB::raw('YEAR(tournamentstartdate) as year'))
      ->groupBy(DB::raw('YEAR(tournamentstartdate)'))
      ->orderBy(DB::raw('YEAR(tournamentstartdate)'), 'desc')
      ->pluck('year');
    $match_results = Fixture::query()->get();

    $data = TournamentGroup::query()
      ->selectRaw('players.fullname, teams.id, teams.clubname, teams.name, tournament_groups.tournament_id, tournaments.tournamentstartdate,tournaments.name as tournamentname')
      ->where('teams.isclub', 1)
      ->where('team_players.iscaptain', 1)
      ->join('tournaments', 'tournaments.id', '=', 'tournament_groups.tournament_id')
      ->join('teams', function ($join) {
        $join->on('teams.id', '=', 'tournament_groups.team_id');
      })->join('team_players', 'team_players.team_id', '=', 'teams.id')
      ->join('players', 'players.id', '=', 'team_players.player_id');

    $data->where('tournament_groups.tournament_id', '=', $tournament_id);

    $results = $data->orderby('tournament_groups.team_id')
      ->get();

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

    $name = Tournament::query()
      ->where('id', '=', $tournament_id)
      ->get();


    return view('viewteams', compact('results', 'ground2', 'match_results', 'ground', 'years', 'tournament', 'image_gallery', 'name'));
  }

  public function matchofficial()
  {
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


    return view('matchofficial', compact('match_results', 'teams', "tournament_name", "grounds", "umpire_matchoffcial"));
  }

  public function playerview(int $playerid)
  {
    $match_results = Fixture::query();
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
    $player_data = FixtureScore::where('fixture_scores.playerid', $playerid)
      ->join('team_players', 'team_players.player_id', '=', 'fixture_scores.playerid')
      ->join('players', 'players.id', '=', 'fixture_scores.playerid')
      ->select('team_players.player_id as playername', 'players.bowlingstyle as playerbowlingstyle', 'players.battingstyle as playerbattingstyle')
      ->distinct('players.playerid')
      ->get();

    $player_team = FixtureScore::where('fixture_scores.playerid', $playerid)
      ->join('team_players', 'team_players.player_id', '=', 'fixture_scores.playerid')
      ->join('players', 'players.id', '=', 'fixture_scores.playerid')
      ->select('team_players.player_id as playername', 'team_players.team_id as playerteam')
      ->distinct('players.playerid')
      ->get();

    $player_club = FixtureScore::where('fixture_scores.playerid', $playerid)
      ->join('team_players', 'team_players.player_id', '=', 'fixture_scores.playerid')
      ->join('teams', 'teams.id', '=', 'team_players.team_id')
      ->where('teams.isclub', 1)
      ->select('teams.clubname as playerclub')
      ->distinct('players.playerid')
      ->get();

    $teamIds = FixtureScore::where('fixture_scores.playerid', $playerid)
      ->join('team_players', 'team_players.player_id', '=', 'fixture_scores.playerid')
      ->join('teams', 'teams.id', '=', 'team_players.team_id')
      ->select('teams.id as playerclub')
      ->distinct('players.playerid')
      ->get()->pluck('playerclub');

    // $player_match = FixtureScore::where('playerid', $playerid)->groupBy('playerid', 'fixture_id')->selectRaw('playerid')->selectRaw('fixture_id')->get();
    // $player_match = $player_match->count();

    $player_match = DB::table(function ($query) use ($teamIds) {
      $query->select('team_id_a AS team_id')
          ->from('fixtures')
          ->whereIn('team_id_a', $teamIds)
          ->where('running_inning', 3)
          ->unionAll(
              DB::table('fixtures')
                  ->select('team_id_b AS team_id')
                  ->whereIn('team_id_b', $teamIds)
                  ->where('running_inning', 3)
          );
  }, 'subquery')
      ->select('team_id', DB::raw('COUNT(*) AS count'))
      ->groupBy('team_id')
      ->get()->pluck('count','team_id');

    $player_runs = FixtureScore::where('playerid', $playerid)
      // ->selectRaw('SUM(runs) as playerruns')
      ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'NBP' OR balltype = 'Wicket' THEN runs ELSE 0 END) as playerruns")
      ->groupBy('playerid')
      ->get();


      $variable1 = 'R';
      $variable2 = 'Wicket';
      $variable3 = 'BYES';
      
      $player_balls = FixtureScore::where(function ($query) use ($variable1, $variable2,$variable3) {
          $query->where('balltype', '=', $variable1)
            ->orWhere('balltype', '=', $variable2)
            ->orWhere('balltype', '=', $variable3);
        })->selectRaw("count(id) as balls")
        ->selectRaw("playerId")->groupBy('playerId')
        ->get()->pluck('balls', 'playerId');

        $playerouts =Fixture::where('fixture_scores.playerid', $playerid)
        ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
        ->where('fixture_scores.balltype','=','Wicket')
        ->groupBy('fixture_scores.playerId')
        ->selectRaw('COUNT(fixture_scores.balltype ) as playeouts, CAST(fixture_scores.playerId AS UNSIGNED) as playerId')
        ->pluck('playeouts', 'playerId');
$higest_score=[];
        foreach ($player_data as $teamPlayer) {
          $higest_score_query = FixtureScore::where('playerId', $teamPlayer->playername)
            ->selectRaw('SUM(runs) as total_runs, fixture_id')
            ->join('fixtures','fixtures.id','=','fixture_scores.fixture_id')
            ->groupBy('fixture_id')
            ->orderByDesc('total_runs')
            ->limit(1);
            $higest_score[$teamPlayer->playername] = $higest_score_query->value('total_runs');
        }

    // $player_balls = FixtureScore::where('playerid', $playerid)
    //   ->where('fixture_scores.balltype', '=', 'R')
    //   ->selectRaw("COUNT(id) as max_ball ")
    //   ->groupBy('playerid')
    //   ->get();



    $player_wicket = FixtureScore::where('bowlerid', $playerid)
      ->where('balltype', '=', 'Wicket')
      ->selectRaw('SUM(isout) as playerwickets')
      ->groupBy('bowlerid')
      ->get();

    $player_total_fifties =   $player_total_fifties = DB::table(function ($query) {
      $query->select('playerId', DB::raw('SUM(runs) AS fifties'), 'fixture_id')
          ->from('fixture_scores')
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
          ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
          ->groupBy('playerId', 'fixture_id');
      }, 'subquery')
      ->select('playerId', DB::raw('COUNT(*) AS hundred'))
      ->where('hundred', '>=', 100)
      ->groupBy('playerId')
      ->get()->pluck('hundred', 'playerId');

    $player_runs1 = FixtureScore::where('playerid', $playerid)
      ->where('fixture_scores.balltype', 'R')
      ->selectRaw('SUM(runs) as playerruns')
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

    $player_average = 0;

    if ($player_runs1 && $player_innings) {
      $runs = $player_runs1->playerruns;
      $innings = $player_innings->innings;

      if ($innings > 0) {
        $player_average = $runs / $innings;
      }
    }
    $player_average = number_format($player_average, 2);
    $player_strike_rate = 0;

    if ($player_runs1 !== null && $player_balls1 !== null && $player_balls1->playerballs > 0) {
      $player_strike_rate = ($player_runs1->playerruns / $player_balls1->playerballs) * 100;
    }

    $player_strike_rate = number_format($player_strike_rate, 2);

    $player_six = FixtureScore::where('playerid', $playerid)
      ->where('fixture_scores.balltype', '=', 'R')
      ->selectRaw("SUM(fixture_scores.issix = 1 AND fixture_scores.balltype = 'R') as total_sixes")
      ->groupBy('playerid')
      ->get();


    $player_inning_score = FixtureScore::where('playerid', $playerid)
      ->where('fixture_scores.balltype', '=', 'R')
      ->selectRaw('SUM(runs) as runs')
      ->selectRaw('fixture_id')
      ->groupBy('fixture_id')
      ->get()->pluck('runs')->toArray();

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

    // dd($player_inning_wickets);


    $player_four = FixtureScore::where('playerid', $playerid)
      ->where('fixture_scores.balltype', '=', 'R')
      ->selectRaw("SUM(fixture_scores.isfour = 1 AND fixture_scores.balltype = 'R') as total_four")
      ->groupBy('playerid')
      ->get();

    // bower state
    $player_matchbowler = FixtureScore::where('bowlerid', $playerid)->count();
    $bower_over = FixtureScore::where('bowlerid', $playerid)
      ->selectRaw("SUM(fixture_scores.balltype = 'WD') as total_wides")
      ->selectRaw('SUM(runs) as bowler_runs')
      ->selectRaw('MAX(overnumber) as max_over')
      ->groupBy('bowlerid')
      ->get();
    $match_dissmissal_name = Dismissal::where('dismissals.name', '=', 'Caught')
      ->selectRaw("dismissals.id as dissmissalname")
      ->groupBy('dismissals.id')
      ->get()->pluck('dissmissalname');

    $player_cauches = FixtureScore::where('bowlerid', $playerid)
      ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
      ->where('match_dismissals.dismissal_id', $match_dissmissal_name)
      ->selectRaw("COUNT(match_dismissals.id) as total_catches")
      ->groupBy('fixture_scores.bowlerid')
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


    return view('playerview', compact("bowler_economy",'higest_score', "bowler_balls", "bowler_strike_rate", "player_cauches", "bower_over", 'player_four', 'match_results', 'teams', 'player_team', "tournament_name", "grounds", "player_data", "teams", "player", "player_club", "player_match", "player_runs", "player_wicket", "player_total_fifties", "player_hundreds", "player_balls", "player_average", "player_strike_rate", "player_innings", "player_six", "player_matchbowler", "chart", 'bowler_inning_wicket', 'bowler_wicket_chart', 'batsman_wicket_chart','playerouts'));
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


    return view('bowling_state', compact('player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years'));
  }

  public function bowling_state_submit(Request $request)
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
      $term = $request->input();

      $tournament = $term['tournament'];

   
    $data = TournamentPlayer::where('tournament_players.tournament_id', $tournament)
      ->selectRaw('fixture_scores.bowlerId as bowler_id')
      ->selectRaw('tournament_players.tournament_id')
      ->selectRaw('team_players.player_id')
      ->selectRaw('team_players.team_id')
      ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches')
      ->selectRaw('COUNT(DISTINCT fixture_scores.id) as total_overs')
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


    
    // dd($term);
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
    
    $teamIds = TournamentGroup::where('tournament_id', $tournament)
    ->select('team_id')
    ->groupBy('team_id')
    ->pluck('team_id');

    $variable1 = 'R';
    $variable2 = 'Wicket';
    $bowlerballs = FixtureScore::where('fixtures.tournament_id', $tournament)
    ->where(function ($query) use ($variable1, $variable2) {
        $query->where('balltype', $variable1)
            ->orWhere('balltype', $variable2);
    })
    ->selectRaw('COUNT(fixture_scores.id) as balls, fixture_scores.bowlerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('fixture_scores.bowlerId')
    ->get()
    ->pluck('balls', 'bowlerId');

    $match_count = DB::table(function ($query) use ($teamIds, $tournament) {
    $query->select('team_id_a AS team_id')
        ->from('fixtures')
        ->whereIn('team_id_a', $teamIds)
        ->where('tournament_id', $tournament)
        ->where('running_inning', 3)
        ->unionAll(
            DB::table('fixtures')
                ->select('team_id_b AS team_id')
                ->whereIn('team_id_b', $teamIds)
                ->where('tournament_id', $tournament)
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
      ->where('fixtures.tournament_id', $tournament)
      ->groupBy('fixture_scores.bowlerId')
      ->get()->pluck('count', 'bowlerId');

      $bowlerruns= FixtureScore::where('fixtures.tournament_id', $tournament)
      ->selectRaw('SUM(fixture_scores.runs) as runs, fixture_scores.bowlerId')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->groupBy('fixture_scores.bowlerId')
      ->get()
      ->pluck('runs', 'bowlerId');

      $match_dissmissal_runout_name= Dismissal::where('dismissals.name', '=', 'Run out')
      ->selectRaw("dismissals.id as dissmissalname")
      ->get()->pluck('dissmissalname');
    

      $bowlerwickets= FixtureScore::where('fixtures.tournament_id', $tournament)
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
    $balls_faced = FixtureScore::where('fixtures.tournament_id', $tournament)
    ->where(function ($query) use ($variable1, $variable2) {
        $query->where('balltype', $variable1)
            ->orWhere('balltype', $variable2);
    })
    ->selectRaw('COUNT(fixture_scores.id) as balls, fixture_scores.playerId')
    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
    ->groupBy('fixture_scores.playerId')
    ->get()
    ->pluck('balls', 'playerId');

    $hatricks = 0;
    $results = array();
    foreach ($getresult as $teamPlayer) {
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
        ->where('fs1.bowlerId', $teamPlayer->bowler_id)
        ->where('fs1.isout', '=', 1)
        ->whereNull('fs4.id')
        ->select(DB::raw('COUNT(*) as total_hat_tricks'))
        ->pluck('total_hat_tricks')
        ->toArray();
      $hatricks = $total_hat_tricks;
      $results[] = [
        'bowler_id' => $teamPlayer->bowler_id,
        'tournament_id' => $teamPlayer->tournament_id,
        'player_id' => $teamPlayer->player_id,
        'team_id' => $teamPlayer->team_id,
        'bowler_id' => $teamPlayer->bowler_id,
        'total_matches' => $teamPlayer->total_matches,
        'total_overs' => $teamPlayer->total_overs,
        'total_wides' => $teamPlayer->total_wides,
        'total_noball' => $teamPlayer->total_noball,
        'total_runs' => $teamPlayer->total_runs,
        'player_wickets_keys' =>$bowlerwickets[$teamPlayer->bowler_id],
      ];
      
    }


$player_wickets_keys = array_column($results, 'player_wickets_keys');
array_multisort($player_wickets_keys, SORT_DESC, $results);

    // dd($hatricks);

    return view('bowling_state', compact('tournamentdata','bowlerwickets','bowlerruns','bowlerballs','inningsCount','match_count' ,'player', 'teams', 'match_results', 'image_gallery', 'years', 'getresult','results'));
  }


  public function pointtable()
  {
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

    $result = array();

    return view('pointtable', compact('result', 'tournamentdata', 'years', 'match_results', 'teams', "tournament_name", "grounds", "umpire_matchoffcial"));
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


    $get_point_table_data = TournamentGroup::query()
      ->selectRaw("team_name.name as teams_name")
      ->selectRaw("team_name.id as teams_id")
      ->selectRaw("tournament_groups.tournament_id")
      ->Join('teams as team_name', 'team_name.id', '=', 'tournament_groups.team_id');


      $match_query = Fixture::query();
      if (!empty($term['year'])) {
        $year = $term['year'];
        $match_query->whereRaw("YEAR(match_startdate) = $year");
      }
      if (!empty($term['tournament'])) 
      {
          $tournament = $term['tournament'];
          $match_query->where('tournament_id', $tournament);
      }
      $match_query->where('running_inning', 3);
      $match_query->selectRaw("COUNT(id)");
      $match_query->selectRaw("team_id_a");
      $match_query->groupby('team_id_a');
      $match_count_team_a = $match_query->get()->pluck('COUNT(id)', 'team_id_a');
      //  $query = DB::getQueryLog();
      //               $query = DB::getQueryLog();
      //       dd($query);
    // dd($match_count_team_a);


      $match_queryb = Fixture::query();
      if (!empty($term['year'])) {
        $year = $term['year'];
        $match_queryb->whereRaw("YEAR(match_startdate) = $year");
      }
      if (!empty($term['tournament'])) 
      {
          $tournament = $term['tournament'];
          $match_queryb->where('tournament_id', $tournament);
      }
      $match_queryb->where('running_inning', 3);
      $match_queryb->selectRaw("COUNT(id)");
      $match_queryb->selectRaw("team_id_b");
      $match_queryb->groupby('team_id_b');
      $match_count_team_b = $match_queryb->get()->pluck('COUNT(id)', 'team_id_b');
      // dd($match_count_team_b);

    // $match_count_team_b = Fixture::query()
    //   ->where('running_inning', 3)
    //   ->selectRaw("COUNT(id)")
    //   ->selectRaw("team_id_b")
    //   ->groupby('team_id_b')
    //   ->get()->pluck('COUNT(id)', 'team_id_b');

    $match_count_winning = Fixture::query();
    if (!empty($term['year'])) {
      $year = $term['year'];
      $match_count_winning->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $match_count_winning->where('tournament_id', $tournament);
    }
    $match_count_winning->selectRaw("COUNT(id)");
    $match_count_winning->where('running_inning', 3);
    $match_count_winning->selectRaw("winning_team_id");
    $match_count_winning->groupby('winning_team_id');
    $match_count_winning_team=$match_count_winning->get()->pluck('COUNT(id)', 'winning_team_id');

    $match_count_loss= Fixture::query();
    if (!empty($term['year'])) {
      $year = $term['year'];
      $match_count_loss->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $match_count_loss->where('tournament_id', $tournament);
    }
    $match_count_loss->selectRaw("COUNT(id)");
    $match_count_loss->selectRaw("lossing_team_id");
    $match_count_loss->where('running_inning', 3);
    $match_count_loss->groupby('lossing_team_id');
    $match_count_loss_team=$match_count_loss->get()->pluck('COUNT(id)', 'lossing_team_id');

    $match_count_tie= Fixture::query();
    if (!empty($term['year'])) {
      $year = $term['year'];
      $match_count_tie->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $match_count_tie->where('tournament_id', $tournament);
    }
    $match_count_tie->where('is_tie_match', 1);
    $match_count_tie->selectRaw('team_id_a, team_id_b, COUNT(is_tie_match) as tie');
    $match_count_tie ->groupBy('team_id_a', 'team_id_b');
    $match_count_tie_team=$match_count_tie->pluck('tie', 'team_id_a', 'team_id_b');

    $bonusPoints_team_A = Fixture::query();
    if (!empty($term['year'])) {
      $year = $term['year'];
      $bonusPoints_team_A->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $bonusPoints_team_A->where('tournament_id', $tournament);
    }
    $bonusPoints_team_A->selectRaw('SUM(teamAbonusPoints) as totalBonusPoints');
    $bonusPoints_team_A->selectRaw('team_id_a');
    $bonusPoints_team_A->groupBy('team_id_a');
    $bonusPointsSum_team_A=$bonusPoints_team_A->pluck('totalBonusPoints', 'team_id_a');

    $bonusPoints_team_B = Fixture::query();
    if (!empty($term['year'])) {
      $year = $term['year'];
      $bonusPoints_team_B->whereRaw("YEAR(match_startdate) = $year");
    }
    if (!empty($term['tournament'])) 
    {
        $tournament = $term['tournament'];
        $bonusPoints_team_B->where('tournament_id', $tournament);
    }
    $bonusPoints_team_B->selectRaw('SUM(teamBbonusPoints) as totalBonusPoints');
    $bonusPoints_team_B->selectRaw('team_id_b');
    $bonusPoints_team_B->groupBy('team_id_b');
    $bonusPointsSum_team_B=$bonusPoints_team_B->pluck('totalBonusPoints', 'team_id_b');

    $ApiController = new ApiController();
    $net_run_rate_result = $ApiController->calculateNetRunRate($tournament);
    // dd($net_run_rate_result);
    // app()->call([ApiController::class, 'otherMethod']);

    $point_table_res_tem_a = $net_run_rate_result['point_table_res_tem_a'];
    $point_table_res_tem_b = $net_run_rate_result['point_table_res_tem_b'];

    // dd($term);
    if (!empty($term['year'])) {
      $year = $term['year'];
      $get_point_table_data->whereRaw("YEAR(tournament_groups.created_at) = $year");
    }
    if (!empty($term['tournament'])) {
      $tournament = $term['tournament'];
      $get_point_table_data->where('tournament_groups.tournament_id', '=', $tournament);
    }

    $getdata = $get_point_table_data->get()->pluck('teams_name', 'teams_id');
    $result = array();
    foreach ($getdata as $team_id => $team_name) {
      $team_netrr = 0;
      $team_wins = isset($match_count_winning_team[$team_id]) ? $match_count_winning_team[$team_id] : 0;

      $team_losses = isset($match_count_loss_team[$team_id]) ? $match_count_loss_team[$team_id] : 0;
      $team_total_matches = isset($match_count_team_a[$team_id]) ? $match_count_team_a[$team_id] : 0;
      $team_tie = isset($match_count_tie_team[$team_id]) ? $match_count_tie_team[$team_id] : 0;


      $bonus_points_A = isset($bonusPointsSum_team_A[$team_id]) ? $bonusPointsSum_team_A[$team_id] : 0;
      $bonus_points_B = isset($bonusPointsSum_team_B[$team_id]) ? $bonusPointsSum_team_B[$team_id] : 0;


      $total_bonus_points = $bonus_points_A + $bonus_points_B;

      if (isset($match_count_team_b[$team_id])) {
        $team_total_matches += $match_count_team_b[$team_id];
      }

      $team_players_count = isset($team_players[$team_id]) ? $team_players[$team_id] : 0;

      
      if(count($point_table_res_tem_a)>0)
      {
          foreach($point_table_res_tem_a as $netrr_team)
          {
              // dd($netrr_team);
              if( $netrr_team->first_inning_team_id==$team_id)
              {
                  $team_netrr += $netrr_team->team_netrr    ;    
              }
          }
      }

      if(count($point_table_res_tem_b)>0)
      {
          foreach($point_table_res_tem_b as $netrr_team)
          {
              if($netrr_team->second_inning_team_id==$team_id)
              {
                  $team_netrr += $netrr_team->team_netrr    ;    
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


    $wins = array_column($result, 'wins');
    array_multisort($wins, SORT_DESC, $result);


    return view('pointtable', compact('result', 'tournamentdata', 'years', 'match_results', 'teams', "tournament_name", "grounds", "umpire_matchoffcial"));
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

    return view('fieldingRecords', compact('fours', 'balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_count_player', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years'));
  }



  public  function viewteams_submit(Request $request)
  {

    $ground = Ground::query()->get()->pluck(
      'name',
      'id'
    );
    DB::enableQueryLog();

    $years = DB::table('tournaments')
      ->select(DB::raw('YEAR(tournamentstartdate) as year'))
      ->groupBy(DB::raw('YEAR(tournamentstartdate)'))
      ->orderBy(DB::raw('YEAR(tournamentstartdate)'), 'desc')
      ->pluck('year');
    $match_results = Fixture::query()->where('isActive', 1)->get();

    $data = TournamentGroup::query()
      ->selectRaw('players.fullname, teams.id, teams.clubname, teams.name, tournament_groups.tournament_id, tournaments.tournamentstartdate,tournaments.name as tournamentname')
      ->where('teams.isclub', 1)
      ->where('team_players.iscaptain', 1)
      ->join('tournaments', 'tournaments.id', '=', 'tournament_groups.tournament_id')
      ->join('teams', function ($join) {
        $join->on('teams.id', '=', 'tournament_groups.team_id');
      })->join('team_players', 'team_players.team_id', '=', 'teams.id')
      ->join('players', 'players.id', '=', 'team_players.player_id');

    // dd($data->get());


    $term = $request;
    if (!empty($term['year'])) {
      $year = $term['year'];
      $data->whereRaw("YEAR(tournaments.tournamentstartdate) = $year");
    }

    if (!empty($term['tournament'])) {
      $tournaments = $term['tournament'];
      // dd($tournaments);
      $data->where('tournament_groups.tournament_id', '=', $tournaments);
      // dd($data);
    }

    $results = $data->orderby('tournament_groups.team_id')
      ->get();
      $tournament = Tournament::query()->where('isActive', '=', 1)->pluck(
        'name',
        'id'
      );

    // dd($results);

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();

    $ground2 = Ground::query()->get()->pluck(
      'name',
      'id'
    );

    return view('viewteams', compact('results', 'ground2', 'match_results', 'ground', 'years', 'tournament', 'image_gallery'));
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


    $match_dissmissal_caught = Dismissal::where('dismissals.name', '=', 'Caught')
      ->first();


      $match_dissmissal_stumped = Dismissal::where('dismissals.name', '=', 'Stumped')
      ->first();

      $dismissalIdcatch = $match_dissmissal_caught->id;
      $dismissalIdstump = $match_dissmissal_stumped->id ;

    $getresult = [];

    $term = $request->input();
    $query = "
SELECT 
    bowler_id,
    team_id,
    MAX(total_matches_catch) AS total_matches,
    SUM(catch) AS catch,
    SUM(stump) AS stump
FROM 
    (SELECT 
        tournament_players.player_id AS bowler_id,
        tournament_players.team_id AS team_id,
        COUNT(DISTINCT fixture_scores.fixture_id) AS total_matches_catch,
        COUNT(DISTINCT CASE WHEN match_dismissals.dismissal_id = $dismissalIdcatch THEN match_dismissals.fixturescores_id END) AS catch,
        0 AS stump
    FROM 
        tournaments 
    JOIN 
        fixtures ON fixtures.tournament_id = tournaments.id 
    JOIN 
        fixture_scores ON fixture_scores.fixture_id = fixtures.id
    JOIN 
        match_dismissals ON match_dismissals.outbyplayer_id = fixture_scores.bowlerId
    JOIN 
        tournament_players ON tournament_players.player_id = fixture_scores.bowlerId
    WHERE 
        fixture_scores.isout = 1
        AND match_dismissals.dismissal_id = $dismissalIdcatch";

if (!empty($term['year'])) {
  $year = $term['year'];
    $query .= " AND YEAR(fixture_scores.created_at) = $year";
}


if (!empty($term['tournament'])) {
  $tournament = $term['tournament'];
  $tournament = (int)$tournament;
  $query .= " AND tournaments.id = $tournament";
}

if (!empty($term['teams'])) {
  $team = $term['teams'];
  $team = (int)$team;
  $query .= " AND  tournament_players.team_id = $team";
}

$query .= " GROUP BY tournament_players.player_id, tournament_players.team_id
    UNION ALL
    SELECT 
        tournament_players.player_id AS bowler_id,
        tournament_players.team_id AS team_id,
        COUNT(DISTINCT fixture_scores.fixture_id) AS total_matches_stump,
        0 AS catch,
        COUNT(DISTINCT CASE WHEN match_dismissals.dismissal_id = $dismissalIdstump THEN match_dismissals.fixturescores_id END) AS stump
    FROM 
        tournaments 
    JOIN 
        fixtures ON fixtures.tournament_id = tournaments.id
    JOIN 
        fixture_scores ON fixture_scores.fixture_id = fixtures.id
    JOIN 
        match_dismissals ON match_dismissals.outbyplayer_id = fixture_scores.bowlerId
    JOIN 
        tournament_players ON tournament_players.player_id = fixture_scores.bowlerId
    WHERE 
        fixture_scores.isout = 1
        AND match_dismissals.dismissal_id = $dismissalIdstump";


if (!empty($term['year'])) {
  $year = $term['year'];
    $query .= " AND YEAR(fixture_scores.created_at) = $year";
}


if (!empty($term['tournament'])) {
  $tournament = $term['tournament'];
  $tournament = (int)$tournament;
  $query .= " AND tournaments.id = $tournament";
}

if (!empty($term['teams'])) {
  $team = $term['teams'];
  $team = (int)$team;
  $query .= " AND  tournament_players.team_id = $team";
}

$query .= " GROUP BY tournament_players.player_id, tournament_players.team_id) AS combined
";

$query .= " GROUP BY bowler_id, team_id";

$result = DB::select($query) ;
// dd($result) ;
$getresult = $result;

////////////////////////////////////////////////////////////////////
    // dd($term)
    return view('fieldingRecords', compact('fours', 'balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_count_player', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years'));
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

    return view('playerRanking', compact('fours', 'balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_count_player', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years'));
  }

  public function playerRanking_submit(Request $request)
  {
    // $teamid = Team::where('id', '=', $team_id)->select('id')->get();
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

      $getresult=[];
      $term = $request->input();
      // dd($term);
        $match_counts_query =  "
  SELECT playerId AS player_id, COUNT(DISTINCT fixture_id) AS total_matches
  FROM `fixture_scores`
  JOIN fixtures ON fixture_scores.fixture_id = fixtures.id
  WHERE
  fixtures.isActive  = 1
  ";
  if (!empty($term['year'])) {
              $year = $term['year'];
              $match_counts_query .= " AND YEAR(fixtures.created_at)= $year";
          }
          
          if (!empty($term['tournament'])) {
            $tournament = $term['tournament'];
            $tournament = (int)$tournament;
            $match_counts_query .= " AND fixtures.tournament_id = $tournament";
          }
          
          if (!empty($term['teams'])) {
            $team = $term['teams'];
            $team = (int)$team;
            $match_counts_query .= " AND (fixtures.team_id_a = $team or fixtures.team_id_b = $team)";
          }


  $match_counts_query .= "
  GROUP BY playerId
  UNION ALL
  SELECT bowlerId AS player_id, COUNT(DISTINCT fixture_id) AS total_matches
  FROM `fixture_scores`
  JOIN fixtures ON fixture_scores.fixture_id = fixtures.id 
  WHERE
  fixtures.isActive  = 1
  ";
          if (!empty($term['year'])) {
              $year = $term['year'];
              $match_counts_query .= " AND YEAR(fixtures.created_at)= $year";
          }
          
          if (!empty($term['tournament'])) {
            $tournament = $term['tournament'];
            $tournament = (int)$tournament;
            $match_counts_query .= " AND fixtures.tournament_id = $tournament";
          }
          
          if (!empty($term['teams'])) {
            $team = $term['teams'];
            $team = (int)$team;
            $match_counts_query .= " AND (fixtures.team_id_a = $team or fixtures.team_id_b = $team)";
          }
  $match_counts_query .= "
      GROUP BY bowlerId "; 

//  dd($match_counts_query);

 $match_counts =  DB::select($match_counts_query);


// dd($match_counts);

      /////////////////////////

      $query = "
      SELECT 
          tournament_id, 
          fixture_id, 
          player_id, 
          team_id, 
          COALESCE(SUM(CASE WHEN player_type = 'batsmen' THEN points END), '') AS 'Batting',
          COALESCE(SUM(CASE WHEN player_type = 'Bowler' THEN points END), '') AS 'Bowling'
      FROM 
          players_contain_points
      WHERE 
          player_type IN ('batsmen', 'Bowler') ";
          if (!empty($term['year'])) {
            $year = $term['year'];
            $query .= " AND YEAR(created_at) = $year";
        }
        
        if (!empty($term['tournament'])) {
          $tournament = $term['tournament'];
          $tournament = (int)$tournament;
          $query .= " AND tournament_id = $tournament";
        }
        
        if (!empty($term['teams'])) {
          $team = $term['teams'];
          $team = (int)$team;
          $query .= " AND team_id = $team";
        }
      $query .= " GROUP BY 
          tournament_id, 
          fixture_id, 
          player_id, 
          team_id  ";

          // dd($query);
          $results = DB::select($query);
          $getresult = $results;

          // dd($getresult);


          /////////////////////////////////////////////////////////


          $man_of_matchs_query =" SELECT fixtures.id, fixtures.tournament_id, fixtures.manofmatch_player_id , COUNT(DISTINCT fixtures.manofmatch_player_id) as MOM
          FROM fixtures
          JOIN players_contain_points ON fixtures.manofmatch_player_id = players_contain_points.player_id
          WHERE
          fixtures.isActive  = 1
          ";
          
          if (!empty($term['year'])) {
                      $year = $term['year'];
                      $man_of_matchs_query .= " AND YEAR(fixtures.created_at)= $year";
                  }
                  
                  if (!empty($term['tournament'])) {
                    $tournament = $term['tournament'];
                    $tournament = (int)$tournament;
                    $man_of_matchs_query .= " AND fixtures.tournament_id = $tournament";
                  }
                  
                  if (!empty($term['teams'])) {
                    $team = $term['teams'];
                    $team = (int)$team;
                    $man_of_matchs_query .= " AND players_contain_points.team_id = $team";
                  }
          
          
          $man_of_matchs_query .= "
          GROUP BY fixtures.id, fixtures.tournament_id, fixtures.manofmatch_player_id ";
          


// dd($man_of_matchs_query);

$man_of_matchs =  DB::select($man_of_matchs_query);

    // dd($totalMatchesArray);

return view('playerRanking', compact('fours','balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_counts', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years' , 'man_of_matchs'));
  }

  public function show_point_table(int $tournament_id)
  {

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


    $get_point_table_data = TournamentGroup::query()
      ->selectRaw("team_name.name as teams_name")
      ->selectRaw("team_name.id as teams_id")
      ->Join('teams as team_name', 'team_name.id', '=', 'tournament_groups.team_id');


    $match_count_team_a = Fixture::query()
      ->where('running_inning', 3)
      ->selectRaw("COUNT(id)")
      ->selectRaw("team_id_a")
      ->groupby('team_id_a')
      ->get()->pluck('COUNT(id)', 'team_id_a');

    $match_count_team_b = Fixture::query()
      ->where('running_inning', 3)
      ->selectRaw("COUNT(id)")
      ->selectRaw("team_id_b")
      ->groupby('team_id_b')
      ->get()->pluck('COUNT(id)', 'team_id_b');

    $match_count_winning_team = Fixture::query()
      ->selectRaw("COUNT(id)")
      ->selectRaw("winning_team_id")
      ->groupby('winning_team_id')
      ->get()->pluck('COUNT(id)', 'winning_team_id');

    $match_count_loss_team = Fixture::query()
      ->selectRaw("COUNT(id)")
      ->selectRaw("lossing_team_id")
      ->groupby('lossing_team_id')
      ->get()->pluck('COUNT(id)', 'lossing_team_id');

    $match_count_tie_team = Fixture::query()
      ->where('is_tie_match', 1)
      ->selectRaw('team_id_a, team_id_b, COUNT(is_tie_match) as tie')
      ->groupBy('team_id_a', 'team_id_b')
      ->pluck('tie', 'team_id_a', 'team_id_b');

    $bonusPointsSum_team_A = Fixture::query()
      ->selectRaw('SUM(teamAbonusPoints) as totalBonusPoints')
      ->selectRaw('team_id_a')
      ->groupBy('team_id_a')
      ->pluck('totalBonusPoints', 'team_id_a');

    $bonusPointsSum_team_B = Fixture::query()
      ->selectRaw('SUM(teamBbonusPoints) as totalBonusPoints')
      ->selectRaw('team_id_b')
      ->groupBy('team_id_b')
      ->pluck('totalBonusPoints', 'team_id_b');

    $teamscorerunsteamA = Fixture::query()
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->where('fixture_scores.inningnumber', 1)
      ->selectRaw('SUM(fixture_scores.runs) as total_runs')
      ->selectRaw('team_id_a')
      ->groupBy('team_id_a')
      ->pluck('total_runs', 'team_id_a');

    $teamscorerunsteamB = Fixture::query()
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->where('fixture_scores.inningnumber', 2)
      ->selectRaw('SUM(fixture_scores.runs) as total_runs')
      ->selectRaw('team_id_b')
      ->groupBy('team_id_b')
      ->pluck('total_runs', 'team_id_b');

    $teamscoreoverfacedteamA = Fixture::query()
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->where('fixture_scores.inningnumber', 1)
      ->selectRaw('MAX(fixture_scores.overnumber) as max_over')
      ->selectRaw('team_id_a')
      ->groupBy('team_id_a')
      ->pluck('max_over', 'team_id_a');

    $teamscoreoverfacedteamB = Fixture::query()
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->where('fixture_scores.inningnumber', 2)
      ->selectRaw('MAX(fixture_scores.overnumber) as max_over')
      ->selectRaw('team_id_b')
      ->groupBy('team_id_b')
      ->pluck('max_over', 'team_id_b');

    $team_runs_concededteamA = Fixture::query()
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->where('fixture_scores.inningnumber', 1)
      ->selectRaw('SUM(fixture_scores.runs) as total_runs')
      ->selectRaw('team_id_a')
      ->groupBy('team_id_a')
      ->pluck('total_runs', 'team_id_a');

    $team_runs_concededteamB = Fixture::query()
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->where('fixture_scores.inningnumber', 1)
      ->selectRaw('SUM(fixture_scores.runs) as total_runs')
      ->selectRaw('team_id_b')
      ->groupBy('team_id_b')
      ->pluck('total_runs', 'team_id_b');

    $team_balls_bowledteamA = Fixture::query()
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->where('fixture_scores.inningnumber', 2)
      ->selectRaw('MAX(fixture_scores.overnumber) as max_over')
      ->selectRaw('team_id_a')
      ->groupBy('team_id_a')
      ->pluck('max_over', 'team_id_a');

    $team_balls_bowledteamB = Fixture::query()
      ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
      ->where('fixture_scores.inningnumber', 2)
      ->selectRaw('MAX(fixture_scores.overnumber) as max_over')
      ->selectRaw('team_id_b')
      ->groupBy('team_id_b')
      ->pluck('max_over', 'team_id_b');




      $get_point_table_data->where('tournament_groups.tournament_id', '=', $tournament_id);
    

    $getdata = $get_point_table_data->get()->pluck('teams_name', 'teams_id');
    $result = array();
    foreach ($getdata as $team_id => $team_name) {
      $team_wins = isset($match_count_winning_team[$team_id]) ? $match_count_winning_team[$team_id] : 0;
      $team_losses = isset($match_count_loss_team[$team_id]) ? $match_count_loss_team[$team_id] : 0;
      $team_total_matches = isset($match_count_team_a[$team_id]) ? $match_count_team_a[$team_id] : 0;

      if (isset($match_count_team_b[$team_id])) {
        $team_total_matches += $match_count_team_b[$team_id];
      }

      $team_players_count = isset($team_players[$team_id]) ? $team_players[$team_id] : 0;
      $bonus_points_A = isset($bonusPointsSum_team_A[$team_id]) ? $bonusPointsSum_team_A[$team_id] : 0;
      $bonus_points_B = isset($bonusPointsSum_team_B[$team_id]) ? $bonusPointsSum_team_B[$team_id] : 0;
      $team_tie = isset($match_count_tie_team[$team_id]) ? $match_count_tie_team[$team_id] : 0;
      $total_bonus_points = $bonus_points_A + $bonus_points_B;

      $team_runs_scoredA = isset($teamscorerunsteamA[$team_id]) ? $teamscorerunsteamA[$team_id] : 0;
      $team_runs_scoredB = isset($teamscorerunsteamB[$team_id]) ? $teamscorerunsteamB[$team_id] : 0;
      $team_runs_scored = $team_runs_scoredA + $team_runs_scoredB;


      $team_balls_facedA = isset($teamscoreoverfacedteamA[$team_id]) ? ($teamscoreoverfacedteamA[$team_id]) : 0;
      $team_balls_facedB = isset($teamscoreoverfacedteamB[$team_id]) ? ($teamscoreoverfacedteamB[$team_id]) : 0;
      $team_ball_face = ($team_balls_facedA + $team_balls_facedB) * 6;


      $team_runs_concededA = isset($teamscoreoverfacedteamA[$team_id]) ? $teamscoreoverfacedteamA[$team_id] : 0;
      $team_runs_concededB = isset($team_runs_concededteamB[$team_id]) ? $team_runs_concededteamB[$team_id] : 0;
      $team_runs_conceded = $team_runs_concededA + $team_runs_concededB;


      $team_balls_bowledA = isset($team_balls_bowledteamA[$team_id]) ? $team_balls_bowledteamA[$team_id] : 0;
      $team_balls_bowledB = isset($team_balls_bowledteamB[$team_id]) ? $team_balls_bowledteamB[$team_id] : 0;
      $team_balls_bowled = ($team_balls_bowledA + $team_balls_bowledB) * 6;

      if ($team_ball_face != 0 && $team_balls_bowled != 0) {
        $net_run_rate = ($team_runs_scored / $team_ball_face) - ($team_runs_conceded / $team_balls_bowled);
      } else {
        $net_run_rate = 0.00;
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
        'net_rr' => $net_run_rate,
      ];
    }


    return view('pointtable', compact('result', 'tournamentdata', 'years', 'match_results', 'teams', "tournament_name", "grounds", "umpire_matchoffcial"));


 ///////////////////////////////////////////////////////////////
  }

  public function show_batting_records(int $tournament_id)
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
    ->where('is_web_display' , '=' , 1)
    ->get()
    ->pluck('name', 'id');

   
   

  $image_gallery = GalleryImages::query()
    ->where('isActive', 1)
    ->get();

  $data = Fixture::query()
    ->selectRaw('fixtures.id')
    ->selectRaw('fixtures.tournament_id')
    ->selectRaw('team_players.player_id')
    ->selectRaw('team_players.team_id')
    ->leftjoin('team_players', 'team_players.team_id', '=', 'fixtures.team_id_a')
    ->distinct('fixtures.id');


  
    $data->where('fixtures.tournament_id', '=', $tournament_id);
  



  $getresult = $data->get();
  // dd($getresult);
  $match_count_player = collect();
  $player_runs = collect();
  $balls_faced = collect();
  $sixes = collect();
  $fours = [];
  $hundreds = [];
  $fifty = [];
  $higest_score = [];



  foreach ($getresult as $teamPlayer) {
    $match_count = FixtureScore::where('playerId', $teamPlayer->player_id)
      ->selectRaw("COUNT(fixture_id)")
      ->selectRaw("fixture_id")
      ->groupBy('fixture_id')
      ->pluck('COUNT(fixture_id)', 'fixture_id')
      ->first();

    $match_count_player[$teamPlayer->player_id] = $match_count;

    $player_runs[$teamPlayer->player_id] = FixtureScore::where('playerId', $teamPlayer->player_id)
      ->orderbydesc('SUM(runs)')
      ->sum('runs');

    $balls_faced[$teamPlayer->player_id] = FixtureScore::where('playerId', $teamPlayer->player_id)
      ->where(function ($query) {
        $query->where('balltype', '!=', 'w')
          ->orWhereNull('balltype');
      })
      ->count();

    $sixes[$teamPlayer->player_id] = FixtureScore::where('playerId', $teamPlayer->player_id)
      ->where('issix', 1)
      ->count();

    $fours[$teamPlayer->player_id]['fours'] = FixtureScore::where('fixture_id', $teamPlayer->id)
      ->where('playerId', $teamPlayer->player_id)
      ->where('isfour', 1)
      ->count();

    $hundreds[$teamPlayer->player_id] = FixtureScore::where('playerid', $teamPlayer->player_id)
      ->where('fixture_scores.balltype', '=', 'R')
      ->select('playerid', DB::raw('COUNT(*) as hundreds_count'))
      ->where('fixture_scores.runs', '>=', 100)
      ->groupBy('playerid')
      ->count();

    $fifty[$teamPlayer->player_id] = FixtureScore::where('playerid', $teamPlayer->player_id)
      ->where('fixture_scores.balltype', '=', 'R')
      ->select('playerid', DB::raw('COUNT(*) as fifties'))
      ->where('runs', '>=', 50)
      ->where('runs', '<', 100)
      ->groupBy('playerid')
      ->count();

    $higest_score_query = FixtureScore::where('playerId', $teamPlayer->player_id)
      ->selectRaw('SUM(runs) as total_runs, fixture_id')
      ->groupBy('fixture_id')
      ->orderByDesc('total_runs')
      ->limit(1);

    $higest_score[$teamPlayer->player_id] = $higest_score_query->value('total_runs');
  }


  return view('batting_states', compact('fours', 'higest_score', 'fifty', 'hundreds', 'balls_faced', 'sixes', 'tournamentdata', 'balls_faced', 'player_runs', 'match_count_player', 'player', 'teams', 'match_results', 'image_gallery', 'years', 'getresult'));

    ////////////////////////////////////////
  }


  public function show_bowling_records(int $tournament_id)
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
    ->where('is_web_display' , '=' , 1)
    ->get()
    ->pluck('name', 'id');


  $image_gallery = GalleryImages::query()
    ->where('isActive', 1)
    ->get();

  $data = TournamentPlayer::query()
    ->selectRaw('fixture_scores.bowlerId as bowler_id')
    ->selectRaw('team_players.player_id')
    ->selectRaw('team_players.team_id')
    ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches')
    ->selectRaw('COUNT(DISTINCT fixture_scores.overnumber) as total_overs')
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
    ->groupBy('fixture_scores.bowlerId');


  
    $data->where('tournament_players.tournament_id', '=', $tournament_id);
  

  if (!empty($term['teams'])) {
    $team = $term['teams'];
    $data->where('tournament_players.team_id', '=', $team);
  }

  $getresult = $data->orderbydesc('total_wickets')->get();


  $hatricks = 0;
  foreach ($getresult as $teamPlayer) {
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
      ->where('fs1.bowlerId', $teamPlayer->bowler_id)
      ->where('fs1.isout', '=', 1)
      ->whereNull('fs4.id')
      ->select(DB::raw('COUNT(*) as total_hat_tricks'))
      ->pluck('total_hat_tricks')
      ->toArray();
    $hatricks = $total_hat_tricks;
  }
  // dd($hatricks);

  return view('bowling_state', compact('tournamentdata', 'player', 'teams', 'match_results', 'image_gallery', 'years', 'getresult'));
    

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


    $match_dissmissal_caught = Dismissal::where('dismissals.name', '=', 'Caught')
      ->first();


      $match_dissmissal_stumped = Dismissal::where('dismissals.name', '=', 'Stumped')
      ->first();

      $dismissalIdcatch = $match_dissmissal_caught->id;
      $dismissalIdstump = $match_dissmissal_stumped->id ;

    $getresult = [];
      // dd($tournaments) ;name
      $results = DB::select("SELECT 
      fixture_scores.bowlerId AS bowler_id,
      tournament_players.team_id AS team_id,
      COUNT(DISTINCT fixture_scores.fixture_id) AS total_matches,
      COUNT(DISTINCT match_dismissals.fixturescores_id) AS catch,
      0 AS stump
  FROM 
      tournaments 
      JOIN fixtures ON fixtures.tournament_id = $tournament_id 
      JOIN fixture_scores ON fixture_scores.fixture_id = fixtures.id
      JOIN match_dismissals ON match_dismissals.outbyplayer_id = fixture_scores.bowlerId
      JOIN tournament_players ON tournament_players.player_id = fixture_scores.bowlerId
  WHERE 
      tournaments.id = $tournament_id 
      AND fixture_scores.isout = 1
      AND match_dismissals.dismissal_id = $dismissalIdcatch
  GROUP BY 
      fixture_scores.bowlerId,
      tournament_players.team_id
  
  UNION
  
  SELECT 
      fixture_scores.bowlerId AS bowler_id,
      tournament_players.team_id AS team_id,
      COUNT(DISTINCT fixture_scores.fixture_id) AS total_matches,
      0 AS catch,
      COUNT(DISTINCT match_dismissals.fixturescores_id) AS stump
  FROM 
      tournaments 
      JOIN fixtures ON fixtures.tournament_id = tournaments.id
      JOIN fixture_scores ON fixture_scores.fixture_id = fixtures.id
      JOIN match_dismissals ON match_dismissals.outbyplayer_id = fixture_scores.bowlerId
      JOIN tournament_players ON tournament_players.player_id = fixture_scores.bowlerId
  WHERE 
  tournaments.id = $tournament_id 
      AND fixture_scores.isout = 1
      AND match_dismissals.dismissal_id = $dismissalIdstump
  GROUP BY 
      fixture_scores.bowlerId,
      tournament_players.team_id;");
      
          $getresult = $results;

  
    return view('fieldingRecords', compact('fours', 'balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_count_player', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years'));

    ////////////////////////////////////////////////
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

      $getresult=[];
      // $term = $request->input();
      // dd($term);
      $match_counts_query =  "
SELECT playerId AS player_id, COUNT(DISTINCT fixture_id) AS total_matches
FROM `fixture_scores`
JOIN fixtures ON fixture_scores.fixture_id = fixtures.id
WHERE
fixtures.isActive  = 1
";
          $match_counts_query .= " AND fixtures.tournament_id = $tournament_id";

$match_counts_query .= "
GROUP BY playerId
UNION ALL
SELECT bowlerId AS player_id, COUNT(DISTINCT fixture_id) AS total_matches
FROM `fixture_scores`
JOIN fixtures ON fixture_scores.fixture_id = fixtures.id 
WHERE
fixtures.isActive  = 1
";
          $match_counts_query .= " AND fixtures.tournament_id = $tournament_id";
$match_counts_query .= "
    GROUP BY bowlerId "; 

//  dd($match_counts_query);

 $match_counts =  DB::select($match_counts_query);


// dd($match_counts);

      /////////////////////////

      $query = "
      SELECT 
          tournament_id, 
          fixture_id, 
          player_id, 
          team_id, 
          COALESCE(SUM(CASE WHEN player_type = 'batsmen' THEN points END), '') AS 'Batting',
          COALESCE(SUM(CASE WHEN player_type = 'Bowler' THEN points END), '') AS 'Bowling'
      FROM 
          players_contain_points
      WHERE 
          player_type IN ('batsmen', 'Bowler') ";
          $query .= " AND tournament_id = $tournament_id";
      $query .= " GROUP BY 
          tournament_id, 
          fixture_id, 
          player_id, 
          team_id  ";

          // dd($query);
          $results = DB::select($query);
          $getresult = $results;

          // dd($getresult);


          /////////////////////////////////////////////////////////


          $man_of_matchs_query =" SELECT fixtures.id, fixtures.tournament_id, fixtures.manofmatch_player_id , COUNT(DISTINCT fixtures.manofmatch_player_id) as MOM
          FROM fixtures
          JOIN players_contain_points ON fixtures.manofmatch_player_id = players_contain_points.player_id
          WHERE
          fixtures.isActive  = 1
          ";
                    $man_of_matchs_query .= " AND fixtures.tournament_id = $tournament_id";
          
          
          $man_of_matchs_query .= "
          GROUP BY fixtures.id, fixtures.tournament_id, fixtures.manofmatch_player_id ";
          


// dd($man_of_matchs_query);

$man_of_matchs =  DB::select($man_of_matchs_query);
$teamid = Team::where('id', '=', $team_id)->select('id')->get();

    return view('playerRanking', compact('fours', 'teamid','balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_counts', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years' , 'man_of_matchs'));
 


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
  $playermatch=TournamentPlayer::query()
  ->selectRaw('tournament_players.tournament_id')
  ->selectRaw('tournament_players.team_id')
  ->selectRaw('tournament_players.player_id')
  ->selectRaw('teams.clubname')
  ->join('teams','teams.id','tournament_players.team_id')
  ->get();
  $result=$playermatch;
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
        ->unionAll(
            DB::table('fixtures')
                ->select('team_id_b AS team_id')
                ->whereIn('team_id_b', $teamIds)
                ->where('tournament_id', $tournamentid)
        );
}, 'subquery')
    ->select('team_id', DB::raw('COUNT(*) AS count'))
    ->groupBy('team_id')
    ->get()->pluck('count','team_id');

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
        ->unionAll(
            DB::table('fixtures')
                ->select('team_id_b AS team_id')
                ->whereIn('team_id_b', $teamIds)
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
  // $match_results = Fixture::where('id', '=', $team_id)->where('isActive', 1)->where('isActive', 1)->orderBy('id')->get();
  // // $teams = Team::pluck('name', 'id');
  $tournament = Tournament::pluck('name', 'id');
  
  // $image_gallery = GalleryImages::query()
  //   ->where('isActive', '=', 1)
  //   ->get();
  // $teams = Team::query()->get()->pluck(
  //   'name',
  //   'id'
  // );


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

    $getresult=[];
    // $term = $request->input();
    // dd($term);
      $match_counts_query =  "
SELECT playerId AS player_id, COUNT(DISTINCT fixture_id) AS total_matches
FROM `fixture_scores`
JOIN fixtures ON fixture_scores.fixture_id = fixtures.id
WHERE
fixtures.isActive  = 1
";
// if (!empty($term['year'])) {
//             $year = $term['year'];
//             $match_counts_query .= " AND YEAR(fixtures.created_at)= $year";
//         }
        
        // if (!empty($term['tournament'])) {
        //   $tournament = $term['tournament'];
        //   $tournament = (int)$tournament;
          $match_counts_query .= " AND fixtures.tournament_id = $tournament_id";
        // }
        
        // if (!empty($term['teams'])) {
        //   $team = $term['teams'];
        //   $team = (int)$team;
          $match_counts_query .= " AND (fixtures.team_id_a = $team_id or fixtures.team_id_b = $team_id)";
        // }


$match_counts_query .= "
GROUP BY playerId
UNION ALL
SELECT bowlerId AS player_id, COUNT(DISTINCT fixture_id) AS total_matches
FROM `fixture_scores`
JOIN fixtures ON fixture_scores.fixture_id = fixtures.id 
WHERE
fixtures.isActive  = 1
";
        // if (!empty($term['year'])) {
        //     $year = $term['year'];
        //     $match_counts_query .= " AND YEAR(fixtures.created_at)= $year";
        // }
        
        // if (!empty($term['tournament'])) {
        //   $tournament = $term['tournament'];
        //   $tournament = (int)$tournament;
          $match_counts_query .= " AND fixtures.tournament_id = $tournament_id";
        // }
        
        // if (!empty($term['teams'])) {
        //   $team = $term['teams'];
        //   $team = (int)$team;
          $match_counts_query .= " AND (fixtures.team_id_a = $team_id or fixtures.team_id_b = $team_id)";
        // }
$match_counts_query .= "
    GROUP BY bowlerId "; 

//  dd($match_counts_query);

$match_counts =  DB::select($match_counts_query);


// dd($match_counts);

    /////////////////////////

    $query = "
    SELECT 
        tournament_id, 
        fixture_id, 
        player_id, 
        team_id, 
        COALESCE(SUM(CASE WHEN player_type = 'batsmen' THEN points END), '') AS 'Batting',
        COALESCE(SUM(CASE WHEN player_type = 'Bowler' THEN points END), '') AS 'Bowling'
    FROM 
        players_contain_points
    WHERE 
        player_type IN ('batsmen', 'Bowler') ";
      //   if (!empty($term['year'])) {
      //     $year = $term['year'];
      //     $query .= " AND YEAR(created_at) = $year";
      // }
      
      // if (!empty($term['tournament'])) {
      //   $tournament = $term['tournament'];
      //   $tournament = (int)$tournament;
        $query .= " AND tournament_id = $tournament_id";
      // }
      
      // if (!empty($term['teams'])) {
      //   $team = $term['teams'];
      //   $team = (int)$team;
        $query .= " AND team_id = $team_id";
      // }
    $query .= " GROUP BY 
        tournament_id, 
        fixture_id, 
        player_id, 
        team_id  ";

        // dd($query);
        $results = DB::select($query);
        $getresult = $results;

        // dd($getresult);


        /////////////////////////////////////////////////////////


        $man_of_matchs_query =" SELECT fixtures.id, fixtures.tournament_id, fixtures.manofmatch_player_id , COUNT(DISTINCT fixtures.manofmatch_player_id) as MOM
        FROM fixtures
        JOIN players_contain_points ON fixtures.manofmatch_player_id = players_contain_points.player_id
        WHERE
        fixtures.isActive  = 1
        ";
        
        // if (!empty($term['year'])) {
        //             $year = $term['year'];
        //             $man_of_matchs_query .= " AND YEAR(fixtures.created_at)= $year";
        //         }
                
                // if (!empty($term['tournament'])) {
                //   $tournament = $term['tournament'];
                //   $tournament = (int)$tournament;
                  $man_of_matchs_query .= " AND fixtures.tournament_id = $tournament_id";
                // }
                
                // if (!empty($term['teams'])) {
                //   $team = $term['teams'];
                //   $team = (int)$team;
                  $man_of_matchs_query .= " AND players_contain_points.team_id = $team_id";
                // }
        
        
        $man_of_matchs_query .= "
        GROUP BY fixtures.id, fixtures.tournament_id, fixtures.manofmatch_player_id ";
        


// dd($man_of_matchs_query);

$man_of_matchs =  DB::select($man_of_matchs_query);

  // dd($totalMatchesArray);
  return view('team_ranking', compact('fours', 'teamid','balls_faced', 'sixes', 'balls_faced', 'player_runs', 'match_counts', 'player', 'getresult', 'teams', 'tournamentdata', 'match_results',  'image_gallery', 'years' , 'man_of_matchs',  'teamData' , 'tournament' , 'tournamentData' , 'team_resultData' , 'teamPlayerCount', 'team_id_data' , 'tournament_ids'));

  ////////////////////////////
  
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
    $data = Fixture::where('running_inning', '=', 0);


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
    $data = Fixture::where('running_inning', '=', 0);


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