@extends('default')
@section('content')

<div class="container">
       
       <div class="show-phone">
			</div>

       	<div class="score-tab">            
           	<div class="complete-list">
           	 	<div class="panel with-nav-tabs panel-default">
					<div class="panel-heading score-tabs">
                           <ul class="nav nav-tabs">
  							<li><a href="{{ route('balltoballScorecard', $match_results[0]->id) }}" >Ball By Ball</a></li>
							<li class="active"><a href="{{ route('fullScorecard', $match_results[0]->id) }}" >Full Scorecard</a></li>
							<li ><a href="{{ route('fullScorecard_overbyover', $match_results[0]->id) }}" >Over by Over Score</a></li>
							<li><a href="{{ route('fullScorecard_chart', $match_results[0]->id) }}" >Charts</a></li>
							</ul>
                       </div>
                       <div class="panel-body">
                           <div class="tab-content">
                               <div class="tab-pane fade " id="tab1default">
                              
											Loading ...
									</div>
                               <div class="tab-pane fade in active" id="tab2default">
                               
                               <div class="complete-list">
											<div class="with-nav-tabs panel-default xs-nopadding">
								                 <div class="panel-heading">
								                 <ul class="nav nav-tabs">
															<li id="ballByBallTeamTab1" class="active"><a href="#ballByBallTeam1" role="tab" data-toggle="tab" onclick="resizeScroll();">{{$teams_one}}</a></li>
															
																<li id="ballByBallTeamTab2"><a href="#ballByBallTeam2" role="tab" data-toggle="tab" onclick="resizeScroll();">{{$teams_two}}</a></li>
															
								                        </ul>
								                        </div>
								                <div class="panel-body">
										 			<div class="tab-content summary-list">
										 			<div class="tab-pane active" id="ballByBallTeam1">
                                            <div class="match-innings-top-all">
                                                <div class="row">
                                                	<div class="col-sm-8">
                                                    	<div class="match-table-innings">
                                                        	<div class="about-table  table-responsive" id="tab1default">
                                                                <table class="table"> 
                                                                    <thead> 
                                                                        <tr> 
                                                                        <th style="text-align: left;" colspan="2" class="hidden-phone">
                                                                        {{$teams_one}} innings   
                                                                        (@foreach($total_over as $over){{$over->numberofover}} @endforeach overs maximum) </th> 
                                                                       <th style="text-align: left;" class="show-phone">{{$teams_one}} innings</th> 
																	   <th style="text-align: right;">R</th>
                                                                        <th style="text-align: right;">B</th>
                                                                        <th style="text-align: right;">4s</th> 
                                                                        <th style="text-align: right;">6s</th>
                                                                        <th style="text-align: center;">SR</th>
                                                                        </tr> 
                                                                    </thead> 
                                                                    @foreach($player_runs as $item)
                                                                    @if($item->inningnumber==1)
                                                                    <tbody> 
                                                                    <tr> 
																		<th>
																		<?php
$imageSrc = "https://eoscl.ca/admin/public/Player/" . $item->playerId . ".jpg";
$altText = "Player ID: " . $item->playerId;

// Check if the image URL returns a 404 error
$headers = get_headers($imageSrc);
if (strpos($headers[0], '404') !== false) {
    $imageSrc = "https://cricclubs.com/documentsRep/profilePics/no_image.png";
    $altText = "No Image Available";
}
?>

<img src="<?php echo $imageSrc; ?>" alt="<?php echo $altText; ?>" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">
                                                                        
<a href="{{ route('playerview', $item->playerId) }}"><b>{{$player[$item->playerId]}}</b> </a>
                                                                        	<a style="display:none" id="btm_video_1126359" href="javascript:openVideoHTMLvs('1126359','bt', 'Pangalia Kunwarajeet Singh');"><img alt="Watch Ball Video" title="Watch Ball Video" width="20px" height="20px" src="/utilsv2/images/youtube.png"></a>
                                                                        	<br/>
																			<br/>
																			<div class="scorecard-out-text show-phone" style="font-size: 2px;width:0px">
	@php
    $isOut = false; // Flag to check if the player is out
@endphp

