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
					                           <li class="lose" style="width:25%">
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
					                           		  {{ $total_overs[1] }}/{{$match_total_overs}} ov
					                           			</p>
					                           		</li>
					                           <li><a href="/MississaugaCricketLeague/viewTeam.do?teamId=1072&amp;clubId=2565">
					                           <img src="https://cricclubs.com/documentsRep/teamLogos/8c45d0e8-b2c3-4d50-bb8b-70573b533552.jpg" class="img-responsive img-circle"></a></li>
					                           <li><a href="/MississaugaCricketLeague/viewTeam.do?teamId=1103&amp;clubId=2565">
					                           <img src="https://cricclubs.com/documentsRep/teamLogos/9deaa818-6154-4a1a-8eaf-89bb6e54f83d.jpg" class="img-responsive img-circle"></a></li>
					                           <li class="win" style="width:25%">
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
                                           {{ $total_overs[2] }}/{{$match_total_overs}} ov 
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
       
       <table style="width: 100%; margin-bottom: 10px;text-align: center;">
	<tbody><tr>
		<td><a class="show-phone" href="#" onclick="javascript:mobileFacebookShare();return false;"> <img src="/utilsv2/images/fb_new.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileTwitterShare();return false;"><img src="/utilsv2/images/twi.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileGoogleShare(); return false;"><img src="/utilsv2/images/goo.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileMailShare(); return false;"><img width="40" src="/utilsv2/images/mail.png"></a></td>
		<td><a class="show-phone whatsapp"><img src="/utilsv2/images/whatsapp.png"></a></td>
	</tr>
</tbody></table><div class="show-phone">
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
                                      {{$player[$item]}}
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
                                    {{$matchball->overnumber}}  . {{$matchball->ballnumber}}</span> &nbsp;
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
                                                              <p style="line-height: 1.85">Over  {{ $total_overs[1] }} ({{ $teams_one }})
                                                                   </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 col-xs-12 sp over-ending">  
                                                          <div class="end-test">
                                                          <p style="line-height: 1.85">Ramkiran N<span style="float: right;"> 12 (11)</span></p>
                                                               <p style="line-height: 1.85"> Imran N<span style="float: right;"> 4 (5)</span> </p>
                                                                </div>
                                                        </div>
                                                        <div class="col-sm-5 col-xs-12 sp"> 
                                                          <div class="end-test">
                                                                <p style="line-height: 1.85">Mujtaba J<span style="float: right;"> 3.0-0-17-0</span> </p>
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
                                                    <h4 class="show-phone">Toronto Jaguars</h4>
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
                                                    </td><td style="text-align: right;"><label class="show-phone" style="font-size: 14px;color: white;">Run Rate : 7.58</label></td></tr></tbody></table>
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
                                    {{$matchball->overnumber}}  . {{$matchball->ballnumber}}</span> &nbsp;
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
                                                              <p style="line-height: 1.85">Over 
                                                              {{ $total_overs[2] }} ({{ $teams_two }})
                                                                   </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 col-xs-12 sp over-ending">  
                                                          <div class="end-test">
                                                          <p style="line-height: 1.85">Ramkiran N<span style="float: right;"> 12 (11)</span></p>
                                                               <p style="line-height: 1.85"> Imran N<span style="float: right;"> 4 (5)</span> </p>
                                                                </div>
                                                        </div>
                                                        <div class="col-sm-5 col-xs-12 sp"> 
                                                          <div class="end-test">
                                                                <p style="line-height: 1.85">Mujtaba J<span style="float: right;"> 3.0-0-17-0</span> </p>
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
                                                    <h4 class="show-phone">Toronto Jaguars</h4>
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
                                                    </td><td style="text-align: right;"><label class="show-phone" style="font-size: 14px;color: white;">Run Rate : 7.58</label></td></tr></tbody></table>
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
            <div id="curve_chart" style="width:100%;height: 300px;"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 400px; height: 300px;"><div style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;" aria-label="A chart."><svg width="400" height="300" aria-label="A chart." style="overflow: hidden;"><defs id="_ABSTRACT_RENDERER_ID_0"><clipPath id="_ABSTRACT_RENDERER_ID_1"><rect x="40" y="45" width="320" height="210"></rect></clipPath></defs><rect x="0" y="0" width="400" height="300" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="95" y="286" width="210" height="11" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><rect x="95" y="286" width="105" height="11" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="121" y="295.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#222222">Toronto Jaguars</text></g><path d="M95,291.5L117,291.5" stroke="#3366cc" stroke-width="3" fill-opacity="1" fill="none"></path></g><g><rect x="218" y="286" width="87" height="11" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="244" y="295.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#222222">Royal Tigers</text></g><path d="M218,291.5L240,291.5" stroke="#dc3912" stroke-width="3" fill-opacity="1" fill="none"></path></g></g><g><rect x="40" y="45" width="320" height="210" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g clip-path="url(https://www.mississaugacricketleague.ca/MississaugaCricketLeague/ballbyball.do?matchId=3319&amp;clubId=2565#_ABSTRACT_RENDERER_ID_1)"><g><rect x="40" y="254" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="212" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="170" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="129" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="87" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="45" width="320" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="40" y="233" width="320" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="40" y="191" width="320" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="40" y="150" width="320" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="40" y="108" width="320" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="40" y="66" width="320" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect></g><g><rect x="40" y="45" width="1" height="210" stroke="none" stroke-width="0" fill="#333333"></rect><rect x="40" y="254" width="320" height="1" stroke="none" stroke-width="0" fill="#333333"></rect></g><g><path d="M40.5,254.5C40.5,254.5,58.22222222222311,244.39833333333402,67.08333333333333,239.87C75.94444444443559,235.34166666666596,84.80555555556441,230.1166666666597,93.66666666666666,227.33C102.5277777777689,224.5433333333403,111.38888888889775,225.24,120.25,223.15C129.11111111110225,221.06,137.9722222222311,218.6216666666597,146.83333333333331,214.79000000000002C155.6944444444356,210.9583333333403,164.5555555555644,206.7783333333403,173.41666666666666,200.16C182.2777777777689,193.5416666666597,200,175.08,200,175.08C200,175.08,200,175.08,200,175.08C200,175.08,226.58333333333331,150,226.58333333333331,150C226.58333333333331,150,226.58333333333331,150,226.58333333333331,150C226.58333333333331,150,244.3055555555644,140.94333333334032,253.16666666666666,135.37C262.0277777777689,129.79666666665972,270.8888888888978,123.875,279.75,116.56C288.6111111111022,109.245,297.4722222222311,97.40166666665971,306.3333333333333,91.48000000000002C315.19444444435584,85.5583333333403,332.91666666666663,81.03,332.91666666666663,81.03C332.91666666666663,81.03,332.91666666666663,81.03,332.91666666666663,81.03C332.91666666666663,81.03,359.5,64.31,359.5,64.31" stroke="#3366cc" stroke-width="3" fill-opacity="1" fill="none"></path><path d="M40.5,254.5C40.5,254.5,58.22222222222311,242.65666666666596,67.08333333333333,237.78C75.94444444443559,232.9033333333403,84.80555555556441,229.42000000000002,93.66666666666666,225.24C102.5277777777689,221.06,111.38888888889775,221.06,120.25,212.7C129.11111111110225,204.34,137.9722222222311,185.1816666666597,146.83333333333331,175.08C155.6944444444356,164.9783333333403,164.5555555555644,159.0566666666597,173.41666666666666,152.09C182.2777777777689,145.1233333333403,200,133.28,200,133.28C200,133.28,200,133.28,200,133.28C200,133.28,226.58333333333331,116.56,226.58333333333331,116.56C226.58333333333331,116.56,226.58333333333331,116.56,226.58333333333331,116.56C226.58333333333331,116.56,244.3055555555644,99.14333333334031,253.16666666666666,93.57000000000002C262.0277777777689,87.9966666666597,270.8888888888978,88.69333333334029,279.75,83.12C288.6111111111022,77.54666666665972,306.3333333333333,60.130000000000024,306.3333333333333,60.130000000000024" stroke="#dc3912" stroke-width="3" fill-opacity="1" fill="none"></path></g></g><g><circle cx="226.58333333333331" cy="150" r="6" stroke="none" stroke-width="0" fill="#3366cc"></circle><circle cx="332.91666666666663" cy="81.03" r="6" stroke="none" stroke-width="0" fill="#3366cc"></circle><circle cx="200" cy="133.28" r="6" stroke="none" stroke-width="0" fill="#dc3912"></circle></g><g><g><text text-anchor="middle" x="40.5" y="267.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">0</text></g><g><text text-anchor="middle" x="173.4167" y="267.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">5</text></g><g><text text-anchor="middle" x="306.3333" y="267.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">10</text></g><g><text text-anchor="end" x="36.333333333333336" y="258.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">0</text></g><g><text text-anchor="end" x="36.333333333333336" y="216.54999999999998" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">20</text></g><g><text text-anchor="end" x="36.333333333333336" y="174.75" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">40</text></g><g><text text-anchor="end" x="36.333333333333336" y="132.95" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">60</text></g><g><text text-anchor="end" x="36.333333333333336" y="91.14999999999999" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">80</text></g><g><text text-anchor="end" x="36.333333333333336" y="49.35" font-family="Arial" font-size="11" stroke="none" stroke-width="0" fill="#444444">100</text></g></g></g><g><g><text text-anchor="middle" x="200" y="281.35" font-family="Arial" font-size="11" font-style="italic" stroke="none" stroke-width="0" fill="#222222">Overs</text><rect x="40" y="272" width="320" height="11" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect></g><g><text text-anchor="middle" x="13.016666666666666" y="150" font-family="Arial" font-size="11" font-style="italic" transform="rotate(-90 13.016666666666666 150)" stroke="none" stroke-width="0" fill="#222222">Runs</text><path d="M3.6666666666666594,255L3.6666666666666723,45L14.666666666666671,45L14.666666666666659,255Z" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></path></g></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>Over</th><th>Toronto Jaguars</th><th>Royal Tigers</th><th></th><th></th></tr></thead><tbody><tr><td>0</td><td>0</td><td>0</td><td></td><td></td></tr><tr><td>1</td><td>7</td><td>8</td><td></td><td></td></tr><tr><td>2</td><td>13</td><td>14</td><td></td><td></td></tr><tr><td>3</td><td>15</td><td>20</td><td></td><td></td></tr><tr><td>4</td><td>19</td><td>38</td><td></td><td></td></tr><tr><td>5</td><td>26</td><td>49</td><td></td><td></td></tr><tr><td>6</td><td>38</td><td>58</td><td></td><td></td></tr><tr><td>6</td><td>38</td><td>58</td><td></td><td>58</td></tr><tr><td>7</td><td>50</td><td>66</td><td></td><td></td></tr><tr><td>7</td><td>50</td><td>66</td><td>50</td><td></td></tr><tr><td>8</td><td>57</td><td>77</td><td></td><td></td></tr><tr><td>9</td><td>66</td><td>82</td><td></td><td></td></tr><tr><td>10</td><td>78</td><td>93</td><td></td><td></td></tr><tr><td>11</td><td>83</td><td></td><td></td><td></td></tr><tr><td>11</td><td>83</td><td></td><td>83</td><td></td></tr><tr><td>12</td><td>91</td><td></td><td></td><td></td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 310px; left: 410px; white-space: nowrap; font-family: Arial; font-size: 11px;">Royal Tigers</div><div></div></div></div>
                    <div class="button-pool text-center">
                        <a href="#" onclick="loadView('graphs');" class="btn btn-chart"> View More Charts  <i class="fa  fa-chevron-circle-right"></i> </a>
                    </div>
                </div>
        </div>
        </div>
    </div>
</div>















<script type="text/javascript">

$(window).resize(function() {
  $("#dialogOpenVideoHTML").dialog("option", "position", "center");
  $("#dialogOpenVideoHTML").dialog({
    width:  $(window).width() > 600 ? $(window).width() - ($(window).width() * 35/100) : 'auto', //resizes the dialog box as the window is resized
  });
  
  $("#dialogOpenVideoLink").dialog("option", "position", "center"); //places the dialog box at the center
  $("#dialogOpenVideoLink").dialog({
    //resizes the dialog box as the window is resized
    width:  $(window).width() > 600 ? $(window).width() - ($(window).width() * 35/100) : 'auto', 
        
  });
});

