@extends('default')
@section('content')

<div id="mainDiv" class="holder scorecard-inner">
    	<div class="score-all">
            <div class="score-top text-center">
                <div class="container">
                    <div class="match-summary">
                        <div class="row">
                        
					 <div class="col-lg-12">
					                   <div class="schedule-logo text-center vsteam-image">
					                   
					                       <ul class="list-inline" style="display:flex; align-items:center; justify-content:center">
                                 @if($team_one_runs < $team_two_runs)
					                           <li class="lose" style="width:25%">
                                     @else
                                     <li class="win" style="width:25%">
                                     @endif
					                           		<span class="teamName">
                                         {{$teams_one}}
                                          <br></span>
					                           		
					                           <span>  @foreach($total_run as $run) 
                                                    @if($run->inningnumber==1)   
                                                    {{ $run->total_runs }}/  @foreach($total_wickets as $wicket)
                                                      @if($wicket->inningnumber == $run->inningnumber)
                                                          {{ $wicket->total_wickets }}
                                                      @endif
                                                  @endforeach
                                                    @endif
                                                    @endforeach</span> <br>
					                           			<p style="text-transform: lowercase;">
                                           @foreach($innings as $inningover)
                                                              @if($inningover->inningnumber == 1)
                                                              Over 
                                                              @if($inningover->max_ball%6 == 0)
                                                              {{ $inningover->max_over }}.{{$inningover->max_ball%6}} 
                                                              @else
                                                              {{ $inningover->max_over-1 }}.{{$inningover->max_ball%6}} 
                                                              @endif
                                                              @endif
                                                              @endforeach
                                          /{{$match_over}} ov
					                           			</p>
					                           		</li>
					                           <li><a href="">
					                           <img src="https://eoscl.ca/admin/public/Team/{{$teams_oneid}}.png" class="img-responsive img-circle"></a></li>
					                           <li><a href="">
					                           <img src="https://eoscl.ca/admin/public/Team/{{$teams_twoid}}.png" class="img-responsive img-circle"></a></li>
                                     @if($team_one_runs > $team_two_runs)
					                           <li class="lose" style="width:25%">
                                     @else
                                     <li class="win" style="width:25%">
                                     @endif
					                           		<span class="teamName">  {{$teams_two}}<br></span>
					                           <span>  @foreach($total_run as $run) 
                                                    @if($run->inningnumber==2)   
                                                    {{ $run->total_runs }}/  @foreach($total_wickets as $wicket)
                                                      @if($wicket->inningnumber == $run->inningnumber)
                                                          {{ $wicket->total_wickets }}
                                                      @endif
                                                  @endforeach
                                                    @endif
                                                    @endforeach</span> <br> 
						                           		<p style="text-transform: lowercase;">
                                           @foreach($innings as $inningover)
                                                              @if($inningover->inningnumber == 2)
                                                              Over 
                                                              @if($inningover->max_ball%6 == 0)
                                                              {{ $inningover->max_over }}.{{$inningover->max_ball%6}}
                                                              @else
                                                              {{ $inningover->max_over-1 }}.{{$inningover->max_ball%6}} 
                                                              @endif
                                                              @endif
                                                              @endforeach
                                           
                                           /{{$match_over}} ov

						                           		</p>
						                           </li>
					                       </ul>
					                       
					                   </div>
					               </div>
                        </div>
                    </div>
                   
                    <h3>{{$tournament}}:  League &nbsp; - &nbsp;{{$match_results[0]->match_result_description}} 
                   ({{ date('d/m/Y', strtotime($match_results[0]->created_at)) }} )</h3>
                     <div class="row">
	                    <div class="col-sm-10">
	                    </div>
						<div class="col-sm-2">
						
				</div>
				</div>
				
				<!-- <div class="exportOptions-panel hidden-phone" style="float: right"> 
					<img alt="Print" title="Print" style="cursor: pointer;" width="32" height="32" src="/utilsv2/images/print.png" onclick="printScorecard();" />&nbsp; 
						<img alt="Download as PDF" title="Download as PDF" style="cursor: pointer;" width="32" height="32" src="/utilsv2/images/pdf.png" onclick="pdfScorecard();" />
				</div>-->
			

				</div>
            </div>
   <div class="all-tab-table sp scorecard">
       <div class="container">
       
      <div class="show-phone">
			</div>

       	<div class="score-tab">            
           	<div class="complete-list">
           	 	<div class="panel with-nav-tabs panel-default">
                <div class="panel-heading score-tabs">
                           <ul class="nav nav-tabs">
  							<li class="active"><a href="{{ route('balltoballScorecard', $match_results[0]->id) }}" >Ball By Ball</a></li>
							<li><a href="{{ route('fullScorecard', $match_results[0]->id) }}" >Full Scorecard</a></li>
							<li ><a href="{{ route('fullScorecard_overbyover', $match_results[0]->id) }}" >Over by Over Score</a></li>
							<li><a href="{{ route('fullScorecard_chart', $match_results[0]->id) }}"  >Charts</a></li>
							</ul>
                       </div>
                       <div class="panel-body">
                           <div class="tab-content">
                               <div class="tab-pane fade in active" id="tab1default">
                              <style>
