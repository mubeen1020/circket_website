<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[HomeController::class,'home'])->name('home');
Route::get('comingsoon', [HomeController::class, 'comingsoon'])->name('comingsoon');
Route::get('search_player',[HomeController::class,'search_player'])->name('search_player');
Route::get('view_tournaments/{tournament_id}',[HomeController::class,'view_tournaments'])->name('view_tournaments');
Route::get('view_all_tournaments',[HomeController::class,'view_all_tournaments'])->name('view_all_tournaments');
Route::get('view_all_grounds',[HomeController::class,'view_all_grounds'])->name('view_all_grounds');
Route::get('show_team/{tournament_id}',[HomeController::class,'show_team'])->name('show_team');


Route::post('searchplayer-form-submit',[HomeController::class,'searchplayer_form_submit']);
Route::get('searchplayer-form-submit',[HomeController::class,'search_player']);

Route::get('balltoballscorecard/{id}',[HomeController::class,'balltoballScorecard'])->name('balltoballScorecard');
Route::get('fullScorecard/{id}', [HomeController::class, 'fullScorecard'])->name('fullScorecard');
Route::get('fullScorecard_overbyover/{id}',[HomeController::class,'fullScorecard_overbyover'])->name('fullScorecard_overbyover');
Route::get('fullScorecard_chart/{id}',[HomeController::class,'fullScorecard_chart'])->name('fullScorecard_chart');

Route::get('result', [HomeController::class, 'result'])->name('result');
Route::post('result-form-submit',[HomeController::class,'result_form_submit'])->name('result_form_submit');
Route::get('result-form-submit',[HomeController::class,'result']);

Route::get('live_score',[ApiController::class,'live_score']);
Route::get('team-view/{team_id}_{tournament_id}',[HomeController::class,'team_view'])->name('team_view');
Route::get('team_result/{team_id}_{tournament_id}',[HomeController::class,'team_result'])->name('team_result');
Route::get('get_point_table/{id}/{type}',[ApiController::class,'get_point_table'])->name('get_point_table');
Route::get('get_season_group/{season_id}',[ApiController::class,'get_season_group'])->name('get_season_group');
Route::get('get_group_team/{group_id}/{tournament_id}',[ApiController::class,'get_group_team'])->name('get_group_team');

Route::get('team_schedule/{team_id}_{tournament_id}',[HomeController::class,'team_schedule'])->name('team_schedule');
Route::get('team_batting/{team_id}_{tournament_id}',[HomeController::class,'team_batting'])->name('team_batting');
Route::get('team_bowling/{team_id}_{tournament_id}',[HomeController::class,'team_bowling'])->name('team_bowling');
Route::get('team_fielding/{team_id}_{tournament_id}',[HomeController::class,'team_fielding'])->name('team_fielding');

Route::get('get_top_scorers/{id}',[ApiController::class,'get_top_scorers'])->name('get_top_scorers');
Route::get('get_top_bowler/{id}',[ApiController::class,'get_top_bowler'])->name('get_top_bowler');
Route::get('get_top_ranking/{id}',[ApiController::class,'get_top_ranking'])->name('get_top_ranking');


Route::get('downloadCSV/{id}', [HomeController::class, 'downloadCSV'])->name('downloadCSV');
Route::get('tournament_name/{season_id}',[ApiController::class,'tournament_name'])->name('tournament_name');
Route::get('clubs',[HomeController::class,'clubs'])->name('clubs');

Route::get('clubteamsearch',[HomeController::class,'clubteamsearch'])->name('clubteamsearch');
Route::post('club-team-search-submit',[HomeController::class,'club_team_search_submit'])->name('club_team_search_submit');
Route::get('club-team-search-submit',[HomeController::class,'clubteamsearch']);

Route::get('schedulesearch',[HomeController::class,'schedulesearch'])->name('schedulesearch');
Route::post('schedulesearch_form_submit',[HomeController::class,'schedulesearch_form_submit'])->name('schedulesearch_form_submit');
Route::get('schedulesearch_form_submit',[HomeController::class,'schedulesearch']);