$(document).ready(function() {

  $( "#errorMessageBall" ).dialog({
    autoOpen: false,
      modal: true
  });

  $( "#dialogOpenVideoLink" ).dialog({
    autoOpen: false,
      modal: true,
      responsive: true,
      width:  $(window).width() > 600 ? $(window).width() - ($(window).width() * 35/100) : 'auto',
      fluid: true,
      show: {effect: 'scale', duration: 700},
      hide: {effect: 'puff', duration: 500},
      dialogClass: 'myTitleClass',
      close:function(){
      $("#playVideo")[0].src="";
        enable = 1;
    }
  });
  $( "#dialogOpenVideoHTML" ).dialog({
    autoOpen: false,
      modal: true,
      responsive: true,
      width:  $(window).width() > 600 ? $(window).width() - ($(window).width() * 35/100) : 'auto',
      fluid: true,
      show: {effect: 'scale', duration: 700},
      hide: {effect: 'puff', duration: 500},
      dialogClass: 'myTitleClass',
        close:function(){
          $("#CCVideoPlayer").src="";
          enable = 1;
      }
   });

  $("#dialog-confirmBall").dialog({
        resizable: false,
        autoOpen: false,
        modal: true,
        buttons: {
          "Delete Ball": function() {
            var ballId = $("#deleteId").val();
            var matchId = '3319';
            var ajaxUrl = '/MississaugaCricketLeague/deleteBall.do?clubId=2565&ballId=' + ballId + '&matchId=' + matchId;
            $.ajax({
              url:ajaxUrl,
              success:function(result){
                
                  $('#errorMessageBall').html(result);
                  $('#errorMessageBall').dialog("open");                
                  $("#ballid_"+ballId).remove();
                  window.location.reload(true);
                
              }});
            $( this ).dialog( "close" );
          },
          Cancel: function() {
            $( this ).dialog( "close" );
          }
        }
      });
  
  $(".dialogAddBall").dialog({
        resizable: true,
        autoOpen: false,
        modal: true,
        width:750,
        height:450,
       
        buttons: {
            "Add Ball": function() {
            
              var matchId = '3319';
              var add_ballNumber = $('#add_ballNumber').val();
              var add_overNumber = $('#add_overNumber').val();
              var add_balltype = $('#add_balltype').val();
              var add_inningsNumber = $('#add_inningsNumber').val();
              var add_inningsId = $('#add_inningsId').val();
              var add_batsmanId = $('#add_batsmanId').val();
              var add_bowlerId = $('#add_bowlerId').val();
              var add_runnerId = $('#add_runnerId').val();
              var add_bowlerName = $('#add_bowlerName').val();
              var add_batsmanName = $('#add_batsmanName').val();
              var add_runnerName = $('#add_runnerName').val();
              var add_runs = $('#add_runs').val();
              var add_wickettype = $('#add_wickettype').val();
              var add_comments = $("#add_comments").val();              
              var add_outplayer = $('#add_outplayer').val();
              var add_wickettaker1 = $('#add_wickettaker1').val();
              var add_wickettaker2 = $("#add_wickettaker2").val();
              
              
              var hostname = '/MississaugaCricketLeague/addBall.do?clubId=2565';
              var ajaxUrl = hostname
                                  +'&add_ballNumber=' + add_ballNumber
                                  +'&add_overNumber=' + add_overNumber
                                  +'&add_balltype=' + add_balltype
                                  +'&add_inningsNumber=' + add_inningsNumber
                                  +'&add_inningsId=' + add_inningsId
                                  +'&add_batsmanId=' + add_batsmanId
                                  +'&add_bowlerId=' + add_bowlerId
                                  +'&add_runnerId=' + add_runnerId
                                  +'&add_bowlerName=' + add_bowlerName
                                  +'&add_batsmanName=' + add_batsmanName
                                  +'&add_runnerName=' + add_runnerName
                                  +'&add_runs=' + add_runs
                                  +'&add_outplayer=' + add_outplayer
                                  +'&add_wickettaker1=' + add_wickettaker1
                                  +'&add_wickettaker2=' + add_wickettaker2
                                  +'&add_comments=' + add_comments
                                  +'&add_wickettype=' + add_wickettype
                                  +'&add_matchId=' + matchId;
                                  
                               
              $.ajax({
                url:ajaxUrl,
                success:function(result){
                  //setTimeout(function(){ location.reload(); }, 2000);
                  $('#errorMessageBall').html(result);
                  $('#errorMessageBall').dialog("open");
                  window.location.reload(true);
                    //$("#ballid_"+ballId).remove();
                  
                }});
              $( this ).dialog( "close" );
            },
            Cancel: function() {
              $( this ).dialog( "close" );
            }
          }
      });

  
  $("#dialogUpdateVideoLink").dialog({
        resizable: false,
        autoOpen: false,
        modal: true,
        width:500,
        height:400,
        buttons: {
          "Update Video Link": function() {
            var update_video_ballId = $("#update_video_ballId").val();
            var update_youtube_link = $("#update_youtube_link").val();
            var update_starttime_hh = $("#update_starttime_hh").val();
            var update_starttime_mm = $("#update_starttime_mm").val();
            var update_starttime_ss = $("#update_starttime_ss").val();
            var update_endtime_hh = $("#update_endtime_hh").val();
            var update_endtime_mm = $("#update_endtime_mm").val();
            var update_endtime_ss = $("#update_endtime_ss").val();
            
            if(update_youtube_link.indexOf("/watch?v=")==-1){
              $('#errorMessageBall').html("Invalid Youtube URL. Please enter URL in the format shown.");
          $('#errorMessageBall').dialog("open");
          $("#update_youtube_link").val();
            }
            else
            {
            
            var ajaxUrl = '/MississaugaCricketLeague/updateBallVideo.do?clubId=2565&update_video_ballId=' + update_video_ballId;
            var hostname = '/MississaugaCricketLeague/updateBallVideo.do?clubId=2565';
            var ajaxUrl = hostname
                      +'&update_video_ballId=' + update_video_ballId
                      +'&update_youtube_link=' + update_youtube_link
                      +'&update_starttime_hh=' + update_starttime_hh
                      +'&update_starttime_mm=' + update_starttime_mm
                      +'&update_starttime_ss=' + update_starttime_ss
                      +'&update_endtime_hh=' + update_endtime_hh
                      +'&update_endtime_mm=' + update_endtime_mm
                      +'&update_endtime_ss=' + update_endtime_ss;
            
            $.ajax({
              url:ajaxUrl,
              success:function(result){
                
                  $('#errorMessageBall').html(result);
                  $('#errorMessageBall').dialog("open");
                  window.location.reload(true);
                  //$("#ballid_"+ballId).remove();
                
              }});
            $( this ).dialog( "close" );
          }
          },
          Cancel: function() {
            $( this ).dialog( "close" );
          }
        }
      });
  
  
  $(".dialogUpdateBall").dialog({
        resizable: true,
        autoOpen: false,
        modal: true,
        width:750,
        height:450,
       
        buttons: {
            "Update Ball": function() {
              var matchId = '3319';
              var update_batsman = $('#update_batsman').val();
              var update_bowler = $('#update_bowler').val();
              var update_runs = $('#update_runs').val();
              var update_balltype = $('#update_balltype').val();
              var update_wickettype = $('#update_wickettype').val();
              var update_outplayer = $('#update_outplayer').val();
              var update_wickettaker1 = $('#update_wickettaker1').val();
              var update_wickettaker2 = $('#update_wickettaker2').val();
              var update_ballId = $("#update_ballId").val();
              //var update_over = $("#update_over").val();
              //var update_ballinover = $("#update_ballinover").val();
              var update_runner = $("#update_runner").val();
              //var update_direction = $("#update_direction").val();
              //var update_isfour = $("#update_isfour").val();
              //var update_issix = $("#update_issix").val();
              //var update_inningsId = $("#update_inningsId").val();
              var update_comments = $("#update_comments").val();
              var hostname = '/MississaugaCricketLeague/updateBall.do?clubId=2565';
              var ajaxUrl = hostname
                        +'&update_batsman=' + update_batsman
                        +'&update_bowler=' + update_bowler
                        +'&update_runs=' + update_runs
                        +'&update_balltype=' + update_balltype
                        +'&update_wickettype=' + update_wickettype
                        +'&update_outplayer=' + update_outplayer
                        +'&update_wickettaker1=' + update_wickettaker1
                        +'&update_wickettaker2=' + update_wickettaker2
                        //+'&update_over=' + update_over
                        //+'&update_ballinover=' + update_ballinover
                        +'&update_runner=' + update_runner
                        //+'&update_direction=' + update_direction
                        //+'&update_isfour=' + update_isfour
                        //+'&update_issix=' + update_issix
                        //+'&update_inningsId=' + update_inningsId
                        +'&update_comments=' + update_comments
                        +'&update_ballId=' + update_ballId
                        +'&update_matchId=' + matchId;
              $.ajax({
                url:ajaxUrl,
                success:function(result){
                  
                    $('#errorMessageBall').html(result);
                    $('#errorMessageBall').dialog("open");
                  window.location.reload(true);
                    //$("#ballid_"+ballId).remove();
                  
                }});
              $( this ).dialog( "close" );
            },
            Cancel: function() {
              $( this ).dialog( "close" );
            }
          }
      });
});
function deleteBall(ballId){
  $("#deleteMessageBall").html("");
  $("#deleteId").val(ballId);
  //$(".dialogDeleteBall").show();
  //$(".dialogUpdateBall").hide();
  $("#dialog-confirmBall").dialog("open");
}

function updateVideoLink(ballId,youtube_link,starthh,startmm,startss,endhh,endmm,endss){
  
  $("#update_video_ballId").val(ballId);
  $("#update_youtube_link").val(youtube_link=="null"?"":youtube_link);
  $("#update_starttime_hh").val(starthh);
  $("#update_starttime_mm").val(startmm);
  $("#update_starttime_ss").val(startss);
  $("#update_endtime_hh").val(endhh);
  $("#update_endtime_mm").val(endmm);
  $("#update_endtime_ss").val(endss);
  //$(".dialogDeleteBall").show();
  //$(".dialogUpdateBall").hide();
  $("#dialogUpdateVideoLink").dialog("open");
}

function openVideoLink(ballId,ballnumber,youtube_link,starttime,endtime){
  
  $(".dialogOpenVideoLink").dialog("option","title","Ball - "+ballnumber+" - Video");
  var videoid = youtube_link.split("/watch?v=");
  $("#playVideo")[0].src="//www.youtube.com/embed/"+videoid[1]+"?start="+starttime+"&end="+endtime+"&autoplay=1";
  $("#dialogOpenVideoLink").dialog("open");
}

function openVideoHTML(ballId,ballnumber,youtube_link,starttime,endtime){
  
  $(".dialogOpenVideoHTML").dialog("option","title","Ball - "+ballnumber+" - Video");
  var Player = document.getElementById('CCVideoPlayer');
  Player.src=youtube_link;
  $("#dialogOpenVideoHTML").dialog("open");
  
  setTimeout(function(){
    Player.play();
  }, 1000);
}