.end-head{
   min-height: 68px!important;
}
.end-red{
   min-height: 68px!important;
}

.over-ending{
padding: 0px;
}
.end-test span{
   font-weight: 600!important;
}
.end-test{
margin: 8px 0px!important;
}
@media ( max-width : 768px) {
	.over-ending{
	padding: 0px 15px;
	}
	.end-test{
margin: 0px 0px!important;
}
}
.match-summary-tab .ball-by-ball-section{
padding: 0px!important;
}
.ball-by-ball-section .bbb-row .col2{
min-width: 100px;}
.ball-by-ball-section .bbb-row .col3{
flex: auto;}
</style>
<div>
  <div class="match-content">
      <div class="row">
          <div class="col-sm-7">
          <div class="complete-list">
      <div class="with-nav-tabs panel-default">
                 <div class="panel-heading">
                           <ul class="nav nav-tabs">
              
              <li id="ballByBallTeamTab1" class="active"><a href="#ballByBallTeam1" role="tab" data-toggle="tab" onclick="resizeScroll();">{{$teams_one}}</a></li>
              <li id="ballByBallTeamTab2" ><a href="#ballByBallTeam2" role="tab" data-toggle="tab" onclick="resizeScroll();">{{$teams_two}}</a></li>
              <!-- Super Over changes Start -->              
		<!-- Super Over changes End -->        
              </ul>
                        </div>