@foreach ($match_description as $out)
    @if ($out->inningnumber == 1 && $out->batsman_id == $item->playerId)
        @php
            $isOut = true; // Set the flag to true if the player is out
        @endphp

        @if ($out->out_description == "Retired Hurt")
            <span><a>b &nbsp;{{ $out->out_description }}</a></span>
        @elseif ($out->out_description == "Run out" || $out->out_description == "Caught" || $out->out_description == "Run Out (NB)" || $out->out_description == "Run Out (WD)" || $out->out_description == "Stumped")
            <span><a>b&nbsp;&nbsp;{{ $out->bowler_name }}</a></span> &nbsp;(<a href="#">{{ $out->out_description }}&nbsp;by&nbsp;{{ $out->fielder_name }}</a>)
        @elseif ($out->out_description == "Bowled" )
            <span><a>b</a></span> (<a href="#">{{ $out->bowler_name }}</a>)

			  @elseif ($out->out_description == "LBW" || $out->out_description == "Hit the ball twice" || $out->out_description == "Hit wicket")
            <span><a>b</a></span> (<a href="#">{{$out->out_description}}&nbsp;&nbsp;&{{ $out->bowler_name }}</a>)
			@else
			<span><a>b &nbsp;{{ $out->out_description }}</a></span>
        @endif
    @endif
@endforeach

@if (!$isOut)
    <span><a>Not out</a></span>
@endif


                                                                        	</div>
                                                                        </th> 
                                                                        <th class="hidden-phone">
@php
    $isOut = false; // Flag to check if the player is out
@endphp

@foreach ($match_description as $out)
    @if ($out->inningnumber == 1 && $out->batsman_id == $item->playerId)
        @php
            $isOut = true; // Set the flag to true if the player is out
        @endphp

        @if ($out->out_description == "Retired Hurt")
            <span><a>b &nbsp;{{ $out->out_description }}</a></span>
        @elseif ($out->out_description == "Run out" || $out->out_description == "Caught" || $out->out_description == "Run Out (NB)" || $out->out_description == "Run Out (WD)" || $out->out_description == "Stumped")
            <span><a>b&nbsp;&nbsp;{{ $out->bowler_name }}</a></span> &nbsp;(<a href="#">{{ $out->out_description }}&nbsp;by&nbsp;{{ $out->fielder_name }}</a>)
        @elseif ($out->out_description == "Bowled" )
            <span><a>b</a></span> (<a href="#">{{ $out->bowler_name }}</a>)

			@elseif ($out->out_description == "LBW" || $out->out_description == "Hit the ball twice" || $out->out_description == "Hit wicket")
            <span><a>b</a></span> (<a href="#">{{$out->out_description}}&nbsp;&nbsp;{{ $out->bowler_name }}</a>)
			@else
			<span><a>b &nbsp;{{ $out->out_description }}</a></span>
        @endif
    @endif
@endforeach

@if (!$isOut)
    <span><a>Not out</a></span>
