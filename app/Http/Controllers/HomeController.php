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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;




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
      ->where('season_id','=',0)
      ->where('is_web_display','=',1)
      ->selectRaw("name as tournamentname")
      ->selectRaw("isgroup")
      ->selectRaw("id as tournament_id")
      ->get();


      // $tournament_name = Tournament::query()
      // ->where('season_id','=',0)
      // ->where('is_web_display','=',1)
      // ->get();

      $Season = Season::query()
      ->where('is_web_display','=',1)
      ->selectRaw("name as season_name")
      ->selectRaw("id as season_id")
      ->distinct('id')
      ->get();

        
        $tournamentArray = [];
        foreach($tournament->toArray() as $subArray)
        {
          $subArray['type'] ='T';
          $tournamentArray[] = $subArray;
        }

        $seasonArray = [];
        foreach($Season->toArray() as $subArray)
        {
          $subArray['type'] ='S';
          $seasonArray[] = $subArray;
        }
       
        
        $tournament_season = array_merge($tournamentArray, $seasonArray);

     
        // dd($tournament_season);

        $ground = Ground::query();
        $ground = $ground->orderBy('id')->get();
        $match_results = Fixture::query();
        $match_results->where('running_inning','=',3);
        $match_results = $match_results->orderBy('id')->get();
        $today = Carbon::now()->toDateString(); 
        $upcoming_match = Fixture::query()->where('match_startdate', '>=', $today)
        ->where('running_inning','=',0)
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
          $image_gallery =GalleryImages::query()
          ->where('isActive','=',1)
          ->get();
          $image_slider =GalleryImages::query()
          ->where('is_main_slider','=',1)
          ->where('isActive','=',1)
          ->get();






        //  dd($image_slider);

        return view('home',compact('tournament','tournament_season' ,'match_results','teams','upcoming_match','ground','image_gallery','image_slider'  ));
    }


    public function balltoballScorecard(int $id)
    {
    
    
      $match_results = Fixture::query();
      $match_results->where('id','=',$id);
  $match_results = $match_results->orderBy('id')->get();
  
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
 
  $total_run =FixtureScore::Where('fixture_id','=',$id)
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


  $match_detail = FixtureScore::Where('fixture_id','=',$id)
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

  $image_gallery =GalleryImages::query()
  ->where('isActive','=',1)
  ->get();



  return view('ballbyballscorecard',compact('team_one_run_rate','team_two_run_rate','teams_one','match_total_overs' ,'match_data', 'teams_two','match_detail','match_results','teams','player','total_run','total_wickets','total_overs','tournament','teams_two_player','teams_one_player' , 'image_gallery' )); 
}


    
    public function fullScorecard_overbyover(int $id)
    {
      $match_results = Fixture::query();
      $match_results->where('id','=',$id);
      $match_results = $match_results->orderBy('id')->get();
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
        $scores->where('fixture_id','=',$id);
        $scores = $scores->orderBy('id')->get();


        $image_gallery =GalleryImages::query()
        ->where('isActive','=',1)
        ->get();


      
      

        return view('score_overbyover', compact('scores','match_results','teams','player','teams_one','teams_two' , 'image_gallery' ));

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
        $match_results->where('id','=',$id);
        $match_results = $match_results->orderBy('id')->get();
        $result = [];
        $match_data = $match_results->find($id); 
        $tournamentId = $match_results->first()->tournament_id;
        $tournament = Tournament::query()->where('id','=',$tournamentId)->get()->pluck(
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
        

        $player_runs =FixtureScore::Where('fixture_id','=',$id)
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
        $player_balls = FixtureScore::where('fixture_id','=',$id)
               ->where(function($query) use ($variable1,$variable2){
                    $query->where('balltype','=',$variable1)
                   ->orWhere('balltype','=',$variable2);
               })->selectRaw("count(id) as balls")
                ->selectRaw("playerId")->groupBy('playerId')
                ->get()->pluck('balls','playerId');;


                $image_gallery =GalleryImages::query()
                ->where('isActive','=',1)
                ->get();




        return view('score_card',compact('player_runs', 'teams_one','teams_two','player_balls','match_results','teams','player','tournament','ground','match_data' , 'image_gallery' ));

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
        $match_results->where('id','=',$id);
        $match_results = $match_results->orderBy('id')->get();
        $result = [];
        $match_data = $match_results->find($id); 
        $tournamentId = $match_results->first()->tournament_id;
        $tournament = Tournament::query()->where('id','=',$tournamentId)->get()->pluck(
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
        

        $player_runs =FixtureScore::Where('fixture_id','=',$id)
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
        $player_balls = FixtureScore::where('fixture_id','=',$id)
               ->where(function($query) use ($variable1,$variable2){
                    $query->where('balltype','=',$variable1)
                   ->orWhere('balltype','=',$variable2);
               })->selectRaw("count(id) as balls")
                ->selectRaw("playerId")->groupBy('playerId')
                ->get()->pluck('balls','playerId');;




                $TournamentName =    array( $tournament[0]. " : ". $match_results[0]->match_result_description . "  (" .$match_data->match_startdate->format('d-m-Y') . ")");
                array_push($data , $TournamentName);  
                $empyt = [] ;

///////////////////////////////////  Team One Bating
             $BattingteamOneName =    array(" " , " " ,  $teams_one." Batting");
             array_push($data , $BattingteamOneName);  
             $empyt = [] ;
             array_push($data , $empyt);  
             $TeamsBattingHead =    array("BatsMan"  ,  'How Out' ,  'Fielder'	,  'Bowler' ,"Runs"  , "Balls" ,"Fours"  , "Sixex" );
            

             array_push($data , $TeamsBattingHead);  
             array_push($data , $empyt);  
              
            foreach ($player_runs as $item) 
                {
                  if ($item->inningnumber==1)
                  {
                  $player_detail1 = array( $player[$item->playerId]  , "L Tucker" ,"C Young" , "G Dockrell" , $item->total_runs  , $player_balls[$item->playerId]  , $item->total_fours  , $item->total_six);
                  array_push($data , $player_detail1);   
                  }
                 
                }

                //////////////////////////////////////////////////////////////////////////////////////////

                ///////////////////////////////////  Team two Bowling


                array_push($data , $empyt);  
                $BowlingteamTwoName =    array(" " , " " ,  $teams_two." Bowling");
                array_push($data , $BowlingteamTwoName); 
                array_push($data , $empyt); 
                $TeamsBowlingHead =    array("Bowler"  ,  'Overs' ,  'Madiens'	,  'Runs' , "Wickets"  ,  'Wides' , 	'No Balls' , 	'Hattricks' ,	'Dot Balls');
                array_push($data , $TeamsBowlingHead); 

                //////////////////////////////////////////////////////////////////////////////////////////

                ///////////////////////////////////  Team one Fall of wickets

                array_push($data , $empyt);  
                $teamOneNFallOfWickets =    array($teams_one." Fall Of Wickets");
                array_push($data , $teamOneNFallOfWickets);
                array_push($data , $empyt); 

                //////////////////////////////////////////////////////////////////////////////////////////

                 ///////////////////////////////////  Team two Batting



                array_push($data , $empyt); 
                array_push($data , $empyt); 
                
                $BattingteamTwoName =    array(" " , " " ,  $teams_two." Batting");
                array_push($data , $BattingteamTwoName);  
                $empyt = [] ;
                array_push($data , $empyt); 

                foreach ($player_runs as $item) 
                {
                  if ($item->inningnumber==2)
                  {
                  $player_detail2 = array( $player[$item->playerId] , "L Tucker" ,"C Young" , "G Dockrell"  ,$item->total_runs  , $player_balls[$item->playerId]  , $item->total_fours  , $item->total_six);
                  array_push($data , $player_detail2);   
                  }
                  
                }
                //////////////////////////////////////////////////////////////////////////////////////////

                ///////////////////////////////////  Team one Bowling

                array_push($data , $empyt);  
                $BowlingteamOneName =    array(" " , " " ,  $teams_one." Bowling");
                array_push($data , $BowlingteamOneName); 
                array_push($data , $empyt); 
                // $TeamsBowlingHead =    array("Bowler"  ,  'Overs' ,  'Madiens'	,  'Runs' , "Wickets"  ,  'Wides' , 	'No Balls' , 	'Hattricks' ,	'Dot Balls');
                array_push($data , $TeamsBowlingHead); 

                //////////////////////////////////////////////////////////////////////////////////////////

                ///////////////////////////////////  Team two Fall of wickets
                array_push($data , $empyt);  
                $teamTwoNFallOfWickets =    array($teams_two." Fall Of Wickets");
                array_push($data , $teamTwoNFallOfWickets);
                array_push($data , $empyt); 

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

    public function search_player()
    {
        $match_results = Fixture::query();
        $match_results->where('running_inning','=',3);
        $teams = Team::query()->get()->pluck(
            'name',
            'id'
          );


        $teams_only = Team::query()->where('isclub','=',0)->get()->pluck(
            'name',
            'id'
          );


        $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
            'name',
            'id'
          );



        $match_results = $match_results->orderBy('id')->get();;
        $result = [];

        $image_gallery =GalleryImages::query()
        ->where('isActive','=',1)
        ->get();



        return view('search_player',compact('result','match_results','teams' , 'image_gallery' , 'clubs', 'teams_only'));


    }

    public function searchplayer_form_submit(Request $request)
    {
      $match_results = Fixture::query();
      $match_results->where('running_inning','=',3);
      $teams = Team::query()->get()->pluck(
          'name',
          'id'
        );


      $teams_only = Team::query()->where('isclub','=',0)->get()->pluck(
            'name',
            'id'
          );


        $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
            'name',
            'id'
          );




        $player = Player::query();

        $term = $request;
        // dd($term);
        if(!empty($term->fullname)){
            $player->where('fullname','like','%'.$term['fullname'].'%');
        }


        if(!empty($term->emailId)){
            $player->where('email','like','%'.$term['emailId'].'%');
        }

        if(!empty($term->team_name)){
          $teamPlayers = TeamPlayer::where('team_id', $term->team_name)->pluck('player_id')->toArray();
           // dd($teamPlayers);
            $player->whereIn('players.id',$teamPlayers);

        }

        if(!empty($term->club)){
          $teamPlayers = TeamPlayer::where('team_id', $term->club)->pluck('player_id')->toArray();
           // dd($teamPlayers);
            $player->whereIn('players.id',$teamPlayers);

        }


        
        if(!empty($term['gender'])){
            $player->where('gender','=',$term['gender']);
        }

        // dd($player);
        $result = $player->orderBy('players.id')
            ->join('team_players as team_players', 'team_players.player_id', '=', 'players.id')
            ->join('teams as teams', 'teams.id', '=', 'team_players.team_id')
            ->get(['name as team_name', 'player_id', 'fullname','players.contact','players.email']);


        // dd($result);

        $image_gallery =GalleryImages::query()
        ->where('isActive','=',1)
        ->get();


        
        return view('search_player',compact('result','match_results' , 'image_gallery' ,'clubs', 'teams_only'));
    }
    
    public function result()
    {
        $match_results = Fixture::query();
        $match_results->where('running_inning','=',3);
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

      $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
        'clubname',
        'id'
      );


      $image_gallery =GalleryImages::query()
      ->where('isActive','=',1)
      ->get();



        return view('result',compact('results','clubs','match_results','tournament','teams' , "image_gallery" ));
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
            $data->where("YEAR(created_at) = $year");
        }
        if (!empty($term['teams'])) {
            $team = $term['teams'];
            $data->where('team_id_a', '=', $team)
            ->oRWhere('team_id_b', '=', $team);
        }
        if(!empty($term->club)){
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

        $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
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
      
          if(count($match_summary)== 2)
          {
              $total_wicket_fixture[$result->id] = [$match_summary[0]['total_wickets'], $match_summary[1]['total_wickets']];
              $total_run_fixture[$result->id] = [$match_summary[0]['max_over'], $match_summary[1]['max_over'] ] ;
              $total_runs[$result->id] = [$match_summary[0]['total_runs'], $match_summary[1]['total_runs']];

             }   
        } 
       
        $image_gallery =GalleryImages::query()
        ->where('isActive','=',1)
        ->get();
  

        return view('result', compact('results','clubs', 'teams', 'match_results', 'years', 'tournament','total_run_fixture','total_runs', 'total_wicket_fixture' , 'image_gallery' ));
    }
    
  
    
    public function team_view(int $team_id,int $tournament_id)
    {
       $team_id_data=$team_id;
       $tournament_ids=$tournament_id;
        $ground = Ground::orderBy('id')->get();
        $ground = $ground->pluck('name', 'id');
        $tournament = Tournament::pluck('name', 'id');
        $match_results = Fixture::where('id', '=', $team_id)->orderBy('id')->get();
        
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

        $image_gallery =GalleryImages::query()
        ->where('isActive','=',1)
        ->get();


        return view('team_view', compact('team_id_data','tournament_ids','team_resultData','teamData', 'playerCount','match_results', 'player', 'ground', 'tournamentData', 'tournament','teamPlayerCount','teamPlayers' , "image_gallery" ));
    }

    public function team_result(int $team_id,int $tournament_id){
      $team_id_data=$team_id;
      $tournament_ids=$tournament_id;
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

      $match_results = Fixture::where('id', '=', $team_id)->orderBy('id')->get();
      // $teams = Team::pluck('name', 'id');
      $tournament = Tournament::pluck('name', 'id');
      $data = Fixture::query();
      $results = $data->get();

      $team_resultData = TournamentPlayer::where(function($query) use($team_id) {
        $query->where('team_id', $team_id);
    })
    ->distinct('fixture.id')
    ->where('fixture.running_inning', 3)
    ->where('tournament_players.tournament_id','=', $tournament_id)
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
   
       if(count($match_summary)== 2)
       {
           $total_wicket_fixture[$result->id] = [$match_summary[0]['total_wickets'], $match_summary[1]['total_wickets']];
           $total_run_fixture[$result->id] = [$match_summary[0]['max_over'], $match_summary[1]['max_over'] ] ;
           $total_runs[$result->id] = [$match_summary[0]['total_runs'], $match_summary[1]['total_runs']];

          }  
        }
        
        $image_gallery =GalleryImages::query()
        ->where('isActive','=',1)
        ->get();

              
      return view('team_result', compact('tournamentData', 'tournament_ids','player','teamCaptain','teamPlayerCount','teamPlayers','team_resultData','team_resultData1','teamData','match_results',  'tournament','total_run_fixture','total_runs', 'total_wicket_fixture','team_id_data' , 'image_gallery' ));
  }


  public function team_schedule(int $team_id,int $tournament_id)
{
    $team_id_data = $team_id;
    $tournament_ids=$tournament_id;
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
        ->orWhere('team_id_b', $team_id)
        ->orderBy('match_startdate')
        ->get();
    // $teams = Team::pluck('name', 'id');



   $team_scheduleData = TournamentPlayer::where(function ($query) use ($team_id, $today, $tournament_id) {
      $query->where('tournament_players.team_id', $team_id)
          ->where('fixtures.match_startdate', '>=', $today);
  })
  ->where('tournament_players.tournament_id','=', $tournament_id)
  ->where('fixtures.running_inning','=', 0)
  ->distinct('fixtures.id')
  ->selectRaw('fixtures.id, fixtures.team_id_a, fixtures.tournament_id, fixtures.team_id_b, fixtures.running_inning, fixtures.numberofover, fixtures.match_startdate, fixtures.match_result_description')
  ->join('fixtures', 'fixtures.tournament_id', '=', 'tournament_players.tournament_id')
  ->orderBy('fixtures.match_startdate')
  ->get()
  ->filter(function ($fixture) use ($today) {
      return Carbon::parse($fixture->match_startdate)->greaterThanOrEqualTo($today);
  });

   
  $image_gallery =GalleryImages::query()
  ->where('isActive','=',1)
  ->get();


    return view('team_schedule', compact( 'teamData', 'tournament_ids','match_results', 'player', 'ground', 'tournamentData', 'tournament','teamPlayerCount','team_resultData', 'teamPlayers', 'team_id_data', 'team_scheduleData' , "image_gallery" ));
}