<!-- Test match Start -->                        
                       <div class="panel-body match-summary-tab">
          <div class="tab-content summary-list">
            <div class="tab-pane active" id="ballByBallTeam1">
          <div class="ball-by-ball-section">
                          
                              <ul class="list-inline bbb-row" id="ballid_651469">
                              
                  
                   <li class="col2">
                                <span class="ov" style="padding-left: 5px;">
                                    
                                    <!-- Condition for match level comment to not to show over and ball number -->
                                     </span></li> 
                   <li class="col3">
                                    <strong>
                                      {{$teams_one}} Players: </strong>
                                      @foreach($teams_one_player as $item)
                                      {{$player[$item]}},
                                      @endforeach
                                      </li>
                     <span class="hidden-phone" style="float: right; font-size:14px; margin-right: 10px;width: 135px;
    text-align: right;"><i class="fa fa-clock-o" style="padding-right: 3px;"></i> 
    					<span> </span></span>
                       
                       <div class="col-1 text-right lakshman"> 
                       </div>
                      </ul>
                                <ul class="list-inline bbb-row" id="ballid_651470">
                              
                  
                   <li class="col2">
                                <span class="ov" style="padding-left: 5px;">
                                    
                                    <!-- Condition for match level comment to not to show over and ball number -->
                                     </span></li> 
                   <li class="col3">
                                    <strong>{{$teams_two}} Players: </strong>
                                    @foreach($teams_two_player as $item)
                                      {{$player[$item]}},
                                      @endforeach
                                  </li>
                     <span class="hidden-phone" style="float: right; font-size:14px; margin-right: 10px;width: 135px;
    text-align: right;"><i class="fa fa-clock-o" style="padding-right: 3px;"></i> 
    					<span> </span></span>
                       
                       <div class="col-1 text-right lakshman"> 
                       </div>
                      </ul>
                                <ul class="list-inline bbb-row" id="ballid_651471">
                              
                  
                   <li class="col2">
                                <span class="ov" style="padding-left: 5px;">
                                    
                                    <!-- Condition for match level comment to not to show over and ball number -->
                                     </span></li> 
                   <li class="col3">
                                    <strong>
                                    {{$teams[$match_data->toss_winning_team_id]}} 
															@if ($teams[$match_data->toss_winning_team_id] == $teams[$match_data->first_inning_team_id])
																won the toss and elected to bat first.
															@elseif ($teams[$match_data->toss_winning_team_id] == $teams[$match_data->second_inning_team_id])
																won the toss and elected to bowl first.
															@else
																Invalid data in the database.
															@endif
                                </strong></li>
                     <span class="hidden-phone" style="float: right; font-size:14px; margin-right: 10px;width: 135px;
    text-align: right;"><i class="fa fa-clock-o" style="padding-right: 3px;"></i> 
    					<span> </span></span>
                       
                       <div class="col-1 text-right lakshman"> 
                       </div>
                      </ul>

                     
                             

                      @foreach($match_detail as $matchball)
                      @if($matchball->inningnumber==1)
                                <ul class="list-inline bbb-row" id="ballid_651486">
                              
                  
                   <li class="col2">
                                <span class="zero"><i class="fa fa-dot-circle-o"></i>{{$matchball->runs}} {{$matchball->balltype}}</span>
                                <span class="ov" style="padding-left: 5px;">
                                    
                                    <!-- Condition for match level comment to not to show over and ball number -->
                                    {{$matchball->overnumber-1}}  .@if($matchball->ballnumber == 0) 0 
                                    @elseif($matchball->ballnumber%6 == 0)
                                    6 @else
                                    {{$matchball->ballnumber%6}}
                                  @endif</span> &nbsp;
                                       </li> 
                   <li class="col3">
                   {{$player[$matchball->bowlerId]}} to {{$player[$matchball->playerId]}}, {{$matchball->runs}} {{$matchball->balltype}} </li>
                     <span class="hidden-phone" style="float: right; font-size:14px; margin-right: 10px;width: 135px;
    text-align: right;"><i class="fa fa-clock-o" style="padding-right: 3px;"></i> 
    					<span> </span></span>
                       
                       <div class="col-1 text-right lakshman"> 
                       </div>
                      </ul>
                      @endif
                      @endforeach

                                <div class="end-over">
                                      <div class="row"> 
                                       <div class="col-sm-10 sp">
                                              <div class="end-red">
                                                  <div class="row">
                                                      <div class="col-sm-3 col-xs-6 sp"> 
                                                          <div class="end-head" style="text-align: left;">                                                           
                                                              <p style="line-height: 1.85">
                                                              @foreach($innings as $inningover)
                                                              @if($inningover->inningnumber == 1)
                                                              Over 
                                                              @if($inningover->max_ball%6 == 0)
                                                              {{ $inningover->max_over }}.{{$inningover->max_ball%6}} ({{ $teams_two }})
                                                              @else
                                                              {{ $inningover->max_over-1 }}.{{$inningover->max_ball%6}} ({{ $teams_two }})
                                                              @endif
                                                              @endif
                                                              @endforeach
                                                             
                                                                   </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 col-xs-12 sp over-ending">  
                                                          <div class="end-test">
                                                          @foreach($matchnotouts1 as $player1)
                                                          <p style="line-height: 1.85">
                                                          
                                                          {{$player[$player1->playerId]}} N<span style="float: right;"> {{$player1->total_runs}}(
                                                           
                                                            {{$player_balls[$player1->playerId]??0}}
                                                            
                                                            )</span></p>
                                                          @endforeach
                                                                </div>
                                                        </div>
                                                        <div class="col-sm-5 col-xs-12 sp"> 
                                                          <div class="end-test">
                                                          <p style="line-height: 1.85">{{$player[$runnerbowler1->bowlerid]}} J<span style="float: right;"> {{$runnerbowler1->total_ball}}.{{$runnerbowler1->max_ball%6}}-0-{{$runnerbowler1->total_runs}}-{{$runnerbowler1->total_wickets}}</span> </p>
                                                              <p style="line-height: 1.85" class="hidden-phone">Run Rate : <span style="float: right"> {{number_format($team_one_run_rate, 2)}}</span>
                                                              </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 sp">
                                              <div class="end-head sp">
                                                <table style="width: 100%"><tbody><tr><td>
                                                    <h4 class="hidden-phone">TOR</h4>
                                                    <h4 class="show-phone"></h4>
                                                    <h4><span>
                                                    @foreach($total_run as $run) 
                                                    @if($run->inningnumber==1)   
                                                    {{ $run->total_runs }}/  @foreach($total_wickets as $wicket)
                                                      @if($wicket->inningnumber == $run->inningnumber)
                                                          {{ $wicket->total_wickets }}
                                                      @endif
                                                  @endforeach
                                                    @endif
                                                    @endforeach
                                                    </span></h4>
                                                    </td><td style="text-align: right;"><label class="show-phone" style="font-size: 14px;color: white;">Run Rate : {{number_format($team_one_run_rate, 2)}}</label></td></tr></tbody></table>
                                                </div>
                                                
                                            </div>
                                        </div>                                        
                                        
                                    </div>
                                    </div>  
                           </div>   
                                
              <div class="tab-pane " id="ballByBallTeam2">
            
                              <div class="ball-by-ball-section">
                              @foreach($match_detail as $matchball)
                      @if($matchball->inningnumber==2)
                                <ul class="list-inline bbb-row" id="ballid_651486">
                              
                  
                   <li class="col2">
                                <span class="zero"><i class="fa fa-dot-circle-o"></i>{{$matchball->runs}} {{$matchball->balltype}}</span>
                                <span class="ov" style="padding-left: 5px;">
                                    
                                    <!-- Condition for match level comment to not to show over and ball number -->
                                    {{$matchball->overnumber-1}}  .@if($matchball->ballnumber == 0) 0 
                                    @elseif($matchball->ballnumber%6 == 0)
                                    6 @else
                                    {{$matchball->ballnumber%6}}
                                  @endif</span> &nbsp;
                                       </li> 
                   <li class="col3">
                   {{$player[$matchball->bowlerId]}} to {{$player[$matchball->playerId]}}, {{$matchball->runs}} {{$matchball->balltype}} </li>
                     <span class="hidden-phone" style="float: right; font-size:14px; margin-right: 10px;width: 135px;
    text-align: right;"><i class="fa fa-clock-o" style="padding-right: 3px;"></i> 
    					<span> </span></span>
                       
                       <div class="col-1 text-right lakshman"> 
                       </div>
                      </ul>
                      @endif
                      @endforeach
                      


                      <div class="end-over">
                                      <div class="row"> 
                                       <div class="col-sm-10 sp">
                                              <div class="end-red">
                                                  <div class="row">
                                                      <div class="col-sm-3 col-xs-6 sp"> 
                                                          <div class="end-head" style="text-align: left;">                                                           
                                                              <p style="line-height: 1.85">
                                                              @foreach($innings as $inningover)
                                                              @if($inningover->inningnumber == 2)
                                                              Over 
                                                              @if($inningover->max_ball%6 == 0)
                                                              {{ $inningover->max_over }}.{{$inningover->max_ball%6}} ({{ $teams_two }})
                                                              @else
                                                              {{ $inningover->max_over-1 }}.{{$inningover->max_ball%6}} ({{ $teams_two }})
                                                              @endif
                                                              @endif
                                                              @endforeach
                                                             
                                                                   </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 col-xs-12 sp over-ending">  
                                                          <div class="end-test">
                                                          @foreach($matchnotouts2 as $player1)
                                                          <p style="line-height: 1.85">
                                                          
                                                          {{$player[$player1->playerId]}} N<span style="float: right;"> {{$player1->total_runs}}(
                                                           
                                                            {{$player_balls[$player1->playerId]??0}}
                                                            
                                                            )</span></p>
                                                          @endforeach
                                                                </div>
                                                        </div>
                                                        <div class="col-sm-5 col-xs-12 sp"> 
                                                          <div class="end-test">
                                                         
                                                          @if(isset($runnerbowler2) && is_object($runnerbowler2))
    <p style="line-height: 1.85">
        {{$player[$runnerbowler2->bowlerid]}} J
        <span style="float: right;">
            {{$runnerbowler2->total_ball}}.{{$runnerbowler2->max_ball%6}}-0-{{$runnerbowler2->total_runs}}-{{$runnerbowler2->total_wickets}}
        </span>
    </p>
