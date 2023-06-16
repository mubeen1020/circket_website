@extends('default')
@section('content')
<!-- head section 1 start -->
<div class="score-top text-center">
	<div class="container">
		<div class="match-summary">
			<div class="row">
				<div class="col-sm-12">
					<div class="match-in-summary">
						<div class="row">
							<div class="col-sm-2">
								<div class="row">
									<div class="col-sm-12">

										<div class="summ-image" id="teamLogo">

											<img src="" class="img-responsive img-circle center-block" style="width: 120px; height: 120px;">

										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-10">
								<div class="team-text-in text-left">
									<h4 style="margin-top: 0px;">{{$teamData[0]->name}}
										(<a href="">{{$tournament[$tournamentData]}}</a>)
									</h4>
									<!--  <p><span>Team Code </span>      :   <span style="text-transform: uppercase">kbu</span></p>-->


									<p>
										<span>Captain </span> :
										@foreach($team_resultData as $data)
										@if ($data['iscaptain'] == 1)
										{{ $player[$data['player_id']] }}
										@endif
										@endforeach

									</p>
									<p>

										<span>Vice Captain</span> :
										Gurpreet Singh
									</p>
									<p>
										<span>Player Count</span> :
										{{$teamPlayerCount}}
									</p>
									<p>
										<span>Home Ground</span> : <b><a href="">Wet n Wild</a></b>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- head section 1 end -->

<div class="all-tab-table team">
	<div class="container p-sm-0">
		<div class="score-tab">
			<div class="complete-list">
				<div class="panel with-nav-tabs panel-default">
					<div class="panel-heading tabs-team">
						<ul class="nav nav-tabs">
							<li><a href="{{ url('team-view', $team_id_data . '_' . $tournament_ids)  }}">Team
									Info</a></li>
							<li><a href="{{ url('team_result', $team_id_data . '_' . $tournament_ids)  }}">Results</a></li>
							<li><a href="{{ url('team_schedule', $team_id_data . '_' . $tournament_ids)  }}">Schedule</a></li>
							<li><a href="#umpiringSchedule" role="tab" data-toggle="tab" onclick="loadView('teamUmpiringSchedule');">Umpiring</a></li>
							<li><a href="{{ url('team_batting', $team_id_data . '_' . $tournament_ids)  }}">Batting</a></li>
							<li><a href="{{ url('team_bowling', $team_id_data . '_' . $tournament_ids)  }}">Bowling</a></li>
							<li><a href="{{ url('team_fielding', $team_id_data . '_' . $tournament_ids)  }}">Fielding</a></li>
							<li><a href="{{ url('team_ranking', $team_id_data . '_' . $tournament_ids)  }}">Ranking</a></li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="tab-content">
							<div class="tab-pane fade " id="team">

								<p></p>

								Loading ...
							</div>
							<div class="tab-pane fade " id="results">

								Loading ...
							</div>
							<div class="tab-pane fade  point-table-all btn-earth" id="schedule">

								Loading ...
							</div>
							<div class="tab-pane fade " id="umpiringSchedule">

								Loading ...
							</div>
							<div class="tab-pane fade in active" id="batting">
								<br>
								<style>
									.width {
										min-width: 30px !important;
										padding: 10px 5px !important;
									}

									.spa {
										min-width: 30px !important;
										padding: 10px 5px !important;
									}

									.sorting_1 {
										min-width: 30px !important;
										padding: 10px 5px !important;
									}

									.sorting_asc {
										min-width: 30px !important;
										padding: 10px 5px !important;
									}

									.odd>th {
										min-width: 30px !important;
										padding: 10px 5px !important;
									}

									.even>th {
										min-width: 30px !important;
										padding: 10px 5px !important;
									}

									th {
										min-width: 30px !important;
										padding: 10px 5px !important;
									}

									.dt-buttons {
										float: right
									}
								</style>

								<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/jquery.dataTables.min.js"></script>
								<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/dataTables.buttons.min.js"></script>
								<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/jszip.min.js"></script>
								<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/pdfmake.min.js"></script>
								<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/vfs_fonts.js"></script>
								<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/buttons.html5.min.js"></script>
								<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/buttons.print.min.js"></script>

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
								<script type="text/javascript">
									$(function() {
										$("#tableBattingRecords").DataTable({
											"bPaginate": false,
											"bFilter": false,
											"bInfo": false
										});
									});


									var tableOffset = $("#tableBattingRecords").offset().top;
									var $header = $("#tableBattingRecords > thead").clone();
									var $fixedHeader = $("#header-fixed").append($header);

									$(window).bind("scroll", function() {
										var offset = $(this).scrollTop();

										if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
											$fixedHeader.show();
										} else if (offset < tableOffset) {
											$fixedHeader.hide();
										}

										$("#header-fixed th").each(function(index) {
											var index2 = index;
											$(this).width(function(index2) {
												return $("#tableBattingRecords th").eq(index).width();
											});
										});
										$("#header-fixed").width($("#tableBattingRecords").width());

									});
								</script>
							</div>
							<div class="tab-pane fade " id="bowling">

								Loading ...
							</div>
							<div class="tab-pane fade " id="fielding">

								Loading ...
							</div>
							<div class="tab-pane fade " id="ranking">

								Loading ...
							</div>
							<div class="tab-pane fade " id="stats">

								Loading ...
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@stop