@endif


                                                                        	</th>
																			<th style="text-align: right;"><b>{{$item->total_runs}}</b></th>
                                                                        <th style="text-align: right;">{{$player_balls[$item->playerId]??0}}</th> 
                                                                        <th style="text-align: right;">{{$item->total_fours}}<a style="display:none" id="btfour_video_1207430" href="javascript:openVideoHTMLvs('1207430','four', 'Noman Siddiqui');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                        <th style="text-align: right;">{{$item->total_six}}</th>
                                                                        <th style="text-align: center;">{{ isset($player_balls[$item->playerId]) && $player_balls[$item->playerId] != 0 ? number_format(($item->total_runs / $player_balls[$item->playerId]) * 100, 2) : 0.00 }}</th>
                                                                    </tr> 
                                                                 
                                                                    @endif
                                                                    @endforeach
                                                                    <tr> 
                                                                        <th>Extras<div class="scorecard-out-text show-phone">
																		@foreach($extra_runs as $item)
		@if($item->inningnumber == 1)
			( lb {{ $item->byes_total_runs }} w {{ $item->wideball_total_runs }} nb {{ $item->noball_total_runs+$item->nbp_total_runs}})
		
																		</th>
                                                                        <th style="text-align: right;"><b>{{($item->byes_total_runs) +($item->wideball_total_runs) + ($item->noball_total_runs)+($item->nbp_total_runs)}}</b></th>
																		@endif
	@endforeach
																		</div></th> 
                                                                        <th class="hidden-phone">
																		@foreach($extra_runs as $item)
		@if($item->inningnumber == 1)
			( lb {{ $item->byes_total_runs }} w {{ $item->wideball_total_runs }} nb {{ $item->noball_total_runs+$item->nbp_total_runs}})
		
																		</th>
																		@endif
	@endforeach
                                                                        <th></th> 
                                                                        <!-- <th class="show-phone-cell"></th> -->
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        
                                                                    </tr>
                                                                    <tr> 
																	@foreach($totalData as $item)
		                                                             @if($item->inningnumber == 1)
																	 @if($item->max_ball%6==0)
                                                                        <th>Total<div class="scorecard-out-text show-phone">({{$item->total_wicket}} wickets, {{(($item->max_over)) }}.{{($item->max_ball)%6 }})</div></th> 
																		@else
																		<th>Total<div class="scorecard-out-text show-phone">({{$item->total_wicket}} wickets, {{(($item->max_over)-1) }}.{{($item->max_ball)%6 }})</div></th> 
																		@endif
																		@if($item->max_ball%6==0)
																		
																		<th class="hidden-phone">({{$item->total_wicket}} wickets, {{$item->max_over }}.{{($item->max_ball)%6 }}
 overs )</th>@else
 										
 <th class="hidden-phone">({{$item->total_wicket}} wickets, {{$item->max_over-1 }}.{{($item->max_ball)%6 }}
 overs )</th>
 @endif

                                                                        <th style="text-align: right;"><b>{{$item->total_runs}}</b></th>
																		@endif
																		@endforeach
                                                                        <th></th>
                                                                        <th></th>
                                                                       <!--   <th class="show-phone-cell"></th> -->
                                                                        <th></th>
                                                                        <th></th>
                                                                    </tr>
                                                                     </tbody>
                                                                   
                                                                </table>
                                                                
                                                                <table class="table">
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                            				</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 xs-nopadding hidden-phone">
                                                    </div>
                                                </div>
                                                <div class="match-innings-bottom-all">
                                                <div class="row">
                                                	<div class="col-sm-6">
                                                    	<div class="match-table-innings">
                                                        	<div class="about-table  table-responsive" id="tab1default">
                                                                <table class="table"> 
                                                                    <thead> 
                                                                        <tr> 
                                                                        <th style="text-align: left;" colspan="2">Bowling</th> 
                                                                        <th style="text-align: right;"> O</th>
                                                                       <th style="text-align: right;">M</th>
                                                                        <th style="text-align: right;">R</th>
                                                                        <th style="text-align: right;">W</th> 
                                                                        <th style="text-align: right;">Econ</th>
                                                                        
                                                                        
                                                                        
                                                                        </tr> 
                                                                    </thead> 
                                                                    <tbody> 
																	@foreach($bowler_data as $item)
  @if($item->inningnumber == 1)
    <tr> 
      <th style="width:40px;">
                                                                       
	  <?php
$imageSrc = "https://eoscl.ca/admin/public/Player/" . $item->bowlerid . ".jpg";
$altText = "Player ID: " . $item->bowlerid;

// Check if the image URL returns a 404 error
$headers = get_headers($imageSrc);
if (strpos($headers[0], '404') !== false) {
    $imageSrc = "https://cricclubs.com/documentsRep/profilePics/no_image.png";
    $altText = "No Image Available";
}
?>

