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
							<li><a href="#tab4default" role="tab" data-toggle="tab" onclick="loadView('graphsView');">Charts</a></li>
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
                        <h5>Charts</h5>
                    </div>
            <div id="curve_chart" style="width:100%;height: 300px;"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 400px; height: 300px;"><div style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;" aria-label="A chart."><svg width="400" height="300" aria-label="A chart." style="overflow: hidden;"><defs id="_ABSTRACT_RENDERER_ID_0"><clipPath id="_ABSTRACT_RENDERER_ID_1"><rect x="40" y="45" width="320" height="210"></rect></clipPath></defs><rect x="0" y="0" width="400" height="300" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="95" y="286" width="210" height="11" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><rect x="95" y="286" width="105" height="11" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="121" y="295.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#222222"></text></g><path d="M95,291.5L117,291.5" stroke="#3366cc" stroke-width="3" fill-opacity="1" fill="none"></path></g><g><rect x="218" y="286" width="87" height="11" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="244" y="295.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#222222">Royal Tigers</text></g><path d="M218,291.5L240,291.5" stroke="#dc3912" stroke-width="3" fill-opacity="1" fill="none"></path></g></g><g><rect x="40" y="45" width="320" height="210" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g clip-path="url()"><g><rect x="40" y="254" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="212" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="170" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="129" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="87" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="45" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="233" width="320" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="40" y="191" width="320" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="40" y="150" width="320" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="40" y="108" width="320" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="40" y="66" width="320" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect></g><g><rect x="40" y="45" width="1" height="210" stroke="none" stroke-width="0" fill="#333333"></rect><rect x="40" y="254" width="320" height="1" stroke="none" stroke-width="0" fill="#333333"></rect></g><g><path d="M40.5,254.5C40.5,254.5,58.22222222222311,244.39833333333402,67.08333333333333,239.87C75.94444444443559,235.34166666666596,84.80555555556441,230.1166666666597,93.66666666666666,227.33C102.5277777777689,224.5433333333403,111.38888888889775,225.24,120.25,223.15C129.11111111110225,221.06,137.9722222222311,218.6216666666597,146.83333333333331,214.79000000000002C155.6944444444356,210.9583333333403,164.5555555555644,206.7783333333403,173.41666666666666,200.16C182.2777777777689,193.5416666666597,200,175.08,200,175.08C200,175.08,200,175.08,200,175.08C200,175.08,226.58333333333331,150,226.58333333333331,150C226.58333333333331,150,226.58333333333331,150,226.58333333333331,150C226.58333333333331,150,244.3055555555644,140.94333333334032,253.16666666666666,135.37C262.0277777777689,129.79666666665972,270.8888888888978,123.875,279.75,116.56C288.6111111111022,109.245,297.4722222222311,97.40166666665971,306.3333333333333,91.48000000000002C315.19444444435584,85.5583333333403,332.91666666666663,81.03,332.91666666666663,81.03C332.91666666666663,81.03,332.91666666666663,81.03,332.91666666666663,81.03C332.91666666666663,81.03,359.5,64.31,359.5,64.31" stroke="#3366cc" stroke-width="3" fill-opacity="1" fill="none"></path><path d="M40.5,254.5C40.5,254.5,58.22222222222311,242.65666666666596,67.08333333333333,237.78C75.94444444443559,232.9033333333403,84.80555555556441,229.42000000000002,93.66666666666666,225.24C102.5277777777689,221.06,111.38888888889775,221.06,120.25,212.7C129.11111111110225,204.34,137.9722222222311,185.1816666666597,146.83333333333331,175.08C155.6944444444356,164.9783333333403,164.5555555555644,159.0566666666597,173.41666666666666,152.09C182.2777777777689,145.1233333333403,200,133.28,200,133.28C200,133.28,200,133.28,200,133.28C200,133.28,226.58333333333331,116.56,226.58333333333331,116.56C226.58333333333331,116.56,226.58333333333331,116.56,226.58333333333331,116.56C226.58333333333331,116.56,244.3055555555644,99.14333333334031,253.16666666666666,93.57000000000002C262.0277777777689,87.9966666666597,270.8888888888978,88.69333333334029,279.75,83.12C288.6111111111022,77.54666666665972,306.3333333333333,60.130000000000024,306.3333333333333,60.130000000000024" stroke="#dc3912" stroke-width="3" fill-opacity="1" fill="none"></path></g></g><g><circle cx="226.58333333333331" cy="150" r="6" stroke="none" stroke-width="0" fill="#3366cc"></circle><circle cx="332.91666666666663" cy="81.03" r="6" stroke="none" stroke-width="0" fill="#3366cc"></circle><circle cx="200" cy="133.28" r="6" stroke="none" stroke-width="0" fill="#dc3912"></circle></g><g><g><text text-anchor="middle" x="40.5" y="267.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">0</text></g><g><text text-anchor="middle" x="173.4167" y="267.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">5</text></g><g><text text-anchor="middle" x="306.3333" y="267.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">10</text></g><g><text text-anchor="end" x="36.333333333333336" y="258.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">0</text></g><g><text text-anchor="end" x="36.333333333333336" y="216.54999999999998" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">20</text></g><g><text text-anchor="end" x="36.333333333333336" y="174.75" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">40</text></g><g><text text-anchor="end" x="36.333333333333336" y="132.95" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">60</text></g><g><text text-anchor="end" x="36.333333333333336" y="91.14999999999999" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">80</text></g><g><text text-anchor="end" x="36.333333333333336" y="49.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">100</text></g></g></g><g><g><text text-anchor="middle" x="200" y="281.35" font-family="Arial" font-size="11" font-style="italic" stroke="none" stroke-width="0" fill="#222222">Overs</text><rect x="40" y="272" width="320" height="11" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect></g><g><text text-anchor="middle" x="13.016666666666666" y="150" font-family="Arial" font-size="11" font-style="italic" transform="rotate(-90 13.016666666666666 150)" stroke="none" stroke-width="0" fill="#222222">Runs</text><path d="M3.6666666666666594,255L3.6666666666666723,45L14.666666666666671,45L14.666666666666659,255Z" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></path></g></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>Over</th><th></th><th>Royal Tigers</th><th></th><th></th></tr></thead><tbody><tr><td>0</td><td>0</td><td>0</td><td></td><td></td></tr><tr><td>1</td><td>7</td><td>8</td><td></td><td></td></tr><tr><td>2</td><td>13</td><td>14</td><td></td><td></td></tr><tr><td>3</td><td>15</td><td>20</td><td></td><td></td></tr><tr><td>4</td><td>19</td><td>38</td><td></td><td></td></tr><tr><td>5</td><td>26</td><td>49</td><td></td><td></td></tr><tr><td>6</td><td>38</td><td>58</td><td></td><td></td></tr><tr><td>6</td><td>38</td><td>58</td><td></td><td>58</td></tr><tr><td>7</td><td>50</td><td>66</td><td></td><td></td></tr><tr><td>7</td><td>50</td><td>66</td><td>50</td><td></td></tr><tr><td>8</td><td>57</td><td>77</td><td></td><td></td></tr><tr><td>9</td><td>66</td><td>82</td><td></td><td></td></tr><tr><td>10</td><td>78</td><td>93</td><td></td><td></td></tr><tr><td>11</td><td>83</td><td></td><td></td><td></td></tr><tr><td>11</td><td>83</td><td></td><td>83</td><td></td></tr><tr><td>12</td><td>91</td><td></td><td></td><td></td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 310px; left: 410px; white-space: nowrap; font-family: Arial; font-size: 11px;">Royal Tigers</div><div></div></div></div>
                    <div class="button-pool text-center">
                        <a href="#" onclick="loadView('graphs');" class="btn btn-chart"> View More Charts  <i class="fa  fa-chevron-circle-right"></i> </a>
                    </div>
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