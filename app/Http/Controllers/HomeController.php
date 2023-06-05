<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;


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
    ->where('isActive',1)
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
    ->where('isActive',1)
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
    $match_results = Fixture::query();
    $match_results->where('running_inning', '=', 3);
    $match_results = $match_results ->where('isActive',1)->orderBy('id')->get();
    $today = Carbon::now()->toDateString();
    $upcoming_match = Fixture::query()->where('match_startdate', '>=', $today)
      ->where('isActive',1)
      ->where('running_inning', '=', 0)
      ->orderBy('id')
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
    $match_results = $match_results ->where('isActive',1)->orderBy('id')->get();

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


    $teams_two_player = TeamPlayer::query()->get()->where('team_id', '=', $match_results[0]->second_inning_team_id)->pluck(
      'player_id',
      'id'
    );


    $teams_one_player = TeamPlayer::query()->get()->where('team_id', '=', $match_results[0]->first_inning_team_id)->pluck(
      'player_id',
      'id'
    );


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

    $overs = floor($match_total_overs);
    $balls = ($match_total_overs - $overs) * 10;
    $total_balls = ($overs * 6) + $balls;
    $match_total_overs = $total_balls / 6;
    $match_total_overs = round($match_total_overs, 2);


    $innings = FixtureScore::where('fixture_id', '=', $id)
      ->selectRaw('inningnumber, max(overnumber) as max_over')
      ->groupBy('inningnumber')
      ->get();

    $total_overs = array();

    foreach ($innings as $inning) {
      $overs = $inning->max_over;
      $balls = ($overs - floor($overs)) * 10;
      $total_balls = ($overs * 6) + $balls;
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

    $team_one_runs = FixtureScore::where('fixture_id', '=', $id)
      ->where('inningnumber', '=', 1)
      ->sum('runs');

    $team_one_overs = $total_overs[1];

    $team_one_run_rate = ($team_one_runs / $team_one_overs);

    $team_two_runs = FixtureScore::where('fixture_id', '=', $id)
      ->where('inningnumber', '=', 2)
      ->sum('runs');

    $team_two_overs = $total_overs[2];

    $team_two_run_rate = ($team_two_runs / $team_two_overs);

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();



    return view('ballbyballscorecard', compact('team_one_run_rate', 'team_two_run_rate', 'teams_one', 'match_total_overs', 'match_data', 'teams_two', 'match_detail', 'match_results', 'teams', 'player', 'total_run', 'total_wickets', 'total_overs', 'tournament', 'teams_two_player', 'teams_one_player', 'image_gallery'));
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
    $match_results = $match_results ->where('isActive',1)->orderBy('id')->get();
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


    return view('fullScorecard_chart', compact('match_results','teams_one','teams_two', 'sum_inning_one', 'sum_inning_two','id', 'over'));
  }


  public function fullScorecard_overbyover(int $id)
  {
    $match_results = Fixture::query();
    $match_results->where('id', '=', $id);
    $match_results = $match_results ->where('isActive',1)->orderBy('id')->get();
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


    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();




    return view('score_card', compact('player_runs', 'teams_one', 'teams_two', 'player_balls', 'match_results', 'teams', 'player', 'tournament', 'ground', 'match_data', 'image_gallery'));
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
    $match_results ->where('isActive',1)->where('running_inning', '=', 3);
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



    $match_results = $match_results->where('isActive',1)->orderBy('id')->get();
    $result = [];

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();
      return view('search_player', compact('result', 'match_results', 'teams', 'image_gallery', 'clubs', 'teams_only'));
    }
  


