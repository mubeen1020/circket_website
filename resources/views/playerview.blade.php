@extends('default')
@section('content')


<div class="score-top sp text-center">
			<div class="container">
				<div class="match-summary">
					<div class="row">
						<div class="col-sm-12">
							<div class="match-in-summary">
								<div class="row">
                                @foreach($player_data as $playerData)
    <div class="col-sm-7">
        <div class="row">
            <div class="col-sm-3">
                <input type="hidden" id="playerInternalClubIdList" value="[114]">
                <input type="hidden" id="playerTeamInternalClubList" value='["114-827","114-1342"]'>
                <div class="profile-image">
                    <?php
$imageSrc = "https://eoscl.ca/admin/public/Player/" . $playerData->playername . ".jpg";
$altText = "Player ID: " . $playerData->playername;

// Check if the image URL returns a 404 error
$curl = curl_init($imageSrc);
curl_setopt($curl, CURLOPT_NOBODY, true);
curl_exec($curl);
$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($responseCode == '404') {
    $imageSrc = "https://cricclubs.com/documentsRep/profilePics/no_image.png";
    
    
    
    $altText = "No Image Available";
}
?>

<img src="<?php echo $imageSrc; ?>" alt="<?php echo $altText; ?>" class="img-responsive center-block" style="width: 100%;">

                </div>
            </div>
            <div class="col-sm-9">
                <div class="profile-team-text-in text-left row">
                    <div class="col-sm-6">
                        <h4 style="margin-top: 0px;">
                            <span>{{$player[$playerData->playername]}}</span>
                            <img alt="Verified" title="Verified" src="https://cdn-icons-png.flaticon.com/512/7595/7595571.png" style="width: 30px;height: 30px;margin: 0px;">
                        </h4>
                        <p>
                            CC Player ID :&nbsp; <strong>{{$playerData->playername}}</strong>
                        </p>
                        <p>
                            Club Names :
                            <strong>
                                <a href="#">
                             @foreach($player_club as $teamclub)
                             {{$teamclub->playerclub}}&nbsp;,
                             @endforeach
                                </a>
                            </strong>
                        </p>
                        <p>
                            Current Team :
                            <strong>
                                <a style="color: #fff;" href="#">
                            @foreach($player_team as $teamdata)
                                {{$teams[$teamdata->playerteam]}}&nbsp;,
                            @endforeach
                            </a>
                            </strong>
                        </p>
                        <p>Teams:  @foreach($player_team as $teamdata)
                                {{$teams[$teamdata->playerteam]}}&nbsp;,
                            @endforeach</p>
                        <p>Playing Role : <strong>All Rounder</strong></p>
                        <p>Batting Style : <strong>{{$playerData->playerbattingstyle}}</strong></p>
                        <p>Bowling Style : <strong>{{$playerData->playerbowlingstyle}}</strong></p>
                    </div>
                    <div class="col-sm-6 user-detilas">
                    </div>
                    <div class="col-sm-12" style="margin-top: 5px; display: flex;">
                    </div>
                </div>
            </div>
        </div>
    </div>



									<div class="col-sm-5">
										<div class="matches-runs-wickets">
											<ul class="list-inline">
                      
												<li>Matches<br> <span>{{$player_match[$player_team_id[0]]??0}}</span></li>
                     
												<li>Runs<br> <span>
                          @if(count($player_runs)>0)
                                                    @foreach($player_runs as $runs)
                                                    {{$runs->playerruns??0}}
                                                @endforeach
                                                @else
                                                0
                                                @endif
                                                </span></li>
												<li>Wickets<br> <span>
                        @if(count($player_wicket)>0)
                                                    @foreach($player_wicket as $wickets)
                                                   {{$wickets->playerwickets??0}}
                                                    @endforeach
                                                    @else
                                                0
                                                @endif
                                                </span></li>
											</ul>
										</div>
                   
                                
                                	
                   @php
                   $playerballs=$player_balls[$playerData->playername]??0;
                   $higestruns=$higest_score[$playerData->playername]??0;
                   $fifty=$player_total_fifties[$playerData->playername]??0;
                   $hundred=$player_hundreds[$playerData->playername]??0;
                   @endphp
                    @endforeach 								
									</div>
									<div class="addthis_sharing_toolbox hidden-phone" style="height: 24px; text-align: right;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

 

        <div class="profile-in-all">
		<div class="container p-sm-0">
			<div class="profile-in-container">
				<div class="all-tab-table profile-player">
					<div class="score-tab">
						<div id="parentHorizontalTab" style="display: block; width: 100%; margin: 0px;">
							
							<div class="resp-tabs-container hor_1" style="border-color: rgb(193, 193, 193);">
								<div class="" >
									<div class="match-summary-tab sp">
										<div  style="display: block; width: 100%; margin: 0px;">
											<!-- <ul class="stat-tabs-list  hor_2 b-stats">
												<li class="hor_2"  style="background-color: rgb(255, 255, 255); border-color: rgb(193, 193, 193);">
													<button onclick="showTable_custom('batting_data')">BATTING STATISTICS</button></li>
												<li class="hor_211"  style="background-color: rgb(245, 245, 245);"  > <button onclick="showTable_custom('bowling_data')"> Bowling STATISTICS</button></li>
											</ul> -->
		<div class="data_div resp-tabs-container hor_2" id="batting_data" style="border-color: rgb(193, 193, 193);">
												
												<h2>BATTING STATISTICS </h2>

                                                <div  class="about-table table-responsive resp-tab-content hor_2 resp-tab-content-active"  aria-labelledby="hor_2_tab_item-0" style="display:block">
													<table  class="table">
														<thead>
															<tr>
																<th>Mat</th>
																<th>Inns</th>
																<th>Runs</th>
																<th>Balls</th>
																<th>Ave</th>
																<th>SR</th>
																<th>HS</th>
																<th>100's</th>
																<th>50's</th>
																<th>4's</th>
																<th>6's</th>
															</tr>
														</thead>
														<tbody>
															<tr>
                           
                              <th>  {{$player_match[$player_team_id[0]]??0}}</th>
                         

    <th>{{$batsman_inning_length??0}}</th>

																
																<th>  @if(count($player_runs)>0)
                                                    @foreach($player_runs as $runs)
                                                    {{$runs->playerruns??0}}
                                                @endforeach
                                                @else
                                                0
                                                @endif</th>
																<th><a class="linkStyle" href="#"><strong>
                                @if(count($player_balls)>0)
                                @foreach($player_balls as $teamdata)
                               {{$teamdata->balls??0}}
                             @endforeach
                                                @else
                                                0
                                                @endif
                               
                                                           
                                                                 </strong></a></th>
																<th>{{$player_average??0}}</th>
                                <th>
    @php
        $totalRuns = 0;
        $totalBalls = 0;
    @endphp

    @if(count($player_runs) > 0)
        @foreach($player_runs as $runs)
            @php
                $totalRuns += $runs->playerruns ?? 0;
            @endphp
        @endforeach
    @endif

    @if(count($player_balls) > 0)
        @foreach($player_balls as $teamdata)
            @php
                $totalBalls += $teamdata->balls ?? 0;
            @endphp
        @endforeach
    @endif

    @php
        $strikeRate = ($totalBalls > 0) ? ($totalRuns / $totalBalls) * 100 : 0;
    @endphp

     {{ number_format($strikeRate, 2) }}
