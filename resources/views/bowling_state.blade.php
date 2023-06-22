@extends('default')
@section('content')

<div class="sta-main" style="background: white !important; overflow:auto; padding-bottom:200px;overflow-x:hidden;overflow-y:hidden;">

<div style="display: none;">
<label id="lblhide"></label>

</div>

	<div class="sta-sidemenu" style="top: 13px;background-color:black;over-flow:auto">
		<h4>Bowling Stats</h4>
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">Click here for More <i class="fa fa-sort-desc" aria-hidden="true" style=" float: right; margin-right: 2%;"></i></button>
		<div class="collapse navbar-collapse" id="myNavbar">
		<div class="league-image">
                        	<a href="{{ route('home')}}"><img
                                                    src="{{ asset('utilsv2/img/others/eoscl-logo.png') }}" border="0"
                                                    style='width:137px;height:100px;'
                                                    class="img-responsive center-block img-circle" /></a>
										</div>

                                        <p style="color:white;font-weight:bold;padding:30px">
    <strong>EOSCL - Event Ontario Softball Cricket League Bowling Stats</strong>
    The EOSCL (East Open Super Cricket League) is a cricket league that showcases the skills and talent of cricket players in the region. As part of the league, bowling statistics are maintained to keep track of the players' performances and provide insights into their bowling abilities.

    The bowling stats in the EOSCL Cricket League provide valuable information about each player's performance with the ball. These statistics highlight various aspects of a player's bowling performance, including wickets taken, matches played, overs bowled, economy rate, and notable milestones achieved, such as five-wicket hauls and hat-tricks.

    The bowling stats are collected and organized for each player participating in the league. They help assess a player's overall performance and contribution to their team. The stats also allow for comparisons between players, highlighting those who consistently perform at a high level and make significant contributions to their team's success.
</p>

<ol style="color:white;font-weight:bold;padding:30px">
    <li>
        <strong>Total Wickets:</strong>
        This represents the cumulative number of wickets taken by a player throughout the league. It showcases a player's ability to dismiss opposing batsmen and contribute to their team's success.
    </li>
    <li>
        <strong>Matches Played:</strong>
        This stat indicates the number of matches in which a player has participated. It reflects their consistency and availability in the league.
    </li>
    <li>
        <strong>Overs Bowled:</strong>
        This represents the number of overs a player has bowled. It provides insights into their endurance and ability to maintain pressure on the batsmen.
    </li>
    <li>
        <strong>Economy Rate:</strong>
        The economy rate is the average number of runs conceded by a bowler per over. It indicates how economically a bowler is bowling, with lower economy rates being more favorable.
    </li>
    <li>
        <strong>Five-Wicket Hauls and Hat-Tricks:</strong>
        These stats track the number of times a player has taken five or more wickets in a single innings and the number of hat-tricks achieved. They signify exceptional bowling performances and the ability to dismantle the opposition's batting lineup.
    </li>
</ol>

<p style="color:white;font-weight:bold;padding:30px">
    By analyzing these bowling stats, fans, coaches, and team management can identify standout performers, evaluate player form, and make informed decisions about team composition and strategy. It also provides a historical record of a player's achievements, allowing them to track their progress over time and set personal milestones.

    The EOSCL Cricket League bowling stats play a crucial role in recognizing and appreciating the bowling prowess of players, fostering healthy competition, and enhancing the overall cricketing experience for fans, players, and stakeholders associated with the league.