function addBall(ballId,inningsNumber,batsmanId,batsmanName,runnerId,runnerName,inningsId,over,ball,runner,direction,isFour,isSix,batsman,bowler,runs,ballType,outMethod,outPerson,wicketTaker1,wicketTaker2,comment,isSuperOver){
  
  $(".dialogAddBall").dialog("option","title","Add Ball");
   $("#add_inningsNumber").val(inningsNumber);
  $("#add_inningsId").val(inningsId);
  $("#add_overNumber").val(over);
  $("#add_ballNumber").val(ball);
  $("#add_runs").val(runs);
  $("#add_balltype").val(ballType);
  $("#add_runnerId").val(runnerId); 
  /* $('#add_outplayer').empty();
  $('#add_outplayer').append("<option value=''>-select-</option>");
   */
  
  /* $('#add_batsman').val(batsman);
  $("#add_runner").val(runnerID);
  $('#add_bowler').val(bowler);
  $('#add_runs').val(runs);
  $("#add_bowlerId").val(bowlerId); 
  $('#add_balltype').val(balltype);
  $('#add_wickettype').val(wickettype);
  $('#add_outplayer').val(outplayer);
  $('#add_wickettaker1').val(wickettaker1);
  $('#add_wickettaker2').val(wickettaker2);
  $('#add_comments').val(comments); */
  
   
  $(".dialogAddBall").dialog("open");
  
  if(inningsNumber==1 || inningsNumber==3){
    
     $('#add_batsmanName').empty(); 
    $('#add_batsmanName').append("<option value=''>-select-</option>");
    $('#add_runnerName').empty();
    $('#add_runnerName').append("<option value=''>-select-</option>");
     $('#add_bowlerName').empty();
    $('#add_bowlerName').append("<option value=''>-select-</option>");
    $('#add_wickettaker1').empty();
    $('#add_wickettaker1').append("<option value=''>-select-</option>");
    $('#add_wickettaker2').empty();
    $('#add_wickettaker2').append("<option value=''>-select-</option>");
    
    if(isSuperOver == 1){
    	 
    	    $('#add_batsmanName').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    	    $('#add_runnerName').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    	    
    	    $('#add_batsmanName').append("<option value='2259558'>Mubashir Jawed</option>");
    	    $('#add_runnerName').append("<option value='2259558'>Mubashir Jawed</option>");
    	    
    	    $('#add_batsmanName').append("<option value='1207430'>Noman Siddiqui</option>");
    	    $('#add_runnerName').append("<option value='1207430'>Noman Siddiqui</option>");
    	    
    	    $('#add_batsmanName').append("<option value='900891'>Saad Haroon</option>");
    	    $('#add_runnerName').append("<option value='900891'>Saad Haroon</option>");
    	    
    	    $('#add_batsmanName').append("<option value='1207349'>Imran Samdani</option>");
    	    $('#add_runnerName').append("<option value='1207349'>Imran Samdani</option>");
    	    
    	    $('#add_batsmanName').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    	    $('#add_runnerName').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    	    
    	    $('#add_batsmanName').append("<option value='1290963'>Turab Ghani</option>");
    	    $('#add_runnerName').append("<option value='1290963'>Turab Ghani</option>");
    	    
    	    $('#add_batsmanName').append("<option value='2160252'>Mujtaba Jiwani</option>");
    	    $('#add_runnerName').append("<option value='2160252'>Mujtaba Jiwani</option>");
    	    
    	    $('#add_batsmanName').append("<option value='2237865'>Abdullah Abbas</option>");
    	    $('#add_runnerName').append("<option value='2237865'>Abdullah Abbas</option>");
    	    
    	    $('#add_batsmanName').append("<option value='2674040'>Irfan Sadaat</option>");
    	    $('#add_runnerName').append("<option value='2674040'>Irfan Sadaat</option>");
    	    
    	    $('#add_batsmanName').append("<option value='2770791'>Aziz Hassan</option>");
    	    $('#add_runnerName').append("<option value='2770791'>Aziz Hassan</option>");
    	    
    	    $('#add_bowlerName').append("<option value='1367221'>Usman Aziz</option>");
    	    $('#add_wickettaker1').append("<option value='1367221'>Usman Aziz</option>");
    	    $('#add_wickettaker2').append("<option value='1367221'>Usman Aziz</option>");
    	    
    	    $('#add_bowlerName').append("<option value='2093443'>Atanu Bharali</option>");
    	    $('#add_wickettaker1').append("<option value='2093443'>Atanu Bharali</option>");
    	    $('#add_wickettaker2').append("<option value='2093443'>Atanu Bharali</option>");
    	    
    	    $('#add_bowlerName').append("<option value='1287664'>Ramkiran Nersu</option>");
    	    $('#add_wickettaker1').append("<option value='1287664'>Ramkiran Nersu</option>");
    	    $('#add_wickettaker2').append("<option value='1287664'>Ramkiran Nersu</option>");
    	    
    	    $('#add_bowlerName').append("<option value='1125901'>Imran Nahid</option>");
    	    $('#add_wickettaker1').append("<option value='1125901'>Imran Nahid</option>");
    	    $('#add_wickettaker2').append("<option value='1125901'>Imran Nahid</option>");
    	    
    	    $('#add_bowlerName').append("<option value='349240'>Jibran Shaukat</option>");
    	    $('#add_wickettaker1').append("<option value='349240'>Jibran Shaukat</option>");
    	    $('#add_wickettaker2').append("<option value='349240'>Jibran Shaukat</option>");
    	    
    	    $('#add_bowlerName').append("<option value='657841'>Adeel Ali</option>");
    	    $('#add_wickettaker1').append("<option value='657841'>Adeel Ali</option>");
    	    $('#add_wickettaker2').append("<option value='657841'>Adeel Ali</option>");
    	    
    	    $('#add_bowlerName').append("<option value='1058041'>Gurpreet Singh</option>");
    	    $('#add_wickettaker1').append("<option value='1058041'>Gurpreet Singh</option>");
    	    $('#add_wickettaker2').append("<option value='1058041'>Gurpreet Singh</option>");
    	    
    	    $('#add_bowlerName').append("<option value='2670355'>Omar Edwards</option>");
    	    $('#add_wickettaker1').append("<option value='2670355'>Omar Edwards</option>");
    	    $('#add_wickettaker2').append("<option value='2670355'>Omar Edwards</option>");
    	    
    	    $('#add_bowlerName').append("<option value='2897036'>Mohammedi</option>");
    	    $('#add_wickettaker1').append("<option value='2897036'>Mohammedi</option>");
    	    $('#add_wickettaker2').append("<option value='2897036'>Mohammedi</option>");
    	    
    }else {
    
    $('#add_batsmanName').append("<option value='1367221'>Usman Aziz</option>");
    $('#add_runnerName').append("<option value='1367221'>Usman Aziz</option>");
    
    $('#add_batsmanName').append("<option value='2093443'>Atanu Bharali</option>");
    $('#add_runnerName').append("<option value='2093443'>Atanu Bharali</option>");
    
    $('#add_batsmanName').append("<option value='1287664'>Ramkiran Nersu</option>");
    $('#add_runnerName').append("<option value='1287664'>Ramkiran Nersu</option>");
    
    $('#add_batsmanName').append("<option value='1125901'>Imran Nahid</option>");
    $('#add_runnerName').append("<option value='1125901'>Imran Nahid</option>");
    
    $('#add_batsmanName').append("<option value='349240'>Jibran Shaukat</option>");
    $('#add_runnerName').append("<option value='349240'>Jibran Shaukat</option>");
    
    $('#add_batsmanName').append("<option value='657841'>Adeel Ali</option>");
    $('#add_runnerName').append("<option value='657841'>Adeel Ali</option>");
    
    $('#add_batsmanName').append("<option value='1058041'>Gurpreet Singh</option>");
    $('#add_runnerName').append("<option value='1058041'>Gurpreet Singh</option>");
    
    $('#add_batsmanName').append("<option value='2670355'>Omar Edwards</option>");
    $('#add_runnerName').append("<option value='2670355'>Omar Edwards</option>");
    
    $('#add_batsmanName').append("<option value='2897036'>Mohammedi</option>");
    $('#add_runnerName').append("<option value='2897036'>Mohammedi</option>");
    
    $('#add_bowlerName').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    $('#add_wickettaker1').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    $('#add_wickettaker2').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    
    $('#add_bowlerName').append("<option value='2259558'>Mubashir Jawed</option>");
    $('#add_wickettaker1').append("<option value='2259558'>Mubashir Jawed</option>");
    $('#add_wickettaker2').append("<option value='2259558'>Mubashir Jawed</option>");
    
    $('#add_bowlerName').append("<option value='1207430'>Noman Siddiqui</option>");
    $('#add_wickettaker1').append("<option value='1207430'>Noman Siddiqui</option>");
    $('#add_wickettaker2').append("<option value='1207430'>Noman Siddiqui</option>");
    
    $('#add_bowlerName').append("<option value='900891'>Saad Haroon</option>");
    $('#add_wickettaker1').append("<option value='900891'>Saad Haroon</option>");
    $('#add_wickettaker2').append("<option value='900891'>Saad Haroon</option>");
    
    $('#add_bowlerName').append("<option value='1207349'>Imran Samdani</option>");
    $('#add_wickettaker1').append("<option value='1207349'>Imran Samdani</option>");
    $('#add_wickettaker2').append("<option value='1207349'>Imran Samdani</option>");
    
    $('#add_bowlerName').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    $('#add_wickettaker1').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    $('#add_wickettaker2').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    
    $('#add_bowlerName').append("<option value='1290963'>Turab Ghani</option>");
    $('#add_wickettaker1').append("<option value='1290963'>Turab Ghani</option>");
    $('#add_wickettaker2').append("<option value='1290963'>Turab Ghani</option>");
    
    $('#add_bowlerName').append("<option value='2160252'>Mujtaba Jiwani</option>");
    $('#add_wickettaker1').append("<option value='2160252'>Mujtaba Jiwani</option>");
    $('#add_wickettaker2').append("<option value='2160252'>Mujtaba Jiwani</option>");
    
    $('#add_bowlerName').append("<option value='2237865'>Abdullah Abbas</option>");
    $('#add_wickettaker1').append("<option value='2237865'>Abdullah Abbas</option>");
    $('#add_wickettaker2').append("<option value='2237865'>Abdullah Abbas</option>");
    
    $('#add_bowlerName').append("<option value='2674040'>Irfan Sadaat</option>");
    $('#add_wickettaker1').append("<option value='2674040'>Irfan Sadaat</option>");
    $('#add_wickettaker2').append("<option value='2674040'>Irfan Sadaat</option>");
    
    $('#add_bowlerName').append("<option value='2770791'>Aziz Hassan</option>");
    $('#add_wickettaker1').append("<option value='2770791'>Aziz Hassan</option>");
    $('#add_wickettaker2').append("<option value='2770791'>Aziz Hassan</option>");
    
    }
  }
  
  if(inningsNumber==2 || inningsNumber==4){
    
     $('#add_batsmanName').empty(); 
     $('#add_batsmanName').append("<option value=''>-select-</option>");
      $('#add_bowlerName').empty();
    $('#add_bowlerName').append("<option value=''>-select-</option>");
    $('#add_runnerName').empty();
    $('#add_runnerName').append("<option value=''>-select-</option>");
    
    $('#add_wickettaker1').empty();
    $('#add_wickettaker1').append("<option value=''>-select-</option>");
    $('#add_wickettaker2').empty();
    $('#add_wickettaker2').append("<option value=''>-select-</option>");
    
    if(isSuperOver == 1){
   	 
   	    $('#add_batsmanName').append("<option value='1367221'>Usman Aziz</option>");
   	    $('#add_runnerName').append("<option value='1367221'>Usman Aziz</option>");
   	    
   	    $('#add_batsmanName').append("<option value='2093443'>Atanu Bharali</option>");
   	    $('#add_runnerName').append("<option value='2093443'>Atanu Bharali</option>");
   	    
   	    $('#add_batsmanName').append("<option value='1287664'>Ramkiran Nersu</option>");
   	    $('#add_runnerName').append("<option value='1287664'>Ramkiran Nersu</option>");
   	    
   	    $('#add_batsmanName').append("<option value='1125901'>Imran Nahid</option>");
   	    $('#add_runnerName').append("<option value='1125901'>Imran Nahid</option>");
   	    
   	    $('#add_batsmanName').append("<option value='349240'>Jibran Shaukat</option>");
   	    $('#add_runnerName').append("<option value='349240'>Jibran Shaukat</option>");
   	    
   	    $('#add_batsmanName').append("<option value='657841'>Adeel Ali</option>");
   	    $('#add_runnerName').append("<option value='657841'>Adeel Ali</option>");
   	    
   	    $('#add_batsmanName').append("<option value='1058041'>Gurpreet Singh</option>");
   	    $('#add_runnerName').append("<option value='1058041'>Gurpreet Singh</option>");
   	    
   	    $('#add_batsmanName').append("<option value='2670355'>Omar Edwards</option>");
   	    $('#add_runnerName').append("<option value='2670355'>Omar Edwards</option>");
   	    
   	    $('#add_batsmanName').append("<option value='2897036'>Mohammedi</option>");
   	    $('#add_runnerName').append("<option value='2897036'>Mohammedi</option>");
   	    
   	    $('#add_bowlerName').append("<option value='1036746'>Muhammad Salman Arshad</option>");
   	    $('#add_wickettaker1').append("<option value='1036746'>Muhammad Salman Arshad</option>");
   	    $('#add_wickettaker2').append("<option value='1036746'>Muhammad Salman Arshad</option>");
   	    
   	    $('#add_bowlerName').append("<option value='2259558'>Mubashir Jawed</option>");
   	    $('#add_wickettaker1').append("<option value='2259558'>Mubashir Jawed</option>");
   	    $('#add_wickettaker2').append("<option value='2259558'>Mubashir Jawed</option>");
   	    
   	    $('#add_bowlerName').append("<option value='1207430'>Noman Siddiqui</option>");
   	    $('#add_wickettaker1').append("<option value='1207430'>Noman Siddiqui</option>");
   	    $('#add_wickettaker2').append("<option value='1207430'>Noman Siddiqui</option>");
   	    
   	    $('#add_bowlerName').append("<option value='900891'>Saad Haroon</option>");
   	    $('#add_wickettaker1').append("<option value='900891'>Saad Haroon</option>");
   	    $('#add_wickettaker2').append("<option value='900891'>Saad Haroon</option>");
   	    
   	    $('#add_bowlerName').append("<option value='1207349'>Imran Samdani</option>");
   	    $('#add_wickettaker1').append("<option value='1207349'>Imran Samdani</option>");
   	    $('#add_wickettaker2').append("<option value='1207349'>Imran Samdani</option>");
   	    
   	    $('#add_bowlerName').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
   	    $('#add_wickettaker1').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
   	    $('#add_wickettaker2').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
   	    
   	    $('#add_bowlerName').append("<option value='1290963'>Turab Ghani</option>");
   	    $('#add_wickettaker1').append("<option value='1290963'>Turab Ghani</option>");
   	    $('#add_wickettaker2').append("<option value='1290963'>Turab Ghani</option>");
   	    
   	    $('#add_bowlerName').append("<option value='2160252'>Mujtaba Jiwani</option>");
   	    $('#add_wickettaker1').append("<option value='2160252'>Mujtaba Jiwani</option>");
   	    $('#add_wickettaker2').append("<option value='2160252'>Mujtaba Jiwani</option>");
   	    
   	    $('#add_bowlerName').append("<option value='2237865'>Abdullah Abbas</option>");
   	    $('#add_wickettaker1').append("<option value='2237865'>Abdullah Abbas</option>");
   	    $('#add_wickettaker2').append("<option value='2237865'>Abdullah Abbas</option>");
   	    
   	    $('#add_bowlerName').append("<option value='2674040'>Irfan Sadaat</option>");
   	    $('#add_wickettaker1').append("<option value='2674040'>Irfan Sadaat</option>");
   	    $('#add_wickettaker2').append("<option value='2674040'>Irfan Sadaat</option>");
   	    
   	    $('#add_bowlerName').append("<option value='2770791'>Aziz Hassan</option>");
   	    $('#add_wickettaker1').append("<option value='2770791'>Aziz Hassan</option>");
   	    $('#add_wickettaker2').append("<option value='2770791'>Aziz Hassan</option>");
   	    
    }else {
    
    $('#add_batsmanName').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    $('#add_runnerName').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    
    $('#add_batsmanName').append("<option value='2259558'>Mubashir Jawed</option>");
    $('#add_runnerName').append("<option value='2259558'>Mubashir Jawed</option>");
    
    $('#add_batsmanName').append("<option value='1207430'>Noman Siddiqui</option>");
    $('#add_runnerName').append("<option value='1207430'>Noman Siddiqui</option>");
    
    $('#add_batsmanName').append("<option value='900891'>Saad Haroon</option>");
    $('#add_runnerName').append("<option value='900891'>Saad Haroon</option>");
    
    $('#add_batsmanName').append("<option value='1207349'>Imran Samdani</option>");
    $('#add_runnerName').append("<option value='1207349'>Imran Samdani</option>");
    
    $('#add_batsmanName').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    $('#add_runnerName').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    
    $('#add_batsmanName').append("<option value='1290963'>Turab Ghani</option>");
    $('#add_runnerName').append("<option value='1290963'>Turab Ghani</option>");
    
    $('#add_batsmanName').append("<option value='2160252'>Mujtaba Jiwani</option>");
    $('#add_runnerName').append("<option value='2160252'>Mujtaba Jiwani</option>");
    
    $('#add_batsmanName').append("<option value='2237865'>Abdullah Abbas</option>");
    $('#add_runnerName').append("<option value='2237865'>Abdullah Abbas</option>");
    
    $('#add_batsmanName').append("<option value='2674040'>Irfan Sadaat</option>");
    $('#add_runnerName').append("<option value='2674040'>Irfan Sadaat</option>");
    
    $('#add_batsmanName').append("<option value='2770791'>Aziz Hassan</option>");
    $('#add_runnerName').append("<option value='2770791'>Aziz Hassan</option>");
    
    $('#add_bowlerName').append("<option value='1367221'>Usman Aziz</option>");
    $('#add_wickettaker1').append("<option value='1367221'>Usman Aziz</option>");
    $('#add_wickettaker2').append("<option value='1367221'>Usman Aziz</option>");
    
    $('#add_bowlerName').append("<option value='2093443'>Atanu Bharali</option>");
    $('#add_wickettaker1').append("<option value='2093443'>Atanu Bharali</option>");
    $('#add_wickettaker2').append("<option value='2093443'>Atanu Bharali</option>");
    
    $('#add_bowlerName').append("<option value='1287664'>Ramkiran Nersu</option>");
    $('#add_wickettaker1').append("<option value='1287664'>Ramkiran Nersu</option>");
    $('#add_wickettaker2').append("<option value='1287664'>Ramkiran Nersu</option>");
    
    $('#add_bowlerName').append("<option value='1125901'>Imran Nahid</option>");
    $('#add_wickettaker1').append("<option value='1125901'>Imran Nahid</option>");
    $('#add_wickettaker2').append("<option value='1125901'>Imran Nahid</option>");
    
    $('#add_bowlerName').append("<option value='349240'>Jibran Shaukat</option>");
    $('#add_wickettaker1').append("<option value='349240'>Jibran Shaukat</option>");
    $('#add_wickettaker2').append("<option value='349240'>Jibran Shaukat</option>");
    
    $('#add_bowlerName').append("<option value='657841'>Adeel Ali</option>");
    $('#add_wickettaker1').append("<option value='657841'>Adeel Ali</option>");
    $('#add_wickettaker2').append("<option value='657841'>Adeel Ali</option>");
    
    $('#add_bowlerName').append("<option value='1058041'>Gurpreet Singh</option>");
    $('#add_wickettaker1').append("<option value='1058041'>Gurpreet Singh</option>");
    $('#add_wickettaker2').append("<option value='1058041'>Gurpreet Singh</option>");
    
    $('#add_bowlerName').append("<option value='2670355'>Omar Edwards</option>");
    $('#add_wickettaker1').append("<option value='2670355'>Omar Edwards</option>");
    $('#add_wickettaker2').append("<option value='2670355'>Omar Edwards</option>");
    
    $('#add_bowlerName').append("<option value='2897036'>Mohammedi</option>");
    $('#add_wickettaker1').append("<option value='2897036'>Mohammedi</option>");
    $('#add_wickettaker2').append("<option value='2897036'>Mohammedi</option>");
    
    }
  }
  $("#add_bowlerName").val(bowler);
  $("#add_batsmanName").val(batsmanId); 
  $("#add_runnerName").val(runnerId); 
  
  getAddBallWicketInfo();
}

