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

												@foreach($teamid as $data)
													<img src="https://eoscl.ca/admin/public/Team/{{$data->id}}.png" class="img-responsive img-circle center-block" style="width: 120px; height: 120px;">
@endforeach

												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-10">
										<div class="team-text-in text-left">
											<h4 style="margin-top: 0px;">{{$teamData[0]->name}}
												(<a href="">{{$tournament[$tournamentData]??''}}</a>)
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
												Gurpreet Singh</p>
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
										<li ><a href="{{ url('team-view', $team_id_data . '_' . $tournament_ids)  }}"  >Team
												Info</a></li>
										<li ><a href="{{ url('team_result', $team_id_data . '_' . $tournament_ids)  }}">Results</a></li>
										<li ><a href="{{ url('team_schedule', $team_id_data . '_' . $tournament_ids)  }}">Schedule</a></li>
										<li ><a href="{{ url('team_batting', $team_id_data . '_' . $tournament_ids)  }}">Batting</a></li>
										<li class="active"><a href="{{ url('team_bowling', $team_id_data . '_' . $tournament_ids)  }}">Bowling</a></li>
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
.width { min-width:30px !important; padding: 10px 5px !important; }
.spa { min-width:30px !important; padding: 10px 5px !important; }
.sorting_1 { min-width:30px !important; padding: 10px 5px !important; }
.sorting_asc { min-width:30px !important; padding: 10px 5px !important; }
.odd>th { min-width:30px !important; padding: 10px 5px !important; }
.even>th { min-width:30px !important; padding: 10px 5px !important; }
th { min-width:30px !important; padding: 10px 5px !important; }
.dt-buttons{float:right}
</style>

<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/jszip.min.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/pdfmake.min.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/vfs_fonts.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/buttons.html5.min.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/buttons.print.min.js"></script>

<div class="about-table table-responsive" id="tab1default">
	<div id="tableBattingRecords_wrapper" class="dataTables_wrapper no-footer"><table class="table dataTable no-footer" id="tableBattingRecords" role="grid">
    <thead> 
                        <tr role="row">
                        <th class="sorting_asc" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 30px;">#<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
                        <th class="width sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Player : activate to sort column ascending" style="width: 208px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 1);return false;">Player<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="width sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Team : activate to sort column ascending" style="width: 210px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 2);return false;">Team<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Mat<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Inns: activate to sort column ascending" style="width: 31px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 4);return false;">Inns<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Overs: activate to sort column ascending" style="width: 41px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 5);return false;">Overs<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Runs: activate to sort column ascending" style="width: 36px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 6);return false;">Runs<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Wkts: activate to sort column ascending" style="width: 36px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 7);return false;">Wkts<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Ave: activate to sort column ascending" style="width: 45px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 8);return false;">Ave<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Econ: activate to sort column ascending" style="width: 36px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 9);return false;">Econ<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="SR: activate to sort column ascending" style="width: 36px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 10);return false;">SR<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Hat-trick: activate to sort column ascending" style="width: 59px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 11);return false;">Hat-trick<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Wides: activate to sort column ascending" style="width: 44px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 14);return false;">Wides<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Nb: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 15);return false;">Nb<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th></tr> 
                    </thead>
		<tbody>
		@foreach($playername as  $playerId => $playerName)
			<tr role="row" class="odd">
				<th class="sorting_1">{{ $playerId}}</th>
				<th align="left" title="{{ $playerName }}"><i class="fa fa-user"></i> <b><a href="viewPlayer.do?playerId=1242838&amp;clubId=2565"> {{ $playerName }}</a></b></th>
				<th>
				<table>
					<tbody><tr>
						<td><img src="https://eoscl.ca/admin/public/Team/{{$playerteam[$playerId]}}.png" class="img-responsive img-circle" style="width: 20px; height: 20px;"></td>
						<td>&nbsp;{{$header_teams[$playerteam[$playerId]]}}</td>
					</tr>
				</tbody></table>
				</th>
				@foreach($teamid as $data)
    @if(isset($playermatch[$data->id]))
        <th>{{$playermatch[$data->id]}}</th>
    @endif
@endforeach
@php
                   $player_balls=round(($playerballs[$playerId]?? 0)/6)??0;
				   $player_runs=$playerruns[$playerId]??0;
				   $player_outs=$playerouts[$playerId]??0;
                   @endphp


				<th>{{ isset($matchcount[$playerId]) ? $matchcount[$playerId] : 0 }}</th>

				<th>{{round(($playerballs[$playerId]?? 0)/6)}}</th>


				<th>
                			<b><a class="linkStyle" href="">{{ isset($playerruns[$playerId]) ? $playerruns[$playerId] : 0 }}
               </a></b>
				</th>
				<th>{{$player_outs??0}}</th>
				@if ($player_outs != 0) 
                <th>{{number_format($player_runs/$player_outs??0,2)}}</th>
				@else
				<th>0</th>
				@endif
				@if (($player_balls??0) != 0) 
                <th>{{number_format($player_runs/$player_balls??0,2)}}</th>
				@else
				<th>0</th>
				@endif
			
				
				@if (isset($player_balls) && isset($player_outs) && $player_outs != 0)
    <th>{{ number_format($player_balls / $player_outs, 2) }}</th>
@else
    <th>0</th>
@endif



    <th>
        {{ isset($hatricks[$playerId][0]) ? $hatricks[$playerId][0] : 0 }}
    </th>




				<th>{{$playerwide[$playerId]??0}}</th>
				<th>{{$playernoball[$playerId]??0}}</th>
			</tr>
			@endforeach
           </tbody>
	</table></div>
	</div>
<script type="text/javascript">

$(function () {
    $("#tableBattingRecords").DataTable({"bPaginate": false, "bFilter": false, "bInfo": false });
});


    var tableOffset = $("#tableBattingRecords").offset().top;
    var $header = $("#tableBattingRecords > thead").clone();
    var $fixedHeader = $("#header-fixed").append($header);

    $(window).bind("scroll", function() {
        var offset = $(this).scrollTop();
        
        if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
            $fixedHeader.show();
        }
        else if (offset < tableOffset) {
            $fixedHeader.hide();
        }
        
        $("#header-fixed th").each(function(index){
            var index2 = index;
            $(this).width(function(index2){
                return $("#tableBattingRecords th").eq(index).width();
            });
        });
        $("#header-fixed").width($("#tableBattingRecords").width());
        
    });
    
</script></div>
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