public function team_batting(int $team_id,int $tournament_id){
  $team_id_data = $team_id;
  $tournament_ids=$tournament_id;
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
      ->orWhere('team_id_b', $team_id)
      ->orderBy('match_startdate')
      ->get();
  // $teams = Team::pluck('name', 'id');

  

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



// foreach ($team_battingdata as $teamPlayer) {
    
// }

$balls_faced = collect();

foreach ($team_battingdata as $teamPlayer) {
    $balls_faced[$teamPlayer->id] = FixtureScore::where('fixture_id', $teamPlayer->id)
        ->where('playerId', $teamPlayer->player_id)
        ->where(function($query) {
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

$image_gallery =GalleryImages::query()
  ->where('isActive','=',1)
  ->get();



  return view('team_batting', compact('teamData', 'tournament_ids','match_results',  'player', 'ground', 'tournamentData', 'tournament', 'teamPlayerCount','team_resultData', 'teamPlayers', 'team_id_data', 'team_battingdata', 'match_count_player', 'player_runs', 'balls_faced','fours','sixes' , "image_gallery" ));

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
  $match_results = Fixture::where('id', '=', $team_id)->orderBy('id')->get();
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
  ->groupBy('team_players.player_id', 'team_players.team_id')
  ->get();

  $image_gallery =GalleryImages::query()
  ->where('isActive','=',1)
  ->get();


  return view('team_bowling', compact('tournamentData', 'tournament_ids','player','teamPlayerCount','team_resultData','teamPlayers','teamData','match_results', 'teams','tournament','team_id_data','team_bowlingdata' , 'image_gallery' ));
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
  $match_results = Fixture::where('id', '=', $team_id)->orderBy('id')->get();
  // $teams = Team::pluck('name', 'id');
  $tournament = Tournament::pluck('name', 'id');
  $team_bowlingdata = TeamPlayer::where('team_id', $team_id)
  ->selectRaw('fixture_scores.bowlerId as bowler_id')
  ->selectRaw('player_id')
  ->selectRaw('team_id')
  ->selectRaw('COUNT(DISTINCT fixtures.id) as total_matches') 
  ->selectRaw('COUNT(DISTINCT fixture_scores.overnumber) as total_overs') 
  ->selectRaw('SUM(fixture_scores.balltype = "WD") as total_wides')
  ->selectRaw('SUM(fixture_scores.balltype = "NB") as total_noball')
  ->selectRaw('SUM( fixture_scores.runs) as total_runs') 
  ->selectRaw('SUM( fixture_scores.isout) as total_out') 
  ->join('fixture_scores', 'fixture_scores.bowlerId', '=', 'team_players.player_id')
  ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
  ->groupBy('bowler_id','team_id')
  ->get();

  $image_gallery =GalleryImages::query()
  ->where('isActive','=',1)
  ->get();


  return view('team_fielding', compact('tournamentData', 'player','teamPlayers','teamData','match_results', 'teams','tournament','team_id_data','team_bowlingdata','teamPlayerCount','team_resultData','tournament_ids' , 'image_gallery' ));
}


public function batting_states()
{
    $tournament = Tournament::query()
    ->get();

    $ground = Ground::query();
    $ground = $ground->orderBy('id')->get();
    $match_results = Fixture::query();
    $match_results->where('running_inning','=',3);
    $match_results = $match_results->orderBy('id')->get();
    
    $teams = Team::query()->get()->pluck(
      'name',
      'id'
    );
    $ground = Ground::query()->get()->pluck(
        'name',
        'id'
      );
      $team_battingdata = FixtureScore::query()
      ->select('playerid')
      ->select('fixtures.team_id_a')
      ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
      ->get();

      $match_count_player = collect();
      $player_runs = collect();
      
      foreach ($team_battingdata as $teamPlayer) {
        $match_count = FixtureScore::query()
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
    
      $image_gallery =GalleryImages::query()
      ->where('isActive','=',1)
      ->get();


    return view('batting_states',compact('tournament','match_results','ground' , 'image_gallery' ));
}

public function comingsoon(){
  $match_results = Fixture::query();
        $match_results->where('running_inning','=',3);
        $match_results = $match_results->orderBy('id')->get();
  // $teams = Team::pluck('name', 'id');
  

  return view('comingsoon',compact('match_results'));
}

public function clubs(){
  $match_results = Fixture::query();
        $match_results->where('running_inning','=',3);
        $match_results = $match_results->orderBy('id')->get();
  // $teams = Team::pluck('name', 'id');
  

  $clubs = Team::query()->where('isclub','=',1)->get();



  return view('clubs',compact('match_results','clubs'));
}

public function clubteamsearch(){
  $match_results = Fixture::query();
  $match_results = $match_results->orderBy('id')->get();
// $teams = Team::pluck('name', 'id');

$tournament = Tournament::query()->get()->pluck(
  'name',
  'id'
);
$data = [];
$result = $data;
return view('clubteamsearch',compact('match_results','result','tournament'));
}


public function club_team_search_submit(Request $request){
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
  




return view('clubteamsearch',compact('match_results','result',));
}


public function schedulesearch(){
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

    $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
      'clubname',
      'id'
    );

    $results = $data->get();
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
  
return view('schedulesearch',compact('results','ground2','ground','clubs', 'match_results', 'years', 'tournament', 'image_gallery' ));
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
    $match_results = Fixture::query()->orderBy('id')->get();
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

    if(!empty($term->club)){
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

    $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
      'clubname',
      'id'
    );
    
  
    
    $results = $data->get();
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
    
// dd($ground);
    return view('schedulesearch', compact('results','ground2','clubs', 'match_results', 'ground','years', 'tournament', 'image_gallery' ));
}

public function imagegallery(){
 
    $match_results = Fixture::query()->orderBy('id')->get();
    $teams = Team::query()->get()->pluck(
        'name',
        'id'
    );
    $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
      'clubname',
      'id'
    );
    $image_gallery =GalleryImages::query()
    ->where('isActive','=',1)
    ->get();


  
  return view('imagegallery', compact('teams', 'match_results', 'image_gallery' ));
}

public function seasonresponsers(){
  $match_results = Fixture::query()->orderBy('id')->get();
  $teams = Team::query()->get()->pluck(
      'name',
      'id'
  );
  $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
    'clubname',
    'id'
  );
  $image_gallery =GalleryImages::query()
  ->where('isActive','=',1)
  ->get();

  
return view('seasonresponsers', compact('teams', 'match_results', 'image_gallery' ));
}

public function leagueinfo(){
  $match_results = Fixture::query()->orderBy('id')->get();
  $teams = Team::query()->get()->pluck(
      'name',
      'id'
  );
  $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
    'clubname',
    'id'
  );
  $image_gallery =GalleryImages::query()
  ->where('isActive','=',1)
  ->get();

  
return view('leagueinfo', compact('teams', 'match_results', 'image_gallery' ));
}

   

public function clubviewteams(){
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
   
  

    $teams = Team::query()->get()->pluck(
        'name',
        'id'
    );

    $clubs = Team::query()->where('isclub','=',1)->get()->pluck(
      'clubname',
      'id'
    );
  
    
    $results = [];
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
    
// dd($ground);
    return view('clubviewteams', compact('results','ground2','clubs', 'teams', 'match_results', 'ground','years', 'tournament', 'image_gallery' ));
}




public function  view_tournaments(int $tournament_id)
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

$select_tournament_name = Tournament::query()
->where('id','=',$tournament_id)
->get();

$seasons = Season::query()
->where('id','=',$select_tournament_name[0]->season_id)
->selectRaw("name as season_name")
->selectRaw("id as season_id")
->get();


  return view('display_tournaments'  , compact( 'match_results', 'teams'  , "tournament_name" , 'select_tournament_name' , 'seasons'));
  
}





public function  view_all_tournaments()
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



  
  return view('display_all_tournaments'  , compact( 'match_results', 'teams'  , "tournament_name"));

}


public function  view_all_grounds()
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




  return view('grounds_view'  , compact( 'match_results', 'teams'  , "tournament_name" , "grounds"));  


}

}