<img src="<?php echo $imageSrc; ?>" alt="<?php echo $altText; ?>" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">

      </th>
      <th>
        <a href="{{ route('playerview', $item->bowlerid) }}"><b>{{$player[$item->bowlerid]}}</b></a>
        <a style="display:none" id="bwl_video_3054417" href="javascript:openVideoHTMLvs('3054417','bwl', 'Rohit Miglani');">
          <img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px">
        </a>
      </th> 
	  @if(($item->max_ball)%6  == 0)
      <th style="text-align: right;">{{ floor($item->over) }}.{{  ($item->max_ball)%6 }}</th>
	  @else
	  <th style="text-align: right;">{{ ($item->over) -1}}.{{  ($item->max_ball)%6 }}</th>
	  @endif
      <th style="text-align: right;">
        {{$maiden_overs[$item->bowlerid] ?? 0}}
      </th>
      <th style="text-align: right;">{{$item->total_runs}}</th>
      <th style="text-align: right;">{{$bowler_wickets[$item->bowlerid]??0}}</th> 
      <th style="text-align: right;">{{ number_format(($item->total_runs)/$item->over, 2) }}</th>
    </tr> 
  @endif
@endforeach

                                                                   
                                                                    </tbody>
                                                                </table>
                                            				</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 show-phone">
														<div class="hidden-phone">	
															</div>
													</div>
                                                    
                                                    <div class="col-sm-6">
                                                    	<div class="fallofwickets">
                                                        	<div class="fall-of-wixket-heading">
                                                            	<h4>Fall of Wickets</h4>
                                                            </div>
                                                            <div class="fall-of-content">
                                                            	<div class="row" style="color:white;">
		                                                         
																		
																@php
																                         
																							$sum_score = 0;
																							$sum_wicket=0;
																							$sum_run = 0;  @endphp

																							@foreach($fallwickets as $score)
																						

																							@if($score->inningnumber==1)
																							@php 
																								$sum_score +=$score['runs'];
																								$sum_run +=$score['runs'];
																						$sum_wicket += ($score['isout'] == 1) ? 1 : 0;
																						 @endphp
																						 @if($score->isout==1)
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
																						                                                                       
	  <?php
$imageSrc = "https://eoscl.ca/admin/public/Player/" . $score->playerId . ".jpg";
$altText = "Player ID: " . $score->playerId;

// Check if the image URL returns a 404 error
$headers = get_headers($imageSrc);
if (strpos($headers[0], '404') !== false) {
    $imageSrc = "https://cricclubs.com/documentsRep/profilePics/no_image.png";
    $altText = "No Image Available";
}
?>

<img src="<?php echo $imageSrc; ?>" alt="<?php echo $altText; ?>" class="img-responsive center-block">

		                                                                            	
																						
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>{{$player[$score->playerId]}}</h5>
		                                                                                <h5>
																						


																						 
																						 {{$sum_score}}/{{$sum_wicket}}
																						
																						
																							
																						, 
		                                                                                
		                                                                                 Over {{($score['overnumber'])-1}}.{{($score['ballnumber']%6)}}</h5>
		                                                                                
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																			@endif
																			@endif
																						 @endforeach
																		
																		
																		
																		
																	
																		
																		</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               
                                                </div>
                                            </div>
                                            </div>
                                            </div>
											<div class="tab-pane" id="ballByBallTeam2">                                            
                                            <div class="match-innings-top-all">
                                                <div class="row">
                                                	<div class="col-sm-8">
                                                    	<div class="match-table-innings">
                                                        	<div class="about-table  table-responsive" id="tab1default">
                                                                <table class="table"> 
                                                                    <thead> 
                                                                        <tr> 
                                                                        <th style="text-align: left;" colspan="2" class="hidden-phone">{{$teams_two}} <div class="name visible-xs"> </div> 
                                                                        
                                                                        (target: @foreach($totalData as $item) @if($item->inningnumber == 1) {{$item->total_runs}} @endif @endforeach runs from @foreach($total_over as $over){{$over->numberofover}} @endforeach overs) </th> 
                                                                       <th style="text-align: left;" class="show-phone">{{$teams_two}} innings </th>
                                                                        <th style="text-align: right;">R</th>
                                                                        <th style="text-align: right;">B</th>
                                                                        <th style="text-align: right;">4s</th> 
                                                                        <th style="text-align: right;">6s</th>
                                                                        <th style="text-align:center;">SR</th>
                                                                        </tr> 
                                                                    </thead> 
                                                                    @foreach($player_runs as $item)
                                                                    @if($item->inningnumber==2)
                                                                    <tbody> 
                                                                    <tr> 
                                                                    <th><!-- <i class="fa fa-user"></i> -->
																		<?php
