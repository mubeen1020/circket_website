@extends('default')
@section('content')

<div class="sta-main" style="background: white !important; overflow:auto; padding-bottom:200px;overflow-x:hidden;overflow-y:hidden;">

    <div style="display: none;">
        <label id="lblhide"></label>

    </div>

    <div class="sta-sidemenu" style="top: 13px;background-color:black">
        <h4>Ranking Stats</h4>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">Click here for More <i class="fa fa-sort-desc" aria-hidden="true" style=" float: right; margin-right: 2%;"></i></button>
        <div class="collapse navbar-collapse" id="myNavbar">
            <div class="league-image">
                <a href="{{ route('home')}}"><img src="{{ asset('utilsv2/img/others/eoscl-logo.png') }}" border="0" style='width:137px;height:100px;' class="img-responsive center-block img-circle" /></a>
            </div>

            <p style="color:white;font-weigth:bold;padding:30px"><strong>EOSCL</strong> <strong>- Event Ontario Softball Circket League Stats</strong>
            Introducing the Player Rankings in the Ontario Softball Cricket League (EOSCL) – an exciting and dynamic system that evaluates players based on their performance in various aspects of the game. The rankings consider key factors such as batting, bowling, Man of the Match awards, and the overall total of these points. Let's dive into the unique content for player rankings in the EOSCL.
            </p>

            <ol style="color:white;font-weigth:bold;padding:30px">
                <li>
                    <strong>Batting Points:</strong>

                    The batting performance of players is closely scrutinized to determine their ranking. A player's ability to score runs consistently, their average, and strike rate all contribute to their overall batting points. Those who exhibit exceptional shot-making skills, demonstrate a solid technique, and display the temperament to build innings are likely to find themselves at the top of the batting rankings.
                </li>
                <li>
                    <strong>Matches Played:</strong>

                    This stat indicates the number of matches in which a player has participated. It reflects their consistency and availability in the league.

                </li>
                <li>
                    <strong>Bowling Points:</strong>

                    The art of bowling is equally vital in determining a player's ranking. The ability to take wickets consistently, maintain an impressive economy rate, and deliver crucial breakthroughs for the team contributes to a player's bowling points. Skillful variations, accuracy, and the ability to trouble batsmen consistently are the traits that propel bowlers up the rankings ladder.
                </li>
                <li>
                    <strong>Man of the Match:</strong>

                    Recognition for outstanding performances, match-winning contributions, and exceptional displays of skill are acknowledged through the Man of the Match awards. Players who consistently rise to the occasion and deliver exceptional performances are rewarded with additional points in the rankings. Their ability to influence the outcome of matches and contribute significantly to their team's success further strengthens their position in the player rankings.
                </li>
                <li>
                    <strong>Centuries and Half-Centuries:</strong>

                    These stats track the number of centuries (100 or more runs) and half-centuries (50 to 99 runs) scored by a player. It signifies their ability to convert good starts into substantial innings and make significant contributions to their team's success.

                </li>

                <li>
                    <strong>Total Points: </strong>
                    
                    The sum of batting points, bowling points, and Man of the Match points determines the overall total points for each player. This comprehensive assessment provides a holistic view of a player's contribution to the team. It considers their impact with both bat and ball, as well as their ability to turn matches in their team's favor.
 
                </li>
            </ol>
            <p style="color:white;font-weigth:bold;padding:30px">
                With each match played in the EOSCL, the player rankings are updated to reflect the performances and contributions of individuals. The dynamic nature of the rankings ensures that players are motivated to consistently perform at their best, constantly vying for higher positions and recognition.

                At the top of the player rankings, you will find the cricketing maestros who consistently demonstrate their prowess across all aspects of the game. They showcase exceptional skills, consistency, and a hunger for success. These top-ranked players serve as role models, inspiring their teammates and captivating fans with their outstanding displays on the field.
            </p>

        </div>
    </div>

    <div class="sta-content">
        <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('playerRanking_submit')}}">
            @csrf
            <section class="sta-search-filter">
                <div class="row">

                    <div class="col-sm-2 col-xs-6">
                        <div class="form-group">
                            <div class="custom-select">
                                <select name="year" id="year" class="form-control">
                                    <option value="">All Years</option>
                                    @for ($year = date('Y'); $year >= 2015; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2 col-xs-6">
                        <div class="form-group">
                            <div class="custom-select">
                                <select name="tournament" id="tournament" class="form-control">
                                    <option value="">Career - All Series</option>
                                    @foreach($tournamentdata as $tournament_id => $tournament_name)
                                    <option value="{{ $tournament_id }}">{{ $tournament_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-xs-6">
                        <div class="form-group">
                            <div class="custom-select">
                                <select name="teams" id="teams" class="form-control">
                                    <option value="">Teams</option>
                                    @foreach($header_teams as $team_id => $team_name)
                                    <option value="{{ $team_id }}">{{ $team_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-sm-2 col-xs-6">
                        <div class="form-group">
                            <div class="custom-select">
                                <select name="club" id="teams" class="form-control">
                                    <option>Clubs</option>
                                    @foreach($header_teams as $team_id => $team_name)
                                    <option value="{{ $team_id }}">{{ $team_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2 col-xs-6">
                        <div class="form-group">
                            <div class="custom-select">
                                <select name="match_type" id="teams" class="form-control">
                                    <option>Match Type</option>
                                    @foreach($header_teams as $team_id => $team_name)
                                    <option value="{{ $team_id }}">{{ $team_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> -->


                    <div class="col-sm-2 col-xs-6">
                        <div class="form-group">
                            <div>

                                <button class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </form>
        <div id="reloaddiv">
            <div class="sta-tables">
                <div class="flex-top">
                    <div class="filter-title">Total</div>
                    <div class="text-right">

                        <!-- <div class="btn-group btn-group-sm" role="group">
						<button type="btn" class="btn btn-primary pdfBtn">PDF</button>
						<button type="btn" class="btn btn-primary csvBtn">CSV</button>
						<button type="btn" class="btn btn-primary excelBtn">Excel</button>
						<button type="btn" class="btn btn-primary printBtn">Print</button>
					</div> -->
                    </div>
                </div>
                <div class="about-table table-responsive" id="tab1default">
                    <div id="tablePlayerRankings_wrapper" class="dataTables_wrapper no-footer">
                        <table class="table sortable dataTable no-footer" id="tablePlayerRankings" role="grid">

                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 30px;">#<p><a href="#"></a></p>
                                    </th>
                                    <th class="width sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Player : activate to sort column ascending" style="width: 80px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 1);return false;">Player<p><a href="#"></a></p></a></th>
                                    <th class="width sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Team : activate to sort column ascending" style="width: 80px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 2);return false;">Team<p><a href="#"></a></p></a></th>
                                    <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Matches <p><a href="#"></a></p></a></th>
                                    <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Batting <p><a href="#"></a></p></a></th>
                                    <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Bowling <p><a href="#"></a></p></a></th>
                                    <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">MOM #<p><a href="#"></a></p></a></th>
                                    <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Total <p><a href="#"></a></p></a></th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($getresult as $key => $data)
                                <tr role="row" class="even">
                                    <td class="sorting_1">{{$key+1}}</td>
                                    <td align="left" title="Rajwant Singh" style="text-align: left;width: 90px;">
                                        <div>
                                            <div class="player-img" style="background-image: url('pic.jpg');"></div>
                                            <a href="viewPlayer.do?playerId=1375981&amp;clubId=2565"> {{$player[$data->player_id]}}</a><br>
                                        </div>
                                    </td>
                                    <td style="text-align: left;font-size: smaller;">{{$teams[$data->team_id]}}</td>
                                    <td style="text-align: left;font-size: smaller;">{{ collect($match_counts)->where('player_id', $data->player_id)->pluck('total_matches')->first() ?? 0 }}</td>
                                    <td>{{ $data->Batting   }}</td>
                                    <td>{{ $data->Bowling }}</td>
                                    <td>{{ collect($man_of_matchs)->where('player_id', $data->player_id)->pluck('MOM')->first() ?? 0 }}</td>
                                    <td>{{ (int)($data->Batting ?? 0) + (int)($data->Bowling ?? 0) }}</td>


                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@stop