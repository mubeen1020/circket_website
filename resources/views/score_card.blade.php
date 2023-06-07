@extends('default')
@section('content')

<div class="container">
       
       <table style="width: 100%; margin-bottom: 10px;text-align: center;">
	<tbody><tr>
		<td><a class="show-phone" href="#" onclick="javascript:mobileFacebookShare();return false;"> <img src="/utilsv2/images/fb_new.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileTwitterShare();return false;"><img src="/utilsv2/images/twi.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileGoogleShare(); return false;"><img src="/utilsv2/images/goo.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileMailShare(); return false;"><img src="/utilsv2/images/mail.png" width="40"></a></td>
		<td><a class="show-phone whatsapp"><img src="/utilsv2/images/whatsapp.png"></a></td>
	</tr>
</tbody></table><div class="show-phone">
			</div>

       	<div class="score-tab">            
           	<div class="complete-list">
           	 	<div class="panel with-nav-tabs panel-default">
					<div class="panel-heading score-tabs">
                           <ul class="nav nav-tabs">
  							<li><a href="{{ route('balltoballScorecard', $match_results[0]->id) }}" >Ball By Ball</a></li>
							<li class="active"><a href="{{ route('fullScorecard', $match_results[0]->id) }}" >Full Scorecard</a></li>
							<li ><a href="{{ route('fullScorecard_overbyover', $match_results[0]->id) }}" >Over by Over Score</a></li>
							<li><a href="#tab4default" role="tab" data-toggle="tab" onclick="loadView('graphsView');">Charts</a></li>
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
                                                                       <th style="text-align: left;" class="show-phone">Royal Tigers innings</th> 
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
                                                                   
                                                                        	<img src="https://cricclubs.com/documentsRep/profilePics/b8e79395-ac46-4e68-b8a5-5ae31d78ee59.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">	
                                                                        
                                                                        	<a href=""><b>{{$player[$item->playerId]}}</b> </a>
                                                                        	<a style="display:none" id="btm_video_1207430" href="javascript:openVideoHTMLvs('1207430','bt', 'Noman Siddiqui');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	<div class="scorecard-out-text show-phone" style="margin-left:34px;">- <a href="viewPlayer.do?playerId=1277414&amp;clubId=2565">-</a> - <a href="viewPlayer.do?playerId=2993131&amp;clubId=2565">-</a><a style="display:none" id="bthowOutPH_video_1207430" href="javascript:openVideoHTMLvs('1207430','out', 'Noman Siddiqui');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</div>
                                                                        </th> 
                                                                        <th class="hidden-phone">c <a href="viewPlayer.do?playerId=1277414&amp;clubId=2565">-</a> - <a href="viewPlayer.do?playerId=2993131&amp;clubId=2565">-</a><a id="bthowOut_video_1207430" style="display:none" href="javascript:openVideoHTMLvs('1207430','out', 'Noman Siddiqui');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                        <th style="text-align: right;"><b>{{$item->total_runs}}</b></th>
                                                                        <th style="text-align: right;">{{$player_balls[$item->playerId]}}</th> 
                                                                        <th style="text-align: right;">{{$item->total_fours}}<a style="display:none" id="btfour_video_1207430" href="javascript:openVideoHTMLvs('1207430','four', 'Noman Siddiqui');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                        <th style="text-align: right;">{{$item->total_six}}</th>
                                                                        <th style="text-align: center;">{{ isset($player_balls[$item->playerId]) && $player_balls[$item->playerId] != 0 ? number_format(($item->total_runs / $player_balls[$item->playerId]) * 100, 2) : 0.00 }}</th>
                                                                    </tr> 
                                                                 
                                                                    @endif
                                                                    @endforeach
                                                                    <tr> 
                                                                        <th>Extras<div class="scorecard-out-text show-phone"></div></th> 
                                                                        <th class="hidden-phone">
																		@foreach($extra_runs as $item)
		@if($item->inningnumber == 1)
			( lb {{ $item->byes_total_runs }} w {{ $item->wideball_total_runs }} nb {{ $item->noball_total_runs }})
		
																		</th>
                                                                        <th style="text-align: right;"><b>{{($item->byes_total_runs) +($item->wideball_total_runs) + ($item->noball_total_runs)}}</b></th>
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
                                                                        <th>Total<div class="scorecard-out-text show-phone">(6 wickets, 12.0 overs)</div></th> 
                                                                        <th class="hidden-phone">({{$item->total_wicket}} wickets, {{round(($item->max_ball)/6) }}.{{($item->max_ball)%6 }}
 overs )</th>
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
        <img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">	                                                                    
      </th>
      <th>
        <a href=""><b>{{$player[$item->bowlerid]}}</b></a>
        <a style="display:none" id="bwl_video_3054417" href="javascript:openVideoHTMLvs('3054417','bwl', 'Rohit Miglani');">
          <img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px">
        </a>
      </th> 
      <th style="text-align: right;">{{ floor($item->over) }}.{{ 6 % ($item->over) }}</th>
      <th style="text-align: right;">
        {{$maiden_overs[$item->bowlerid] ?? 0}}
      </th>
      <th style="text-align: right;">{{$item->total_runs}}</th>
      <th style="text-align: right;">{{$item->total_wicket}}</th> 
      <th style="text-align: right;">{{ number_format(($item->total_runs) / round(($item->max_ball) / 6), 2) }}</th>
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
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="img-responsive center-block">	 
		                                                                            	
																						
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>{{$player[$score->playerId]}}</h5>
		                                                                                <h5>
																						


																						 
																						 {{$sum_score}}/{{$sum_wicket}}
																						
																						
																							
																						, 
		                                                                                
		                                                                                Over {{round($score['ballnumber']/6)}}.{{$score['ballnumber']%6}}</h5>
		                                                                                
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
                                                                       <th style="text-align: left;" class="show-phone">820 CC innings </th>
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
                                                                        <img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">
                                                                        
                                                                          <a href=""><b> {{$player[$item->playerId]}}</b> </a>
                                                                        	<a style="display:none" id="btm_video_3088351" href="javascript:openVideoHTMLvs('3088351','bt', 'Rohit Arora');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        <div class="scorecard-out-text show-phone" style="margin-left:34px">b <a href="viewPlayer.do?playerId=2674040&amp;clubId=2565">Irfan S</a><a style="display:none" id="bthowOutPH_video_3088351" href="javascript:openVideoHTMLvs('3088351','out', 'Rohit Arora');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</div>
                                                                        </th> 
                                                                        <th class="hidden-phone">b <a href="viewPlayer.do?playerId=2674040&amp;clubId=2565">Irfan S</a><a style="display:none" id="bthowOut_video_3088351" href="javascript:openVideoHTMLvs('3088351','out', 'Rohit Arora');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                            <th style="text-align: right;"><b>{{$item->total_runs}}</b></th>
                                                                            <th style="text-align: right;">{{$player_balls[$item->playerId]}}  </th>
                                                                            <th style="text-align: right;">{{$item->total_fours}}</th> 
                                                                        <th style="text-align: right;">{{$item->total_six}}</th>
                                                                        <th style="text-align: center;"> {{ isset($player_balls[$item->playerId]) && $player_balls[$item->playerId] != 0 ? number_format(($item->total_runs / $player_balls[$item->playerId]) * 100, 2) : 0.00 }}</th>
                                                                    </tr> 
                                                                    @endif
                                                                    @endforeach
                                                                    <tr> 
                                                                        <th>Extras<div class="scorecard-out-text show-phone">(b 0 lb 0 w 7 nb 0)</div></th> 
                                                                        <th class="hidden-phone">
																		
																												@foreach($extra_runs as $item)
												@if($item->inningnumber == 2)
													( lb {{ $item->byes_total_runs }} w {{ $item->wideball_total_runs }} nb {{ $item->noball_total_runs }})
												
																												</th>
																												<th style="text-align: right;"><b>{{($item->byes_total_runs) +($item->wideball_total_runs) + ($item->noball_total_runs)}}</b></th>
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
                                                                        <th>Total<div class="scorecard-out-text show-phone">(6 wickets, 12.0 overs)</div></th> 
                                                                        <th class="hidden-phone">({{$item->total_wicket}} wickets, {{ round(($item->max_ball)/6) }}.{{($item->max_ball)%6 }}
 overs )</th>
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
															      	<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">	                                                                    
                                                                    </th>

                                                                        <th>
                                                                        	<a href=""><b>{{$player[$item->bowlerid]}}</b></a><a style="display:none" id="bwl_video_3054417" href="javascript:openVideoHTMLvs('3054417','bwl', 'Rohit Miglani');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">{{($item->over) }}.{{6%($item->over) }}</th>
                                                                          <th style="text-align: right;">
																		  {{$maiden_overs[$item->bowlerid] ?? 0}}
																		</th>
                                                                          		<th style="text-align: right;">{{$item->total_runs}}</th>
                                                                        <th style="text-align: right;">{{$item->total_wicket}}</th> 
                                                                        </th>
                                                                        <th style="text-align: right;">{{ number_format(($item->total_runs)/round(($item->max_ball)/6), 2) }}
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
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="img-responsive center-block">	 
		                                                                            	
																						
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>{{$player[$score->playerId]}}</h5>
		                                                                                <h5>
																						


																						 
																						 {{$sum_score}}/{{$sum_wicket}}
																						
																						
																							
																						, 
		                                                                                
		                                                                                 Over {{round($score['ballnumber']/6)}}.{{$score['ballnumber']%6}}</h5>
		                                                                                
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
      															
												    		<a href="{{ route('downloadCSV', $match_results[0]->id) }}"><img alt="Download as Excel" title="Download as Excel" class="excelBtn" style="cursor:pointer;" src="/utilsv2/images/excel.png" width="32" height="32"></a>
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
                                                            {{$tournament[0]}}
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
							                                <th><a href="">Irfan Sadaat</a></th>
							                            </tr>
							                            <tr>															
														<th style="padding: 0px 0px"><table><tbody><tr><th><strong>Umpires: </strong> </th></tr></tbody></table></th><th style="padding: 0px 5px"><table><tbody><tr><th style="padding: 0px 5px">1. <a style="color: inherit;" href="">Saurabh Naik</a></th></tr><tr><th style="padding: 0px 5px">2. <a style="color: inherit;" href="">Syed Muhammad Talha Anwar</a></th></tr></tbody></table></th>  
														</tr>
														<tr>
															<th><strong>Location: </strong> </th><th> {{$ground[$match_data->ground_id]}}</th>
														</tr>
									
														<tr>
															<th>
																	<strong>Points Earned:</strong> </th><th>Royal Tigers: 6, 820 CC: 0</th>
														</tr>
														<tr title="America/New_York"><th style="padding-left: 10px"><strong>Match Start: </strong></th><th><strong></strong> &nbsp; &nbsp; &nbsp; &nbsp;{{$match_data->match_starttime->format('h:i:s A')}}  </th></tr><tr title="America/New_York">
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