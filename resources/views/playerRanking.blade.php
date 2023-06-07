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
                    <div class="text-right">
                        <!-- <div class="btn-group btn-group-sm" role="group">
						<img alt="Download as PDF" title="Download as PDF" class="pdfBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/pdf.png">&nbsp;
						<img alt="Download as CSV" title="Download as CSV" class="csvBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/csvicon.png">&nbsp;
				<img alt="Download as Excel" title="Download as Excel" class="excelBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/excel.png">	
				<img alt="Print" title="Print" class="printBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/print.png">&nbsp;
						</div> -->
                    </div>
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
                                    <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Fielding <p><a href="#"></a></p></a></th>
                                    <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Other <p><a href="#"></a></p></a></th>
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
                                            <a href="viewPlayer.do?playerId=1375981&amp;clubId=2565"> {{$player[$data->bowler_id]}}</a><br>
                                        </div>
                                    </td>
                                    <td style="text-align: left;font-size: smaller;">{{$teams[$data->team_id]}}</td>
                                    <td>{{  $data->total_matches   }}</td>
                                    <td>{{ $data->catch }}</td>
                                    <td>{{ $data->stump }}</td>
                                    <td> {{ $data->catch + $data->stump  }} </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <table id="header-fixed" style="position: fixed; top: 0px; background-color: white; width: 1110px; display: none;" class="sortable dataTable">
                        <thead>
                            <tr>
                                <th style="width: 51px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 0);return false;">#<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th>
                                <th class="width" style="text-align: left !important; width: 194px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 1);return false;">Player <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th>
                                <th class="width" style="text-align: left !important; width: 229px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 2);return false;">Team <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th>
                                <th class="spa" style="width: 100px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Matches <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th>
                                <th class="spa" style="width: 90px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 4);return false;">Batting <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th>
                                <th class="spa" style="width: 95px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 5);return false;">Bowling <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th>
                                <th class="spa" style="width: 95px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 6);return false;">Fielding <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th>
                                <th class="spa" style="width: 79px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 7);return false;">Other <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th>
                                <th class="spa" style="width: 91px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 8);return false;">MOM # <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th>
                                <th class="spa" style="width: 76px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 9);return false;">Total <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@stop