$imageSrc = "https://eoscl.ca/admin/public/Player/" . $item->playerId . ".jpg";
$altText = "Player ID: " . $item->playerId;

// Check if the image URL returns a 404 error
$headers = get_headers($imageSrc);
if (strpos($headers[0], '404') !== false) {
    $imageSrc = "https://cricclubs.com/documentsRep/profilePics/no_image.png";
    $altText = "No Image Available";
}
?>

<img src="<?php echo $imageSrc; ?>" alt="<?php echo $altText; ?>" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">

                                                                        
                                                                          <a href="{{ route('playerview', $item->playerId) }}"><b> {{$player[$item->playerId]}}</b> </a>
                                                                        	<a style="display:none" id="btm_video_3088351" href="javascript:openVideoHTMLvs('3088351','bt', 'Rohit Arora');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
																			<br/>
																			<br/>
																			<div class="scorecard-out-text show-phone" style="font-size: 2px;width:0px">
																	@php
    $isOut = false; // Flag to check if the player is out
@endphp

@foreach ($match_description as $out)
    @if ($out->inningnumber == 2 && $out->batsman_id == $item->playerId)
        @php
            $isOut = true; // Set the flag to true if the player is out
        @endphp

        @if ($out->out_description == "Retired Hurt")
            <span><a>b &nbsp;{{ $out->out_description }}</a></span>
        @elseif ($out->out_description == "Run out" || $out->out_description == "Caught" || $out->out_description == "Run Out (NB)" || $out->out_description == "Run Out (WD)" || $out->out_description == "Stumped")
            <span><a>b&nbsp;&nbsp;{{ $out->bowler_name }}</a></span> &nbsp;(<a href="#">{{ $out->out_description }}&nbsp;by&nbsp;{{ $out->fielder_name }}</a>)
        @elseif ($out->out_description == "Bowled" )
            <span><a>b</a></span> (<a href="#">{{ $out->bowler_name }}</a>)

			@elseif ($out->out_description == "LBW" || $out->out_description == "Hit the ball twice" || $out->out_description == "Hit wicket")
            <span><a>b</a></span> (<a href="#">{{$out->out_description}}&nbsp;&nbsp;{{ $out->bowler_name }}</a>)
			@else
			<span><a>b &nbsp;{{ $out->out_description }}</a></span>
        @endif
    @endif
@endforeach

@if (!$isOut)
    <span><a>Not out</a></span>
@endif
                                                                        	</div>

                                                                        <div class="scorecard-out-text show-phone" style="margin-left:34px">
																
                                                                        	</div>
                                                                        </th> 
                                                                        <th class="hidden-phone">
																@php
    $isOut = false; // Flag to check if the player is out
@endphp

@foreach ($match_description as $out)
    @if ($out->inningnumber == 2 && $out->batsman_id == $item->playerId)
        @php
            $isOut = true; // Set the flag to true if the player is out
        @endphp

        @if ($out->out_description == "Retired Hurt")
            <span><a>b &nbsp;{{ $out->out_description }}</a></span>
        @elseif ($out->out_description == "Run out" || $out->out_description == "Caught" || $out->out_description == "Run Out (NB)" || $out->out_description == "Run Out (WD)" || $out->out_description == "Stumped")
            <span><a>b&nbsp;&nbsp;{{ $out->bowler_name }}</a></span> &nbsp;(<a href="#">{{ $out->out_description }}&nbsp;by&nbsp;{{ $out->fielder_name }}</a>)
        @elseif ($out->out_description == "Bowled" )
            <span><a>b</a></span> (<a href="#">{{ $out->bowler_name }}</a>)

			@elseif ($out->out_description == "LBW" || $out->out_description == "Hit the ball twice" || $out->out_description == "Hit wicket")
            <span><a>b</a></span> (<a href="#">{{$out->out_description}}&nbsp;&nbsp;{{ $out->bowler_name }}</a>)
			@else
			<span><a>b &nbsp;{{ $out->out_description }}</a></span>
        @endif
    @endif
