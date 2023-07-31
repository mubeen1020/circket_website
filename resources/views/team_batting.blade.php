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
												<span>Player Count</span> :
												{{$teamPlayerCount}}
											</p>
											<p>
											@if($tournamentgrounds) 
												<span>Home Ground</span> : <b><a href="">{{$ground[$tournamentgrounds->ground_id]}}</a></b>
												@else
												""
												@endif
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
										<li class="active"><a href="{{ url('team_batting', $team_id_data . '_' . $tournament_ids)  }}">Batting</a></li>
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
				<th class="sorting_asc" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 27px;">#</th>
            <th class="width sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="Player : activate to sort column ascending" style="width: 227px;">Player <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="width sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="Team : activate to sort column ascending" style="width: 195px;">Team <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="Mat : activate to sort column ascending" style="width: 27px;">Mat <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="Ins : activate to sort column ascending" style="width: 27px;">Ins <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="Runs : activate to sort column ascending" style="width: 33px;">Runs <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="Balls : activate to sort column ascending" style="width: 32px;">Balls <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="Avg : activate to sort column ascending" style="width: 41px;">Avg <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="Sr : activate to sort column ascending" style="width: 50px;">Sr <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th><th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="Hs : activate to sort column ascending" style="width: 28px;">Hs <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="100's : activate to sort column ascending" style="width: 35px;">100's <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="50's : activate to sort column ascending" style="width: 27px;">50's <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="6's : activate to sort column ascending" style="width: 27px;">6's <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
            <th class="spa sorting" tabindex="0" aria-controls="tableBattingRecords" rowspan="1" colspan="1" aria-label="4's : activate to sort column ascending" style="width: 27px;">4's <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th></tr>
		</thead>
		<tbody>
		@foreach($playername as $playerId => $playerName)
    <tr role="row" class="odd">
        <th class="sorting_1">{{ $playerId }}</th>
		
        <th align="left" title="{{ $playerName }}"><i class="fa fa-user"></i> <b><a href="#">{{ $playerName }}</a></b></th>
        <th>
            <table>
                <tbody>
                    <tr>
                        <td><img src="https://eoscl.ca/admin/public/Team/{{$playerteam[$playerId]}}.png" class="img-responsive img-circle" style="width: 20px; height: 20px;"></td>
                        <td>&nbsp;{{$header_teams[$playerteam[$playerId]]}}</td>
                    </tr>
                </tbody>
            </table>
        </th>
	
		@foreach($teamid as $data)
		@if(count($playermatch)>0)
    @if(isset($playermatch[$data->id]))
        <th>{{$playermatch[$data->id]}}</th>
    @endif
	@else
<th>0 </th>
 @endif
@endforeach

        <th>{{ isset($matchcount[$playerId]) ? $matchcount[$playerId] : 0 }}</th>
        <th>
            <b><a class="linkStyle" href="">{{$playerruns[$playerId]??0}}</a></b>
        </th>
        <th>{{$playerballs[$playerId]??0}}</th>
		@if ($playerouts[$playerId]??0 != 0) 
                <th>{{number_format($playerruns[$playerId]/$playerouts[$playerId],2)}}</th>
				@else
				<th>0</th>
				@endif

				@if ($playerballs[$playerId]??0 != 0) 
                <th>{{number_format(($playerruns[$playerId]/$playerballs[$playerId])*100,2)}}</th>
				@else
				<th>0</th>
				@endif
        <th>{{$higest_score[$playerId]??0}}</th>
	
        <th>{{$playerHundreds[$playerId]??0}}</th>
	
        <th>{{$playerfifty[$playerId]??0}}</th>
        <th>{{$playersix[$playerId]??0}}</th>
        <th>{{$playerfour[$playerId]??0}}</th>
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