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

// Route::get('/',[HomeController::class,'test'])->name('test');

Route::get('search_player',[HomeController::class,'search_player'])->name('search_player');
Route::post('searchplayer-form-submit',[HomeController::class,'searchplayer_form_submit']);


Route::get('balltoballscorecard/{id}',[HomeController::class,'balltoballScorecard'])->name('balltoballScorecard');
Route::get('fullScorecard/{id}', [HomeController::class, 'fullScorecard'])->name('fullScorecard');
Route::get('fullScorecard_overbyover/{id}',[HomeController::class,'fullScorecard_overbyover'])->name('fullScorecard_overbyover');
Route::get('result', [HomeController::class, 'result'])->name('result');
Route::post('result-form-submit',[HomeController::class,'result_form_submit'])->name('result_form_submit');
Route::get('live_score',[ApiController::class,'live_score']);
Route::get('team-view/{team_id}_{tournament_id}',[HomeController::class,'team_view'])->name('team_view');
Route::get('team_result/{team_id}_{tournament_id}',[HomeController::class,'team_result'])->name('team_result');
Route::get('get_point_table/{id}/{type}',[ApiController::class,'get_point_table'])->name('get_point_table');
Route::get('get_season_group/{season_id}/{type}',[ApiController::class,'get_season_group'])->name('get_season_group');
Route::get('get_group_team/{group_id}/{tournament_id}/{type}',[ApiController::class,'get_group_team'])->name('get_group_team');
Route::get('team_schedule/{team_id}_{tournament_id}',[HomeController::class,'team_schedule'])->name('team_schedule');
Route::get('team_batting/{team_id}_{tournament_id}',[HomeController::class,'team_batting'])->name('team_batting');
Route::get('team_bowling/{team_id}_{tournament_id}',[HomeController::class,'team_bowling'])->name('team_bowling');
Route::get('team_fielding/{team_id}_{tournament_id}',[HomeController::class,'team_fielding'])->name('team_fielding');
Route::get('batting_states',[HomeController::class,'batting_states'])->name('batting_states');
Route::get('get_top_scorers/{id}',[ApiController::class,'get_top_scorers'])->name('get_top_scorers');
Route::get('get_top_bowler/{id}',[ApiController::class,'get_top_bowler'])->name('get_top_bowler');

Route::get('downloadCSV/{id}', [HomeController::class, 'downloadCSV'])->name('downloadCSV');