@endforeach

@if (!$isOut)
    <span><a>Not out</a></span>
@endif
                                                                        	</div>
                                                                        	</th>
   																			 <th style="text-align: right;"><b>{{$item->total_runs}}</b></th>
                                                                            <th style="text-align: right;">{{$player_balls[$item->playerId]?? 0}}  </th>
                                                                            <th style="text-align: right;">{{$item->total_fours}}</th> 
                                                                        <th style="text-align: right;">{{$item->total_six}}</th>
                                                                        <th style="text-align: center;"> {{ isset($player_balls[$item->playerId]) && $player_balls[$item->playerId] != 0 ? number_format(($item->total_runs / $player_balls[$item->playerId]) * 100, 2) : 0.00 }}</th>
                                                                    </tr> 
                                                                    @endif
                                                                    @endforeach
                                                                    <tr> 
                                                                        <th>Extras<div class="scorecard-out-text show-phone">											@foreach($extra_runs as $item)
												@if($item->inningnumber == 2)
													( lb {{ $item->byes_total_runs }} w {{ $item->wideball_total_runs }} nb {{ $item->noball_total_runs+$item->nbp_total_runs}})
		
		</th>
		<th style="text-align: right;"><b>{{($item->byes_total_runs) +($item->wideball_total_runs) + ($item->noball_total_runs)+($item->nbp_total_runs)}}</b></th>
																												@endif
											@endforeach</div></th> 
                                                                        <th class="hidden-phone">
																		
																												@foreach($extra_runs as $item)
												@if($item->inningnumber == 2)
													( lb {{ $item->byes_total_runs }} w {{ $item->wideball_total_runs }} nb {{$item->noball_total_runs+$item->nbp_total_runs }})
												
																												</th>
																												@endif
											@endforeach
                                                                        <th></th> 
                                                                       <!--  <th class="show-phone-cell"></th> -->
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                    </tr>
                                                                    <tr> 
																	@foreach($totalData as $item)
		                                                             @if($item->inningnumber == 2)
																	 @if($item->max_ball%6==0)
                                                                        <th>Total<div class="scorecard-out-text show-phone">({{$item->total_wicket}} wickets, {{(($item->max_over)) }}.{{($item->max_ball)%6 }})</div></th> 
																		@else
																		<th>Total<div class="scorecard-out-text show-phone">({{$item->total_wicket}} wickets, {{(($item->max_over)-1) }}.{{($item->max_ball)%6 }})</div></th> 
																		@endif
                                                                        
																		@if($item->max_ball%6==0)
																		
																		<th class="hidden-phone">({{$item->total_wicket}} wickets, {{$item->max_over }}.{{($item->max_ball)%6 }}
 overs )</th>@else
 										
 <th class="hidden-phone">({{$item->total_wicket}} wickets, {{$item->max_over-1 }}.{{($item->max_ball)%6 }}
 overs )</th>
 @endif

                                                                        <th style="text-align: right;"><b>{{$item->total_runs}}</b></th>
																		@endif
																		@endforeach
                                                                        <th></th> 
                                                                        <!-- <th class="show-phone-cell"></th> -->
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                    </tr>
                                                                    </tbody>
                                                                    </table>
                                                                    <table class="table"> 
                                                                    <tbody>
                                                                    
                                                                </tbody></table>
                                            				</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 xs-nopadding hidden-phone">
                                                    <div class="hidden-phone">	
                                                    </div>
                                                     </div>
                                                </div>
                                                <div class="match-innings-bottom-all">
                                                <div class="row">
                                                	<div class="col-sm-6">
                                                    	<div class="match-table-innings">
                                                        	<div class="about-table  table-responsive" id="tab1default">
                                                                <table class="table"> 
                                                                    <thead> 
                                                                        <tr> 
                                                                        <th style="text-align: left;" colspan="2">Bowling</th> 
                                                                        <th style="text-align: right;">O</th>
                                                                          <th style="text-align: right;">M</th>
                                                                         
                                                                        <th style="text-align: right;">R</th>
                                                                        <th style="text-align: right;">W</th> 
                                                                        <th style="text-align: right;">Econ</th>
                                                                      
                                                                        </tr> 
                                                                    </thead> 
                                                                    <tbody> 
                                                                    @foreach($bowler_data as $item)
		                                                             @if($item->inningnumber == 2)
                                                                    <tr> 
                                                                    <th style="width:40px;">
																	<?php