Route::get('imagegallery',[HomeController::class,'imagegallery'])->name('imagegallery');
Route::get('seasonresponsers',[HomeController::class,'seasonresponsers'])->name('seasonresponsers');
Route::get('leagueinfo/{id}',[HomeController::class,'leagueinfo'])->name('leagueinfo');

Route::get('clubviewteams',[HomeController::class,'clubviewteams'])->name('clubviewteams');
Route::post('clubviewteams_submit',[HomeController::class,'clubviewteams_submit'])->name('clubviewteams_submit');
Route::get('clubviewteams_submit',[HomeController::class,'clubviewteams']);


Route::post('viewteams_submit',[HomeController::class,'viewteams_submit']);




Route::get('articals',[HomeController::class,'articals'])->name('articals');
Route::get('newsdata',[HomeController::class,'newsdata'])->name('newsdata');
Route::get('contactus',[HomeController::class,'contactus'])->name('contactus');

Route::get('tournamnet_all_data/{id}', [ApiController::class, 'tournamnet_all_data'])->name('tournamnet_all_data');

Route::get('matchofficial',[HomeController::class,'matchofficial'])->name('matchofficial');
Route::get('playerview/{playerid}',[HomeController::class,'playerview'])->name('playerview');


Route::get('test_chart',[HomeController::class,'test_chart'])->name('test_chart');

Route::get('batting_states',[HomeController::class,'batting_states'])->name('batting_states');
Route::post('batting_states_submit',[HomeController::class,'batting_states_submit'])->name('batting_states_submit');
Route::get('batting_states_submit',[HomeController::class,'batting_states']);

Route::get('bowling_state',[HomeController::class,'bowling_state'])->name('bowling_state');
Route::post('bowling_state_submit',[HomeController::class,'bowling_state_submit'])->name('bowling_state_submit');
Route::get('bowling_state_submit',[HomeController::class,'bowling_state']);


Route::get('fieldingRecords',[HomeController::class,'fieldingRecords'])->name('fieldingRecords');
Route::post('fielding_states_submit',[HomeController::class,'fielding_states_submit'])->name('fielding_states_submit');



Route::get('playerRanking',[HomeController::class,'playerRanking'])->name('playerRanking');
Route::post('playerRanking_submit',[HomeController::class,'playerRanking_submit'])->name('playerRanking_submit');





Route::get('pointtable',[HomeController::class,'pointtable'])->name('pointtable');
Route::post('pointtable_submit',[HomeController::class,'pointtable_submit'])->name('pointtable_submit');
Route::get('pointtable_submit',[HomeController::class,'pointtable']);


Route::get('show_point_table/{tournament_id}',[HomeController::class,'show_point_table'])->name('show_point_table');
Route::get('show_batting_records/{tournament_id}',[HomeController::class,'show_batting_records'])->name('show_batting_records');
Route::get('show_bowling_records/{tournament_id}',[HomeController::class,'show_bowling_records'])->name('show_bowling_records');
Route::get('show_fielding_records/{tournament_id}',[HomeController::class,'show_fielding_records'])->name('show_fielding_records');
Route::get('show_player_ranking/{tournament_id}',[HomeController::class,'show_player_ranking'])->name('show_player_ranking');





Route::get('team_ranking/{team_id}_{tournament_id}',[HomeController::class,'team_ranking'])->name('team_ranking');
Route::get('playermatchcount',[HomeController::class,'playermatchcount'])->name('playermatchcount');
Route::post('playermatchcount_submit',[HomeController::class,'playermatchcount_submit'])->name('playermatchcount_submit');
Route::get('playermatchcount_submit',[HomeController::class,'playermatchcount']);

Route::get('fixturesCalendar',[HomeController::class,'fixturesCalendar']);
Route::post('fixturesCalendar',[HomeController::class,'fixturesCalendar_post']);