public function team_bowling(int $team_id,int $tournament_id){
  $team_id_data=$team_id;
  $tournament_ids=$tournament_id;
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
  $match_results = Fixture::where('id', '=', $team_id)->where('isActive',1)->orderBy('id')->get();
  // $teams = Team::pluck('name', 'id');
  $tournament = Tournament::pluck('name', 'id');



  $team_bowlingdata= TournamentPlayer::where('tournament_players.team_id', $team_id)
  ->where('tournament_players.tournament_id', '=', $tournament_id)
  ->selectRaw('fixture_scores.bowlerId as bowler_id')
  ->selectRaw('team_players.player_id')
  ->selectRaw('team_players.team_id')
  ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches') 
  ->selectRaw('COUNT(DISTINCT fixture_scores.overnumber) as total_overs') 
  ->selectRaw('SUM(fixture_scores.balltype = "WD") as total_wides')
  ->selectRaw('SUM(fixture_scores.balltype = "NB") as total_noball')
  ->selectRaw('SUM(fixture_scores.runs) as total_runs') 
  ->selectRaw('SUM(fixture_scores.isout) as total_wickets') 
  ->join('team_players', function ($join) {
    $join->on('team_players.team_id', '=', 'tournament_players.team_id')
    ->on('team_players.player_id', '=', 'tournament_players.player_id');
})
  ->join('fixture_scores', 'fixture_scores.bowlerId', '=', 'team_players.player_id')
  ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
  ->groupBy('team_players.player_id', 'team_players.team_id', 'fixture_scores.bowlerId')
  ->get();

  $image_gallery =GalleryImages::query()
  ->where('isActive','=',1)
  ->get();
  $teams = Team::query()->get()->pluck(
    'name',
    'id'
  );

  return view('team_bowling', compact('teams','tournamentData', 'tournament_ids','player','teamPlayerCount','team_resultData','teamPlayers','teamData','match_results','tournament','team_id_data','team_bowlingdata' , 'image_gallery' ));
}
    