$imageSrc = "https://eoscl.ca/admin/public/Player/" . $item->bowlerid . ".jpg";
$altText = "Player ID: " . $item->bowlerid;

// Check if the image URL returns a 404 error
$headers = get_headers($imageSrc);
if (strpos($headers[0], '404') !== false) {
    $imageSrc = "https://cricclubs.com/documentsRep/profilePics/no_image.png";
    $altText = "No Image Available";
}
?>

<img src="<?php echo $imageSrc; ?>" alt="<?php echo $altText; ?>" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">
                                                                   
                                                                    </th>

                                                                        <th>
                                                                        	<a href="{{ route('playerview', $item->bowlerid) }}"><b>{{$player[$item->bowlerid]}}</b></a><a style="display:none" id="bwl_video_3054417" href="javascript:openVideoHTMLvs('3054417','bwl', 'Rohit Miglani');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        @if(($item->max_ball)%6  == 0)
      <th style="text-align: right;">{{ floor($item->over) }}.{{  ($item->max_ball)%6 }}</th>
	  @else
	  <th style="text-align: right;">{{ ($item->over) -1}}.{{  ($item->max_ball)%6 }}</th>
	  @endif
                                                                          <th style="text-align: right;">
																		  {{$maiden_overs[$item->bowlerid] ?? 0}}
																		</th>
                                                                          		<th style="text-align: right;">{{$item->total_runs}}</th>
                                                                        <th style="text-align: right;">{{$bowler_wickets[$item->bowlerid]??0}}</th> 
                                                                        </th>
                                                                        <th style="text-align: right;">{{ number_format(($item->total_runs)/$item->over, 2) }}
</th>
                                               
                                                                    </tr> 
																	@endif
																	@endforeach
                                                                    
                                                                    </tbody>
                                                                </table>
                                            				</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 show-phone">
                                                    <div class="hidden-phone">	
                                                    </div>
                                                     </div>
                                                    <div class="col-sm-6">
                                                    	<div class="fallofwickets">
                                                        	<div class="fall-of-wixket-heading">
                                                            	<h4>Fall of Wickets</h4>
                                                            </div>
                                                            <div class="fall-of-content">
                                                            	<div class="row" style="color:white;">
		                                                           
																@php
																							$sum_score = 0;
																							$sum_wicket=0;
																							$sum_run = 0;  @endphp

																							@foreach($fallwickets as $score)
																						

																							@if($score->inningnumber==2)
																							@php 
																								$sum_score +=$score['runs'];
																								$sum_run +=$score['runs'];
																								
																						$sum_wicket += ($score['isout'] == 1) ? 1 : 0;
																						 @endphp
																						 @if($score->isout==1)
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
																						<?php
$imageSrc = "https://eoscl.ca/admin/public/Player/" . $score->playerId . ".jpg";
$altText = "Player ID: " . $score->playerId;

// Check if the image URL returns a 404 error
$headers = get_headers($imageSrc);
if (strpos($headers[0], '404') !== false) {
    $imageSrc = "https://cricclubs.com/documentsRep/profilePics/no_image.png";
    $altText = "No Image Available";
}
?>