function updateBall(ballId,inningsnumber,out1ID,out1player,out2ID,out2player,inningsID,overnumber,ballinover,runnerID,direction,isFour,isSix,batsman,bowler,runs,balltype,wickettype,outplayer,wickettaker1,wickettaker2,comments,isSuperOver){
  $(".dialogUpdateBall").dialog("option","title","Update Ball - "+overnumber+"."+ballinover);
  $("#update_ballId").val(ballId);
  $('#update_outplayer').empty();
  $('#update_outplayer').append("<option value=''>-select-</option>");
  $('#update_outplayer').append("<option value='"+out1ID+"'>"+out1player+"</option>");
  $('#update_outplayer').append("<option value='"+out2ID+"'>"+out2player+"</option>");
  $(".dialogUpdateBall").dialog("open");
 
  if(inningsnumber==1 || inningsnumber==3){
    $('#update_batsman').empty();
    //$('#update_batsman').append("<option value=''>-select-</option>");
    $('#update_runner').empty();
    //$('#update_runner').append("<option value=''>-select-</option>");
    $('#update_bowler').empty();
    //$('#update_bowler').append("<option value=''>-select-</option>");
    $('#update_wickettaker1').empty();
    $('#update_wickettaker1').append("<option value=''>-select-</option>");
    $('#update_wickettaker2').empty();
    $('#update_wickettaker2').append("<option value=''>-select-</option>");

	if(isSuperOver == 1){
   		
    	  $('#update_batsman').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    	    $('#update_runner').append("<option value='1036746'>Muhammad Salman Arshad</option>");  
    	
    	  $('#update_batsman').append("<option value='2259558'>Mubashir Jawed</option>");
    	    $('#update_runner').append("<option value='2259558'>Mubashir Jawed</option>");  
    	
    	  $('#update_batsman').append("<option value='1207430'>Noman Siddiqui</option>");
    	    $('#update_runner').append("<option value='1207430'>Noman Siddiqui</option>");  
    	
    	  $('#update_batsman').append("<option value='900891'>Saad Haroon</option>");
    	    $('#update_runner').append("<option value='900891'>Saad Haroon</option>");  
    	
    	  $('#update_batsman').append("<option value='1207349'>Imran Samdani</option>");
    	    $('#update_runner').append("<option value='1207349'>Imran Samdani</option>");  
    	
    	  $('#update_batsman').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    	    $('#update_runner').append("<option value='1211778'>Abdul Rehman Alamgir</option>");  
    	
    	  $('#update_batsman').append("<option value='1290963'>Turab Ghani</option>");
    	    $('#update_runner').append("<option value='1290963'>Turab Ghani</option>");  
    	
    	  $('#update_batsman').append("<option value='2160252'>Mujtaba Jiwani</option>");
    	    $('#update_runner').append("<option value='2160252'>Mujtaba Jiwani</option>");  
    	
    	  $('#update_batsman').append("<option value='2237865'>Abdullah Abbas</option>");
    	    $('#update_runner').append("<option value='2237865'>Abdullah Abbas</option>");  
    	
    	  $('#update_batsman').append("<option value='2674040'>Irfan Sadaat</option>");
    	    $('#update_runner').append("<option value='2674040'>Irfan Sadaat</option>");  
    	
    	  $('#update_batsman').append("<option value='2770791'>Aziz Hassan</option>");
    	    $('#update_runner').append("<option value='2770791'>Aziz Hassan</option>");  
    	
    }
    else{
    
    $('#update_batsman').append("<option value='1367221'>Usman Aziz</option>");
    $('#update_runner').append("<option value='1367221'>Usman Aziz</option>");
   	
    $('#update_batsman').append("<option value='2093443'>Atanu Bharali</option>");
    $('#update_runner').append("<option value='2093443'>Atanu Bharali</option>");
   	
    $('#update_batsman').append("<option value='1287664'>Ramkiran Nersu</option>");
    $('#update_runner').append("<option value='1287664'>Ramkiran Nersu</option>");
   	
    $('#update_batsman').append("<option value='1125901'>Imran Nahid</option>");
    $('#update_runner').append("<option value='1125901'>Imran Nahid</option>");
   	
    $('#update_batsman').append("<option value='349240'>Jibran Shaukat</option>");
    $('#update_runner').append("<option value='349240'>Jibran Shaukat</option>");
   	
    $('#update_batsman').append("<option value='657841'>Adeel Ali</option>");
    $('#update_runner').append("<option value='657841'>Adeel Ali</option>");
   	
    $('#update_batsman').append("<option value='1058041'>Gurpreet Singh</option>");
    $('#update_runner').append("<option value='1058041'>Gurpreet Singh</option>");
   	
    $('#update_batsman').append("<option value='2670355'>Omar Edwards</option>");
    $('#update_runner').append("<option value='2670355'>Omar Edwards</option>");
   	
    $('#update_batsman').append("<option value='2897036'>Mohammedi</option>");
    $('#update_runner').append("<option value='2897036'>Mohammedi</option>");
   	
    }
	 if(isSuperOver == 1){
	    	
	    	$('#update_bowler').append("<option value='1367221'>Usman Aziz</option>");
	        $('#update_wickettaker1').append("<option value='1367221'>Usman Aziz</option>");
	        $('#update_wickettaker2').append("<option value='1367221'>Usman Aziz</option>");
	    	
	    	$('#update_bowler').append("<option value='2093443'>Atanu Bharali</option>");
	        $('#update_wickettaker1').append("<option value='2093443'>Atanu Bharali</option>");
	        $('#update_wickettaker2').append("<option value='2093443'>Atanu Bharali</option>");
	    	
	    	$('#update_bowler').append("<option value='1287664'>Ramkiran Nersu</option>");
	        $('#update_wickettaker1').append("<option value='1287664'>Ramkiran Nersu</option>");
	        $('#update_wickettaker2').append("<option value='1287664'>Ramkiran Nersu</option>");
	    	
	    	$('#update_bowler').append("<option value='1125901'>Imran Nahid</option>");
	        $('#update_wickettaker1').append("<option value='1125901'>Imran Nahid</option>");
	        $('#update_wickettaker2').append("<option value='1125901'>Imran Nahid</option>");
	    	
	    	$('#update_bowler').append("<option value='349240'>Jibran Shaukat</option>");
	        $('#update_wickettaker1').append("<option value='349240'>Jibran Shaukat</option>");
	        $('#update_wickettaker2').append("<option value='349240'>Jibran Shaukat</option>");
	    	
	    	$('#update_bowler').append("<option value='657841'>Adeel Ali</option>");
	        $('#update_wickettaker1').append("<option value='657841'>Adeel Ali</option>");
	        $('#update_wickettaker2').append("<option value='657841'>Adeel Ali</option>");
	    	
	    	$('#update_bowler').append("<option value='1058041'>Gurpreet Singh</option>");
	        $('#update_wickettaker1').append("<option value='1058041'>Gurpreet Singh</option>");
	        $('#update_wickettaker2').append("<option value='1058041'>Gurpreet Singh</option>");
	    	
	    	$('#update_bowler').append("<option value='2670355'>Omar Edwards</option>");
	        $('#update_wickettaker1').append("<option value='2670355'>Omar Edwards</option>");
	        $('#update_wickettaker2').append("<option value='2670355'>Omar Edwards</option>");
	    	
	    	$('#update_bowler').append("<option value='2897036'>Mohammedi</option>");
	        $('#update_wickettaker1').append("<option value='2897036'>Mohammedi</option>");
	        $('#update_wickettaker2').append("<option value='2897036'>Mohammedi</option>");
	    	
	    }
	    else{
   	
    $('#update_bowler').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    $('#update_wickettaker1').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    $('#update_wickettaker2').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    
    $('#update_bowler').append("<option value='2259558'>Mubashir Jawed</option>");
    $('#update_wickettaker1').append("<option value='2259558'>Mubashir Jawed</option>");
    $('#update_wickettaker2').append("<option value='2259558'>Mubashir Jawed</option>");
    
    $('#update_bowler').append("<option value='1207430'>Noman Siddiqui</option>");
    $('#update_wickettaker1').append("<option value='1207430'>Noman Siddiqui</option>");
    $('#update_wickettaker2').append("<option value='1207430'>Noman Siddiqui</option>");
    
    $('#update_bowler').append("<option value='900891'>Saad Haroon</option>");
    $('#update_wickettaker1').append("<option value='900891'>Saad Haroon</option>");
    $('#update_wickettaker2').append("<option value='900891'>Saad Haroon</option>");
    
    $('#update_bowler').append("<option value='1207349'>Imran Samdani</option>");
    $('#update_wickettaker1').append("<option value='1207349'>Imran Samdani</option>");
    $('#update_wickettaker2').append("<option value='1207349'>Imran Samdani</option>");
    
    $('#update_bowler').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    $('#update_wickettaker1').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    $('#update_wickettaker2').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    
    $('#update_bowler').append("<option value='1290963'>Turab Ghani</option>");
    $('#update_wickettaker1').append("<option value='1290963'>Turab Ghani</option>");
    $('#update_wickettaker2').append("<option value='1290963'>Turab Ghani</option>");
    
    $('#update_bowler').append("<option value='2160252'>Mujtaba Jiwani</option>");
    $('#update_wickettaker1').append("<option value='2160252'>Mujtaba Jiwani</option>");
    $('#update_wickettaker2').append("<option value='2160252'>Mujtaba Jiwani</option>");
    
    $('#update_bowler').append("<option value='2237865'>Abdullah Abbas</option>");
    $('#update_wickettaker1').append("<option value='2237865'>Abdullah Abbas</option>");
    $('#update_wickettaker2').append("<option value='2237865'>Abdullah Abbas</option>");
    
    $('#update_bowler').append("<option value='2674040'>Irfan Sadaat</option>");
    $('#update_wickettaker1').append("<option value='2674040'>Irfan Sadaat</option>");
    $('#update_wickettaker2').append("<option value='2674040'>Irfan Sadaat</option>");
    
    $('#update_bowler').append("<option value='2770791'>Aziz Hassan</option>");
    $('#update_wickettaker1').append("<option value='2770791'>Aziz Hassan</option>");
    $('#update_wickettaker2').append("<option value='2770791'>Aziz Hassan</option>");
    
  	}
  } 
  if(inningsnumber==2 || inningsnumber==4){
    $('#update_batsman').empty();
    //$('#update_batsman').append("<option value=''>-select-</option>");
    $('#update_runner').empty();
    //$('#update_runner').append("<option value=''>-select-</option>");
    $('#update_bowler').empty();
    //$('#update_bowler').append("<option value=''>-select-</option>");
    $('#update_wickettaker1').empty();
    $('#update_wickettaker1').append("<option value=''>-select-</option>");
    $('#update_wickettaker2').empty();
    $('#update_wickettaker2').append("<option value=''>-select-</option>");
    if(isSuperOver == 1){
   	 
   	 //if ball is SuperOver swap player names 
   	  $('#update_batsman').append("<option value='1367221'>Usman Aziz</option>");
   	    $('#update_runner').append("<option value='1367221'>Usman Aziz</option>");
   	  
   	 //if ball is SuperOver swap player names 
   	  $('#update_batsman').append("<option value='2093443'>Atanu Bharali</option>");
   	    $('#update_runner').append("<option value='2093443'>Atanu Bharali</option>");
   	  
   	 //if ball is SuperOver swap player names 
   	  $('#update_batsman').append("<option value='1287664'>Ramkiran Nersu</option>");
   	    $('#update_runner').append("<option value='1287664'>Ramkiran Nersu</option>");
   	  
   	 //if ball is SuperOver swap player names 
   	  $('#update_batsman').append("<option value='1125901'>Imran Nahid</option>");
   	    $('#update_runner').append("<option value='1125901'>Imran Nahid</option>");
   	  
   	 //if ball is SuperOver swap player names 
   	  $('#update_batsman').append("<option value='349240'>Jibran Shaukat</option>");
   	    $('#update_runner').append("<option value='349240'>Jibran Shaukat</option>");
   	  
   	 //if ball is SuperOver swap player names 
   	  $('#update_batsman').append("<option value='657841'>Adeel Ali</option>");
   	    $('#update_runner').append("<option value='657841'>Adeel Ali</option>");
   	  
   	 //if ball is SuperOver swap player names 
   	  $('#update_batsman').append("<option value='1058041'>Gurpreet Singh</option>");
   	    $('#update_runner').append("<option value='1058041'>Gurpreet Singh</option>");
   	  
   	 //if ball is SuperOver swap player names 
   	  $('#update_batsman').append("<option value='2670355'>Omar Edwards</option>");
   	    $('#update_runner').append("<option value='2670355'>Omar Edwards</option>");
   	  
   	 //if ball is SuperOver swap player names 
   	  $('#update_batsman').append("<option value='2897036'>Mohammedi</option>");
   	    $('#update_runner').append("<option value='2897036'>Mohammedi</option>");
   	  
   }
   	 else {
    
    $('#update_batsman').append("<option value='1036746'>Muhammad Salman Arshad</option>");
    $('#update_runner').append("<option value='1036746'>Muhammad Salman Arshad</option>");  
    
    $('#update_batsman').append("<option value='2259558'>Mubashir Jawed</option>");
    $('#update_runner').append("<option value='2259558'>Mubashir Jawed</option>");  
    
    $('#update_batsman').append("<option value='1207430'>Noman Siddiqui</option>");
    $('#update_runner').append("<option value='1207430'>Noman Siddiqui</option>");  
    
    $('#update_batsman').append("<option value='900891'>Saad Haroon</option>");
    $('#update_runner').append("<option value='900891'>Saad Haroon</option>");  
    
    $('#update_batsman').append("<option value='1207349'>Imran Samdani</option>");
    $('#update_runner').append("<option value='1207349'>Imran Samdani</option>");  
    
    $('#update_batsman').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
    $('#update_runner').append("<option value='1211778'>Abdul Rehman Alamgir</option>");  
    
    $('#update_batsman').append("<option value='1290963'>Turab Ghani</option>");
    $('#update_runner').append("<option value='1290963'>Turab Ghani</option>");  
    
    $('#update_batsman').append("<option value='2160252'>Mujtaba Jiwani</option>");
    $('#update_runner').append("<option value='2160252'>Mujtaba Jiwani</option>");  
    
    $('#update_batsman').append("<option value='2237865'>Abdullah Abbas</option>");
    $('#update_runner').append("<option value='2237865'>Abdullah Abbas</option>");  
    
    $('#update_batsman').append("<option value='2674040'>Irfan Sadaat</option>");
    $('#update_runner').append("<option value='2674040'>Irfan Sadaat</option>");  
    
    $('#update_batsman').append("<option value='2770791'>Aziz Hassan</option>");
    $('#update_runner').append("<option value='2770791'>Aziz Hassan</option>");  
    
  } 
    if(isSuperOver == 1){
   	 
   	 	$('#update_bowler').append("<option value='1036746'>Muhammad Salman Arshad</option>");
   	    $('#update_wickettaker1').append("<option value='1036746'>Muhammad Salman Arshad</option>");
   	    $('#update_wickettaker2').append("<option value='1036746'>Muhammad Salman Arshad</option>");
   	 
   	 	$('#update_bowler').append("<option value='2259558'>Mubashir Jawed</option>");
   	    $('#update_wickettaker1').append("<option value='2259558'>Mubashir Jawed</option>");
   	    $('#update_wickettaker2').append("<option value='2259558'>Mubashir Jawed</option>");
   	 
   	 	$('#update_bowler').append("<option value='1207430'>Noman Siddiqui</option>");
   	    $('#update_wickettaker1').append("<option value='1207430'>Noman Siddiqui</option>");
   	    $('#update_wickettaker2').append("<option value='1207430'>Noman Siddiqui</option>");
   	 
   	 	$('#update_bowler').append("<option value='900891'>Saad Haroon</option>");
   	    $('#update_wickettaker1').append("<option value='900891'>Saad Haroon</option>");
   	    $('#update_wickettaker2').append("<option value='900891'>Saad Haroon</option>");
   	 
   	 	$('#update_bowler').append("<option value='1207349'>Imran Samdani</option>");
   	    $('#update_wickettaker1').append("<option value='1207349'>Imran Samdani</option>");
   	    $('#update_wickettaker2').append("<option value='1207349'>Imran Samdani</option>");
   	 
   	 	$('#update_bowler').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
   	    $('#update_wickettaker1').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
   	    $('#update_wickettaker2').append("<option value='1211778'>Abdul Rehman Alamgir</option>");
   	 
   	 	$('#update_bowler').append("<option value='1290963'>Turab Ghani</option>");
   	    $('#update_wickettaker1').append("<option value='1290963'>Turab Ghani</option>");
   	    $('#update_wickettaker2').append("<option value='1290963'>Turab Ghani</option>");
   	 
   	 	$('#update_bowler').append("<option value='2160252'>Mujtaba Jiwani</option>");
   	    $('#update_wickettaker1').append("<option value='2160252'>Mujtaba Jiwani</option>");
   	    $('#update_wickettaker2').append("<option value='2160252'>Mujtaba Jiwani</option>");
   	 
   	 	$('#update_bowler').append("<option value='2237865'>Abdullah Abbas</option>");
   	    $('#update_wickettaker1').append("<option value='2237865'>Abdullah Abbas</option>");
   	    $('#update_wickettaker2').append("<option value='2237865'>Abdullah Abbas</option>");
   	 
   	 	$('#update_bowler').append("<option value='2674040'>Irfan Sadaat</option>");
   	    $('#update_wickettaker1').append("<option value='2674040'>Irfan Sadaat</option>");
   	    $('#update_wickettaker2').append("<option value='2674040'>Irfan Sadaat</option>");
   	 
   	 	$('#update_bowler').append("<option value='2770791'>Aziz Hassan</option>");
   	    $('#update_wickettaker1').append("<option value='2770791'>Aziz Hassan</option>");
   	    $('#update_wickettaker2').append("<option value='2770791'>Aziz Hassan</option>");
   	 
   }
   	 else{
    
    $('#update_bowler').append("<option value='1367221'>Usman Aziz</option>");
    $('#update_wickettaker1').append("<option value='1367221'>Usman Aziz</option>");
    $('#update_wickettaker2').append("<option value='1367221'>Usman Aziz</option>");
    
    $('#update_bowler').append("<option value='2093443'>Atanu Bharali</option>");
    $('#update_wickettaker1').append("<option value='2093443'>Atanu Bharali</option>");
    $('#update_wickettaker2').append("<option value='2093443'>Atanu Bharali</option>");
    
    $('#update_bowler').append("<option value='1287664'>Ramkiran Nersu</option>");
    $('#update_wickettaker1').append("<option value='1287664'>Ramkiran Nersu</option>");
    $('#update_wickettaker2').append("<option value='1287664'>Ramkiran Nersu</option>");
    
    $('#update_bowler').append("<option value='1125901'>Imran Nahid</option>");
    $('#update_wickettaker1').append("<option value='1125901'>Imran Nahid</option>");
    $('#update_wickettaker2').append("<option value='1125901'>Imran Nahid</option>");
    
    $('#update_bowler').append("<option value='349240'>Jibran Shaukat</option>");
    $('#update_wickettaker1').append("<option value='349240'>Jibran Shaukat</option>");
    $('#update_wickettaker2').append("<option value='349240'>Jibran Shaukat</option>");
    
    $('#update_bowler').append("<option value='657841'>Adeel Ali</option>");
    $('#update_wickettaker1').append("<option value='657841'>Adeel Ali</option>");
    $('#update_wickettaker2').append("<option value='657841'>Adeel Ali</option>");
    
    $('#update_bowler').append("<option value='1058041'>Gurpreet Singh</option>");
    $('#update_wickettaker1').append("<option value='1058041'>Gurpreet Singh</option>");
    $('#update_wickettaker2').append("<option value='1058041'>Gurpreet Singh</option>");
    
    $('#update_bowler').append("<option value='2670355'>Omar Edwards</option>");
    $('#update_wickettaker1').append("<option value='2670355'>Omar Edwards</option>");
    $('#update_wickettaker2').append("<option value='2670355'>Omar Edwards</option>");
    
    $('#update_bowler').append("<option value='2897036'>Mohammedi</option>");
    $('#update_wickettaker1').append("<option value='2897036'>Mohammedi</option>");
    $('#update_wickettaker2').append("<option value='2897036'>Mohammedi</option>");
    
  }
  }
  
   $('#update_batsman').val(batsman);
  $("#update_runner").val(runnerID);
  $('#update_bowler').val(bowler); 
  $('#update_runs').val(runs);
  $('#update_balltype').val(balltype);
  $('#update_wickettype').val(wickettype);
  $('#update_outplayer').val(outplayer);
  $('#update_wickettaker1').val(wickettaker1);
  $('#update_wickettaker2').val(wickettaker2);
  $('#update_comments').val(comments);
  
  
  getWicketInfo();

}