public function team_fielding(int $team_id,int $tournament_id){
  $team_id_data=$team_id;
  $tournament_ids=$tournament_id;
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
  $match_results = Fixture::where('id', '=', $team_id) ->where('isActive',1)->where('isActive',1)->orderBy('id')->get();
  // $teams = Team::pluck('name', 'id');
  $tournament = Tournament::pluck('name', 'id');
  $team_bowlingdata= TournamentPlayer::where('tournament_players.team_id', $team_id)
  ->where('tournament_players.tournament_id', '=', $tournament_id)
  ->selectRaw('fixture_scores.bowlerId as bowler_id')
  ->selectRaw('team_players.player_id')
  ->selectRaw('team_players.team_id')
  ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches') 
  ->selectRaw('COUNT(DISTINCT fixture_scores.overnumber) as total_overs') 
  ->selectRaw('SUM(fixture_scores.balltype = "WD") as total_wides')
  ->selectRaw('SUM(fixture_scores.balltype = "NB") as total_noball')
  ->selectRaw('SUM(fixture_scores.runs) as total_runs') 
  ->selectRaw('SUM(fixture_scores.isout) as total_wickets') 
  ->join('team_players', function ($join) {
    $join->on('team_players.team_id', '=', 'tournament_players.team_id')
    ->on('team_players.player_id', '=', 'tournament_players.player_id');
})
  ->join('fixture_scores', 'fixture_scores.bowlerId', '=', 'team_players.player_id')
  ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
  ->groupBy('team_players.player_id', 'team_players.team_id', 'fixture_scores.bowlerId')
  ->get();


  $image_gallery =GalleryImages::query()
  ->where('isActive','=',1)
  ->get();

  $teams = Team::query()->get()->pluck(
    'name',
    'id'
  );
  return view('team_fielding', compact('teams','tournamentData', 'player','teamPlayers','teamData','match_results','tournament','team_id_data','team_bowlingdata','teamPlayerCount','team_resultData','tournament_ids' , 'image_gallery' ));
}

   

  public function searchplayer_form_submit(Request $request)
  {

    // dd($request);
    
    $match_results = Fixture::query();
    $match_results->where('isActive',1)->where('running_inning', '=', 3);
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




    $player = Player::query();

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


    $result = $player->orderBy('id')->get();

    // dd($result);

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();



    return view('search_player', compact('result', 'match_results', 'image_gallery', 'clubs', 'teams_only'));
  }

  public function result()
  {
    $match_results = Fixture::query();
    $match_results->where('running_inning', '=', 3);
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    // dd($teams);
    $match_results = $match_results->orderBy('id')->get();;
    $results = [];
    $tournament = Tournament::query()->pluck(
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



    return view('result', compact('results', 'clubs', 'match_results', 'tournament', 'teams', "image_gallery"));
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
      $data->whereRaw("YEAR(created_at) = $year");
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
    $tournament = Tournament::query()->pluck(
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
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $ground = Ground::orderBy('id')->get();
    $ground = $ground->pluck('name', 'id');
    $tournament = Tournament::pluck('name', 'id');
    $match_results = Fixture::where('id', '=', $team_id)->where('isActive',1)->orderBy('id')->get();

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


    return view('team_view', compact('team_id_data', 'tournament_ids', 'team_resultData', 'teamData', 'playerCount', 'match_results', 'player', 'ground', 'tournamentData', 'tournament', 'teamPlayerCount', 'teamPlayers', "image_gallery"));
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

    $match_results = Fixture::where('id', '=', $team_id)->where('isActive',1)->orderBy('id')->get();
    $teams = Team::pluck('name', 'id');
    $tournament = Tournament::pluck('name', 'id');
    $data = Fixture::query();
    $results = $data->get();

    $team_resultData = TournamentPlayer::where(function ($query) use ($team_id) {
      $query->where('team_id', $team_id);
    })
      ->distinct('fixture.id')
      ->where('fixture.running_inning', 3)
      ->where('tournament_players.tournament_id', '=', $tournament_id)
      ->selectRaw('fixture.created_at')
      ->selectRaw('fixture.team_id_b')
      ->selectRaw('fixture.id')
      ->selectRaw('fixture.running_inning')
      ->selectRaw("fixture.numberofover")
      ->selectRaw('fixture.match_result_description')
      ->selectRaw('tournament_players.tournament_id')
      ->selectRaw('tournament_players.team_id')
      ->selectRaw('tournament_players.player_id')
      ->selectRaw('tournament_players.domain_id')
      ->selectRaw('fixture.team_id_a')
      ->join('fixtures as fixture', 'fixture.tournament_id', '=', 'tournament_players.tournament_id')
      ->orderBy('tournament_id')
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


    return view('team_result', compact('tournamentData', 'tournament_ids', 'player', 'teamCaptain', 'teamPlayerCount', 'teamPlayers', 'team_resultData', 'team_resultData1', 'teamData', 'match_results',  'tournament', 'total_run_fixture', 'total_runs', 'total_wicket_fixture', 'team_id_data', 'image_gallery'));
  }


  public function team_schedule(int $team_id, int $tournament_id)
  {
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $tournament = Tournament::pluck('name', 'id');
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
    ->where('isActive',1)
      ->orWhere('team_id_b', $team_id)
      ->orderBy('match_startdate')
      ->get();
    // $teams_data = Team::pluck('name', 'id');



    $team_scheduleData = TournamentPlayer::where(function ($query) use ($team_id, $today, $tournament_id) {
      $query->where('tournament_players.team_id', $team_id)
        ->where('fixtures.match_startdate', '>=', $today);
    })
      ->where('tournament_players.tournament_id', '=', $tournament_id)
      ->where('fixtures.running_inning', '=', 0)
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


    return view('team_schedule', compact('teamData', 'tournament_ids', 'match_results', 'player', 'ground', 'tournamentData', 'tournament', 'teamPlayerCount', 'team_resultData', 'teamPlayers', 'team_id_data', 'team_scheduleData', "image_gallery"));
  }

  public function team_batting(int $team_id, int $tournament_id)
  {
    $team_id_data = $team_id;
    $tournament_ids = $tournament_id;
    $tournament = Tournament::pluck('name', 'id');
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
    ->where('isActive',1)
      ->orWhere('team_id_b', $team_id)
      ->orderBy('match_startdate')
      ->get();

    $team_battingdata = TournamentPlayer::where('tournament_players.team_id', $team_id)
      ->where('tournament_players.tournament_id', '=', $tournament_id)
      ->selectRaw('team_players.player_id')
      ->selectRaw('team_players.team_id')
      ->selectRaw('team_players.id')
      ->join('team_players', function ($join) {
        $join->on('team_players.team_id', '=', 'tournament_players.team_id')
          ->on('team_players.player_id', '=', 'tournament_players.player_id');
      })
      ->get();

    $match_count_player = collect();
    $player_runs = collect();

    foreach ($team_battingdata as $teamPlayer) {
      $match_count = FixtureScore::where('fixture_id', $teamPlayer->id)
        ->selectRaw("COUNT(playerId)")
        ->selectRaw("fixture_id")
        ->groupby('fixture_id')
        ->pluck('COUNT(playerId)', 'fixture_id')
        ->first();

      $match_count_player[$teamPlayer->id] = $match_count;

      $player_runs[$teamPlayer->id] = FixtureScore::where('fixture_id', $teamPlayer->id)
        ->where('playerId', $teamPlayer->player_id)
        ->sum('runs');
    }

    $balls_faced = collect();

    foreach ($team_battingdata as $teamPlayer) {
      $balls_faced[$teamPlayer->id] = FixtureScore::where('fixture_id', $teamPlayer->id)
        ->where('playerId', $teamPlayer->player_id)
        ->where(function ($query) {
          $query->where('balltype', '!=', 'w')
            ->orWhere('balltype', null);
        })
        ->count();
    }

    $sixes = collect();

    foreach ($team_battingdata as $teamPlayer) {
      $sixes[$teamPlayer->id] = FixtureScore::where('fixture_id', $teamPlayer->id)
        ->where('playerId', $teamPlayer->player_id)
        ->where('runs', 6)
        ->count();
    }

    $fours = [];

    foreach ($team_battingdata as $teamPlayer) {
      $fours[$teamPlayer->id]['fours'] = FixtureScore::where('fixture_id', $teamPlayer->id)
        ->where('playerId', $teamPlayer->player_id)
        ->where('runs', 4)
        ->count();

      $fours[$teamPlayer->id]['high_score'] = DB::select("select max(td.score) as score from (select SUM(runs) as score FROM fixture_scores WHERE playerId=$teamPlayer->player_id group by fixture_id) td")[0]->score;
    }

    $image_gallery = GalleryImages::query()
      ->where('isActive', '=', 1)
      ->get();



    return view('team_batting', compact('teamData', 'tournament_ids', 'match_results',  'player', 'ground', 'tournamentData', 'tournament', 'teamPlayerCount', 'team_resultData', 'teamPlayers', 'team_id_data', 'team_battingdata', 'match_count_player', 'player_runs', 'balls_faced', 'fours', 'sixes', "image_gallery"));
  }

  public function batting_states()
  {

    $tournamentdata = Tournament::query()->pluck(
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
        $getresult=[];
        $match_count_player = collect();
        $player_runs = collect();
        $balls_faced = collect();
        $sixes = collect();
        $fours = [];

    return view('batting_states', compact('fours','balls_faced','sixes','balls_faced','player_runs','match_count_player','player','getresult','teams','tournamentdata', 'match_results',  'image_gallery','years'));
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
          ->pluck('name', 'id');
  
      $image_gallery = GalleryImages::query()
          ->where('isActive', 1)
          ->get();
  
      $data = TournamentPlayer::query()
          ->selectRaw('tournament_players.player_id')
          ->selectRaw('tournament_players.team_id')
          ->selectRaw('team_players.id')
          ->join('team_players', function ($join) {
              $join->on('team_players.team_id', '=', 'tournament_players.team_id')
                  ->on('team_players.player_id', '=', 'tournament_players.player_id');
          })
          ->distinct('tournament_players.player_id');
  
      $term = $request->input();
      
      if (!empty($term['year'])) {
          $year = $term['year'];
          $data->whereRaw("YEAR(tournament_players.created_at) = $year");
      }
      if (!empty($term['tournament'])) {
          $tournament = $term['tournament'];
          $data->where('tournament_players.tournament_id', '=', $tournament);
      }
  
      // if (!empty($term['teams'])) {
      //     $team = $term['teams'];
      //     $data->where('tournament_players.team_id', '=', $team);
      // }
  
      $getresult = $data->get();
  
      $match_count_player = collect();
      $player_runs = collect();
      $balls_faced = collect();
      $sixes = collect();
      $fours = [];
      $hundreds=[];
      $fifty=[];
      $higest_score=[];
     
      
  
      foreach ($getresult as $teamPlayer) {
          $match_count = FixtureScore::where('fixture_id', $teamPlayer->id)
              ->selectRaw("COUNT(playerId)")
              ->selectRaw("fixture_id")
              ->groupBy('fixture_id')
              ->pluck('COUNT(playerId)', 'fixture_id')
              ->first();
  
          $match_count_player[$teamPlayer->id] = $match_count;
  
          $player_runs[$teamPlayer->id] = FixtureScore::where('fixture_id', $teamPlayer->id)
              ->where('playerId', $teamPlayer->player_id)
              ->orderbydesc('SUM(runs)')
              ->sum('runs');
  
          $balls_faced[$teamPlayer->id] = FixtureScore::where('fixture_id', $teamPlayer->id)
              ->where('playerId', $teamPlayer->player_id)
              ->where(function ($query) {
                  $query->where('balltype', '!=', 'w')
                      ->orWhereNull('balltype');
              })
              ->count();
  
          $sixes[$teamPlayer->id] = FixtureScore::where('fixture_id', $teamPlayer->id)
              ->where('playerId', $teamPlayer->player_id)
              ->where('issix', 1)
              ->count();
  
          $fours[$teamPlayer->id]['fours'] = FixtureScore::where('fixture_id', $teamPlayer->id)
              ->where('playerId', $teamPlayer->player_id)
              ->where('isfour', 1)
              ->count();

          $hundreds[$teamPlayer->id] = FixtureScore::where('fixture_id', $teamPlayer->id)
          ->where('playerid', $teamPlayer->player_id)
          ->where('fixture_scores.balltype','=','R')
          ->select('playerid', DB::raw('COUNT(*) as hundreds_count'))
          ->where('fixture_scores.runs', '>=', 100)
          ->groupBy('playerid')
          ->count();

          $fifty[$teamPlayer->id] = FixtureScore::where('fixture_id', $teamPlayer->id)
          ->where('playerid', $teamPlayer->player_id)
          ->where('fixture_scores.balltype','=','R')
          ->select('playerid', DB::raw('COUNT(*) as fifties'))
          ->where('runs', '>=', 50)
          ->where('runs', '<', 100)
          ->groupBy('playerid')
          ->count();
          
          $higest_score_query = FixtureScore::where('fixture_id', $teamPlayer->id)
          ->where('playerId', $teamPlayer->player_id)
          ->selectRaw('SUM(runs) as total_runs, fixture_id')
          ->groupBy('fixture_id')
          ->orderByDesc('total_runs')
          ->limit(1);
  
      $higest_score[$teamPlayer->id] = $higest_score_query->value('total_runs');
  
      }
      
  
      return view('batting_states', compact('fours','higest_score','fifty','hundreds','balls_faced','sixes','tournamentdata','balls_faced','player_runs','match_count_player', 'player','teams', 'match_results', 'image_gallery', 'years','getresult'));
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
    // $teams = Team::pluck('name', 'id');

    $tournament = Tournament::query()->get()->pluck(
      'name',
      'id'
    );
    $data = [];
    $result = $data;
    return view('clubteamsearch', compact('match_results', 'result', 'tournament'));
  }


  public function club_team_search_submit(Request $request)
  {
    $match_results = Fixture::query();
    $match_results = $match_results->orderBy('id')->get();
    // $teams = Team::pluck('name', 'id');



    if ($request->method() !== 'POST') {
      abort(405, 'Method Not Allowed');
    }
    $term = $request->input('teamName');

    $query = Team::query()->where('isclub', '=', 1)
      ->where('iscaptain', 1)
      ->select('teams.id', 'teams.name', 'players.fullname', 'tournament_groups.tournament_id', 'tournaments.name as tournament')
      ->join('team_players', 'team_players.team_id', '=', 'teams.id')
      ->join('players', 'players.id', '=', 'team_players.player_id')
      ->join('tournament_groups', 'tournament_groups.team_id', '=', 'teams.id')
      ->join('tournaments', 'tournaments.id', '=', 'tournament_groups.tournament_id');

    if (!empty($term)) {
      $query->where('teams.name', 'like', '%' . $term . '%');
    }
    $result = $query->get();





    return view('clubteamsearch', compact('match_results', 'result',));
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
    $match_results = Fixture::query()->where('isActive',1)->orderBy('id')->get();
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
      $data->whereRaw("YEAR(created_at) = $year");
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
    $match_results = Fixture::query()->where('isActive',1)->orderBy('id')->get();
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
      $rulesandregulations =Rulesandregulation::where('id', '=', $id)
      ->get();

    return view('leagueinfo', compact('teams', 'match_results', 'image_gallery','rulesandregulations'));
  }





public function clubviewteams(){
  $tournament_name = Tournament::query()->where('season_id','=',0)->where('is_web_display','=',1)->get();
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
    $match_results = Fixture::query()->where('isActive',1)->orderBy('id')->get();



    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );

    $clubs = Team::query()->where('isclub', '=', 1)->get()->pluck(
      'clubname',
      'id'
    );


    $results = [];
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
    
// dd($ground);

    return view('clubviewteams', compact('tournament_name','results', 'match_results','ground2','clubs', 'ground','years', 'tournament', 'image_gallery' ));
}


public function clubviewteams_submit(Request $request){
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
    $match_results = Fixture::query()->where('isActive',1)->get();
   
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
      
    $image_gallery =GalleryImages::query()
    ->where('isActive','=',1)
    ->get();

    $ground2= Ground::query()->get()->pluck(
      'name',
      'id'
    );
    
    return view('clubviewteams', compact('results','ground2', 'match_results', 'ground','years', 'tournament', 'image_gallery' ));
}


  public function  view_tournaments(int $tournament_id)
  {


    $match_results = Fixture::query();
    $match_results = $match_results->where('isActive',1)->where('running_inning', '=', 3)->orderBy('id')->get();
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
    $match_results = $match_results->where('isActive',1)->orderBy('id')->get();
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
    $match_results = $match_results->where('isActive',1)->orderBy('id')->get();
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

public function articals(){

$match_results = Fixture::query();
$match_results = $match_results->where('isActive',1)->orderBy('id')->get();
$teams = Team::query()->get()->pluck(
  'name',
  'id'
);

$sponsor_gallery =Sponsor::query()
->where('isActive','=',1)
->get();

$tournament_name = Tournament::query()
->where('season_id','=',0)
->where('is_web_display','=',1)
->get();


$grounds =Ground::query()
->where('isActive','=',1)
->get();




  return view('articals'  , compact( 'match_results', 'teams'  , "tournament_name" , "grounds"));  
}

public function newsdata(){

  $match_results = Fixture::query();
  $match_results = $match_results->orderBy('id')->get();
  $teams = Team::query()->get()->pluck(
    'name',
    'id'
  );
  
  $sponsor_gallery =Sponsor::query()
  ->where('isActive','=',1)
  ->get();
  
  $tournament_name = Tournament::query()
  ->where('season_id','=',0)
  ->where('is_web_display','=',1)
  ->get();
  
  
  $grounds =Ground::query()
  ->where('isActive','=',1)
  ->get();
  
  $image_slider =GalleryImages::query()
  ->where('is_main_slider','=',1)
  ->where('isActive','=',1)
  ->get();
  
  
    return view('newsdata'  , compact( 'image_slider','match_results', 'teams'  , "tournament_name" , "grounds"));  
  }

  public function contactus()
  {
    $match_results = Fixture::query();
$match_results = $match_results->orderBy('id')->get();
$teams = Team::query()->get()->pluck(
  'name',
  'id'
);

$sponsor_gallery =Sponsor::query()
->where('isActive','=',1)
->get();

$tournament_name = Tournament::query()
->where('season_id','=',0)
->where('is_web_display','=',1)
->get();


$grounds =Ground::query()
->where('isActive','=',1)
->get();

  return view('contactus'  , compact( 'match_results', 'teams'  , "tournament_name" , "grounds"));  
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

      // dd($results);
      
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

    $name = Tournament::query()
    ->where('id', '=', $tournament_id)
    ->get();


    return view('viewteams', compact('results', 'ground2', 'match_results', 'ground', 'years', 'tournament', 'image_gallery' , 'name'));

  
  }

  public function matchofficial(){
    $match_results = Fixture::query();
    $match_results = $match_results->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    
    $sponsor_gallery =Sponsor::query()
    ->where('isActive','=',1)
    ->get();
    
    $tournament_name = Tournament::query()
    ->where('season_id','=',0)
    ->where('is_web_display','=',1)
    ->get();
    
    
    $grounds =Ground::query()
    ->where('isActive','=',1)
    ->get();
    
    $umpire_matchoffcial = Umpire::query()
    ->get();
    
   
      return view('matchofficial'  , compact( 'match_results', 'teams'  , "tournament_name" , "grounds","umpire_matchoffcial"));   
  }

  public function playerview(int $playerid)
  {
    $match_results = Fixture::query();
    $match_results = $match_results->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    
    $sponsor_gallery =Sponsor::query()
    ->where('isActive','=',1)
    ->get();
    
    $tournament_name = Tournament::query()
    ->where('season_id','=',0)
    ->where('is_web_display','=',1)
    ->get();
    
    
    $grounds =Ground::query()
    ->where('isActive','=',1)
    ->get();

    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $player = Player::query()->get()->pluck(
      'fullname',
      'id'
    );
   $player_data=FixtureScore::where('fixture_scores.playerid',$playerid )
   ->join('team_players','team_players.player_id','=','fixture_scores.playerid')
   ->join('players','players.id','=','fixture_scores.playerid')
   ->select('team_players.player_id as playername','players.bowlingstyle as playerbowlingstyle','players.battingstyle as playerbattingstyle')
   ->distinct('players.playerid')
   ->get();

   $player_team=FixtureScore::where('fixture_scores.playerid',$playerid )
   ->join('team_players','team_players.player_id','=','fixture_scores.playerid')
   ->join('players','players.id','=','fixture_scores.playerid')
   ->select('team_players.player_id as playername','team_players.team_id as playerteam')
   ->distinct('players.playerid')
   ->get();

   $player_club=FixtureScore::where('fixture_scores.playerid',$playerid )
   ->join('team_players','team_players.player_id','=','fixture_scores.playerid')
   ->join('teams','teams.id','=','team_players.team_id')
   ->where('teams.isclub',1)
   ->select('teams.clubname as playerclub')
   ->distinct('players.playerid')
   ->get();

   $player_match = FixtureScore::where('playerid', $playerid)->count();

   $player_runs = FixtureScore::where('playerid', $playerid)
   ->where('fixture_scores.balltype','=','R')
   ->selectRaw('SUM(runs) as playerruns')
   ->groupBy('playerid')
   ->get();

   $player_balls = FixtureScore::where('playerid', $playerid)
   ->where('fixture_scores.balltype','=','R')
   ->selectRaw("COUNT(id) as max_ball ") 
   ->groupBy('playerid')
   ->get();

 

   $player_wicket = FixtureScore::where('bowlerid', $playerid)
   ->where('balltype','=','Wicket')
   ->selectRaw('SUM(isout) as playerwickets')
   ->groupBy('bowlerid')
   ->get();

   $player_total_fifties = DB::table('fixture_scores')
   ->where('fixture_scores.playerid', $playerid)
   ->where('fixture_scores.balltype','=','R')
   ->select('playerid', DB::raw('COUNT(*) as fifties'))
   ->where('runs', '>=', 50)
   ->where('runs', '<', 100)
   ->groupBy('playerid')
   ->get();

$player_fifties = $player_total_fifties->sum('fifties');

$player_hundreds = DB::table('fixture_scores')
->where('fixture_scores.playerid', $playerid)
->where('fixture_scores.balltype','=','R')
->select('playerid', DB::raw('COUNT(*) as hundreds_count'))
->where('fixture_scores.runs', '>=', 100)
->groupBy('playerid')
->get();

$total_hundreds = $player_hundreds->sum('hundreds_count');

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
->where('fixture_scores.balltype','=','R')
->selectRaw("SUM(fixture_scores.issix = 1 AND fixture_scores.balltype = 'R') as total_sixes")
->groupBy('playerid')
->get();


$player_inning_score = FixtureScore::where('playerid', $playerid)
->where('fixture_scores.balltype','=','R')
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
   ->where('balltype','=','Wicket')
   ->selectRaw('SUM(isout) as playerwickets')
   ->groupBy('fixture_id')
   ->get()->pluck('playerwickets')->toArray();

// dd($player_inning_wickets);


$player_four = FixtureScore::where('playerid', $playerid)
->where('fixture_scores.balltype','=','R')
->selectRaw("SUM(fixture_scores.isfour = 1 AND fixture_scores.balltype = 'R') as total_four")
->groupBy('playerid')
->get();

// bower state
$player_matchbowler = FixtureScore::where('bowlerid', $playerid)->count();
$bower_over=FixtureScore::where('bowlerid', $playerid)
->selectRaw("SUM(fixture_scores.balltype = 'WD') as total_wides")
->selectRaw('SUM(runs) as bowler_runs')
->selectRaw('MAX(overnumber) as max_over')  
->groupBy('bowlerid')
->get();
$match_dissmissal_name= Dismissal::where('dismissals.name', '=', 'Caught')
->selectRaw("dismissals.id as dissmissalname")
->groupBy('dismissals.id')
->get()->pluck('dissmissalname');

$player_cauches = FixtureScore::where('bowlerid', $playerid)
->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
->where('match_dismissals.dismissal_id',$match_dissmissal_name)
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

  //     return view('playerview'  , compact( "bowler_economy","bowler_balls","bowler_strike_rate","player_cauches","bower_over",'player_four','match_results', 'teams','player_team'  , "tournament_name" , "grounds","player_data","teams","player","player_club","player_match","player_runs","player_wicket","player_fifties","total_hundreds","player_balls","player_average","player_strike_rate","player_innings","player_six","player_matchbowler"));   
  // }

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
      


//         $wicket_chart = (object)[
//           'labels' => '',
//           'dataset' => '',
//         ];
//         $wicket_chart->labels = (array_keys($bowler_wicket_chart));
//         $wicket_chart->dataset = (array_values($bowler_wicket_chart));
      
// dd($wicket_chart);


      return view('playerview'  , compact( "bowler_economy","bowler_balls","bowler_strike_rate","player_cauches","bower_over",'player_four','match_results', 'teams','player_team'  , "tournament_name" , "grounds","player_data","teams","player","player_club","player_match","player_runs","player_wicket","player_fifties","total_hundreds","player_balls","player_average","player_strike_rate","player_innings","player_six","player_matchbowler", "chart", 'bowler_inning_wicket','bowler_wicket_chart','batsman_wicket_chart'));  
} 


  public function bowling_state(){
    $tournamentdata = Tournament::query()->pluck(
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
        $getresult=[];
       

    return view('bowling_state', compact('player','getresult','teams','tournamentdata', 'match_results',  'image_gallery','years'));
  }

  public function bowling_state_submit(Request $request){
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


$term = $request->input();
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

$getresult = $data->orderbydesc('total_wickets')->get();


$hatricks=0;
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

return view('bowling_state', compact('tournamentdata', 'player','teams', 'match_results', 'image_gallery', 'years','getresult'));

  
  }




  public function fieldingRecords()
  {

    $tournamentdata = Tournament::query()->pluck(
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
        $getresult=[];
        $match_count_player = collect();
        $player_runs = collect();
        $balls_faced = collect();
        $sixes = collect();
        $fours = [];

    return view('fieldingRecords', compact('fours','balls_faced','sixes','balls_faced','player_runs','match_count_player','player','getresult','teams','tournamentdata', 'match_results',  'image_gallery','years'));
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
      $match_results = Fixture::query()->where('isActive',1)->get();
     
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
      $tournament = Tournament::query()->pluck(
              'name',
              'id'
          );

          // dd($results);
        
      $image_gallery =GalleryImages::query()
      ->where('isActive','=',1)
      ->get();
  
      $ground2= Ground::query()->get()->pluck(
        'name',
        'id'
      );
      
      return view('viewteams', compact('results','ground2', 'match_results', 'ground','years', 'tournament', 'image_gallery' ));


  }

 
  
  
}