<img src="<?php echo $imageSrc; ?>" alt="<?php echo $altText; ?>" class="img-responsive center-block">
     
																						
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>{{$player[$score->playerId]}}</h5>
		                                                                                <h5>
																						


																						 
																						 {{$sum_score}}/{{$sum_wicket}}
																						
																						
																							
																						, 
		                                                                                
		                                                                                 Over {{($score['overnumber'])}}.{{($score['ballnumber']%6)}}</h5>
		                                                                                
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																			@endif
																			@endif
																						 @endforeach	
																		
																		
																		
																		
																		
																		
																		
																		
																		
																
																		
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
                                    
						            <div class="row">
                                    	<div class="col-sm-8">
								            <div class="match-detail-table">
							                    <div class="about-table  table-responsive" id="tab1default">
							                    	<div class="border-heading">
							                    		<div style="float:left">
								                            <h5>Match Details</h5>
								                        </div>
							                            <div class="exportOptions-panel" style="float:right">
							                            <div style="text-align: right;padding-left: 10px;">
      															
												    		<!-- <a href="{{ route('downloadCSV', $match_results[0]->id) }}"><img alt="Download as Excel" title="Download as Excel" class="excelBtn" style="cursor:pointer;" src="/utilsv2/images/excel.png" width="32" height="32"></a> -->
												    	</div><br>
							                        </div>
							                        <font size="1"></font><table class="table"> 
							                            <thead> 
							                                <tr> 
							                                    <th style="text-align: left;">Topic</th> 
							                                    <th style="text-align: left;">Details</th>
							                                </tr> 
							                            </thead> 
							                            <tbody> 
							                            <tr> 
							                                <th>Series:</th> 
                                                            <th>
                                                            {{$tournament[0]??''}}
                                                            </th>

							                            </tr> 
							                            <tr> 
							                                <th>Match Date:</th> 
															<th>{{$match_data->match_startdate->format('Y-m-d') }}</th>

</th>
							                            </tr> 
							                            <tr> 
							                                <th>Toss:</th> 
							                                <th>   {{$teams[$match_data->toss_winning_team_id]}} 
															@if ($teams[$match_data->toss_winning_team_id] == $teams[$match_data->first_inning_team_id])
																won the toss and elected to bat first.
															@elseif ($teams[$match_data->toss_winning_team_id] == $teams[$match_data->second_inning_team_id])
																won the toss and elected to bowl first.
															@else
																Invalid data in the database.
															@endif
							                            </tr>
							                            <tr> 
							                                <th>Player of the Match:</th> 
							                                <th>
															@if ( $match_data->manofmatch_player_id > 0)
    {{$player[$match_data->manofmatch_player_id] }}
@else
    N/A
@endif

</th>
							                            </tr>
							                            <tr>															
														<th style="padding: 0px 0px"><table><tbody><tr><th><strong>Umpires: </strong> </th></tr></tbody></table></th><th style="padding: 0px 5px"><table><tbody><tr><th style="padding: 0px 5px">1. <a style="color: inherit;" href="">Saurabh Naik</a></th></tr><tr><th style="padding: 0px 5px">2. <a style="color: inherit;" href="">Syed Muhammad Talha Anwar</a></th></tr></tbody></table></th>  
														</tr>
														<tr>
															<th><strong>Location: </strong> </th><th> {{$ground[$match_data->ground_id]}}</th>
														</tr>
									
														<tr>
															<th>
																	<strong>Points Earned:</strong> </th><th>{{$teams[$match_data->team_id_a]}}: {{$match_data->teamAbonusPoints}}, {{$teams[$match_data->team_id_b]}}: {{$match_data->teamBbonusPoints}}</th>
														</tr>
														<tr title="America/New_York"><th style="padding-left: 10px"><strong>Match Start: </strong></th><th><strong></strong> &nbsp; &nbsp; &nbsp; &nbsp;{{$match_data->match_starttime->format('h:i:s ')}}  </th></tr><tr title="America/New_York">
															<th><strong>Last Updated:  </strong></th><th> ({{$match_data-> updated_at }})
															</th>
														</tr>
														<tr>
															<th><strong>Match Documents: </strong></th><th>
															<div id="documentsDiv">
															</div>
															</th>
														</tr>
														</tbody>
							                        </table>
							            		</div>
		                					</div>	
	       						 		</div>
	       						 	</div>
	       						 	
	       						 	  </div>
                               <div class="tab-pane fade " id="tab3default">
								
								Loading ...
								</div>
       						   <div class="tab-pane fade " id="tab4default">
								
								Loading ...
								</div>
       					<div class="tab-pane fade " id="tab5default">
								
								Loading ...
								</div>
       				</div>
       			</div>
       		</div>
            </div>
            </div>
            </div>
@stop