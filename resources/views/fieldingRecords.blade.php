@extends('default')
@section('content')

<div class="sta-main" style="background: white !important; overflow:auto; padding-bottom:200px;overflow-x:hidden;overflow-y:hidden;">

    <div style="display: none;">
        <label id="lblhide"></label>

    </div>

    <div class="sta-sidemenu" style="top: 13px;background-color:black">
        <h4>Batting stats</h4>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">Click here for More <i class="fa fa-sort-desc" aria-hidden="true" style=" float: right; margin-right: 2%;"></i></button>
        <div class="collapse navbar-collapse" id="myNavbar">
            <div class="league-image">
                <a href="{{ route('home')}}"><img src="{{ asset('utilsv2/img/others/eoscl-logo.png') }}" border="0" style='width:137px;height:100px;' class="img-responsive center-block img-circle" /></a>
            </div>

            <p style="color:white;font-weigth:bold;padding:30px"><strong>EOSCL</strong> <strong>- Event Ontario Softball Circket League Batting Stats </strong>
                The EOSCL (East Open Super Cricket League) is a cricket league that showcases the skills and talent of cricket players in the region. As part of the league, batting statistics are maintained to keep track of the players' performances and provide insights into their batting abilities.

                The batting stats in the EOSCL Cricket League provide valuable information about each player's performance with the bat. These statistics highlight various aspects of a player's batting performance, including runs scored, matches played, balls faced, boundaries hit (such as fours and sixes), and notable milestones achieved, such as centuries and half-centuries.

                The batting stats are collected and organized for each player participating in the league. They help assess a player's overall performance and contribution to their team. The stats also allow for comparisons between players, highlighting those who consistently perform at a high level and make significant contributions to their team's success.

            </p>

            <ol style="color:white;font-weigth:bold;padding:30px">
                <li>
                    <strong>Total Runs:</strong>

                    This represents the cumuative number of runs scored by a player throughout the league. It showcases a player's ability to accumulate runs and contribute to their team's total score.

                </li>
                <li>
                    <strong>Matches Played:</strong>

                    This stat indicates the number of matches in which a player has participated. It reflects their consistency and availability in the league.

                </li>
                <li>
                    <strong>Balls Faced:</strong>

                    This represents the number of deliveries a player has faced while batting. It provides insights into their ability to spend time at the crease and handle the opposition's bowling attack.

                </li>
                <li>
                    <strong>Boundaries</strong>

                    This includes the number of fours and sixes hit by a player. It demonstrates their power-hitting ability and their capability to find the gaps in the field.

                </li>
                <li>
                    <strong>Centuries and Half-Centuries:</strong>

                    These stats track the number of centuries (100 or more runs) and half-centuries (50 to 99 runs) scored by a player. It signifies their ability to convert good starts into substantial innings and make significant contributions to their team's success.

                </li>
            </ol>
            <p style="color:white;font-weigth:bold;padding:30px">
                By analyzing these batting stats, fans, coaches, and team management can identify standout performers, evaluate player form, and make informed decisions about team composition and strategy. It also provides a historical record of a player's achievements, allowing them to track their progress over time and set personal milestones.

                The EOSCL Cricket League batting stats play a crucial role in recognizing and appreciating the batting prowess of players, fostering healthy competition, and enhancing the overall cricketing experience for fans, players, and stakeholders associated with the league.
            </p>

        </div>
    </div>

    <div class="sta-content">
        <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('fielding_states_submit')}}">
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
                                    <option>Teams</option>
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
                                <select name="teams" id="teams" class="form-control">
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
                                <select name="teams" id="teams" class="form-control">
                                    <option>Match Type</option>
                                    @foreach($header_teams as $team_id => $team_name)
                                    <option value="{{ $team_id }}">{{ $team_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


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

                <div class="table-responsive about-table">

                    <div id="webrecordtable_wrapper" class="dataTables_wrapper no-footer">
                        <div id="webrecordtable_filter" class="dataTables_filter"><label>Search:<input type="search" class="" placeholder="" aria-controls="webrecordtable"></label></div>
                        <table class="table table-striped table-active2 playersData sortable dataTable no-footer" role="grid" aria-describedby="webrecordtable_info">

                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 30px;">#<p><a href="#"></a></p>
                                    </th>
                                    <th class="width sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Player : activate to sort column ascending" style="width: 80px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 1);return false;">Player<p><a href="#"></a></p></a></th>
                                    <th class="width sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Team : activate to sort column ascending" style="width: 80px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 2);return false;">Team<p><a href="#"></a></p></a></th>
                                    <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Matches <p><a href="#"></a></p></a></th>
                                    <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Catches <p><a href="#"></a></p></a></th>
                                    <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Stumpings <p><a href="#"></a></p></a></th>
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
                                    <td>{{ isset($match_count_player[$data['id']]) ? $match_count_player[$data['id']] : '0' }}</td>
                                    <td>{{ isset($match_count_player[$data['id']]) ? $match_count_player[$data['id']] : '0' }}</td>
                                    <td>{{$balls_faced[$data['id']]}}</td>
                                    <td> {{$player_runs[$data['id']]}}</td>
                                    <!-- <td>{{$fours[$data['id']]['fours']}}</td>
                                    <td>{{$sixes[$data['id']]}}</td> -->
                                    <!-- <td>{{$fifty[$data['id']]}}</td>
                                    <td>{{$hundreds[$data['id']]}}</td>
                                    <td>{{$higest_score[$data['id']]}}</td>
                                    @if($balls_faced[$data['id']] > 0)
                                    <td>{{ number_format(($player_runs[$data['id']] / $balls_faced[$data['id']]) * 100, 2) }}</td>
                                    @else
                                    <td>0</td>
                                    @endif
                                    @if($match_count_player[$data['id']] > 0)
                                    <td>{{ number_format($player_runs[$data['id']] / $match_count_player[$data['id']], 2)}}</td>
                                    @else
                                    <td>0</td>
                                    @endif -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="dataTables_info" id="webrecordtable_info" role="status" aria-live="polite">Showing 1 to 200 of 200 entries</div>
                        <div class="dataTables_paginate paging_simple_numbers" id="webrecordtable_paginate"><a class="paginate_button previous disabled" aria-controls="webrecordtable" data-dt-idx="0" tabindex="-1" id="webrecordtable_previous">Previous</a><span><a class="paginate_button current" aria-controls="webrecordtable" data-dt-idx="1" tabindex="0">1</a></span><a class="paginate_button next disabled" aria-controls="webrecordtable" data-dt-idx="2" tabindex="-1" id="webrecordtable_next">Next</a></div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@stop