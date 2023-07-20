@extends('default')
@section('content')

<div class="holder point">
    	<div class="container p-0-sm">
        	<div class="point-table-all">
            <div class="series-drop">
            	<div class="row">
            	<div class="col-sm-6">
                    	<div class="border-heading">
                            <h5 style="text-align: left;"><a href="#"><font color="white">Point Table</font></a></h5>
                        </div>
                    </div>
                     <div class="exportOptions-panel" style="float:right ;position: relative; left: -20px;">
                     &nbsp;
					
                     
                   </div>
                   </div>
                <div class="row"> 
				<form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('pointtable_submit')}}">
                	<div class="col-lg-3 col-sm-6">
					
                @csrf
						<div class="dropdown">
						<select name="year"  id="year" class="form-control" >
								<option value="">All Years</option>
								@for ($year = date('Y'); $year >= 2015; $year--)
								<option <?php if(isset($_POST['year']) && $_POST['year']== $year){ echo 'selected'; } ?> value="{{$year}}">{{$year}}</option>
                                        @endfor
								</select>
						</div>
					</div>
                    <div class="col-lg-3 col-sm-6 ">
                    	<div class="dropdown">
						<select name="tournament"  id="tournament" class="form-control" >
							<option value="">Career - All Series</option>
							@foreach($tournamentdata as $tournament_id => $tournament_name)
							<option <?php if(isset($_POST['tournament']) && $_POST['tournament']== $tournament_id){ echo 'selected'; } ?> value="{{$tournament_id}}">{{$tournament_name}}</option>
                                       @endforeach
								</select>
								
								</div>
						

                    </div>

                    <div class="col-lg-3 col-sm-6 ">
                    	<div class="dropdown">
						<select name="group"  id="group" class="form-control" >
							<option value="">Career - All Groups</option>
							@foreach($groupdata as $group_id => $group_name)
							<option <?php if(isset($_POST['group']) && $_POST['group']== $group_id){ echo 'selected'; } ?> value="{{$group_id}}">{{$group_name}}</option>
                                       @endforeach
								</select>
								
								</div>
						

                    </div>

					<div class="col-sm-2 col-xs-6">
					<div class="form-group">
						<div >
							
						<button class="btn btn-primary" >Search</button>
						</div>
					</div>
				</div>
					</form>
					<div class="col-lg-3 col-sm-6">
</div>
<div class="col-lg-3 col-sm-6" style="display:none">
    <button style="color: black; font-size: 20px; float:right;" class="fa fa-info-circle" aria-hidden="true" onclick="showPointsTableInfo('pointsTable_popup')" title="Points Table Calculation"></button>
</div>

<div class="modal fade common-modal-cls" id="pointsTable_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title text-center">Points Table Calculation</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-lg-5">
                <p><b>PTS - </b>(Number of Wins x Win Points) + (Number of Ties x Tie Points) + (Number of NR x Abandon Points) + Bonus Points </p>&nbsp;
                <br>
                <p><b>WIN % - </b>[(Number of Wins x Win Points) + (Number of Ties x Tie Points) ]/[(Total Number of Matches - Abandoned Matches) x Win points]</p>
                <br>
                <p><b>NET RR - </b>[(FOR - AGAINST)]  </p>&nbsp;
                <br>
                <p><b>FOR  - </b>[(Number of Total runs Scored) ]/[(Number of Total Balls Faced)]</p>
                <br>
                <p><b>AGAINST  - </b>[(Number of Total runs Conceded) ]/[(Number of Total Balls Bowled)]</p>
                <br>
            </div>
        </div>
    </div>
</div>

<script>
    function showPointsTableInfo(modalId) {
        $('#' + modalId).modal('show');
    }
</script>

				</div>
                </div>
              <div class="button-pool text-left" style="margin-top: 0px!important;">
             </div>
               <!-- Combined Loop Starts -->
             	<!-- Combined Loop Ends -->  

