<?php 
namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Item;

use App\Models\Player;
use App\Models\Tournament;
use App\Models\Fixture;
use App\Models\TournamentGroup;
use App\Models\TournamentPlayer;
use App\Models\FixtureScore;
use App\Models\Team;
use App\Models\Ground;
use App\Models\TeamPlayer;
use App\Models\Dismissal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 



class ApiController extends Controller

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


 
    
    public function live_score(Request $request)
    {
        $match_results = Fixture::query();
        $match_results->orWhere('running_inning', '=', 1);
        $match_results->orWhere('running_inning', '=', 2); 
     
        $data = $match_results->pluck('id')->all();
        
        $today = Carbon::now()->toDateString(); 
        $now = Carbon::now();
        $currentTime = Carbon::createFromFormat('H:i:s', $now->format('H:i:s'), 'UTC');
        $localTime = $currentTime->addHours(5);
        
        $player_runs = FixtureScore::whereIn('fixture_id', $data)
            ->selectRaw("fixture_id, inningnumber, sum(runs) as total_runs")
            ->selectRaw("tournament.name as tournaments_name")
            ->selectRaw("tournament.id as tournamentID")
            ->selectRaw("ground.name as ground_name")
            ->selectRaw("team_a.name as team_a_name, team_b.name as team_b_name")
            ->selectRaw("first_inning_team_id as team_id_a,second_inning_team_id as team_id_b")
            ->selectRaw("match_startdate")
            ->selectRaw("numberofover")
            ->selectRaw("inningnumber")
            ->selectRaw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) as total_wickets")
            ->groupBy('fixture_id', 'inningnumber')
            ->selectRaw('inningnumber, max(overnumber) as max_over ')
            ->selectRaw('inningnumber, max(ballnumber) as max_ball ')
            ->join('fixtures', 'fixtures.id', '=', 'fixture_id')
            ->Join('teams as team_a', 'team_a.id', '=', 'fixtures.first_inning_team_id')
            ->Join('teams as team_b', 'team_b.id', '=', 'fixtures.second_inning_team_id')
            ->Join('tournaments as tournament', 'tournament.id', '=', 'fixtures.tournament_id')
            ->Join('grounds as ground', 'ground.id', '=', 'fixtures.ground_id')
            ->whereDate('match_startdate', $today)
            // ->whereTime('match_starttime', '<=', $localTime)
            // ->whereTime('match_endtime', '>=', $localTime)
            ->orderBy('fixture_id')
            ->get();
    
        return response()->json($player_runs);
    }
    
    public function get_point_table(int $id,string $type)
    {
        if($type === 'T'){
        $get_point_table_data = TournamentGroup::where('tournament_id', '=', $id)
            ->selectRaw("team_name.name as teams_name")
            ->selectRaw("team_name.id as teams_id")
            ->Join('teams as team_name', 'team_name.id', '=', 'tournament_groups.team_id')
            ->get()->pluck('teams_name', 'teams_id');
    
        $match_count_team_a = Fixture::where('tournament_id', '=', $id)
            ->where('running_inning',3)
            ->selectRaw("COUNT(id)")
            ->selectRaw("team_id_a")
            ->groupby('team_id_a')
            ->get()->pluck('COUNT(id)', 'team_id_a');
    
        $match_count_team_b = Fixture::where('tournament_id', '=', $id)
            ->where('running_inning',3)
            ->selectRaw("COUNT(id)")
            ->selectRaw("team_id_b")
            ->groupby('team_id_b')
            ->get()->pluck('COUNT(id)', 'team_id_b');
    
        $match_count_winning_team = Fixture::where('tournament_id', '=', $id)
            ->selectRaw("COUNT(id)")
            ->selectRaw("winning_team_id")
            ->groupby('winning_team_id')
            ->get()->pluck('COUNT(id)', 'winning_team_id');
    
        $match_count_loss_team = Fixture::where('tournament_id', '=', $id)
            ->selectRaw("COUNT(id)")
            ->selectRaw("lossing_team_id")
            ->groupby('lossing_team_id')
            ->get()->pluck('COUNT(id)', 'lossing_team_id');
            
        $match_count_tie_team = Fixture::where('tournament_id', $id)
            ->where('is_tie_match', 1)
            ->selectRaw('team_id_a, team_id_b, COUNT(is_tie_match) as tie')
            ->groupBy('team_id_a', 'team_id_b')
            ->pluck('tie', 'team_id_a', 'team_id_b');
        
        $bonusPointsSum_team_A = Fixture::where('tournament_id', $id)
            ->selectRaw('SUM(teamAbonusPoints) as totalBonusPoints')
            ->selectRaw('team_id_a')
            ->groupBy('team_id_a')
            ->pluck('totalBonusPoints', 'team_id_a');    
        
        $bonusPointsSum_team_B = Fixture::where('tournament_id', $id)
            ->selectRaw('SUM(teamBbonusPoints) as totalBonusPoints')
            ->selectRaw('team_id_b')
            ->groupBy('team_id_b')
            ->pluck('totalBonusPoints', 'team_id_b');

            $teamscorerunsteamA = Fixture::where('tournament_id', $id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixture_scores.inningnumber', 1)
            ->selectRaw('SUM(fixture_scores.runs) as total_runs')
            ->selectRaw('team_id_a')
            ->groupBy('team_id_a')
            ->pluck('total_runs', 'team_id_a');
        
            $teamscorerunsteamB = Fixture::where('tournament_id', $id)
                ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
                ->where('fixture_scores.inningnumber', 2)
                ->selectRaw('SUM(fixture_scores.runs) as total_runs')
                ->selectRaw('team_id_b')
                ->groupBy('team_id_b')
                ->pluck('total_runs', 'team_id_b');
            
            $teamscoreoverfacedteamA = Fixture::where('tournament_id', $id)
                ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
                ->where('fixture_scores.inningnumber', 1)
                ->selectRaw('MAX(fixture_scores.overnumber) as max_over')
                ->selectRaw('team_id_a')
                ->groupBy('team_id_a')
                ->pluck('max_over', 'team_id_a');
            
            $teamscoreoverfacedteamB = Fixture::where('tournament_id', $id)
                ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
                ->where('fixture_scores.inningnumber', 2)
                ->selectRaw('MAX(fixture_scores.overnumber) as max_over')
                ->selectRaw('team_id_b')
                ->groupBy('team_id_b')
                ->pluck('max_over', 'team_id_b');

            $team_runs_concededteamA = Fixture::where('tournament_id', $id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixture_scores.inningnumber', 1)
            ->selectRaw('SUM(fixture_scores.runs) as total_runs')
            ->selectRaw('team_id_a')
            ->groupBy('team_id_a')
            ->pluck('total_runs', 'team_id_a');

            $team_runs_concededteamB = Fixture::where('tournament_id', $id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixture_scores.inningnumber', 1)
            ->selectRaw('SUM(fixture_scores.runs) as total_runs')
            ->selectRaw('team_id_b')
            ->groupBy('team_id_b')
            ->pluck('total_runs', 'team_id_b');

            $team_balls_bowledteamA = Fixture::where('tournament_id', $id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixture_scores.inningnumber', 2)
            ->selectRaw('MAX(fixture_scores.overnumber) as max_over')
            ->selectRaw('team_id_a')
            ->groupBy('team_id_a')
            ->pluck('max_over', 'team_id_a');

            $team_balls_bowledteamB = Fixture::where('tournament_id', $id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixture_scores.inningnumber', 2)
            ->selectRaw('MAX(fixture_scores.overnumber) as max_over')
            ->selectRaw('team_id_b')
            ->groupBy('team_id_b')
            ->pluck('max_over', 'team_id_b');
        
   
        $result = array();
        foreach ($get_point_table_data as $team_id => $team_name) {
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
            $team_ball_face = ($team_balls_facedA + $team_balls_facedB)*6;
        
          
            $team_runs_concededA = isset($teamscoreoverfacedteamA[$team_id]) ? $teamscoreoverfacedteamA[$team_id] : 0;
            $team_runs_concededB = isset($team_runs_concededteamB[$team_id]) ? $team_runs_concededteamB[$team_id] : 0;
            $team_runs_conceded = $team_runs_concededA + $team_runs_concededB;


            $team_balls_bowledA = isset($team_balls_bowledteamA[$team_id]) ? $team_balls_bowledteamA[$team_id] : 0;
            $team_balls_bowledB = isset($team_balls_bowledteamB[$team_id]) ? $team_balls_bowledteamB[$team_id] : 0;
            $team_balls_bowled = ($team_balls_bowledA + $team_balls_bowledB)*6;

            if ($team_ball_face != 0 && $team_balls_bowled != 0) {
                $net_run_rate =($team_runs_conceded / $team_balls_bowled) -($team_runs_scored / $team_ball_face) ;
            } else {
                $net_run_rate=0.00;
            }
            
            $result[] = [
                'tournament_id' => $id,
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
        
        return response()->json($result);
    }else {
        return;
    }
      
    }

    public function tournament_name(int $season_id){
        $get_tournament = TournamentGroup::whereIn('tournament_id', function($query) use ($season_id) {
            $query->select('id')
                  ->from('tournaments')
                  ->where('season_id', $season_id);
        })
        ->selectRaw('tournaments.id as tournamentID')
        ->selectRaw('tournaments.id as tournamentID')
        ->selectRaw('tournaments.name as tournamentname')
        ->Join('tournaments', 'tournaments.id', '=', 'tournament_groups.tournament_id')
        ->groupBy('tournaments.id')
        ->get();

       
        return response()->json($get_tournament);  
    }

    public function get_season_group(int $tournament_id)
    {

        // sub query in laravel
        $get_season = TournamentGroup::whereIn('tournament_id', function($query) use ($tournament_id) {
                $query->select('id')
                      ->from('tournaments')
                      ->where('tournament_id', $tournament_id);
            })
            ->selectRaw('groups.id as group_id')
            ->selectRaw('groups.name as groupname')
            ->Join('groups', 'groups.id', '=', 'tournament_groups.group_id')
            ->groupBy('group_id')
            ->get();

            
        return response()->json($get_season);
    }

    
public function get_group_team(int $group_id,int $tournamnet_id)
    {
       
       

        $Groups_team = TournamentGroup::query()
        ->where('group_id', $group_id)
        ->where('tournaments.id', $tournamnet_id)
        ->selectRaw("team_name.name as teams_name")
            ->selectRaw("team_name.id as teams_id")
              ->Join('tournaments', 'tournaments.id', '=', 'tournament_groups.tournament_id')
            ->Join('teams as team_name', 'team_name.id', '=', 'tournament_groups.team_id')
            ->get()->pluck('teams_name', 'teams_id');
            
        $match_count_team_a = Fixture::query()
        ->where('tournament_id', $tournamnet_id)
        ->where('running_inning',3)
            ->selectRaw("COUNT(id)")
            ->selectRaw("team_id_a")
            ->groupby('team_id_a')
            ->get()->pluck('COUNT(id)', 'team_id_a');
    
        $match_count_team_b = Fixture::query()
        ->where('tournament_id', $tournamnet_id)
        ->where('running_inning',3)
            ->selectRaw("COUNT(id)")
            ->selectRaw("team_id_b")
            ->groupby('team_id_b')
            ->get()->pluck('COUNT(id)', 'team_id_b');
    
        $match_count_winning_team = Fixture::query()
        ->where('tournament_id', $tournamnet_id)
            ->selectRaw("COUNT(id)")
            ->selectRaw("winning_team_id")
            ->groupby('winning_team_id')
            ->get()->pluck('COUNT(id)', 'winning_team_id');
    
        $match_count_loss_team = Fixture::query()
        ->where('tournament_id', $tournamnet_id)
            ->selectRaw("COUNT(id)")
            ->selectRaw("lossing_team_id")
            ->groupby('lossing_team_id')
            ->get()->pluck('COUNT(id)', 'lossing_team_id');

            $bonusPointsSum_team_A = Fixture::where('tournament_id', $tournamnet_id)
            ->selectRaw('SUM(teamAbonusPoints) as totalBonusPoints')
            ->selectRaw('team_id_a')
            ->groupBy('team_id_a')
            ->pluck('totalBonusPoints', 'team_id_a');    
        
            $bonusPointsSum_team_B = Fixture::where('tournament_id', $tournamnet_id)
            ->selectRaw('SUM(teamBbonusPoints) as totalBonusPoints')
            ->selectRaw('team_id_b')
            ->groupBy('team_id_b')
            ->pluck('totalBonusPoints', 'team_id_b');
    
            $match_count_tie_team = Fixture::where('tournament_id', $tournamnet_id)
            ->where('is_tie_match', 1)
            ->selectRaw('team_id_a, team_id_b, COUNT(is_tie_match) as tie')
            ->groupBy('team_id_a', 'team_id_b')
            ->pluck('tie', 'team_id_a', 'team_id_b');

            $teamscorerunsteamA = Fixture::where('tournament_id', $tournamnet_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixture_scores.inningnumber', 1)
            ->selectRaw('SUM(fixture_scores.runs) as total_runs')
            ->selectRaw('first_inning_team_id')
            ->groupBy('first_inning_team_id')
            ->pluck('total_runs', 'first_inning_team_id');
        
            $teasecond_inning_team_idmscorerunsteamB = Fixture::where('tournament_id', $tournamnet_id)
                ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
                ->where('fixture_scores.inningnumber', 2)
                ->selectRaw('SUM(fixture_scores.runs) as total_runs')
                ->selectRaw('second_inning_team_id')
                ->groupBy('second_inning_team_id')
                ->pluck('total_runs', 'second_inning_team_id');
            
            $teamscoreoverfacedteamA = Fixture::where('tournament_id', $tournamnet_id)
                ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
                ->where('fixture_scores.inningnumber', 1)
                ->selectRaw('MAX(fixture_scores.ballnumber) as max_over')
                ->selectRaw('first_inning_team_id')
                ->groupBy('first_inning_team_id')
                ->pluck('max_over', 'first_inning_team_id');
            
            $teamscoreoverfacedteamB = Fixture::where('tournament_id', $tournamnet_id)
                ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
                ->where('fixture_scores.inningnumber', 2)
                ->selectRaw('MAX(fixture_scores.ballnumber) as max_over')
                ->selectRaw('second_inning_team_id')
                ->groupBy('second_inning_team_id')
                ->pluck('max_over', 'second_inning_team_id');

            $team_runs_concededteamA = Fixture::where('tournament_id', $tournamnet_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixture_scores.inningnumber', 1)
            ->selectRaw('SUM(fixture_scores.runs) as total_runs')
            ->selectRaw('second_inning_team_id')
            ->groupBy('second_inning_team_id')
            ->pluck('total_runs', 'second_inning_team_id');

            $team_runs_concededteamB = Fixture::where('tournament_id', $tournamnet_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixture_scores.inningnumber', 2)
            ->selectRaw('SUM(fixture_scores.runs) as total_runs')
            ->selectRaw('first_inning_team_id')
            ->groupBy('first_inning_team_id')
            ->pluck('total_runs', 'first_inning_team_id');

            $team_balls_bowledteamA = Fixture::where('tournament_id', $tournamnet_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixture_scores.inningnumber', 2)
            ->selectRaw('MAX(fixture_scores.ballnumber) as max_over')
            ->selectRaw('first_inning_team_id')
            ->groupBy('first_inning_team_id')
            ->pluck('max_over', 'first_inning_team_id');

            $team_balls_bowledteamB = Fixture::where('tournament_id', $tournamnet_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixture_scores.inningnumber', 1)
            ->selectRaw('MAX(fixture_scores.ballnumber) as max_over')
            ->selectRaw('second_inning_team_id')
            ->groupBy('second_inning_team_id')
            ->pluck('max_over', 'second_inning_team_id');
        
        $result = array();
        foreach ($Groups_team as $team_id => $team_name) {
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
            $team_ball_face = ($team_balls_facedA + $team_balls_facedB)*6;
        
          
            $team_runs_concededA = isset($teamscoreoverfacedteamA[$team_id]) ? $teamscoreoverfacedteamA[$team_id] : 0;
            $team_runs_concededB = isset($team_runs_concededteamB[$team_id]) ? $team_runs_concededteamB[$team_id] : 0;
            $team_runs_conceded = $team_runs_concededA + $team_runs_concededB;


            $team_balls_bowledA = isset($team_balls_bowledteamA[$team_id]) ? $team_balls_bowledteamA[$team_id] : 0;
            $team_balls_bowledB = isset($team_balls_bowledteamB[$team_id]) ? $team_balls_bowledteamB[$team_id] : 0;
            $team_balls_bowled = ($team_balls_bowledA + $team_balls_bowledB)*6;

            if ($team_ball_face != 0 && $team_balls_bowled != 0) {
                $net_run_rate =($team_runs_conceded / ($team_balls_bowled/6))-($team_runs_scored / ($team_ball_face/6)) ;
            } else {
                $net_run_rate=0.00;
            }
        
            $result[] = [
                'tournament_id' => $tournamnet_id,
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

        // $teamnetrr= TournamentGroup::query()
        // ->where('group_id', $group_id)
        // ->where('tournaments.id', $tournamnet_id)
        // ->selectRaw("fixtures.id as matchid")
        // ->selectRaw("team_name.id as teams_id")
        // ->Join('fixtures', 'fixtures.tournament_id', '=', 'tournament_groups.tournament_id')
        // ->Join('teams as team_name', 'team_name.id', '=', 'tournament_groups.team_id')
        // ->get()->pluck('matchid', 'teams_id');

        
        // $result2 = array();
        // foreach ($teamnetrr as $team_id => $matchid) {
        
        //     $team_runs_scoredA = isset($teamscorerunsteamA[$team_id]) ? $teamscorerunsteamA[$team_id] : 0;
        //     $team_runs_scoredB = isset($teamscorerunsteamB[$team_id]) ? $teamscorerunsteamB[$team_id] : 0;
        //     $team_runs_scored = $team_runs_scoredA + $team_runs_scoredB;
        
          
        //     $team_balls_facedA = isset($teamscoreoverfacedteamA[$team_id]) ? ($teamscoreoverfacedteamA[$team_id]) : 0;
        //     $team_balls_facedB = isset($teamscoreoverfacedteamB[$team_id]) ? ($teamscoreoverfacedteamB[$team_id]) : 0;
        //     $team_ball_face = ($team_balls_facedA + $team_balls_facedB)*6;
        
          
        //     $team_runs_concededA = isset($teamscoreoverfacedteamA[$team_id]) ? $teamscoreoverfacedteamA[$team_id] : 0;
        //     $team_runs_concededB = isset($team_runs_concededteamB[$team_id]) ? $team_runs_concededteamB[$team_id] : 0;
        //     $team_runs_conceded = $team_runs_concededA + $team_runs_concededB;


        //     $team_balls_bowledA = isset($team_balls_bowledteamA[$team_id]) ? $team_balls_bowledteamA[$team_id] : 0;
        //     $team_balls_bowledB = isset($team_balls_bowledteamB[$team_id]) ? $team_balls_bowledteamB[$team_id] : 0;
        //     $team_balls_bowled = ($team_balls_bowledA + $team_balls_bowledB)*6;

        //     if ($team_ball_face != 0 && $team_balls_bowled != 0) {
        //         $net_run_rate =($team_runs_conceded / $team_balls_bowled)-($team_runs_scored / $team_ball_face) ;
        //     } else {
        //         $net_run_rate=0.00;
        //     }
        
        //     $result2[] = [
        //         'net_rr' => $net_run_rate,
        //     ];
        // }

        
        return response()->json($result);
    }
    
    public function get_top_scorers(int $tournament_id)
    {
        $top_scorers = Fixture::where('tournament_id', $tournament_id)
        ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
        ->join('players', 'players.id', '=', 'fixture_scores.playerid')
        // ->where('fixture_scores.balltype','=','R' )
        ->selectRaw('players.fullname ,SUM(fixture_scores.runs) AS total_runs,players.id')
        ->groupBy('fixture_scores.playerid')
        ->orderbyDesc('total_runs')
        ->take('5')
        ->get();
    
        return response()->json($top_scorers);
    }
    
    public function get_top_bowler(int $tournament_id)
    {
        // $top_scorers = Fixture::where('tournament_id', $tournament_id)
        //     ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
        //     ->join('match_dismissals', 'match_dismissals.fixture_id', '=', 'fixture_scores.fixture_id')
        //     ->join('players', 'players.id', '=', 'fixture_scores.bowlerid')
        //     ->whereIn('match_dismissals.dismissal_id', [2, 3, 4, 6, 7, 8])
        //     ->where('fixture_scores.isout', '=', 1)
        //     ->selectRaw('players.fullname, SUM(fixture_scores.isout) as total_wickets, fixture_scores.bowlerid')
        //     ->groupBy('fixture_scores.bowlerid', 'fixture_scores.isout') 
        //     ->orderbyDesc('total_wickets')
        //     ->take(5)
        //     ->get();


        $top_scorers=Fixture::where('tournament_id', $tournament_id)
        ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
        ->join('players', 'players.id', '=', 'fixture_scores.bowlerid')
        ->selectRaw('players.fullname, SUM(fixture_scores.isout = 1) as total_wickets, fixture_scores.bowlerid')
        ->groupBy('fixture_scores.bowlerid', 'fixture_scores.isout') 
        ->orderbyDesc('total_wickets')
        ->take(5)
        ->get();

        return response()->json($top_scorers);
    }

    public function tournamnet_all_data(int $tournament_id)
    {
        DB::enableQueryLog();
        $tournamnetdata = Fixture::where('fixtures.tournament_id', $tournament_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->selectRaw("SUM(fixture_scores.issix = 1 ) as total_sixes")
            ->selectRaw("SUM(fixture_scores.isfour = 1 ) as total_fours")
            ->selectRaw("SUM(fixture_scores.balltype = 'RunOut' OR fixture_scores.balltype = 'RunOut(WD)' OR fixture_scores.balltype = 'RunOut(NB)') as runout")
            ->selectRaw("SUM(fixture_scores.isout = 1 ) as total_Wicket")
            ->selectRaw("SUM(fixture_scores.balltype = 'WD') as total_wides")
            ->selectRaw("SUM(CASE WHEN fixture_scores.balltype = 'NB' OR fixture_scores.balltype = 'NBB' OR fixture_scores.balltype = 'NBP' THEN 1 ELSE 0 END) as total_noballs")
            ->selectRaw('SUM(fixture_scores.runs) as total_runs')
            ->groupBy('fixtures.tournament_id')
            ->get();

            $tournament_players =Fixture::where('fixtures.tournament_id', $tournament_id) 
            ->join('tournament_groups', 'tournament_groups.tournament_id', '=', 'fixtures.tournament_id')
            ->join('tournament_players', 'tournament_players.tournament_id', '=', 'tournament_groups.tournament_id') 
            ->selectRaw("COUNT(DISTINCT tournament_players.player_id) as total_players")   
            ->groupBy('fixtures.tournament_id')
            ->get();
    
            $match_dissmissal_name= Dismissal::where('dismissals.name', '=', 'Caught')
            ->selectRaw("dismissals.id as dissmissalname")
            ->groupBy('dismissals.id')
            ->get()->pluck('dissmissalname');

            $tournament_cauches = Fixture::where('fixtures.tournament_id', $tournament_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
            ->where('match_dismissals.dismissal_id',$match_dissmissal_name)
            ->selectRaw("COUNT(match_dismissals.id) as total_catches")
            ->groupBy('fixtures.tournament_id')
            ->get();

            $match_dissmissal_runout_name= Dismissal::where('dismissals.name', '=', 'Run out')
            ->selectRaw("dismissals.id as dissmissalname")
            ->get()->pluck('dissmissalname');

            $tournament_runout = Fixture::where('fixtures.tournament_id', $tournament_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->join('match_dismissals', 'match_dismissals.fixturescores_id', '=', 'fixture_scores.id')
            ->where('match_dismissals.dismissal_id',$match_dissmissal_runout_name)
            ->selectRaw("COUNT(match_dismissals.id) as total_runout")
            ->groupBy('fixtures.tournament_id')
            ->get();


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
            ->where('fs1.isout', '=', 1)
            ->whereNull('fs4.id')
            ->select(DB::raw('COUNT(*) as total_hat_tricks'))
            ->pluck('total_hat_tricks')
            ->toArray();
        
        $total_hat_tricks_count = $total_hat_tricks[0] ?? 0;
        
        $result = ['hatricks' => $total_hat_tricks_count];
        
        // $tournament_hundreds = DB::table('fixture_scores')
        // ->where('fixtures.tournament_id', $tournament_id)
        // ->where('fixture_scores.balltype','=','R')
        // ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
        // ->select('playerid', DB::raw('COUNT(*) as hundreds_count'))
        // ->where('fixture_scores.runs', '>=', 100)
        // ->groupBy('playerid')
        // ->get();

        $tournament_hundreds = DB::table(function ($query) use ($tournament_id) {
            $query->select('playerid', DB::raw('SUM(runs) AS hundred'), 'fixture_id')
                ->from('fixture_scores')
                ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
                ->where('fixtures.tournament_id', $tournament_id)
                ->groupBy('playerid', 'fixture_id');
        }, 'subquery')
        ->select('playerId', DB::raw('COUNT(*) AS hundred'))
        ->where('hundred', '>=', 100)
        ->groupBy('playerId')
        ->get();
        
        $total_hundreds = $tournament_hundreds->sum('hundreds_count');
        $tournament_total_hundreds=['tournament_hundreds'=>$total_hundreds];
       

        // $tournament_fifties = DB::table('fixture_scores')
        // ->where('fixtures.tournament_id', $tournament_id)
        // ->where('fixture_scores.balltype','=','R')
        // ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
        // ->select('playerId', DB::raw('sum(runs) as fifties'))
        // ->having('fifties', '>=', 50)
        // ->having('fifties', '<', 100)
        // ->groupBy('playerId')
        // ->get();
        $tournament_fifties = DB::table(function ($query) use ($tournament_id) {
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
            ->get();

        // $tournament_fifties = DB::select("SELECT playerid, COUNT(*) AS fifties FROM ( SELECT playerid, SUM(runs) AS fifties, fixture_id FROM fixture_scores JOIN fixtures ON fixtures.id = fixture_scores.fixture_id WHERE fixtures.tournament_id = 53 GROUP BY playerid, fixture_id ) AS subquery WHERE fifties >= 50 AND fifties < 100 GROUP BY playerid");

        
        $total_fifties = $tournament_fifties->sum('fifties');
        $tournament_total_fifties = ['tournament_fifties' => $total_fifties];
    


    // $query = DB::getQueryLog();
    //                 $query = DB::getQueryLog();
    //         dd($query);
    $variable1 = 'R';
    $variable2 = 'Wicket';
  

        $tournament_balls =Fixture::where('fixtures.tournament_id', $tournament_id) 
        ->where(function ($query) use ($variable1, $variable2) {
            $query->where('balltype', '=', $variable1)
              ->orWhere('balltype', '=', $variable2);
          })
        ->selectRaw("COUNT((overnumber * 6) + ballnumber) as max_ball")
        ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')

        ->groupBy('fixtures.tournament_id')
        ->get();

        $tournament_teams = Fixture::where('fixtures.tournament_id', $tournament_id)
        ->join('tournament_groups', 'tournament_groups.tournament_id', '=', 'fixtures.tournament_id')
        ->selectRaw("COUNT(DISTINCT tournament_groups.team_id) as totalteams")
        ->groupBy('fixtures.tournament_id')
        ->get();
        
        //   $tournament_runout;  
            
        return response()->json([$tournamnetdata,$tournament_players,$tournament_cauches,$result,$tournament_total_hundreds,$tournament_total_fifties,$tournament_balls,$tournament_teams,$tournament_runout]);
    }
    

    public function get_top_ranking(int $tournament_id)
    {
       error_log($tournament_id);
        $top_ranking = DB::select("SELECT 
        p.fullname,
        pc.tournament_id, 
        pc.fixture_id, 
        pc.player_id, 
        pc.team_id, 
        COALESCE(SUM(CASE WHEN pc.player_type = 'batsmen' THEN pc.points END), '') AS 'Batting',
        COALESCE(SUM(CASE WHEN pc.player_type = 'Bowler' THEN pc.points END), '') AS 'Bowling',
        COALESCE(SUM(CASE WHEN pc.player_type IN ('batsmen', 'Bowler') THEN pc.points END), '') AS 'Total'
      FROM 
        players_contain_points AS pc
      JOIN 
        players AS p ON p.id = pc.player_id
      WHERE 
        pc.player_type IN ('batsmen', 'Bowler')
        AND pc.tournament_id = $tournament_id
      GROUP BY 
        p.fullname,
        pc.tournament_id, 
        pc.fixture_id, 
        pc.player_id, 
        pc.team_id
      ORDER BY
         Total DESC
      LIMIT 5") ;

        return response()->json($top_ranking);
    }
    

    
 }