function setOutPersonOptions(){
  $('#update_outplayer').empty();
  $('#update_outplayer').append("<option value=''>-select-</option>");
  $('#update_outplayer').append("<option value='"+$('#update_batsman').val()+"'>"+$('#update_batsman option:selected').text()+"</option>");
  $('#update_outplayer').append("<option value='"+$('#update_runner').val()+"'>"+$('#update_runner option:selected').text()+"</option>");
}

function getWicketInfo(){
  if($('#update_wickettype').val()=="")
    {
    $('.wicket_info').hide();
    $('#update_outplayer').val("");
    $('#update_wickettaker1').val("");
    $('#update_wickettaker2').val("");
    }
  else
    {
    $('.wicket_info').show();
    }
}

function setAddBallOutPersonOptions(){
  $('#add_outplayer').empty();
  $('#add_outplayer').append("<option value=''>-select-</option>");
  $('#add_outplayer').append("<option value='"+$('#add_batsmanName').val()+"'>"+$('#add_batsmanName option:selected').text()+"</option>");
  $('#add_outplayer').append("<option value='"+$('#add_runnerName').val()+"'>"+$('#add_runnerName option:selected').text()+"</option>");
}

function getAddBallWicketInfo(){
  if($('#add_wickettype').val()=="")
    {
    $('.addBall_wicket_info').hide();
    $('#add_outplayer').val("");
    $('#add_wickettaker1').val("");
    $('#add_wickettaker2').val("");
    }
  else
    {
    $('.addBall_wicket_info').show();
    setAddBallOutPersonOptions();
    }
}

function checkUpdateInputs(){
  var update_batsman = $('#update_batsman').val();
  var update_bowler = $('#update_bowler').val();
  var update_runs = $('#update_runs').val();
  var update_balltype = $('#update_balltype').val();
  var update_wickettype = $('#update_wickettype').val();
  var update_outplayer = $('#update_outplayer').val();
  var update_wickettaker1 = $('#update_wickettaker1').val();
  var update_wickettaker2 = $('#update_wickettaker2').val();
  var add_bowlerName = $('#add_bowlerName').val();
  var update_error=false;
  var error_message = "";
  if(update_batsman.length<1){
    update_error=true;
    error_message += "Please Select Batsman to Update\n";
  }
  if(update_bowler.length<1){
    update_error=true;
    error_message += "Please Select Bowler to Update\n";
  }
  if(update_runs.length<1){
    update_error=true;
    error_message += "Please enter Runs to Update\n";
  }
  if(update_balltype.length<1){
    update_error=true;
    error_message += "Please Select Ball Type to Update\n";
  }
  if(update_wickettype.length<1){
    update_error=true;
    error_message += "Please Select Wicket Type to Update\n";
  }
  if(update_outplayer.length<1){
    update_error=true;
    error_message += "Please Select Out Player to Update\n";
  }
  if(update_wickettaker1.length<1){
    update_error=true;
    error_message += "Please Select Wicket Bowler to Update\n";
  }
  if(update_wickettaker2.length<1){
    update_error=true;
    error_message += "Please Select Wicket Fielder to Update\n";
  }
}

