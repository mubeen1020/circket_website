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
        ->where('fixtures.isActive', '=', 1)
           ->whereIN('running_inning',[1,2,3])
            ->selectRaw("COUNT(id)")
            ->selectRaw("team_id_a")
            ->groupby('team_id_a')
            ->get()->pluck('COUNT(id)', 'team_id_a');
    
        $match_count_team_b = Fixture::where('tournament_id', '=', $id)
        ->where('fixtures.isActive', '=', 1)
           ->whereIN('running_inning',[1,2,3])
            ->selectRaw("COUNT(id)")
            ->selectRaw("team_id_b")
            ->groupby('team_id_b')
            ->get()->pluck('COUNT(id)', 'team_id_b');
    
        $match_count_winning_team = Fixture::where('tournament_id', '=', $id)
        ->where('fixtures.isActive', '=', 1)
            ->selectRaw("COUNT(id)")
            ->selectRaw("winning_team_id")
            ->groupby('winning_team_id')
            ->get()->pluck('COUNT(id)', 'winning_team_id');
    
        $match_count_loss_team = Fixture::where('tournament_id', '=', $id)
        ->where('fixtures.isActive', '=', 1)
            ->selectRaw("COUNT(id)")
            ->selectRaw("lossing_team_id")
            ->groupby('lossing_team_id')
            ->get()->pluck('COUNT(id)', 'lossing_team_id');
            
            $match_count_tie_team_b = Fixture::where('tournament_id', $id)
            ->where('fixtures.isActive', '=', 1)
            ->where('is_tie_match', 1)
            ->selectRaw('team_id_b, COUNT(is_tie_match) as tie')
            ->groupBy('team_id_b')
            ->pluck('tie', 'team_id_b');

            $match_count_tie_team_a = Fixture::where('tournament_id', $id)
            ->where('fixtures.isActive', '=', 1)
            ->where('is_tie_match', 1)
            ->selectRaw('team_id_a, COUNT(is_tie_match) as tie')
            ->groupBy('team_id_a')
            ->pluck('tie', 'team_id_a');
        
            $net_run_rate_result = $this->calculateNetRunRate($id);
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
            'tournament_id' => $id,
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
        ->where('fixtures.isActive', '=', 1)
        ->where('tournament_id', $tournamnet_id)
        ->whereIN('running_inning',[1,2,3])
            ->selectRaw("COUNT(id)")
            ->selectRaw("team_id_a")
            ->groupby('team_id_a')
            ->get()->pluck('COUNT(id)', 'team_id_a');
    
        $match_count_team_b = Fixture::query()
        ->where('fixtures.isActive', '=', 1)
        ->where('tournament_id', $tournamnet_id)
        ->whereIN('running_inning',[1,2,3])
            ->selectRaw("COUNT(id)")
            ->selectRaw("team_id_b")
            ->groupby('team_id_b')
            ->get()->pluck('COUNT(id)', 'team_id_b');
    
        $match_count_winning_team = Fixture::query()
        ->where('fixtures.isActive', '=', 1)
        ->where('tournament_id', $tournamnet_id)
            ->selectRaw("COUNT(id)")
            ->selectRaw("winning_team_id")
            ->groupby('winning_team_id')
            ->get()->pluck('COUNT(id)', 'winning_team_id');
    
        $match_count_loss_team = Fixture::query()
        ->where('fixtures.isActive', '=', 1)
        ->where('tournament_id', $tournamnet_id)
            ->selectRaw("COUNT(id)")
            ->selectRaw("lossing_team_id")
            ->groupby('lossing_team_id')
            ->get()->pluck('COUNT(id)', 'lossing_team_id');

            $bonusPointsSum_team_A = Fixture::where('tournament_id', $tournamnet_id)
            ->where('fixtures.isActive', '=', 1)
            ->selectRaw('SUM(teamAbonusPoints) as totalBonusPoints')
            ->selectRaw('team_id_a')
            ->groupBy('team_id_a')
            ->pluck('totalBonusPoints', 'team_id_a');    
        
            $bonusPointsSum_team_B = Fixture::where('tournament_id', $tournamnet_id)
            ->where('fixtures.isActive', '=', 1)
            ->selectRaw('SUM(teamBbonusPoints) as totalBonusPoints')
            ->selectRaw('team_id_b')
            ->groupBy('team_id_b')
            ->pluck('totalBonusPoints', 'team_id_b');
    
            $match_count_tie_team_b = Fixture::where('tournament_id', $tournamnet_id)
            ->where('fixtures.isActive', '=', 1)
            ->where('is_tie_match', 1)
            ->selectRaw('team_id_b, COUNT(is_tie_match) as tie')
            ->groupBy('team_id_b')
            ->pluck('tie', 'team_id_b');

            $match_count_tie_team_a = Fixture::where('tournament_id', $tournamnet_id)
            ->where('is_tie_match', 1)
            ->selectRaw('team_id_a, COUNT(is_tie_match) as tie')
            ->groupBy('team_id_a')
            ->pluck('tie', 'team_id_a');

            $teamscorerunsteamA = Fixture::where('tournament_id', $tournamnet_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixtures.isActive', '=', 1)
            ->where('fixture_scores.inningnumber', 1)
            ->selectRaw('SUM(fixture_scores.runs) as total_runs')
            ->selectRaw('first_inning_team_id')
            ->groupBy('first_inning_team_id')
            ->pluck('total_runs', 'first_inning_team_id');
        
            $teasecond_inning_team_idmscorerunsteamB = Fixture::where('tournament_id', $tournamnet_id)
                ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
                ->where('fixtures.isActive', '=', 1)
                ->where('fixture_scores.inningnumber', 2)
                ->selectRaw('SUM(fixture_scores.runs) as total_runs')
                ->selectRaw('second_inning_team_id')
                ->groupBy('second_inning_team_id')
                ->pluck('total_runs', 'second_inning_team_id');
            
            $teamscoreoverfacedteamA = Fixture::where('tournament_id', $tournamnet_id)
                ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
                ->where('fixtures.isActive', '=', 1)
                ->where('fixture_scores.inningnumber', 1)
                ->selectRaw('MAX(fixture_scores.ballnumber) as max_over')
                ->selectRaw('first_inning_team_id')
                ->groupBy('first_inning_team_id')
                ->pluck('max_over', 'first_inning_team_id');
            
            $teamscoreoverfacedteamB = Fixture::where('tournament_id', $tournamnet_id)
                ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
                ->where('fixtures.isActive', '=', 1)
                ->where('fixture_scores.inningnumber', 2)
                ->selectRaw('MAX(fixture_scores.ballnumber) as max_over')
                ->selectRaw('second_inning_team_id')
                ->groupBy('second_inning_team_id')
                ->pluck('max_over', 'second_inning_team_id');

            $team_runs_concededteamA = Fixture::where('tournament_id', $tournamnet_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixtures.isActive', '=', 1)
            ->where('fixture_scores.inningnumber', 1)
            ->selectRaw('SUM(fixture_scores.runs) as total_runs')
            ->selectRaw('second_inning_team_id')
            ->groupBy('second_inning_team_id')
            ->pluck('total_runs', 'second_inning_team_id');

            $team_runs_concededteamB = Fixture::where('tournament_id', $tournamnet_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixtures.isActive', '=', 1)
            ->where('fixture_scores.inningnumber', 2)
            ->selectRaw('SUM(fixture_scores.runs) as total_runs')
            ->selectRaw('first_inning_team_id')
            ->groupBy('first_inning_team_id')
            ->pluck('total_runs', 'first_inning_team_id');

            $team_balls_bowledteamA = Fixture::where('tournament_id', $tournamnet_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixtures.isActive', '=', 1)
            ->where('fixture_scores.inningnumber', 2)
            ->selectRaw('MAX(fixture_scores.ballnumber) as max_over')
            ->selectRaw('first_inning_team_id')
            ->groupBy('first_inning_team_id')
            ->pluck('max_over', 'first_inning_team_id');

            $team_balls_bowledteamB = Fixture::where('tournament_id', $tournamnet_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixtures.isActive', '=', 1)
            ->where('fixture_scores.inningnumber', 1)
            ->selectRaw('MAX(fixture_scores.ballnumber) as max_over')
            ->selectRaw('second_inning_team_id')
            ->groupBy('second_inning_team_id')
            ->pluck('max_over', 'second_inning_team_id');
      
            $net_run_rate_result = $this->calculateNetRunRate($tournamnet_id);
            $point_table_net_rr = $net_run_rate_result['point_table_net_rr'];
          
    
        

            
        $result = array();
        
        foreach ($Groups_team as $team_id => $team_name) {
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
            'tournament_id' => $tournamnet_id,
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
        
        return response()->json($result);
    }
    
    public function get_top_scorers(int $tournament_id)
    {
        $top_scorers = Fixture::where('tournament_id', $tournament_id)
        ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
        ->join('players', 'players.id', '=', 'fixture_scores.playerid')
        ->where('fixtures.isActive', '=', 1)
        ->selectRaw("SUM(CASE WHEN balltype = 'R' OR balltype = 'Wicket' OR balltype='RunOut'  THEN runs WHEN balltype = 'NBP' THEN runs - 1 ELSE 0 END) as total_runs")
        ->selectRaw('players.fullname ,players.id')
        ->groupBy('fixture_scores.playerid')
        ->orderbyDesc('total_runs')
        ->take('5')
        ->get();
    
        return response()->json($top_scorers);
    }
    

public function get_top_ranking(int $tournament_id)
{
    $points = DB::table('players_points_types')
    ->where('code', 'MOTM')
    ->pluck('points')
    ->first(); 

    $top_ranking = DB::select("
        SELECT 
            SUM(players_contain_points.points) + COALESCE(motm_points.playermompoints, 0) AS total_points,
            players_contain_points.player_id,
            players.fullname AS playername
        FROM
            players_contain_points
            JOIN players ON players.id = players_contain_points.player_id
            LEFT JOIN (
                SELECT
                    SUM($points) AS playermompoints,
                    manofmatch_player_id
                FROM
                    fixtures
                WHERE
                    tournament_id = $tournament_id
                GROUP BY
                    manofmatch_player_id
            ) AS motm_points ON motm_points.manofmatch_player_id = players_contain_points.player_id
        WHERE
            players_contain_points.tournament_id = $tournament_id
        GROUP BY
            players_contain_points.player_id, players.fullname, motm_points.playermompoints
        ORDER BY
            total_points DESC
        LIMIT 5
    ");
    
    return response()->json($top_ranking);
}



    public function get_top_bowler(int $tournament_id)
    {
        $match_dissmissal_runout_name= Dismissal::where('dismissals.name', '=', 'Run out')
        ->selectRaw("dismissals.id as dissmissalname")
        ->get()->pluck('dissmissalname');
        $match_dissmissal_Retired_name= Dismissal::where('dismissals.name', '=', 'Retired')
        ->selectRaw("dismissals.id as dissmissalname")
        ->get()->pluck('dissmissalname');
        $top_scorers=Fixture::where('tournament_id', $tournament_id)
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
        ->take(5)
        ->get();

        return response()->json($top_scorers);
    }

    public function tournamnet_all_data(int $tournament_id)
    {
        DB::enableQueryLog();
        $tournamnetdata = Fixture::where('fixtures.tournament_id', $tournament_id)
            ->join('fixture_scores', 'fixture_scores.fixture_id', '=', 'fixtures.id')
            ->where('fixtures.isActive', '=', 1)
            ->selectRaw("SUM(fixture_scores.isfour = 1 ) as total_fours")
            ->selectRaw("SUM(fixture_scores.issix = 1 ) as total_sixes")
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
            ->where('fixtures.isActive', '=', 1)
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
            ->where('fixtures.isActive', '=', 1)
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
            ->where('fixtures.isActive', '=', 1)
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
            ->where('fixtures.isActive', '=', 1)
            ->where('fs1.isout', '=', 1)
            ->whereNull('fs4.id')
            ->select(DB::raw('COUNT(*) as total_hat_tricks'))
            ->pluck('total_hat_tricks')
            ->toArray();
        
        $total_hat_tricks_count = $total_hat_tricks[0] ?? 0;
        
        $result = ['hatricks' => $total_hat_tricks_count];
        
        $tournament_hundreds = DB::table(function ($query) use ($tournament_id) {
            $query->select('playerid', DB::raw('SUM(runs) AS hundred'), 'fixture_id')
                ->from('fixture_scores')
                ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
                ->where('fixtures.tournament_id', $tournament_id)
                ->where('fixtures.isActive', '=', 1)
                ->groupBy('playerid', 'fixture_id');
        }, 'subquery')
        ->select('playerid', DB::raw('COUNT(*) AS hundreds_count'))
        ->where('hundred', '>=', 100)
        ->groupBy('playerid')
        ->get();
        
        $total_hundreds = $tournament_hundreds->sum('hundreds_count');
        $tournament_total_hundreds = ['tournament_hundreds' => $total_hundreds];
        
       

      
        $tournament_fifties = DB::table(function ($query) use ($tournament_id) {
                $query->select('playerId', DB::raw('SUM(runs) AS fifties'), 'fixture_id')
                    ->from('fixture_scores')
                    ->join('fixtures', 'fixtures.id', '=', 'fixture_scores.fixture_id')
                    ->where('fixtures.tournament_id', $tournament_id)
                    ->where('fixtures.isActive', '=', 1)
                    ->groupBy('playerId', 'fixture_id');
            }, 'subquery')
            ->select('playerId', DB::raw('COUNT(*) AS fifties'))
            ->where('fifties', '>=', 50)
            ->where('fifties', '<', 100)
            ->groupBy('playerId')
            ->get();


        
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
        ->where('fixtures.isActive', '=', 1)
        ->groupBy('fixtures.tournament_id')
        ->get();

        $tournament_teams = Fixture::where('fixtures.tournament_id', $tournament_id)
        ->where('fixtures.isActive', '=', 1)
        ->join('tournament_groups', 'tournament_groups.tournament_id', '=', 'fixtures.tournament_id')
        ->selectRaw("COUNT(DISTINCT tournament_groups.team_id) as totalteams")
        ->groupBy('fixtures.tournament_id')
        ->get();
        
            
        return response()->json([$tournamnetdata,$tournament_players,$tournament_cauches,$result,$tournament_total_hundreds,$tournament_total_fifties,$tournament_balls,$tournament_teams,$tournament_runout]);
    }
    

   
    
    

  public function calculateNetRunRate($id)
{
    $point_table_net_rr = DB::select("
    SELECT (total_runs_scored/over_bowled)- (total_runs_conceded/over_faced) as net_rr, team_id
    FROM (
        SELECT
            team_id,
            SUM(total_runs_scored) AS total_runs_scored,
            SUM(over_faced) AS over_faced,
            SUM(total_runs_conceded) AS total_runs_conceded,
            SUM(over_bowled) AS over_bowled
        FROM (
            SELECT
                first_inning_team_id AS team_id,
                SUM(fixture_scores.runs) AS total_runs_scored,
                 CASE WHEN MAX(DISTINCT fixture_scores.ballnumber) % 6 = 0
            THEN CONCAT(FLOOR(MAX(DISTINCT fixture_scores.overnumber)), '.',
                MAX(DISTINCT fixture_scores.ballnumber) % 6)
            ELSE CONCAT(FLOOR(MAX(DISTINCT fixture_scores.overnumber - 1)), '.',
                MAX(DISTINCT fixture_scores.ballnumber) % 6)
        END AS over_faced,
                0 AS total_runs_conceded,
                0 AS over_bowled
            FROM
                fixtures
            INNER JOIN
                fixture_scores ON fixture_scores.fixture_id = fixtures.id
            JOIN tournaments On tournaments.id=fixtures.tournament_id
            WHERE tournaments.id = $id
                AND inningnumber = 1 AND running_inning = 3 AND fixtures.isActive = 1
            GROUP BY
                first_inning_team_id
    
            UNION ALL
    
            SELECT
                second_inning_team_id AS team_id,
                SUM(fixture_scores.runs) AS total_runs_scored,
                 CASE WHEN MAX(DISTINCT fixture_scores.ballnumber) % 6 = 0
            THEN CONCAT(FLOOR(MAX(DISTINCT fixture_scores.overnumber)), '.',
                MAX(DISTINCT fixture_scores.ballnumber) % 6)
            ELSE CONCAT(FLOOR(MAX(DISTINCT fixture_scores.overnumber - 1)), '.',
                MAX(DISTINCT fixture_scores.ballnumber) % 6)
        END AS over_faced,
                0 AS total_runs_conceded,
                0 AS over_bowled
            FROM
                fixtures
            INNER JOIN
                fixture_scores ON fixture_scores.fixture_id = fixtures.id
            JOIN tournaments On tournaments.id=fixtures.tournament_id
            WHERE tournaments.id = $id
                AND inningnumber = 1 AND running_inning = 3 AND fixtures.isActive = 1
            GROUP BY
                second_inning_team_id
    
            UNION ALL
    
            SELECT
                first_inning_team_id AS team_id,
                0 AS total_runs_scored,
                0 AS over_faced,
                SUM(fixture_scores.runs) AS total_runs_conceded,
                 CASE WHEN MAX(DISTINCT fixture_scores.ballnumber) % 6 = 0
            THEN CONCAT(FLOOR(MAX(DISTINCT fixture_scores.overnumber)), '.',
                MAX(DISTINCT fixture_scores.ballnumber) % 6)
            ELSE CONCAT(FLOOR(MAX(DISTINCT fixture_scores.overnumber - 1)), '.',
                MAX(DISTINCT fixture_scores.ballnumber) % 6)
        END AS over_bowled
            FROM
                fixtures
            INNER JOIN
                fixture_scores ON fixture_scores.fixture_id = fixtures.id
                JOIN tournaments On tournaments.id=fixtures.tournament_id
                WHERE tournaments.id = $id
                AND inningnumber = 2 AND running_inning = 3 AND fixtures.isActive = 1
            GROUP BY
                first_inning_team_id
    
            UNION ALL
    
            SELECT
                second_inning_team_id AS team_id,
                0 AS total_runs_scored,
                0 AS over_faced,
                SUM(fixture_scores.runs) AS total_runs_conceded,
                 CASE WHEN MAX(DISTINCT fixture_scores.ballnumber) % 6 = 0
            THEN CONCAT(FLOOR(MAX(DISTINCT fixture_scores.overnumber)), '.',
                MAX(DISTINCT fixture_scores.ballnumber) % 6)
            ELSE CONCAT(FLOOR(MAX(DISTINCT fixture_scores.overnumber - 1)), '.',
                MAX(DISTINCT fixture_scores.ballnumber) % 6)
        END AS over_bowled
            FROM
                fixtures
            INNER JOIN
                fixture_scores ON fixture_scores.fixture_id = fixtures.id
                JOIN tournaments On tournaments.id=fixtures.tournament_id
                WHERE tournaments.id = $id
                AND inningnumber = 2 AND running_inning = 3 AND fixtures.isActive = 1
            GROUP BY
                second_inning_team_id
        ) AS subquery
        GROUP BY
            team_id
    ) AS q1;");

    

    return [
        'point_table_net_rr' => $point_table_net_rr,
    ];
}
  
 }