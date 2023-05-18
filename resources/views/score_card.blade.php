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
                                                                        Royal Tigers innings   
                                                                        (12 overs maximum) </th> 
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
                                                                        	<div class="scorecard-out-text show-phone" style="margin-left:34px;">c <a href="viewPlayer.do?playerId=1277414&amp;clubId=2565">Sandy D</a> b <a href="viewPlayer.do?playerId=2993131&amp;clubId=2565">Shubh A P</a><a style="display:none" id="bthowOutPH_video_1207430" href="javascript:openVideoHTMLvs('1207430','out', 'Noman Siddiqui');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</div>
                                                                        </th> 
                                                                        <th class="hidden-phone">c <a href="viewPlayer.do?playerId=1277414&amp;clubId=2565">Sandy D</a> b <a href="viewPlayer.do?playerId=2993131&amp;clubId=2565">Shubh A P</a><a id="bthowOut_video_1207430" style="display:none" href="javascript:openVideoHTMLvs('1207430','out', 'Noman Siddiqui');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
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
                                                                        <th>Extras<div class="scorecard-out-text show-phone">(b 0 lb 1 w 5 nb 1)</div></th> 
                                                                        <th class="hidden-phone">(b 0 lb 1 w 5 nb 1)</th>
                                                                        <th style="text-align: right;"><b>7</b></th>
                                                                        <th></th> 
                                                                        <!-- <th class="show-phone-cell"></th> -->
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        
                                                                    </tr>
                                                                    <tr> 
                                                                        <th>Total<div class="scorecard-out-text show-phone">(6 wickets, 12.0 overs)</div></th> 
                                                                        <th class="hidden-phone">(6 wickets, 12.0 overs )</th>
                                                                        <th style="text-align: right;"><b>106</b></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                       <!--   <th class="show-phone-cell"></th> -->
                                                                        <th></th>
                                                                        <th></th>
                                                                    </tr>
                                                                     </tbody>
                                                                   
                                                                </table>
                                                                
                                                                <table class="table">
                                                                    <tbody><tr> 
                                                                        <th colspan="7">Did not bat: &nbsp;&nbsp;
                                                                        <i class="fa fa-user"></i> <a href=""><b>Saad Haroon</b></a>*, <i class="fa fa-user"></i> <a href=""><b>Khawar Azad</b></a>, <i class="fa fa-user"></i> <a href=""><b>Aziz Hassan</b></a></th>
                                                                    </tr>
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
                                                                       	<th style="text-align: right;">Dot</th>
                                                                        <th style="text-align: right;">R</th>
                                                                        <th style="text-align: right;">W</th> 
                                                                        <th style="text-align: right;">Econ</th>
                                                                        <th></th>
                                                                        
                                                                        
                                                                        </tr> 
                                                                    </thead> 
                                                                    <tbody> 
                                                                    <tr> 
                                                                    <th style="width:40px;">
															      	<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">	                                                                    
                                                                    </th>
                                                                        <th>
                                                                        	<a href=""><b>Rohit Miglani</b></a><a style="display:none" id="bwl_video_3054417" href="javascript:openVideoHTMLvs('3054417','bwl', 'Rohit Miglani');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">3.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          		<th style="text-align: right;">6</th>
                                                                        <th style="text-align: right;">27</th> 
                                                                        <th style="text-align: right;"><b>0</b>
                                                                        </th>
                                                                        <th style="text-align: right;">9.00</th>
                                                                        <th>
								(3w1nb) </th>
                                                                    </tr> 
                                                                    <tr> 
                                                                    <th style="width:40px;">
															      	<img src="https://cricclubs.com/documentsRep/profilePics/7216d4b9-4a00-4ee3-bd5c-99eebb0c7936.jpeg" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">	                                                                    
                                                                    </th>
                                                                        <th>
                                                                        	<a href=""><b>Shubh A Patel</b></a><a style="display:none" id="bwl_video_2993131" href="javascript:openVideoHTMLvs('2993131','bwl', 'Shubh A Patel');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">2.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          		<th style="text-align: right;">6</th>
                                                                        <th style="text-align: right;">13</th> 
                                                                        <th style="text-align: right;"><b>1</b>
                                                                        <a style="display:none" id="bwlwicket_video_2993131" href="javascript:openVideoHTMLvs('2993131','blwicket', 'Shubh A Patel');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                        <th style="text-align: right;">6.50</th>
                                                                        <th>
								(1w) </th>
                                                                    </tr> 
                                                                    <tr> 
                                                                    <th style="width:40px;">
															      	<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">	                                                                    
                                                                    </th>
                                                                        <th>
                                                                        	<a href=""><b>Rohit Arora</b></a><a style="display:none" id="bwl_video_3088351" href="javascript:openVideoHTMLvs('3088351','bwl', 'Rohit Arora');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">3.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          		<th style="text-align: right;">6</th>
                                                                        <th style="text-align: right;">19</th> 
                                                                        <th style="text-align: right;"><b>1</b>
                                                                        <a style="display:none" id="bwlwicket_video_3088351" href="javascript:openVideoHTMLvs('3088351','blwicket', 'Rohit Arora');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                        <th style="text-align: right;">6.33</th>
                                                                        <th></th>
                                                                    </tr> 
                                                                    <tr> 
                                                                    <th style="width:40px;">
															      	<img src="https://cricclubs.com/documentsRep/profilePics/a052f5b9-f07c-4597-9fa8-ccf72ba021c3.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">	                                                                    
                                                                    </th>
                                                                        <th>
                                                                        	<a href=""><b>Jival Sachdeva</b></a><a style="display:none" id="bwl_video_2155902" href="javascript:openVideoHTMLvs('2155902','bwl', 'Jival Sachdeva');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">2.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          		<th style="text-align: right;">4</th>
                                                                        <th style="text-align: right;">14</th> 
                                                                        <th style="text-align: right;"><b>2</b>
                                                                        <a style="display:none" id="bwlwicket_video_2155902" href="javascript:openVideoHTMLvs('2155902','blwicket', 'Jival Sachdeva');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                        <th style="text-align: right;">7.00</th>
                                                                        <th>
								(1w) </th>
                                                                    </tr> 
                                                                    <tr> 
                                                                    <th style="width:40px;">
															      	<img src="https://cricclubs.com/documentsRep/profilePics/e3eded48-ec87-4508-a149-0a192784861d.jpg" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">	                                                                    
                                                                    </th>
                                                                        <th>
                                                                        	<a href=""><b>Prashant Sachdeva</b></a>*<a style="display:none" id="bwl_video_1287040" href="javascript:openVideoHTMLvs('1287040','bwl', 'Prashant Sachdeva');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">1.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          		<th style="text-align: right;">1</th>
                                                                        <th style="text-align: right;">19</th> 
                                                                        <th style="text-align: right;"><b>1</b>
                                                                        <a style="display:none" id="bwlwicket_video_1287040" href="javascript:openVideoHTMLvs('1287040','blwicket', 'Prashant Sachdeva');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                        <th style="text-align: right;">19.00</th>
                                                                        <th></th>
                                                                    </tr> 
                                                                    <tr> 
                                                                    <th style="width:40px;">
															      	<img src="https://cricclubs.com/documentsRep/profilePics/66f17808-5d5e-48f7-83e9-ffc27ddc140a.jpeg" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">	                                                                    
                                                                    </th>
                                                                        <th>
                                                                        	<a href=""><b>Junesh Thapasi Muthu</b></a><a style="display:none" id="bwl_video_2617639" href="javascript:openVideoHTMLvs('2617639','bwl', 'Junesh Thapasi Muthu');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">1.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          		<th style="text-align: right;">3</th>
                                                                        <th style="text-align: right;">13</th> 
                                                                        <th style="text-align: right;"><b>0</b>
                                                                        </th>
                                                                        <th style="text-align: right;">13.00</th>
                                                                        <th></th>
                                                                    </tr> 
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
		                                                            <div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/b8e79395-ac46-4e68-b8a5-5ae31d78ee59.png" class="img-responsive center-block">	 
		                                                                            	
		                                                                            	
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Noman S</h5>
		                                                                                <h5>1-22 , 
		                                                                                
		                                                                                Over 3.2</h5>
		                                                                                
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="img-responsive center-block">	 
		                                                                            	
		                                                                            	
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Abdullah</h5>
		                                                                                <h5>2-33 , 
		                                                                                
		                                                                                Over 4.3</h5>
		                                                                                
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="img-responsive center-block">	 
		                                                                            	
		                                                                            	
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Turab G</h5>
		                                                                                <h5>3-59 , 
		                                                                                
		                                                                                Over 7.4</h5>
		                                                                                
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="img-responsive center-block">	 
		                                                                            	
		                                                                            	
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Saood P</h5>
		                                                                                <h5>4-59 , 
		                                                                                
		                                                                                Over 7.5</h5>
		                                                                                
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/e8b0d6d5-494d-4615-8832-66d07113eab5.png" class="img-responsive center-block">	 
		                                                                            	
		                                                                            	
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Manik S</h5>
		                                                                                <h5>5-76 , 
		                                                                                
		                                                                                Over 9.3</h5>
		                                                                                
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/14461a6d-3328-446b-9e27-82e35cf05d6f.jpeg" class="img-responsive center-block">	 
		                                                                            	
		                                                                            	
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Muhammad</h5>
		                                                                                <h5>6-82 , 
		                                                                                
		                                                                                Over 9.5</h5>
		                                                                                
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
											<div class="tab-pane" id="ballByBallTeam2">                                            
                                            <div class="match-innings-top-all">
                                                <div class="row">
                                                	<div class="col-sm-8">
                                                    	<div class="match-table-innings">
                                                        	<div class="about-table  table-responsive" id="tab1default">
                                                                <table class="table"> 
                                                                    <thead> 
                                                                        <tr> 
                                                                        <th style="text-align: left;" colspan="2" class="hidden-phone">820 CC innings <div class="name visible-xs"> </div> 
                                                                        
                                                                        (target: 107 runs from 12 overs) </th> 
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
                                                                            <th style="text-align: right;"> {{$item->total_fours}}</th>
                                                                            <th style="text-align: right;">{{$player_balls[$item->playerId]}}</th> 
                                                                        <th style="text-align: right;">{{$item->total_six}}</th>
                                                                        <th style="text-align: center;"> {{ isset($player_balls[$item->playerId]) && $player_balls[$item->playerId] != 0 ? number_format(($item->total_runs / $player_balls[$item->playerId]) * 100, 2) : 0.00 }}</th>
                                                                    </tr> 
                                                                    @endif
                                                                    @endforeach
                                                                    <tr> 
                                                                        <th>Extras<div class="scorecard-out-text show-phone">(b 0 lb 0 w 7 nb 0)</div></th> 
                                                                        <th class="hidden-phone">(b 0 lb 0 w 7 nb 0)</th>
                                                                        <th style="text-align: right;"><b>7</b></th>
                                                                        <th></th> 
                                                                       <!--  <th class="show-phone-cell"></th> -->
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                    </tr>
                                                                    <tr> 
                                                                        <th>Total<div class="scorecard-out-text show-phone">(8 wickets, 10.4 overs)</div></th> 
                                                                        <th class="hidden-phone">(8 wickets, 10.4 overs)</th>
                                                                        <th style="text-align: right;"><b>70</b></th>
                                                                        <th></th> 
                                                                        <!-- <th class="show-phone-cell"></th> -->
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                    </tr>
                                                                    </tbody>
                                                                    </table>
                                                                    <table class="table"> 
                                                                    <tbody><tr> 
                                                                        <th colspan="7">Did not bat: &nbsp;&nbsp;
                                                                        <i class="fa fa-user"></i><a href=""><b>Niral Kumar</b></a>, <i class="fa fa-user"></i><a href=""><b>Bikram Sandhu</b></a></th>
                                                                    </tr>
                                                                    
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
                                                                          <th style="text-align: right;">Dot</th>
                                                                        <th style="text-align: right;">R</th>
                                                                        <th style="text-align: right;">W</th> 
                                                                        <th style="text-align: right;">Econ</th>
                                                                        <th></th>
                                                                        </tr> 
                                                                    </thead> 
                                                                    <tbody> 
                                                                    <tr>
                                                                    <th style="width:40px;">
																		<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">
																	</th> 
                                                                        <th>
                                                                        	<a href=""><b>Irfan Sadaat</b></a><a style="display:none" id="bwl_video_2674040" href="javascript:openVideoHTMLvs('2674040','bwl', 'Irfan Sadaat');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">2.4</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          <th style="text-align: right;">14</th>
                                                                        <th style="text-align: right;">5</th> 
                                                                        <th style="text-align: right;"><b>5</b>
                                                                        <a style="display:none" id="bwlwicket_video_2674040" href="javascript:openVideoHTMLvs('2674040','blwicket', 'Irfan Sadaat');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                        <th style="text-align: right;">1.88</th>
                                                                       	<th>
								(3w) </th>
                                                                    </tr> 
                                                                    <tr>
                                                                    <th style="width:40px;">
																		<img src="https://cricclubs.com/documentsRep/profilePics/ded9fcde-c763-4134-ac0b-e76970da84a4.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">
																	</th> 
                                                                        <th>
                                                                        	<a href=""><b>Imran Samdani</b></a><a style="display:none" id="bwl_video_1207349" href="javascript:openVideoHTMLvs('1207349','bwl', 'Imran Samdani');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">2.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          <th style="text-align: right;">8</th>
                                                                        <th style="text-align: right;">16</th> 
                                                                        <th style="text-align: right;"><b>1</b>
                                                                        <a style="display:none" id="bwlwicket_video_1207349" href="javascript:openVideoHTMLvs('1207349','blwicket', 'Imran Samdani');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                        <th style="text-align: right;">8.00</th>
                                                                       	<th></th>
                                                                    </tr> 
                                                                    <tr>
                                                                    <th style="width:40px;">
																		<img src="https://cricclubs.com/documentsRep/profilePics/e8b0d6d5-494d-4615-8832-66d07113eab5.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">
																	</th> 
                                                                        <th>
                                                                        	<a href=""><b>Manik Sharma</b></a><a style="display:none" id="bwl_video_941674" href="javascript:openVideoHTMLvs('941674','bwl', 'Manik Sharma');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">1.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                        <th style="text-align: right;">11</th> 
                                                                        <th style="text-align: right;"><b>0</b>
                                                                        </th>
                                                                        <th style="text-align: right;">11.00</th>
                                                                       	<th>
								(2w) </th>
                                                                    </tr> 
                                                                    <tr>
                                                                    <th style="width:40px;">
																		<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">
																	</th> 
                                                                        <th>
                                                                        	<a href=""><b>Abdullah Abbas</b></a><a style="display:none" id="bwl_video_2237865" href="javascript:openVideoHTMLvs('2237865','bwl', 'Abdullah Abbas');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">2.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          <th style="text-align: right;">7</th>
                                                                        <th style="text-align: right;">15</th> 
                                                                        <th style="text-align: right;"><b>0</b>
                                                                        </th>
                                                                        <th style="text-align: right;">7.50</th>
                                                                       	<th>
								(2w) </th>
                                                                    </tr> 
                                                                    <tr>
                                                                    <th style="width:40px;">
																		<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">
																	</th> 
                                                                        <th>
                                                                        	<a href=""><b>Saood Pirzada</b></a><a style="display:none" id="bwl_video_3103735" href="javascript:openVideoHTMLvs('3103735','bwl', 'Saood Pirzada');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">2.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          <th style="text-align: right;">8</th>
                                                                        <th style="text-align: right;">14</th> 
                                                                        <th style="text-align: right;"><b>1</b>
                                                                        <a style="display:none" id="bwlwicket_video_3103735" href="javascript:openVideoHTMLvs('3103735','blwicket', 'Saood Pirzada');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        	</th>
                                                                        <th style="text-align: right;">7.00</th>
                                                                       	<th></th>
                                                                    </tr> 
                                                                    <tr>
                                                                    <th style="width:40px;">
																		<img src="https://cricclubs.com/documentsRep/profilePics/14461a6d-3328-446b-9e27-82e35cf05d6f.jpeg" class="left-block img-circle" style="width:23px; height:23px;margin-right:8px">
																	</th> 
                                                                        <th>
                                                                        	<a href=""><b>Muhammad Salman Arshad</b></a><a style="display:none" id="bwl_video_1036746" href="javascript:openVideoHTMLvs('1036746','bwl', 'Muhammad Salman Arshad');"><img alt="Watch Ball Video" title="Watch Ball Video" src="/utilsv2/images/youtube.png" width="20px" height="20px"></a>
                                                                        </th> 
                                                                        <th style="text-align: right;">1.0</th>
                                                                          <th style="text-align: right;">0</th>
                                                                          <th style="text-align: right;">3</th>
                                                                        <th style="text-align: right;">9</th> 
                                                                        <th style="text-align: right;"><b>0</b>
                                                                        </th>
                                                                        <th style="text-align: right;">9.00</th>
                                                                       	<th></th>
                                                                    </tr> 
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
		                                                            <div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="img-responsive center-block">
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Rohit A</h5>
		                                                                                <h5>1-2 , 
		                                                                                
		                                                                                 
		                                                                                Over 0.4</h5>
		                                                                               
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="img-responsive center-block">
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Rohit M</h5>
		                                                                                <h5>2-4 , 
		                                                                                
		                                                                                 
		                                                                                Over 1.5</h5>
		                                                                               
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/66f17808-5d5e-48f7-83e9-ffc27ddc140a.jpeg" class="img-responsive center-block">
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Junesh T</h5>
		                                                                                <h5>3-4 , 
		                                                                                
		                                                                                 
		                                                                                Over 2.2</h5>
		                                                                               
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/7216d4b9-4a00-4ee3-bd5c-99eebb0c7936.jpeg" class="img-responsive center-block">
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Shubh A </h5>
		                                                                                <h5>4-5 , 
		                                                                                
		                                                                                 
		                                                                                Over 2.4</h5>
		                                                                               
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/a052f5b9-f07c-4597-9fa8-ccf72ba021c3.png" class="img-responsive center-block">
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Jival S</h5>
		                                                                                <h5>5-6 , 
		                                                                                
		                                                                                 
		                                                                                Over 2.6</h5>
		                                                                               
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/4b652093-bde7-4ffe-b316-1a9d22dcb530.png" class="img-responsive center-block">
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Sandy D</h5>
		                                                                                <h5>6-68 , 
		                                                                                
		                                                                                 
		                                                                                Over 9.3</h5>
		                                                                               
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/e3eded48-ec87-4508-a149-0a192784861d.jpg" class="img-responsive center-block">
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Prashant</h5>
		                                                                                <h5>7-68 , 
		                                                                                
		                                                                                 
		                                                                                Over 9.4</h5>
		                                                                               
		                                                                            </div>
		                                                                           </div>
		                                                                    </div>
																		
																		<div class="col-sm-6 col-xs-6 sp">
		                                                                    	<div class="fall-in-all">
		                                                                        	<div class="fall-image">
		                                                                        	<a href="">
		                                                                            	<img src="https://cricclubs.com/documentsRep/profilePics/04a9757e-fe42-4d15-a2d1-b4a4cc879802.png" class="img-responsive center-block">
		                                                                            	</a>
		                                                                            	</div>
		                                                                            	<div class="fall-text">
		                                                                            	<h5>Kanwarje</h5>
		                                                                                <h5>8-70 , 
		                                                                                
		                                                                                 
		                                                                                Over 10.4</h5>
		                                                                               
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
      															<a href="javascript:window.open('');" class="btn btn-default dropdown-toggle">Paper Score Card</a>
      														</div>
      														<img alt="Print" title="Print" style="cursor:pointer;" src="/utilsv2/images/print.png" onclick="printScorecard();" width="32" height="32">&nbsp;
												   			<img alt="Download as PDF" title="Download as PDF" style="cursor:pointer;" src="/utilsv2/images/pdf.png" onclick="pdfScorecard();" width="32" height="32">&nbsp;
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
														<tr title="America/New_York"><th style="padding-left: 10px"><strong>1st Innings: </strong></th><th><strong>45 min</strong> &nbsp; &nbsp; &nbsp; &nbsp;{{$match_data->match_starttime->format('h:i:s A')}}   &nbsp; &nbsp; &nbsp; &nbsp;{{$match_data->match_endtime->format('h:i:s A')}}</th></tr><tr title="America/New_York"><th style="padding-left: 10px"><strong><font size="2">Innings break:</font></strong></th><th><strong>5 min</strong> &nbsp; &nbsp; &nbsp; &nbsp;9:16 PM   &nbsp; &nbsp; &nbsp; &nbsp;9:21 PM </th></tr><tr title="America/New_York"><th style="padding-left: 10px"><strong>2nd Innings: </strong></th><th><strong>33 min</strong> &nbsp; &nbsp; &nbsp; &nbsp;{{$match_data->match_starttime->format('h:i:s A')}}    &nbsp; &nbsp; &nbsp; &nbsp;{{$match_data->match_endtime->format('h:i:s A')}} </th></tr><tr title="America/New_York">
															<th><strong>Last Updated:  </strong></th><th>Syed Muhammad Talha Anwar ({{$match_data-> updated_at }})
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