function checkAddBallInputs(){
  var add_batsman = $('#add_batsman').val();
  var add_bowler = $('#add_bowler').val();
  var add_runs = $('#add_runs').val();
  var add_balltype = $('#add_balltype').val();
  var add_wickettype = $('#add_wickettype').val();
  var add_outplayer = $('#add_outplayer').val();
  var add_wickettaker1 = $('#add_wickettaker1').val();
  var add_wickettaker2 = $('#add_wickettaker2').val();
  var add_bowlerName = $('#add_bowlerName').val();
  var add_error=false;
  var error_message = "";
  if(add_batsman.length<1){
    add_error=true;
    error_message += "Please Select Batsman to Add\n";
  }
  if(add_bowler.length<1){
    add_error=true;
    error_message += "Please Select Bowler to Add\n";
  }
  if(add_runs.length<1){
    add_error=true;
    error_message += "Please enter Runs to Add\n";
  }
  if(add_balltype.length<1){
    add_error=true;
    error_message += "Please Select Ball Type to Add\n";
  }
  if(add_wickettype.length<1){
    add_error=true;
    error_message += "Please Select Wicket Type to Add\n";
  }
  if(add_outplayer.length<1){
    add_error=true;
    error_message += "Please Select Out Player to Add\n";
  }
  if(add_wickettaker1.length<1){
    add_error=true;
    error_message += "Please Select Wicket Bowler to Add\n";
  }
  if(add_wickettaker2.length<1){
    add_error=true;
    error_message += "Please Select Wicket Fielder to Add\n";
  }
}
</script>
  <script type="text/javascript">
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Over', 'Toronto Jaguars',{type: 'string', role: 'tooltip', 'p': {'html': true}},
           'Royal Tigers',{type: 'string', role: 'tooltip', 'p': {'html': true}},'Wickets1',{type: 'string', role: 'tooltip', 'p': {'html': true}},'Wickets2',{type: 'string', role: 'tooltip', 'p': {'html': true}}]
          ,[0,0,null,0,null,null,null,null,null]
          
          
          ,[1,7,'<strong>Toronto Jaguars: 7/0 (1.0 Overs)</strong>',8,'<strong>Royal Tigers: 8/0 (1.0 Overs)</strong>',null,null,null,null]
          
          
          ,[2,13,'<strong>Toronto Jaguars: 13/0 (2.0 Overs)</strong>',14,'<strong>Royal Tigers: 14/0 (2.0 Overs)</strong>',null,null,null,null]
          
          
          ,[3,15,'<strong>Toronto Jaguars: 15/0 (3.0 Overs)</strong>',20,'<strong>Royal Tigers: 20/0 (3.0 Overs)</strong>',null,null,null,null]
          
          
          ,[4,19,'<strong>Toronto Jaguars: 19/0 (4.0 Overs)</strong>',38,'<strong>Royal Tigers: 38/0 (4.0 Overs)</strong>',null,null,null,null]
          
          
          ,[5,26,'<strong>Toronto Jaguars: 26/0 (5.0 Overs)</strong>',49,'<strong>Royal Tigers: 49/0 (5.0 Overs)</strong>',null,null,null,null]
          
          
          ,[6,38,'<strong>Toronto Jaguars: 38/0 (6.0 Overs)</strong>',58,'<strong>Royal Tigers: 58/1 (6.0 Overs)</strong>',null,null,null,null]
          
              ,[6,38,'<strong>Toronto Jaguars: 38/0 (6.0 Overs)</strong>',58,'<strong>Royal Tigers: 58/1 (6.0 Overs)</strong>',null,null,1,'5.2 Usman A to Mubashir J <strong>STUMPED</strong><BR><strong>Mubashir Jawed</strong> St Atanu B b Usman A 20 (18b  1 Fours, 1 Sixers SR 111.11)']
              
          
          ,[7,50,'<strong>Toronto Jaguars: 50/1 (7.0 Overs)</strong>',66,'<strong>Royal Tigers: 66/1 (7.0 Overs)</strong>',null,null,null,null]
          
          ,[7,50,'<strong>Toronto Jaguars: 50/1 (7.0 Overs)</strong>',66,'<strong>Royal Tigers: 66/1 (7.0 Overs)</strong>',1,'6.4 Muhammad Salman A to Usman A1 runs <strong>RUN OUT</strong><BR><strong>Usman Aziz</strong> run out (Muhammad Salman A)  13 (15b  0 Fours, 1 Sixers SR 86.67)',null,null]
          
          
          ,[8,57,'<strong>Toronto Jaguars: 57/1 (8.0 Overs)</strong>',77,'<strong>Royal Tigers: 77/1 (8.0 Overs)</strong>',null,null,null,null]
          
          
          ,[9,66,'<strong>Toronto Jaguars: 66/1 (9.0 Overs)</strong>',82,'<strong>Royal Tigers: 82/1 (9.0 Overs)</strong>',null,null,null,null]
          
          
          ,[10,78,'<strong>Toronto Jaguars: 78/1 (10.0 Overs)</strong>',93,'<strong>Royal Tigers: 93/1 (10.0 Overs)</strong>',null,null,null,null]
          
          
          ,[11,83,'<strong>Toronto Jaguars: 83/2 (11.0 Overs)</strong>',null,'<strong>Royal Tigers: null/1 (11.0 Overs)</strong>',null,null,null,null]
          
          ,[11,83,'<strong>Toronto Jaguars: 83/2 (11.0 Overs)</strong>',null,'<strong>Royal Tigers: null/1 (11.0 Overs)</strong>',1,'10.4 Irfan S to Atanu B <strong>BOWLED</strong><BR><strong>Atanu Bharali</strong> b Irfan S 49 (41b  2 Fours, 1 Sixers SR 119.51)',null,null]
          
          
          ,[12,91,'<strong>Toronto Jaguars: 91/2 (12.0 Overs)</strong>',null,'<strong>Royal Tigers: null/1 (12.0 Overs)</strong>',null,null,null,null]
          
        ]);
        
        var chartWidth;
        var chartArea;
        if($(window).width()>768){
        chartWidth=400;
        chartArea="80%";
        }
        else if($(window).width()<320){
           chartWidth=280;
           chartArea="60%";
        }
        else{
          chartWidth=320;
          chartArea="75%";
        }
        

        var options = {
          curveType: 'function',
          width:chartWidth,
          height:300,
          legend: {position: 'bottom'},
          lineWidth: 3,
          chartArea: {width: chartArea,height:'70%'},
          vAxis:{title:'Runs',viewWindow: {min: 0}},
          hAxis:{title:'Overs',gridlines:{color:'transparent'}},
          tooltip: {
                isHtml: true
            },
            series: {
                2: {
                    pointSize: 10,
                    lineWidth: 0,
                    color: '#3366cc',
                    visibleInLegend:false
                },
                3: {
                    pointSize: 10,
                    lineWidth: 0,
                    color: '#dc3912',
                    visibleInLegend:false
                }
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,2,3,4, {
            type: 'number',
            label: data.getColumnLabel(2),
            calc: function (dt, row) {
              var wickets = dt.getValue(row, 5);
              var displayValue = null;
                if(wickets > 0){
                  displayValue = dt.getValue(row, 1);
                  if(wickets > 1){
                    displayValue = displayValue + (wickets *2);
                  }
                }
                return displayValue;
              }
          },6,
            {
                type: 'number',
                label: data.getColumnLabel(2),
                calc: function (dt, row) {
                  var wickets = dt.getValue(row, 7);
                  var displayValue = null;
                  if(wickets > 0){
                      displayValue = dt.getValue(row, 3);
                      if(wickets > 1){
                        displayValue = displayValue + (wickets *2);
                      }
                    }
                    return displayValue;
                }
            },8
        ]);

        chart.draw(view, options);
      }
    </script>
    
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


<script>



//Load Video Data.
var listOfVideo = [];
function loadVideoInitData(matchId, clubId){
	
	console.log(matchId, " -- ", clubId);
	
	
	//var ajaxUrl = "/utilsv2/ballVideos.json";
	var apiHost = 'https://sportapi.cricclubs.com';
	//apiHost = "https://poc.cricclubs.com";
	var ajaxUrl = apiHost + "/sport/app/scorecard/getBallVideosList?clubId="+clubId+"&matchId="+matchId;
	//var ajaxUrl = "/utilsv2/ballVideos.json";
	$.ajax({
		url: ajaxUrl,
		headers: {
			"Content-Type":"application/json",
			"consumerKey":"9e9L7i5Te",
			"ApiKey":"Et5I7L9E9"
		   },
		success:function(result){
			console.log("result " , result);
			var templistOfVideo = result.data;
			if(templistOfVideo){
				listOfVideo = [];
				for(var i = 0; i < templistOfVideo.length; i ++){
					if(templistOfVideo[i].ballVideoLink){
						listOfVideo.push(templistOfVideo[i]);
					}
				}
				
				for(var i = 0; i < listOfVideo.length; i ++){
					$('#btm_video_'+listOfVideo[i].batsman).show();
					$('#bwl_video_'+listOfVideo[i].bowler).show();
					if(listOfVideo[i].runsDisplay == 6 ){
						$('#btsix_video_'+listOfVideo[i].batsman).show();
					}else if(listOfVideo[i].runsDisplay == 4){
						$('#btfour_video_'+listOfVideo[i].batsman).show();
					}else if(listOfVideo[i].runsDisplay == 'W'){
						$('#bthowOutPH_video_'+listOfVideo[i].batsman).show();
						$('#bthowOut_video_'+listOfVideo[i].batsman).show();
						$('#bwlwicket_video_'+listOfVideo[i].bowler).show();
					}
				}
			}
	  }});
}

$(window).resize(function() {
	$("#dialogOpenVideoHTML").dialog("option", "position", "center"); //places the dialog box at the center
	$("#dialogOpenVideoHTML").dialog({
		width:  $(window).width() > 600 ? $(window).width() - ($(window).width() * 35/100) : 'auto',
	});
	$("#dialogOpenVideoYouTube").dialog("option", "position", "center"); //places the dialog box at the center
	$("#dialogOpenVideoYouTube").dialog({
		width:  $(window).width() > 600 ? $(window).width() - ($(window).width() * 35/100) : 'auto', 
	});
	
});
var nextsrc = [];
var runningVideo = 0;
function playInHtml5Video(playerId, playerType, playerName){
	nextsrc = [];
	if(!listOfVideo){
		return false;
	}
	$("#ball-on-videoplayer").empty();
	var videoSrcIdx = 0;
	if(playerType == 'bt'){
		for(var i = 0; i < listOfVideo.length; i++){
			if(listOfVideo[i].batsman == playerId){
				nextsrc.push(listOfVideo[i].ballVideoLink);
				$( "#ball-on-videoplayer" ).append("<span class='circle-ball' style='cursor: pointer;' id='ball_idx_"+videoSrcIdx+"' onclick='changeTheVideo("+ 
						videoSrcIdx +")'>"+listOfVideo[i].runsDisplay+"</span>");
				videoSrcIdx = videoSrcIdx + 1;
			}
		}
	}else if(playerType == 'bwl'){
		for(var i = 0; i < listOfVideo.length; i++){
			if(listOfVideo[i].bowler == playerId){
				nextsrc.push(listOfVideo[i].ballVideoLink);
				$( "#ball-on-videoplayer" ).append("<span class='circle-ball' style='cursor: pointer;' id='ball_idx_"+videoSrcIdx+"' onclick='changeTheVideo("+ 
						videoSrcIdx +")'>"+listOfVideo[i].runsDisplay+"</span>");
				videoSrcIdx = videoSrcIdx + 1;
			}
		}
	}else if(playerType == 'out'){
		for(var i = 0; i < listOfVideo.length; i++){
			if(listOfVideo[i].batsman == playerId && listOfVideo[i].runsDisplay == 'W'){
				nextsrc.push(listOfVideo[i].ballVideoLink);
				$( "#ball-on-videoplayer" ).append("<span class='circle-ball' style='cursor: pointer;' id='ball_idx_"+videoSrcIdx+"' onclick='changeTheVideo("+ 
						videoSrcIdx +")'>"+listOfVideo[i].runsDisplay+"</span>");
				videoSrcIdx = videoSrcIdx + 1;
			}
		}
	}else if(playerType == 'four'){
		for(var i = 0; i < listOfVideo.length; i++){
			if(listOfVideo[i].batsman == playerId && listOfVideo[i].runsDisplay == 4){
				nextsrc.push(listOfVideo[i].ballVideoLink);
				$( "#ball-on-videoplayer" ).append("<span class='circle-ball' style='cursor: pointer;' id='ball_idx_"+videoSrcIdx+"' onclick='changeTheVideo("+ 
						videoSrcIdx +")'>"+listOfVideo[i].runsDisplay+"</span>");
				videoSrcIdx = videoSrcIdx + 1;
			}
		}
	}else if(playerType == 'six'){
		for(var i = 0; i < listOfVideo.length; i++){
			if(listOfVideo[i].batsman == playerId && listOfVideo[i].runsDisplay == 6){
				nextsrc.push(listOfVideo[i].ballVideoLink);
				$( "#ball-on-videoplayer" ).append("<span class='circle-ball' style='cursor: pointer;' id='ball_idx_"+videoSrcIdx+"' onclick='changeTheVideo("+ 
						videoSrcIdx +")'>"+listOfVideo[i].runsDisplay+"</span>");
				videoSrcIdx = videoSrcIdx + 1;
			}
		}
	}else if(playerType == 'blwicket'){
		for(var i = 0; i < listOfVideo.length; i++){
			if(listOfVideo[i].bowler == playerId && listOfVideo[i].runsDisplay == 'W'){
				nextsrc.push(listOfVideo[i].ballVideoLink);
				$( "#ball-on-videoplayer" ).append("<span class='circle-ball' style='cursor: pointer;' id='ball_idx_"+videoSrcIdx+"' onclick='changeTheVideo("+ 
						videoSrcIdx +")'>"+listOfVideo[i].runsDisplay+"</span>");
				videoSrcIdx = videoSrcIdx + 1;
			}
		}
	}
	var elm = 0;
	
	$(".dialogOpenVideoHTML").dialog("option","title",playerName+" - Video(s)");
	var Player = document.getElementById('CCVideoPlayer');
	//Player.src="";
	Player.onended = function(){
	    if(elm < nextsrc.length){
	         Player.src = nextsrc[elm];
	         elm = elm+1;
	    }
	}
	Player.onended();
	$("#dialogOpenVideoHTML").dialog("open");
	setTimeout(function(){
		Player.play();
	}, 500);
	runningVideo = 0;
	$(".circle-ball").css("background","#26578b");
    $("#ball_idx_"+runningVideo+"").css("background","rgb(84, 154, 228)");
}
var Player = document.getElementById('CCVideoPlayer');
Player.addEventListener("ended", function(e) {
    // here the end is detected
	console.log("The video has ended");
	runningVideo = runningVideo +1;
	$(".circle-ball").css("background","#26578b");
	$("#ball_idx_"+runningVideo+"").css("background","rgb(84, 154, 228)");
});

