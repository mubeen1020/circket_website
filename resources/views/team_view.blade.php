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

<!-- section 2 start -->

<div class="all-tab-table team">
			<div class="container p-sm-0">
				<div class="score-tab">
					<div class="complete-list">
						<div class="panel with-nav-tabs panel-default">
								<div class="panel-heading tabs-team">
								
									<ul class="nav nav-tabs">
										<li class="active"><a href="{{ url('team-view', $team_id_data)  }}"  >Team
												Info</a></li>
										<li ><a href="{{ url('team_result', $team_id_data. '_' .$tournament_ids)  }}">Results</a></li>
										<li><a href="{{ url('team_schedule', $team_id_data. '_' . $tournament_ids)  }}">Schedule</a></li>
										<li><a href="{{ url('team_batting', $team_id_data. '_' . $tournament_ids)  }}">Batting</a></li>
										<li><a href="{{ url('team_bowling', $team_id_data. '_' . $tournament_ids)  }}">Bowling</a></li>
										<li><a href="{{ url('team_fielding', $team_id_data. '_' . $tournament_ids)  }}">Fielding</a></li>
										<li><a href="{{ url('team_ranking', $team_id_data . '_' . $tournament_ids)  }}">Ranking</a></li>
										</ul>
                                    
								</div>
								<!-- <div class="panel-body">
									<div class="tab-content">
						<div class="tab-pane fade in active" id="team">
											
											<p></p>
											<div class="row" style="margin-top: 15px;">
											<div class="border-heading col-sm-6">
												<h5 style="cursor: pointer; background: rgb(253, 126, 93);" id="playerTeamTab" onclick="showPlayerDiv('allPlayersDiv', 'allTeamOfficialDiv', 'playerTeamTab', 'OfficialTeamTab')">Players</h5>
										<h5 style="cursor: pointer; background: gray;" id="OfficialTeamTab" onclick="showPlayerDiv('allTeamOfficialDiv', 'allPlayersDiv', 'OfficialTeamTab', 'playerTeamTab')">Team Officials</h5>
												</div>
											<div class="col-sm-6 col-xs-12">
													 <div class="search-play" style="margin-left: 65px; width: 50%;">
					  <input type="text" class="form-control" onkeyup="searchPlayer()" id="searchplayer" name="searchplayer" placeholder="Search Player">
						</div>
							<div class="text-right" style="margin-top :-32px;">
						<div class="btn-group btn-group-sm" role="group">
						 <img alt="Download as PDF" title="Download as PDF" class="pdfBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/pdf.png">&nbsp;
						<img alt="Download as CSV" title="Download as CSV" class="csvBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/csvicon.png">&nbsp;
				<img alt="Download as Excel" title="Download as Excel" class="excelBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/excel.png">	
				<img alt="Print" title="Print" class="printBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/print.png">&nbsp; -->
						</div>
					</div> 
						<div>					

						<div id="playersearchdiv">
											</div>
											</div>
											</div>
										<div class="row" id="allPlayersDiv">
										@if(count($team_resultData) > 0)
										@foreach($team_resultData as $teamPlayer)
										<div class="col-sm-3 col-xs-6" id="Ajmer S">
                                        	<div class="team-player-all" style="height: 340px;">
                                            	<div class="team-player-image-all">
                                                	<div class="team-player-image">
<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
$imageSrc = "https://eoscl.ca/admin/public/Player/" . $teamPlayer['player_id'] . ".jpg";
$altText = "Player ID: " . $teamPlayer['player_id'];

// Check if the image URL returns a 404 error
$headers = get_headers($imageSrc);
if (strpos($headers[0], '404') !== false) {
    $imageSrc = "https://cricclubs.com/documentsRep/profilePics/no_image.png";
    $altText = "No Image Available";
}
?>

<img src="<?php echo $imageSrc; ?>" alt="<?php echo $altText; ?>" class="img-responsive center-block" style="width:240px; height:200px; object-fit: cover;">



                                                    </div>
                                                    </div>
                                                <div class="team-player-text text-center">
                                                	<h4>{{ $player[$teamPlayer['player_id']] }}<img alt="Not Verified" title="Not Verified" src="https://cdn-icons-png.flaticon.com/512/7595/7595571.png" style="width: 30px;height: 30px;margin: 0px;"> </h4>
                                                   <h5></h5>
												   <a style="cursor:pointer;" href="{{ route('playerview', $teamPlayer->player_id) }}" class="btn btn-team">View Profile <i class="fa fa-chevron-circle-right"></i></a>

														</div>
													</div>
										</div>
										@endforeach
										@else
                                       <p>No players found.</p>
                                        @endif
									
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
<!-- section 2 end -->
@stop