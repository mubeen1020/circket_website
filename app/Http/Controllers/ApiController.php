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
            ->selectRaw("team_id_a,team_id_b")
            ->selectRaw("match_startdate")
            ->selectRaw("numberofover")
            ->selectRaw("inningnumber")
            ->selectRaw("SUM(CASE WHEN balltype = 'Wicket' THEN 1 ELSE 0 END) as total_wickets")
            ->groupBy('fixture_id', 'inningnumber')
            ->selectRaw('inningnumber, max(overnumber) as max_over ')
            ->selectRaw('inningnumber, max(ballnumber) as max_ball ')
            ->join('fixtures', 'fixtures.id', '=', 'fixture_id')
            ->Join('teams as team_a', 'team_a.id', '=', 'fixtures.team_id_a')
            ->Join('teams as team_b', 'team_b.id', '=', 'fixtures.team_id_b')
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
            ->selectRaw("COUNT(id)")
            ->selectRaw("team_id_a")
            ->groupby('team_id_a')
            ->get()->pluck('COUNT(id)', 'team_id_a');
    
        $match_count_team_b = Fixture::where('tournament_id', '=', $id)
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
            ->selectRaw("COUNT(id)")
            ->selectRaw("team_id_a")
            ->groupby('team_id_a')
            ->get()->pluck('COUNT(id)', 'team_id_a');
    
        $match_count_team_b = Fixture::query()
        ->where('tournament_id', $tournamnet_id)
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
                ];
            }
    
    
        
    
        return response()->json($result);
    }
    
    public function get_top_scorers(int $tournament_id)
    {
        $top_scorers = Fixture::where('tournament_id', $tournament_id)
        ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
        ->join('players', 'players.id', '=', 'fixture_scores.playerid')
        ->where('fixture_scores.balltype','=','R')
        ->selectRaw('players.fullname ,SUM(fixture_scores.runs) AS total_runs')
        ->groupBy('fixture_scores.playerid')
        ->orderbyDesc('total_runs')
        ->take('5')
        ->get();
    
        return response()->json($top_scorers);
    }
    
    public function get_top_bowler(int $tournament_id)
    {
        $top_scorers = Fixture::where('tournament_id', $tournament_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->join('match_dismissals', 'match_dismissals.fixture_id', '=', 'fixture_scores.fixture_id')
            ->join('players', 'players.id', '=', 'fixture_scores.bowlerid')
            ->whereIn('match_dismissals.dismissal_id', [2, 3, 4, 6, 7, 8])
            ->where('fixture_scores.isout', '=', 1)
            ->selectRaw('players.fullname, SUM(fixture_scores.isout) as total_wickets, fixture_scores.bowlerid')
            ->groupBy('fixture_scores.bowlerid', 'fixture_scores.isout') 
            ->orderbyDesc('total_wickets')
            ->take(5)
            ->get();
        return response()->json($top_scorers);
    }
    

    
 }