</th>



																<th>{{$higestruns}}</th>
																<th>{{$hundred??0}}</th>
																<th>{{$fifty??0}}</th>
																<th>
                                @if(count($player_four)>0)
                                @foreach($player_four as $four)
                                                    {{$four->total_four??0}}
                                                @endforeach  
                                                @else
                                                0
                                                @endif
                                                               
                                                               </th>
																<th> 
                                @if(count($player_six)>0)
                                @foreach($player_six as $six)
                                                    {{$six->total_sixes??0}}
                                                @endforeach  
                                                @else
                                                0
                                                @endif  
                                 </th>
																
															</tr>
															<tr id="battingGrouping2565_OneDay" style="display: none;">
																<td colspan="5">View statistics by : <select style="width: 150px; min-height: 25px; margin-bottom: 0px" id="groupingBattingSelection2565_OneDay" >
																		<option value="Series" selected="">Series</option>
																		<option value="Year">Year</option>
																</select>
																</td>
															</tr>
															<tr id="battingBySeries2565_OneDay" style="display: none;">
																<td id="battingBySeriesData2565_OneDay" colspan="15">Loading ...</td>
															</tr>

														
														</tbody>
													</table>
												</div>

												<h2>BOWLING STATISTICS</h2>
<div  class="about-table table-responsive resp-tab-content hor_2 resp-tab-content-active"  aria-labelledby="hor_2_tab_item-0" style="display:block">													
													<table class="table">
														<thead>
															<tr>
																<th>Mat</th>
																<th>Inns</th>
																<th>Overs</th>
																<th>Runs</th>
																<th>Wkts</th>
																<th>Econ</th>
																<th>SR</th>
																<th>Wides</th>
																<th>Catches</th>
															</tr>
														</thead>
														<tbody>
															<tr>
                           
                              <th>  {{$player_match[$player_team_id[0]]??0}}</th>
                            
    <th>{{$bowler_inning_length??0}}</th>                   
																<th>
                                @if(count($bowlerballs)>0)
                                @foreach($bowlerballs as $over1)
                                @php
                                $maxover=$over1->over/6??0;
                                $maxball=$over1->over;
                                @endphp
                                {{(round($over1->over/6))??0}}
                                @endforeach  
                                                @else
                                                0
                                                @endif  
                                
                               </th>
																<th>
                                @if(count($bower_over)>0)
                                @foreach($bower_over as $over)
                                  {{$over->bowler_runs??0}}
                                  @endforeach
                                                @else
                                                0
                                                @endif 

                               
                                </th>
                                                                
																<th><a class="linkStyle" href="#"><strong>
                                @if(count($player_wicket)>0)
                                @foreach($player_wicket as $wickets)
                                                   {{$wickets->playerwickets??0}}
                                                    @endforeach
                                                @else
                                                0
                                                @endif 
                                 
                                                  </strong></a></th>
                                                  <th>
    @if(count($bower_over) > 0)
        @foreach($bower_over as $over)
            {{
            number_format(
                $over->bowler_runs /$maxover,2) ?? 0
            }}
        @endforeach
    @else
        0
    @endif