function changeTheVideo(videoIndex){
	var Player = document.getElementById('CCVideoPlayer');
	var elm = videoIndex;
	//Player.src="";
	runningVideo = videoIndex;
	$(".circle-ball").css("background","#26578b");
    $("#ball_idx_"+runningVideo+"").css("background","rgb(84, 154, 228)");
	Player.onended = function(){
	    if(elm < nextsrc.length){         
	         Player.src = nextsrc[elm];
	         elm = elm+1;
	    }
	}
	Player.onended();
	Player.play();
}
var youTubePlayer, currentVideoId = 0;
var videoYouTubeIDs = [];
function openVideoHTMLvs(playerId, playerType, playerName){
	console.log(playerId, playerType, playerName);
	enable = 0;
	var isYouTubeURL = false;
	if(listOfVideo && listOfVideo.length > 0){
		var videoUrl = listOfVideo[0].ballVideoLink;
		if(videoUrl && videoUrl.toLowerCase().includes("youtube.")){
			isYouTubeURL = true;
		}
	}
	if(!isYouTubeURL){
		playInHtml5Video(playerId, playerType, playerName);		
	}else{
		// Play YouTube Video URL List
	  	/**
	     * Put your video IDs in this array
	     
	    videoYouTubeIDs = [
	        'fiPELKb9nhY',
	        '3ObMJvHkPTQ'
	    ];
		*/
	    if(playerType == 'bt'){
	    	videoYouTubeIDs = [];
	    	for(var i = 0; i < listOfVideo.length; i++){
				if(listOfVideo[i].batsman == playerId){
					var ytLink =  listOfVideo[i].ballVideoLink;
					if(ytLink.includes("v=")){
						var ytId = ytLink.substr((ytLink.indexOf("v=")+2));
						videoYouTubeIDs.push(ytId);
					}
				}
	    	}
	    }else if(playerType == 'bwl'){
	    	videoYouTubeIDs = [];
			for(var i = 0; i < listOfVideo.length; i++){
				if(listOfVideo[i].bowler == playerId){
					var ytLink =  listOfVideo[i].ballVideoLink;
					if(ytLink.includes("v=")){
						var ytId = ytLink.substr((ytLink.indexOf("v=")+2));
						videoYouTubeIDs.push(ytId);
					}
				}
			}
	    }else if(playerType == 'out'){
	    	videoYouTubeIDs = [];
			for(var i = 0; i < listOfVideo.length; i++){
				if(listOfVideo[i].batsman == playerId && listOfVideo[i].runsDisplay == 'W'){
					var ytLink =  listOfVideo[i].ballVideoLink;
					if(ytLink.includes("v=")){
						var ytId = ytLink.substr((ytLink.indexOf("v=")+2));
						videoYouTubeIDs.push(ytId);
					}
				}
			}
	    }else if(playerType == 'four'){
	    	videoYouTubeIDs = [];
			for(var i = 0; i < listOfVideo.length; i++){
				if(listOfVideo[i].batsman == playerId && listOfVideo[i].runsDisplay == 4){
					var ytLink =  listOfVideo[i].ballVideoLink;
					if(ytLink.includes("v=")){
						var ytId = ytLink.substr((ytLink.indexOf("v=")+2));
						videoYouTubeIDs.push(ytId);
					}
				}
			}			
	    }else if(playerType == 'six'){
	    	videoYouTubeIDs = [];
			for(var i = 0; i < listOfVideo.length; i++){
				if(listOfVideo[i].batsman == playerId && listOfVideo[i].runsDisplay == 6){
					var ytLink =  listOfVideo[i].ballVideoLink;
					if(ytLink.includes("v=")){
						var ytId = ytLink.substr((ytLink.indexOf("v=")+2));
						videoYouTubeIDs.push(ytId);
					}
				}
			}
	    }else if(playerType == 'blwicket'){
	    	videoYouTubeIDs = [];
			for(var i = 0; i < listOfVideo.length; i++){
				if(listOfVideo[i].bowler == playerId && listOfVideo[i].runsDisplay == 'W'){
					var ytLink =  listOfVideo[i].ballVideoLink;
					if(ytLink.includes("v=")){
						var ytId = ytLink.substr((ytLink.indexOf("v=")+2));
						videoYouTubeIDs.push(ytId);
					}
				}
			}
	    }

	    $("#dialogOpenVideoYouTube").dialog("open");
	    onYouTubeIframeAPIReady();
	}

}

	function onYouTubeIframeAPIReady() {
		youTubePlayer = new YT.Player('youTubeListPlayer', {
			width:700,
			 height:450,
	        playerVars: { 'autoplay': 1, 'controls': 1,'autohide':1,'wmode':'opaque' },
	        events: {
	            'onReady': onPlayerReady,
	            'onStateChange': onPlayerStateChange
	        }
	    });
	}

	function onPlayerReady(event) {
	    event.target.loadVideoById(videoYouTubeIDs[currentVideoId]);
	}
	
	function onPlayerStateChange(event) {
	    if (event.data == YT.PlayerState.ENDED) {
	        currentVideoId++;
	        if (currentVideoId < videoYouTubeIDs.length) {
	        	youTubePlayer.loadVideoById(videoYouTubeIDs[currentVideoId]);
	        }
	    }
	}
	function fluidDialog() {
	    var $visible = $(".ui-dialog:visible");
	    // each open dialog
	    $visible.each(function () {
	        var $this = $(this);
	        var dialog = $this.find(".ui-dialog-content").data("ui-dialog");
	        // if fluid option == true
	        if (dialog.options.fluid) {
	            var wWidth = $(window).width();
	            // check window width against dialog width
	            if (wWidth < (parseInt(dialog.options.maxWidth) + 50))  {
	                // keep dialog from filling entire screen
	                $this.css("max-width", "95%");
	            } else {
	                // fix maxWidth bug
	                $this.css("max-width", dialog.options.maxWidth + "px");
	            }
	            //reposition dialog
	            dialog.option("position", dialog.options.position);
	        }
	    });

	}