@endif



                                                              <p style="line-height: 1.85" class="hidden-phone">Run Rate : <span style="float: right"> {{number_format($team_two_run_rate, 2)}}</span>
                                                              </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 sp">
                                              <div class="end-head sp">
                                                <table style="width: 100%"><tbody><tr><td>
                                                    <h4 class="hidden-phone">TOR</h4>
                                                    <h4 class="show-phone"></h4>
                                                    <h4><span>
                                                    @foreach($total_run as $run) 
                                                    @if($run->inningnumber==2)   
                                                    {{ $run->total_runs }}/  @foreach($total_wickets as $wicket)
                                                      @if($wicket->inningnumber == $run->inningnumber)
                                                          {{ $wicket->total_wickets }}
                                                      @endif
                                                  @endforeach
                                                    @endif
                                                    @endforeach
                                                    </span></h4>
                                                    </td><td style="text-align: right;"><label class="show-phone" style="font-size: 14px;color: white;">Run Rate : {{number_format($team_one_run_rate, 2)}}</label></td></tr></tbody></table>
                                                </div>
                                                
                                            </div>
                                        </div>                                        
                                        
                                    </div>
                                    </div>  
                           </div>
                      
                            
                            

                            
<!-- Super Over data Starts -->                        
          					<div class="tab-pane " id="ballByBallSuperOver1">
          						<div class="ball-by-ball-section">
          							</div>
          					</div>
          					<div class="tab-pane " id="ballByBallSuperOver2">
          						<div class="ball-by-ball-section">
          							</div>
          					</div>
                        