<!-- Groups Loop Starts -->             
             <div class="about-table table-responsive">

                <table id="point-table" class="table btn-earth list-table table-hover" style="margin-top: 0px;"> 
					<thead>
						<tr>
							<th>#</th>
							<th style="text-align: left !important;">TEAM</th>
							<th>MAT</th>
							<th>WON</th>
							<th>LOST</th>
							<th>TIE</th>
							<th>PTS</th>
							<th>NET RR</th>
							
							</tr>
					</thead>
					<tbody>
	
						
						
					@php
usort($result, function($a, $b) {
    if ($a['net_rr'] == $b['net_rr']) {
        return $b['teambonusPoints'] <=> $a['teambonusPoints'];
    }
    return $b['net_rr'] <=> $a['net_rr'];
});
@endphp

@foreach($result as $key => $item)
    <tr>
        <th>{{ $key + 1 }}</th>
        <th style="text-align: left !important;"><img src="https://eoscl.ca/admin/public/Team/{{ $item['team_id'] }}.png" class="left-block img-circle" style="width: 25px;height: 25px;">
        <a href="#">{{ $item['team_name'] }}</a></th>
        <th>{{ $item['total_matches'] }}</th>
        <th>{{ $item['wins'] }}</th>
        <th>{{ $item['losses'] }}</th>
        <th>{{ $item['draws'] }}</th>
        <th>{{ $item['teambonusPoints'] }}</th>
        <th>{{ number_format($item['net_rr'], 2) }}</th>
    </tr>
@endforeach


						</tbody>
				</table>
				</div>
				<br>
				<!-- Groups Loop Ends -->             
<!-- Super Teams Loop -->             
				<!-- Super Teams Loop Ends -->				
				<br>
            </div>
            </div>
            
            </div>


<script type="text/javascript">
function get_point_table(tornament_season_id, type) {
    console.log(" I called first_item_type");
    $('#tour_tab_id_'+tornament_season_id+'_'+type).css('background-color', '#ccc');

    $.ajax({
        url: "{{ url('/get_point_table/')}}/" + tornament_season_id + '/' + type,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var match_count = data[0];
            var team_name = data[1];
            var point_table_data = document.getElementById('point_table');
            point_table_data.innerHTML = '';

            if (data.length === 0) {
                point_table_data.innerHTML = '<tr><td colspan="7">Empty list</td></tr>';
            } else {
                data.sort((a, b) => {
                // Sort by teambonusPoints in descending order
                if (a.teambonusPoints > b.teambonusPoints) return -1;
                if (a.teambonusPoints < b.teambonusPoints) return 1;
                // If teambonusPoints are equal, sort by net_rr in descending order
                if (a.teambonusPoints === b.teambonusPoints) {
                    if (a.net_rr > b.net_rr) return -1;
                    if (a.net_rr < b.net_rr) return 1;
                }
                return 0;
            });
                data.forEach((item, index) => {
                    point_table_data.innerHTML += `
                        <tr>
                            <th>${index + 1}</th>
                            <th>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td><img src="https://eoscl.ca/admin/public/Team/${item.team_id}.png" class="img-responsive img-circle" style="width: 20px; height: 20px;"></td>
                                            <td>&nbsp; <a href="{{ url('team-view/${item.team_id}_${item.tournament_id}') }}">${item.team_name}</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </th>
                            <th><a href="#">${item.total_matches}</a></th>
                            <th>${item.wins}</th>
                            <th>${item.losses}</th>
                            <th>${item.draws}</th>
                            <th style="font-weight: bold;padding-right: 15px; text-align: left;"><a href="#"><span title="">${item.teambonusPoints}</span></a></th>
                          <th>${Number(item.net_rr).toFixed(2)}</th>


                        </tr>
                    `;
                });
            }
        },
    });
    get_season_group(tornament_season_id);
    get_season_tournament(tornament_season_id);
}