$(document).ready(function(){
	
	// Video Player realated chagnes.
	loadVideoInitData(3319, 2565);
	$( "#dialogOpenVideoHTML" ).dialog({
		autoOpen: false,
	    modal: true,
	    responsive: true,
	    width:  $(window).width() > 600 ? $(window).width() - ($(window).width() * 35/100) : 'auto',
	    fluid: true,
	    show: {effect: 'scale', duration: 700},
	    hide: {effect: 'puff', duration: 500},
	    dialogClass: 'myTitleClass',
	    close:function(){
	    	$("#CCVideoPlayer").src="";
	    	enable = 1;
		}
	    });
	
	$( "#dialogOpenVideoYouTube" ).dialog({
		autoOpen: false,
	    modal: true,
	    responsive: true,
	    width:  $(window).width() > 600 ? $(window).width() - ($(window).width() * 35/100) : 'auto',
	    fluid: true,
	    show: {effect: 'scale', duration: 700},
	    hide: {effect: 'puff', duration: 500},
	    dialogClass: 'myTitleClass',
		    close:function(){
		    	$("#CCVideoPlayer").src="";
		    	enable = 1;
			}
	    });
	
	var scoreCardViewTabId = 3319 + '' +  2565;
	// Stay on same page for ball by ball.
	$('#ballByBallTeamTab1').click(function(){
		localStorage.setItem("showScoreSelectedTab"+scoreCardViewTabId, "ballByBallTeam1");
		showScoreSelectedTab();
	})
	$('#ballByBallTeamTab2').click(function(){
		localStorage.setItem("showScoreSelectedTab"+scoreCardViewTabId, "ballByBallTeam2");
		showScoreSelectedTab();
	})
	$('#ballByBallTeamTab1_2').click(function(){
		localStorage.setItem("showScoreSelectedTab"+scoreCardViewTabId, "ballByBallTeam1_2");
		showScoreSelectedTab();
	})
	$('#ballByBallTeamTab2_2').click(function(){
		localStorage.setItem("showScoreSelectedTab"+scoreCardViewTabId, "ballByBallTeam2_2");
		showScoreSelectedTab();
	})
	$('#ballByBallTeamTab3').click(function(){
		localStorage.setItem("showScoreSelectedTab"+scoreCardViewTabId, "ballByBallTeam1_2");
		showScoreSelectedTab();
	})
	$('#ballByBallTeamTab4').click(function(){
		localStorage.setItem("showScoreSelectedTab"+scoreCardViewTabId, "ballByBallTeam2_2");
		showScoreSelectedTab();
	})
	$('#ballByBalltabSuperOver1').click(function(){
		localStorage.setItem("showScoreSelectedTab"+scoreCardViewTabId, "ballByBallSuperOver1");
		showScoreSelectedTab();
	})
	$('#ballByBalltabSuperOver2').click(function(){
		localStorage.setItem("showScoreSelectedTab"+scoreCardViewTabId, "ballByBallSuperOver2");
		showScoreSelectedTab();
	})
	
	function showScoreSelectedTab(){
		if(localStorage['showScoreSelectedTab'+scoreCardViewTabId] == "ballByBallTeam1"){
		  $('#ballByBallTeam2').removeClass("active");
		  $('#ballByBallTeamTab2').removeClass("active");
		  $('#ballByBallTeam1_2').removeClass("active");
		  $('#ballByBallTeamTab1_2').removeClass("active");
		  $('#ballByBallTeam2_2').removeClass("active");
		  $('#ballByBallTeamTab2_2').removeClass("active");
		  $('#ballByBallTeam3').removeClass("active");
		  $('#ballByBallTeamTab3').removeClass("active");
		  $('#ballByBallTeam4').removeClass("active");
		  $('#ballByBallTeamTab4').removeClass("active");
		  $('#ballByBallTeam1').addClass("active");
		  $('#ballByBallTeamTab1').addClass("active");
		  if($('#ballByBalltabSuperOver1').length != 0 && $('#ballByBallSuperOver1').length != 0 
				  && $('#ballByBalltabSuperOver2').length != 0 && $('#ballByBallSuperOver2').length != 0){
			  $('#ballByBalltabSuperOver1').removeClass("active");
			  $('#ballByBallSuperOver1').removeClass("active");
			  $('#ballByBalltabSuperOver2').removeClass("active");
			  $('#ballByBallSuperOver2').removeClass("active");
		  }
		}else if(localStorage['showScoreSelectedTab'+scoreCardViewTabId] == "ballByBallTeam2"){
			 $('#ballByBallTeam2').addClass("active");
			  $('#ballByBallTeamTab2').addClass("active");
			  $('#ballByBallTeam1_2').removeClass("active");
			  $('#ballByBallTeamTab1_2').removeClass("active");
			  $('#ballByBallTeam2_2').removeClass("active");
			  $('#ballByBallTeamTab2_2').removeClass("active");
			  $('#ballByBallTeam3').removeClass("active");
			  $('#ballByBallTeamTab3').removeClass("active");
			  $('#ballByBallTeam4').removeClass("active");
			  $('#ballByBallTeamTab4').removeClass("active");
			  $('#ballByBallTeam1').removeClass("active");
			  $('#ballByBallTeamTab1').removeClass("active");
			  if($('#ballByBalltabSuperOver1').length != 0 && $('#ballByBallSuperOver1').length != 0 
					  && $('#ballByBalltabSuperOver2').length != 0 && $('#ballByBallSuperOver2').length != 0){
				  $('#ballByBalltabSuperOver1').removeClass("active");
				  $('#ballByBallSuperOver1').removeClass("active");
				  $('#ballByBalltabSuperOver2').removeClass("active");
				  $('#ballByBallSuperOver2').removeClass("active");
			  }
		}else if(localStorage['showScoreSelectedTab'+scoreCardViewTabId] == "ballByBallTeam1_2"){
			 $('#ballByBallTeam2').removeClass("active");
			  $('#ballByBallTeamTab2').removeClass("active");
			  $('#ballByBallTeam1_2').addClass("active");
			  $('#ballByBallTeamTab1_2').addClass("active");
			  $('#ballByBallTeam2_2').removeClass("active");
			  $('#ballByBallTeamTab2_2').removeClass("active");
			  $('#ballByBallTeam3').addClass("active");
			  $('#ballByBallTeamTab3').addClass("active");
			  $('#ballByBallTeam4').removeClass("active");
			  $('#ballByBallTeamTab4').removeClass("active");
			  $('#ballByBallTeam1').removeClass("active");
			  $('#ballByBallTeamTab1').removeClass("active");
			  if($('#ballByBalltabSuperOver1').length != 0 && $('#ballByBallSuperOver1').length != 0 
					  && $('#ballByBalltabSuperOver2').length != 0 && $('#ballByBallSuperOver2').length != 0){
				  $('#ballByBalltabSuperOver1').removeClass("active");
				  $('#ballByBallSuperOver1').removeClass("active");
				  $('#ballByBalltabSuperOver2').removeClass("active");
				  $('#ballByBallSuperOver2').removeClass("active");
			  }
		}else if(localStorage['showScoreSelectedTab'+scoreCardViewTabId] == "ballByBallTeam2_2"){
			 $('#ballByBallTeam2').removeClass("active");
			  $('#ballByBallTeamTab2').removeClass("active");
			  $('#ballByBallTeam1_2').removeClass("active");
			  $('#ballByBallTeamTab1_2').removeClass("active");
			  $('#ballByBallTeam2_2').addClass("active");
			  $('#ballByBallTeamTab2_2').addClass("active");
			  
			  $('#ballByBallTeam3').removeClass("active");
			  $('#ballByBallTeamTab3').removeClass("active");
			  $('#ballByBallTeam4').addClass("active");
			  $('#ballByBallTeamTab4').addClass("active");
			  
			  $('#ballByBallTeam1').removeClass("active");
			  $('#ballByBallTeamTab1').removeClass("active");
			  if($('#ballByBalltabSuperOver1').length != 0 && $('#ballByBallSuperOver1').length != 0 
					  && $('#ballByBalltabSuperOver2').length != 0 && $('#ballByBallSuperOver2').length != 0){
				  $('#ballByBalltabSuperOver1').removeClass("active");
				  $('#ballByBallSuperOver1').removeClass("active");
				  $('#ballByBalltabSuperOver2').removeClass("active");
				  $('#ballByBallSuperOver2').removeClass("active");
			  }
		}else if(localStorage['showScoreSelectedTab'+scoreCardViewTabId] == "ballByBallSuperOver1"){
			$('#ballByBallTeam2').removeClass("active");
			  $('#ballByBallTeamTab2').removeClass("active");
			  $('#ballByBallTeam1_2').removeClass("active");
			  $('#ballByBallTeamTab1_2').removeClass("active");
			  $('#ballByBallTeam2_2').removeClass("active");
			  $('#ballByBallTeamTab2_2').removeClass("active");
			  $('#ballByBallTeam3').removeClass("active");
			  $('#ballByBallTeamTab3').removeClass("active");
			  $('#ballByBallTeam4').removeClass("active");
			  $('#ballByBallTeamTab4').removeClass("active");
			  $('#ballByBallTeam1').removeClass("active");
			  $('#ballByBallTeamTab1').removeClass("active");
			  $('#ballByBalltabSuperOver2').removeClass("active");
			  $('#ballByBallSuperOver2').removeClass("active");
			  $('#ballByBalltabSuperOver1').addClass("active");
			  $('#ballByBallSuperOver1').addClass("active");
		}else if(localStorage['showScoreSelectedTab'+scoreCardViewTabId] == "ballByBallSuperOver2"){
			$('#ballByBallTeam2').removeClass("active");
			  $('#ballByBallTeamTab2').removeClass("active");
			  $('#ballByBallTeam1_2').removeClass("active");
			  $('#ballByBallTeamTab1_2').removeClass("active");
			  $('#ballByBallTeam2_2').removeClass("active");
			  $('#ballByBallTeamTab2_2').removeClass("active");
			  $('#ballByBallTeam3').removeClass("active");
			  $('#ballByBallTeamTab3').removeClass("active");
			  $('#ballByBallTeam4').removeClass("active");
			  $('#ballByBallTeamTab4').removeClass("active");
			  $('#ballByBallTeam1').removeClass("active");
			  $('#ballByBallTeamTab1').removeClass("active");
			  $('#ballByBalltabSuperOver1').removeClass("active");
			  $('#ballByBallSuperOver1').removeClass("active");
			  $('#ballByBalltabSuperOver2').addClass("active");
			  $('#ballByBallSuperOver2').addClass("active");
		}
	}
	showScoreSelectedTab();
	
var uploadObj = $("#fileUpload").uploadFile({
    url: "/MississaugaCricketLeague/matchDataUpload.do?matchId=3319&clubId=2565",
    dragDrop:true,
    fileName: "matchData",
    allowedTypes:"pdf,csv,doc,docx,xls,xlsx,xlsm,txt,zip,rar,jpg,jpeg,gif,png",	
    showStatusAfterSuccess:false,
    onSuccess:function(files,data,xhr){
    	var dataArray = data.split(",");
    	var status = dataArray[0];
    	var documentId = dataArray[1];
    	var documentName = dataArray[2];
       if(status == 'success'){
    	   var docHTML = '<span id="document'+documentId+'"><a target="_new" href="/MississaugaCricketLeague/document.doc?clubId=2565&documentId='+documentId + '">'+documentName+'</a>'
			+ ' <a href="javascript:deleteDocument('+documentId+',\''+documentName+'\');"><img alt="Delete" title="Delete" width="16" height="16" src="/utilsv2/images/delete.png"></a></span><br/>';
			
			$("#documentsDiv").append(docHTML);					
       }
    }
		});
		
$( "#errorMessage" ).dialog({
	autoOpen: false,
    modal: true
    });
$( "#loading" ).dialog({
	autoOpen: false,
    modal: true
    });
    
$( "#downloading" ).dialog({
	autoOpen: false,
    modal: true
    });


$("#dialog-confirm3").dialog({
      resizable: false,
      autoOpen: false,
      modal: true,
      buttons: {
        "Delete": function() {
        	var documentId = $("#deleteId-doc").val();
        	var ajaxUrl = '/MississaugaCricketLeague/deleteMatchData.do?clubId=2565&documentId=' + documentId;
        	$.ajax({
        		url:ajaxUrl,
        		success:function(result){
        			if(result != 'success'){
        				$('#errorMessage').html(result);
        				$('#errorMessage').dialog("open");
        			}else{
        				$("#document"+documentId).remove();
        			}
        	  }});
          $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
    
$( "#dialogOpenVideoLink" ).dialog({
	autoOpen: false,
    modal: true,
    width:700,
    height:450,
    close:function(){
    	enable = 1;
    $("#playVideo")[0].src="";
	}
    });

var livevideolinkimg = "";
var videoidimg = livevideolinkimg.split("/watch?v=");
$('.livevideothumbnail').each(function(){
	$(this)[0].src="//img.youtube.com/vi/"+videoidimg[1]+"/0.jpg";
});


});


function openLiveVideoLink(livevideolink){
	enable = 0;
	$(".dialogOpenVideoLink").dialog("option","title","Live Streaming...");
	var videoid = livevideolink.split("/watch?v=");
	$("#playVideo")[0].src="//www.youtube.com/embed/"+videoid[1]+"?autoplay=1&rel=0";	
	$("#dialogOpenVideoLink").dialog("open");
}

function deleteDocument(documentId,name){
	$("#deleteMessage-doc").html(name);
	$("#deleteId-doc").val(documentId);
	$("#dialog-confirm3").dialog("open");
}

function pdfScorecard(){
	//var canvas = document.getElementById("my-canvas"), ctx = canvas.getContext("2d");
	// draw to canvas...
	
	$("header").hide();
	$("footer").hide();
	
	$(".panel-heading").hide();
	$("#ballByBallTeam1").show();
	$("#ballByBallTeam2").show();
	$("#ballByBallTeam1_2").show();
	$("#ballByBallTeam2_2").show();
	
	$(window).scrollTop(0);
	$("#downloading").dialog('open');
	
		
	html2canvas($(".holder"), {scale: 2,onrendered: function(canvas) {
	        var canvasImg = canvas.toDataURL("image/png");
	               
              /*
          Here are the numbers (paper width and height) that I found to work. 
          It still creates a little overlap part between the pages, but good enough for me.
          if you can find an official number from jsPDF, use them.
          */
          var imgWidth = 210; 
          var pageHeight = 295;  
          var imgHeight = canvas.height * imgWidth / canvas.width;
          var heightLeft = imgHeight;

          var doc = new jsPDF('p', 'mm');
          var position = 0;
          doc.addImage(canvasImg, 'PNG', 0, position, imgWidth, imgHeight);
          heightLeft -= pageHeight;
          while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            doc.addPage();
            doc.addImage(canvasImg, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
          }
          doc.save('Scorecard.pdf'); 
          
          window.location.reload();
           
 	     	$("#divhidden").hide();
 			$(".holder").show();
 			$("#ballByBallTeam2").hide();
 			$("#ballByBallTeam1_2").hide();
 			$("#ballByBallTeam2_2").hide();
 			$(".panel-heading").show();
 			$("header").show();
 			$("footer").show();
 			$(window).scrollTop(0);
 			$("#downloading").dialog('close'); 
	    	
	    },
	});
}

function printScorecard(){
	
	$("header").hide();
	$("footer").hide();
	$(".panel-heading").hide();
	$("#ballByBallTeam1").show();
	$("#ballByBallTeam2").show();
	$("#ballByBallTeam1_2").show();
	$("#ballByBallTeam2_2").show();
	$("#AdminActionDropDown").hide();
	$("#downloading").dialog('open');
	$(window).scrollTop(0);
	html2canvas($(".holder"), {scale: 2,onrendered: function(canvas) {
    var canvasImg = canvas.toDataURL("image/png");
    var imgWidth = 210; 
    var pageHeight = 295;  
    var imgHeight = canvas.height * imgWidth / canvas.width;
    var heightLeft = imgHeight;

    var doc = new jsPDF('p', 'mm');
    var position = 0;
    doc.addImage(canvasImg, 'PNG', 0, position, imgWidth, imgHeight);
    heightLeft -= pageHeight;
    while (heightLeft >= 0) {
      position = heightLeft - imgHeight;
      doc.addPage();
      doc.addImage(canvasImg, 'PNG', 0, position, imgWidth, imgHeight);
      heightLeft -= pageHeight;
    }
    
     var printwindow=window.open(doc.output('bloburl'));
     printwindow.print();
 	location.reload();

	    }
	});
	    $("#divhidden").hide();
		$("#ballByBallTeam2").hide();
		$("#ballByBallTeam1_2").hide();
		$("#ballByBallTeam2_2").hide();
		$(".panel-heading").show();
		$("header").show();
		$("footer").show();
		$(window).scrollTop(0);
	   $("#AdminActionDropDown").show();
	   $("#downloading").dialog('close'); 

	

}

function printnow(){
	if($("#divhidden").css('display')==='block'){
		window.print();
		$("#divhidden").hide();
		$(".holder").show();
		$("#ballByBallTeam2").hide();
		$("#ballByBallTeam1_2").hide();
		$("#ballByBallTeam2_2").hide();
		$(".panel-heading").show();
		$("header").show();
		$("footer").show();
		 window.location.reload();
		return true;
	}
	else
		return false;
	
}

</script>
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
 
							
<!-- Go to www.addthis.com/dashboard to customize your tools -->
	<script async="async" type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5400c8d21856f56e"></script>
	<!-- For mobile share-->
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push([ '_setAccount', 'UA-22738381-1' ]);
	_gaq.push([ '_trackPageview' ]);

	(function() {
		var ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl'
				: 'http://www')
				+ '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);
	})();
</script>


<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '802686347040044');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=802686347040044&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"mluzr1O7kI20L7", domain:"cricclubs.com",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://certify-js.alexametrics.com/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="https://certify.alexametrics.com/atrk.gif?account=mluzr1O7kI20L7" style="display:none" height="1" width="1" alt="" /></noscript>
<!-- End Alexa Certify Javascript -->

<script type="text/javascript">
	window.fbAsyncInit = function() {
	  FB.init({
	    appId      : '529159335529995',
	    cookie     : true,  // enable cookies to allow the server to access 
	                        // the session
	    xfbml      : true,  // parse social plugins on this page
	    version    : 'v2.6' // use version 2.1
	  });
	
	  // Now that we've initialized the JavaScript SDK, we call 
	  // FB.getLoginStatus().  This function gets the state of the
	  // person visiting this page and can return one of three states to
	  // the callback you provide.  They can be:
	  //
	  // 1. Logged into your app ('connected')
	  // 2. Logged into Facebook, but not your app ('not_authorized')
	  // 3. Not logged into Facebook and can't tell if they are logged into
	  //    your app or not.
	  //
	  // These three cases are handled in the callback function.
	
/*  						  FB.getLoginStatus(function(response) {
	    statusChangeCallback(response);
	  });  */
	
	  };
	
</script>
<div id="fb-root" class=" fb_reset"><div style="position: absolute; top: -10000px; width: 0px; height: 0px;"><div></div></div></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=745734118815722";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>    	
		
 <!-- For mobile share-->
<script type="text/javascript">
$(document).ready(function() {
	
/////// Mobile Install app Ends	
	var linkOfApp = "https://play.google.com/store/apps/details?id=com.cricclubs&hl=en_US";
	$("#open-app-confirm").dialog({
	      resizable: false,
	      autoOpen: false,
	      modal: true,
	      buttons: {
	        "Install": function() {
	        	window.location = linkOfApp;
	          $( this ).dialog( "close" );
	        },
	        "Never Ask": function() {
	        	localStorage.setItem("dontAskMobileApp", true);
	         	$( this ).dialog( "close" );
	        },
	        Cancel: function() {
	          $( this ).dialog( "close" );
	        }
	      }
	    });

	function openInApp(appStoreName){
		$("#shoqAppStoreName").html(appStoreName);
		$("#open-app-confirm").dialog("open");
	}
	function checkAndOpenForMobileUser() {
		  var userAgent = navigator.userAgent || navigator.vendor || window.opera;
		
		  if( userAgent.match( /iPad/i ) || userAgent.match( /iPhone/i ) || userAgent.match( /iPod/i ) )
		  {
			  linkOfApp = "https://apps.apple.com/us/app/cricclubs/id978682715"
			  openInApp('App Store');
		
		  }
		  else if( userAgent.match( /Android/i ) )
		  {
			  linkOfApp = 'https://play.google.com/store/apps/details?id=com.cricclubs&hl=en_US;';
			  openInApp('Play Store');
		  }
	} 
	
	 
/////// Mobile Install app Ends	
	
	$("#dialog-confirm-TnC" ).dialog({
	      resizable: false,
	      autoOpen: false,
	      modal: true,
	      width : '80%'
	    });
	

var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
$(".calendarBox").datepicker();



 $(document).on("click", '.whatsapp', function() {
        if( isMobile.any() ) {
            var text = document.title;
            var url = window.location.href;
            var message = encodeURIComponent(text) + " - " + encodeURIComponent(url);
            var whatsapp_url = "whatsapp://send?text=" + message;
            window.location.href = whatsapp_url;
        } else {
            alert("Please share this article in mobile device");
        }
    });
	
});
function showTerms(){
  	$("#dialog-confirm-TnC").dialog("open");
  }
function mobileFacebookShare(){
	window.open('https://www.facebook.com/sharer/sharer.php?u='+window.location.href , 'facebookWindow', 'width=600, height=300, left=64, top=44, scrollbars, resizable'); 
}
function mobileTwitterShare(){
	window.open('https://twitter.com/intent/tweet?url='+window.location.href+'&text='+document.title , 'twitterWindow', 'width=600, height=300, left=64, top=44, scrollbars, resizable'); 
}
function mobileGoogleShare(){
	window.open('https://plus.google.com/share?url='+window.location.href , 'googleWindow', 'width=600, height=300, left=64, top=44, scrollbars, resizable'); 
}
function mobileMailShare(){
	window.location.href = 'mailto:?Subject='+ document.title+'&Body='+window.location.href; 
}

var addthis_share = {
		   url: window.location.href,
		   title: document.title
		}
function getURLOfPage(){
	return window.location.href;
}

function getTitleofPage(){
	return document.title;
}

function resizeScroll(){
	$('html').getNiceScroll().remove();
	var nice = $("html").niceScroll({zindex:1000000,cursorborder:"",cursorborderradius:"2px",cursorwidth:"14px",cursorcolor:"#191919",cursoropacitymin:.5}); 
}	
$(document).ready(function (){
	
	   
		    
	 	});
	 	
	 	

</script>
 <script type="text/javascript" src="/utilsv2/js/duplicate.js"></script>
 <script type="text/javascript" src="/utilsv2/js/forms.js"></script>

  
        
    

</div>
@stop