</p>

                    	
		</div>
		</div>

	<div class="sta-content">
	<form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('bowling_state_submit')}}">
                @csrf
		<section class="sta-search-filter">
			<div class="row">
			
			<div class="col-sm-2 col-xs-6">
						<div class="form-group">
						<div class="custom-select">
						<select name="year"  id="year" class="form-control" >
								<option value="">All Years</option>
								@for ($year = date('Y'); $year >= 2015; $year--)
								<option <?php if(isset($_POST['year']) && $_POST['year']== $year){ echo 'selected'; } ?> value="{{$year}}">{{$year}}</option>
                                        @endfor
								</select>
						</div>
						</div>
					</div>
			
			<div class="col-sm-2 col-xs-6">
					<div class="form-group">
						<div class="custom-select">
						<select name="tournament"  id="tournament" class="form-control" >
							<option value="">Career - All Series</option>
							@foreach($tournamentdata as $tournament_id => $tournament_name)
							<option <?php if(isset($_POST['tournament']) && $_POST['tournament']== $tournament_id){ echo 'selected'; } ?> value="{{$tournament_id}}">{{$tournament_name}}</option>
                                       @endforeach
								</select>
						</div>
					</div>
				</div>
			<!-- <div class="col-sm-2 col-xs-6">
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
				</div> -->
				
			
				<div class="col-sm-2 col-xs-6">
					<div class="form-group">
						<div >
							
						<button class="btn btn-primary" >Search</button>
						</div>
					</div>
				</div>

				</div>
		</section>
		</form>
		<div id="reloaddiv">
		<div class="sta-tables">		
			<div class="flex-top">
				<div class="filter-title">Most Wickets</div>
				<div class="text-right">
				</div>
			</div>

			<div class="table-responsive about-table">
				
					<div id="webrecordtable_wrapper" class="dataTables_wrapper no-footer"><div id="webrecordtable_filter" class="dataTables_filter"><label>Search:<input type="search" class="" placeholder="" aria-controls="webrecordtable"></label></div>
					<table class="table table-striped table-active2 playersData sortable dataTable no-footer"  role="grid" aria-describedby="webrecordtable_info">
						
						<thead> 
                        <tr role="row">
                        <th class="sorting_asc" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 30px;">#<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></th>
                        <th class="width sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Player : activate to sort column ascending" style="width: 208px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 1);return false;">Player<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="width sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Team : activate to sort column ascending" style="width: 210px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 2);return false;">Team<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 30px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Mat <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Inns: activate to sort column ascending" style="width: 31px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 4);return false;">Inns<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Overs: activate to sort column ascending" style="width: 41px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 5);return false;">Overs <p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Runs: activate to sort column ascending" style="width: 36px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 6);return false;">Runs</p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Wkts: activate to sort column ascending" style="width: 36px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 7);return false;">Wkts<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Ave: activate to sort column ascending" style="width: 45px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 8);return false;">Econ<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
						<th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Inns: activate to sort column ascending" style="width: 31px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 4);return false;">Avg<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Overs: activate to sort column ascending" style="width: 41px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 5);return false;">SR<p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                        <th class="spa sorting" tabindex="0" aria-controls="tableBowlingRecords" rowspan="1" colspan="1" aria-label="Runs: activate to sort column ascending" style="width: 36px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 6);return false;">Hat-Ricks</p><a href="#"><i class="fa-solid fa-arrow-down-short-wide"></i></a></p></a></th>
                       </tr> 
                    </thead>
						<tbody>
				@foreach($getresult as $key => $data)
							<tr role="row" class="even">
								<td class="sorting_1">{{$key+1}}</td>
								<td align="left" title="Rajwant Singh" style="text-align: left;width: 90px;">
									<div>
										<div class="player-img" style="background-image: url('pic.jpg');"></div>
										<a href="" >{{$player[$data['bowler_id']]}}</a><br></div>
								</td>
						<td style="text-align: left;font-size: smaller;">{{$header_teams[$data['team_id']]}}</td>
								<td>{{$match_count[$data->team_id]??0}}</td>
								<td>{{$inningsCount[$data->bowler_id]??0}}</td>
								<td>{{ isset($bowlerballs[$data->bowler_id]) ? round($bowlerballs[$data->bowler_id] / 6) : 0 }}</td>
								<td>{{$bowlerruns[$data->bowler_id]??0}}</td>
								<td>{{$bowlerwickets[$data->bowler_id]??0}}</td>
								@if ($data->total_overs != 0 && isset($bowlerballs[$data->bowler_id]) && $bowlerballs[$data->bowler_id] != 0)
    @php
        $bowlerOvers = round($bowlerballs[$data->bowler_id] / 6);
        $bowlerEconomy = $bowlerOvers != 0 ? number_format($bowlerruns[$data->bowler_id] / $bowlerOvers, 2) : 0;
    @endphp
    <td>{{ $bowlerEconomy }}</td>
@else
    <td>0</td>
@endif


				@if (isset($bowlerwickets[$data->bowler_id]) && $bowlerwickets[$data->bowler_id] != 0)
						<td>{{ number_format($bowlerruns[$data->bowler_id] / $bowlerwickets[$data->bowler_id], 2) }}</td>
					@else
						<td>0</td>
					@endif
				@if (isset($bowlerwickets[$data->bowler_id]) && $bowlerwickets[$data->bowler_id] != 0)
					<td>{{ number_format(round($bowlerballs[$data->bowler_id] / 6) / $bowlerwickets[$data->bowler_id], 2) }}</td>
				@else
					<td>0</td>
				@endif

								<td>
                                <?php
				$hat_tricks = 0;
				$current_overs = 0;
				$current_wickets = 0;
				if ($data->bowling_details) {
					foreach($data->bowling_details as $detail) {
					if ($detail->isout) {
						$current_wickets++;
						if ($current_wickets >= 3) {
						$hat_tricks++;
						$current_wickets = 0;
						$current_overs = 0;
						}
					}
					$current_overs = $detail->overnumber;
					}
				}
				echo $hat_tricks;
				?>
                                </td>
							</tr>
						@endforeach
						</tbody>
					</table><div class="dataTables_info" id="webrecordtable_info" role="status" aria-live="polite">Showing 1 to 200 of 200 entries</div><div class="dataTables_paginate paging_simple_numbers" id="webrecordtable_paginate"><a class="paginate_button previous disabled" aria-controls="webrecordtable" data-dt-idx="0" tabindex="-1" id="webrecordtable_previous">Previous</a><span><a class="paginate_button current" aria-controls="webrecordtable" data-dt-idx="1" tabindex="0">1</a></span><a class="paginate_button next disabled" aria-controls="webrecordtable" data-dt-idx="2" tabindex="-1" id="webrecordtable_next">Next</a></div></div>
				
			</div>
		</div>
</div>

	</div>
</div>
@stop