<!-- Super Over data Ends -->           
                            </div>
                        </div>
                         </div>  
                </div>
            </div>
            <div class="col-sm-5">
        <div class="hidden-phone">
          </div>
        <div class="chart">
                    <div class="border-heading">
                        <h5>Grounds</h5>
                    </div>
            <div id="curve_chart" style="width:100%;height: 300px;">
                   <img src="https://i.pinimg.com/564x/2f/83/cd/2f83cd1ec292ea9d35f2a475813eb6d6.jpg" alt="">
                </div>
        </div>
        </div>
    </div>
</div>















    
    <style>
    

    .addBallBtn{
      background-color: black;
      color:#fff;
      margin: 0px 0px 10px 15px;
    }
    .addBallBtn:hover{
      background-color: black;
      color:#fff;
    }
    .btn:focus, .btn.focus {
    color: #fff;
    text-decoration: none;
  }
    
    </style>  </div>
                               <div class="tab-pane fade " id="tab2default">
                               
                               
									Loading ...
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
            
        </div>
    </div>
<div id="holder2" class="holder2" style="display:none"></div>
<div id="divhidden" class="divhidden" style="display:none"></div>













<link href="/utilsv2/upload/uploadfile.css" rel="stylesheet">
<script src="/utilsv2/upload/jquery.uploadfile.min.js"></script>
<script src="/utilsv2/js/youtube_iframe_api.js"></script>



<style>

.sponser1{
	
	background-color: #2ea1cd;
    margin-left: 3px;
    width: 98%;
    color: white;
    font-size: 16px;
    text-align:center;
}
</style>


<style>
				.footer-bottom{
					width: 100%;
   			  position: fixed;
   			  bottom: 0;
				}
			</style>
 
							

 <script type="text/javascript" src="/utilsv2/js/duplicate.js"></script>
 <script type="text/javascript" src="/utilsv2/js/forms.js"></script>

  
        
    

</div>
@stop