function get_season_tournament(season_id) {
    $.ajax({
        url: "{{ url('/tournament_name/')}}/" + season_id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const tournaments = document.getElementById('tournamentname');
            tournaments.innerHTML = '';

            if (data.length === 0) {
                tournaments.innerHTML = `
                 
                `;
            } else {
                data.forEach((item, index) => {
                    if (index === 0) {
                        tournaments.innerHTML += `
                            <li style="display: flex; flex-direction: row; list-style-type: none;">
                                <a href="#92tabGroup1" data-toggle="tab" onclick="get_season_group(${item.tournamentID})" style="padding: 10px; background-color: #f1f1f1; border: 1px solid #ccc; border-bottom: none; cursor: pointer;" class="selected-tournament">
                                    ${item.tournamentname}
                                </a>
                            </li>
                        `;
                        // Call the function for the first tournament
                        get_season_group(item.tournamentID);
                    } else {
                        tournaments.innerHTML += `
                            <li style="display: flex; flex-direction: row; list-style-type: none;">
                                <a href="#92tabGroup1" data-toggle="tab" onclick="get_season_group(${item.tournamentID})" style="padding: 10px; background-color: #f1f1f1; border: 1px solid #ccc; border-bottom: none; cursor: pointer;">
                                    ${item.tournamentname}
                                </a>
                            </li>
                        `;
                    }
                });
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}



function get_season_group(tornament_season_id) {
    get_tournamnet_all_data(tornament_season_id);
    $.ajax({
        url: "{{ url('/get_season_group/')}}/" + tornament_season_id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const groups = document.getElementById('groupname');
            groups.innerHTML = '';

            if (data.length === 0) {
                groups.innerHTML = `
                    <a href="#92tabGroup1" data-toggle="tab">All</a>
                `;
            } else {
                data.forEach((item, index) => {
                    if (index === 0) {
                        groups.innerHTML += `
                            <a href="#92tabGroup${index+1}" data-toggle="tab" class="selected" onclick="get_group_team(${item.group_id}, ${tornament_season_id})">${item.groupname}</a>
                        `;
                        get_group_team(item.group_id, tornament_season_id);
                    } else {
                        groups.innerHTML += `
                            <a href="#92tabGroup${index+1}" data-toggle="tab" onclick="get_group_team(${item.group_id}, ${tornament_season_id})">${item.groupname}</a>
                        `;
                    }
                });
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}


function get_group_team(group_id, tournamnet_id) {
    $.ajax({
        url: "{{ url('/get_group_team/')}}/" + group_id + '/' + tournamnet_id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            const point_table_data = document.getElementById('point_table');
            point_table_data.innerHTML = '';
            data.sort((a, b) => {
                // Sort by teambonusPoints in descending order
                if (a.teambonusPoints > b.teambonusPoints) return -1;
                if (a.teambonusPoints < b.teambonusPoints) return 1;
                // If teambonusPoints are equal, sort by net_rr in descending order
                if (a.teambonusPoints === b.teambonusPoints) {
                    if (a.net_rr > b.net_rr) return -1;
                    if (a.net_rr < b.net_rr) return 1;
                }
                return 0;
            });
            data.forEach((item, index) => {
                point_table_data.innerHTML += `
                    <tr>	
                        <th>${index + 1}</th> 
                        <th>
                            <table>
                                <tbody>
                                    <tr>
                                        <td><img src="https://eoscl.ca/admin/public/Team/${item.team_id}.png" class="img-responsive img-circle" style="width: 20px; height: 20px;"></td>
                                        <td>&nbsp; <a href="{{ url('team-view/${item.team_id}_${item.tournament_id}') }}">${item.team_name}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </th>
                        <th><a ">${item.total_matches}</a></th>
                        <th>${item.wins}</th> 
                        <th>${item.losses}</th>
                        <th>${item.draws}</th>
                        <th style="font-weight: bold;padding-right: 15px; text-align: left;"><a href="#"><span title="">${item.teambonusPoints}</span></a></th> 
                        <th>${Number(item.net_rr).toFixed(2)}</th>

                    </tr>
                `;
            });
        }
    });
}

</script>
@stop