</th>

																<th>
                                @if(count($bower_over)>0)
                                @foreach($bower_over as $over)
                                @foreach($player_wicket as $wickets)
                                {{ number_format(($maxball / $wickets->playerwickets) , 2) ?? 0 }}
                                  @endforeach 
                                  @endforeach
                                                @else
                                                0
                                                @endif 

                               
                                </th>
																<th>
                                @if(count($bower_over)>0)
                                @foreach($bower_over as $over)
                                  {{$over->total_wides??0}}
                                  @endforeach 
                                                @else
                                                0
                                                @endif 

                               
                                </th>
                                                              
																<th>
                                @if(count($player_cauches)>0)
                                @foreach($player_cauches as $catch)
                                                                        {{$catch->total_catches??0}}
                                                                    @endforeach
                                                @else
                                                0
                                                @endif 
                                                                    
                                                                </th>
															</tr>
															<tr id="bowlingGrouping2565_OneDay" style="display: none;">
																<td colspan="5">View statistics by : <select style="width: 150px; min-height: 25px; margin-bottom: 0px" id="groupingBowlingSelection2565_OneDay" >
																		<option value="Series" selected="">Series</option>
																		<option value="Year">Year</option>
																</select>
																</td>
															</tr>
															<tr id="bowlingBySeries2565_OneDay" style="display: none;">
																<td id="bowlingBySeriesData2565_OneDay" colspan="15">Loading ...</td>
															</tr>
													
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									<div class="stat-row">
										<div class="row">
											<div class="col-sm-6">
												<div class="stat-image-all">
													<div class="border-heading">
														<h5>BATTING: RUNS PER INNINGS</h5>
													</div>



        										            <div class="col-lg-8">
		                                            <canvas id="userChart" class="rounded shadow"></canvas>
                  									</div>

											</div>
										</div>
										<div class="col-sm-6">
												<div class="stat-image-all">
													<div class="border-heading">
														<h5>BATTING: DISMISSAL TYPE</h5>
													</div>
										            <div class="col-lg-8">
		                                            <canvas id="batsman_wicket_type_chart" class="rounded shadow"></canvas>
                  									</div>

											</div>
										</div>
										

									</div>
									<div class="row">
										<div class="col-sm-6">
												<div class="stat-image-all">
													<div class="border-heading">
														<h5>BOWLING: DISMISSAL TYPE</h5>
													</div>
        										            <div class="col-lg-8">
		                                            <canvas id="bolwer_wicket_type_chart" class="rounded shadow"></canvas>
                  									</div>

											</div>
										</div>

										<div class="col-sm-6">
												<div class="stat-image-all">
													<div class="border-heading">
														<h5>BOWLING: WICKETS PER INNINGS</h5>
													</div>



        										            <div class="col-lg-8">
		                                            <canvas id="bolwer_wicket_chart" class="rounded shadow"></canvas>
                  									</div>

											</div>
										</div>

									</div>

									<!-- </div> -->

											
											</div>
									</div>
									<div class="stat-row">
										<div class="row">
											<div class="col-sm-7">
												<div class="stat-image-all">
													<div class="border-heading">
														<h5>BOWLING: WICKETS PER INNINGS</h5>
													</div>
													<div class="stat-image">
														<div id="bar_chart1" style="width: 100%; height: 400px;"><div style="position: relative; width: 635px; height: 400px;"><div style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;"><svg width="635" height="400"><defs><clipPath id="rablfilter0"><rect x="23.5" y="7.5" width="571" height="340"></rect></clipPath></defs><g><rect x="0" y="0" width="635" height="400" fill="#ffffff" fill-opacity="0" stroke="#ffffff" stroke-opacity="0" stroke-width="0"></rect><rect x="23.5" y="7.5" width="571" height="340" fill="#ffffff" fill-opacity="0" stroke="#ffffff" stroke-opacity="0" stroke-width="1"></rect></g><g><line x1="23.5" x2="594.5" y1="347.5" y2="347.5" stroke="#9E9E9E" stroke-width="1"></line><line x1="23.5" x2="594.5" y1="305" y2="305" stroke="#E0E0E0" stroke-width="1"></line><line x1="23.5" x2="594.5" y1="262.5" y2="262.5" stroke="#E0E0E0" stroke-width="1"></line><line x1="23.5" x2="594.5" y1="220" y2="220" stroke="#E0E0E0" stroke-width="1"></line><line x1="23.5" x2="594.5" y1="177.5" y2="177.5" stroke="#E0E0E0" stroke-width="1"></line><line x1="23.5" x2="594.5" y1="135" y2="135" stroke="#E0E0E0" stroke-width="1"></line><line x1="23.5" x2="594.5" y1="92.5" y2="92.5" stroke="#E0E0E0" stroke-width="1"></line><line x1="23.5" x2="594.5" y1="50" y2="50" stroke="#E0E0E0" stroke-width="1"></line><line x1="23.5" x2="594.5" y1="7.5" y2="7.5" stroke="#E0E0E0" stroke-width="1"></line></g><g><path d="M 580 8 A 2 2 0 0 1 582 10 L 582 347 A 0 0 0 0 1 582 347 L 36 347 A 0 0 0 0 1 36 347 L 36 10 A 2 2 0 0 1 38 8 Z" fill="#4285f4" clip-path="url(#rablfilter0)"></path></g><g></g><g><text x="309.00000000000006" y="364.5" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#757575" dx="-31.8984375px">07/16/2022</text><text x="309" y="396.5" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#424242" dx="-30.734375px">Match Date</text><text x="17.5" y="351.5" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#757575" dx="-6.75px">0</text><text x="17.5" y="309" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#757575" dx="-16.640625px">0.5</text><text x="17.5" y="266.5" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#757575" dx="-6.75px">1</text><text x="17.5" y="224" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#757575" dx="-16.640625px">1.5</text><text x="17.5" y="181.5" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#757575" dx="-6.75px">2</text><text x="17.5" y="139" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#757575" dx="-16.640625px">2.5</text><text x="17.5" y="96.5" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#757575" dx="-6.75px">3</text><text x="17.5" y="54" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#757575" dx="-16.640625px">3.5</text><text x="17.5" y="11.5" style="cursor: default; user-select: none; -webkit-font-smoothing: antialiased; font-family: Roboto; font-size: 12px;" fill="#757575" dx="-6.75px">4</text></g><g></g><g></g><g></g></svg></div></div></div>
													</div>
												</div>
											</div>
											<div class="col-sm-5">
												<div class="stat-image-all">
													<div class="border-heading">
														<h5>BOWLING: DISMISSAL TYPE</h5>
													</div>
													<div class="stat-image">
														<div id="out_type_chart1" style="width: 100%; height: 400px;"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 445px; height: 400px;"><div aria-label="A chart." style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;"><svg width="445" height="400" aria-label="A chart." style="overflow: hidden;"><defs id="defs"></defs><rect x="0" y="0" width="445" height="400" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="289" y="40" width="134" height="31" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><rect x="289" y="40" width="134" height="12" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="306" y="50.2" font-family="Arial" font-size="12" stroke="none" stroke-width="0" fill="#222222">Caught</text></g><circle cx="295" cy="46" r="6" stroke="none" stroke-width="0" fill="#3366cc"></circle></g><g><rect x="289" y="59" width="134" height="12" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="306" y="69.2" font-family="Arial" font-size="12" stroke="none" stroke-width="0" fill="#222222">WktKpr Catch</text></g><circle cx="295" cy="65" r="6" stroke="none" stroke-width="0" fill="#dc3912"></circle></g></g><g><path d="M146,286.8L146,311.6A124,99.2,0,0,1,22,212.4L22,187.6A124,99.2,0,0,0,146,286.8" stroke="#a52b0e" stroke-width="1" fill="#a52b0e"></path><path d="M146,187.6L146,286.8A124,99.2,0,0,1,146,88.39999999999999L146,187.6A0,0,0,0,0,146,187.6" stroke="#dc3912" stroke-width="1" fill="#dc3912"></path><text text-anchor="start" x="34.265443753174054" y="191.8" font-family="Arial" font-size="12" stroke="none" stroke-width="0" fill="#ffffff">2</text></g><g><path d="M270,187.6L270,212.4A124,99.2,0,0,1,146,311.6L146,286.8A124,99.2,0,0,0,270,187.6" stroke="#264d99" stroke-width="1" fill="#264d99"></path><path d="M146,187.6L146,88.39999999999999A124,99.2,0,0,1,146,286.8L146,187.6A0,0,0,0,0,146,187.6" stroke="#3366cc" stroke-width="1" fill="#3366cc"></path><text text-anchor="start" x="250.73455624682595" y="191.79999999999998" font-family="Arial" font-size="12" stroke="none" stroke-width="0" fill="#ffffff">2</text></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>Out type</th><th>Count</th></tr></thead><tbody><tr><td>Caught</td><td>2</td></tr><tr><td>WktKpr Catch</td><td>2</td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 410px; left: 455px; white-space: nowrap; font-family: Arial; font-size: 12px;">WktKpr Catch</div><div></div></div></div>
													</div>
												</div>
											</div>
											</div>
									</div>
								</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
	function showTable_custom(show_div)
		{
			console.log(' in here')
			$('.data_div').hide();
			$('#'+show_div).show();
		}

	$( document ).ready(function() {

		

  const data_batsman_wicket_chart = @json($batsman_wicket_chart); // Assuming you pass the object from Laravel to JavaScript

  const labels_bat = [];
  const counts_bat = [];

  // Iterating over the data object to extract label and count values
  data_batsman_wicket_chart.forEach((item) => {
    labels_bat.push(item.name);
    counts_bat.push(item.count);
  });
var batsman_wicket_type_chart  = document.getElementById('batsman_wicket_type_chart').getContext('2d');

    var chart = new Chart(batsman_wicket_type_chart, {
    	type: 'pie',
  data: {
  labels: labels_bat,
  datasets: [{
        data: counts_bat,
        backgroundColor: [
          'rgba(255, 99, 132, 0.8)',   // Color for "Caught"
          'rgba(54, 162, 235, 0.8)',   // Color for "Bowled"
          'rgba(255, 206, 86, 0.8)',   // Color for other data points
  		  'rgba(255, 206, 86, 0.8)',   // Color for other data points
		  'rgba(255, 206, 86, 0.8)',   // Color for other data points
		  'rgba(255, 206, 86, 0.8)',   // Color for other data points
		  'rgba(255, 206, 86, 0.8)',   // Color for other data points
		  'rgba(255, 206, 86, 0.8)',   // Color for other data points
          // Add more colors here if needed
        ],
      }]
},
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Chart.js Pie Chart'
      }
    }
  },
    });














  const data_bowler_wicket_chart = @json($bowler_wicket_chart); // Assuming you pass the object from Laravel to JavaScript

  const labels = [];
  const counts = [];

  // Iterating over the data object to extract label and count values
  data_bowler_wicket_chart.forEach((item) => {
    labels.push(item.name);
    counts.push(item.count);
  });


	var bolwer_wicket_type_chart  = document.getElementById('bolwer_wicket_type_chart').getContext('2d');

    var chart = new Chart(bolwer_wicket_type_chart, {
    	type: 'pie',
  data: {
  labels: labels,
  datasets: [{
        data: counts,
        backgroundColor: [
          'rgba(255, 99, 132, 0.8)',   // Color for "Caught"
          'rgba(54, 162, 235, 0.8)',   // Color for "Bowled"
          'rgba(255, 206, 86, 0.8)',   // Color for other data points
  		  'rgba(255, 206, 86, 0.8)',   // Color for other data points
		  'rgba(255, 206, 86, 0.8)',   // Color for other data points
		  'rgba(255, 206, 86, 0.8)',   // Color for other data points
		  'rgba(255, 206, 86, 0.8)',   // Color for other data points
		  'rgba(255, 206, 86, 0.8)',   // Color for other data points
          // Add more colors here if needed
        ],
      }]
},
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Chart.js Pie Chart'
      }
    }
  },
    });



    var ctx1 = document.getElementById('bolwer_wicket_chart').getContext('2d');

    var chart = new Chart(ctx1, {
    	type: 'bar',
  data: {
  labels: {!!json_encode($bowler_inning_wicket->labels)!!},
  datasets: [
    {
      label: 'Inning Wickets',
      data: {!! json_encode($bowler_inning_wicket->dataset)!!},
      backgroundColor: '#2b1ec8',
    }
  ]
},
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Chart.js Pie Chart'
      }
    }
  },
    });





    var ctx = document.getElementById('userChart').getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'bar',
// The data for our dataset
        data: {
            labels:  {!!json_encode($chart->labels)!!} ,
            datasets: [
                {
                    label: 'Inning Runs',
                    backgroundColor: '#2b1ec8' ,
                    data:  {!! json_encode($chart->dataset)!!} ,
                },
            ]
        },
// Configuration options go here
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {if (value % 1 === 0) {return value;}}
                    },
                    scaleLabel: {
                        display: false
                    }
                }]
            },
            legend: {
                labels: {
                    // This more specific font property overrides the global property
                    fontColor: '#122C4B',
                    fontFamily: "'Muli', sans-serif",
                    padding: 25,
                    boxWidth: 25,
                    fontSize: 14,
                }
            },
            layout: {
                padding: {
                    left: 10,
                    right: 10,
                    top: 0,
                    bottom: 10
                }
            }
        }
    });

    });
</script>