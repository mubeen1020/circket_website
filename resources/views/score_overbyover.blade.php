@extends('default')
@section('content')

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
  							<li><a href="{{ route('balltoballScorecard', $match_results[0]->id) }}" >Ball By Ball</a></li>
							<li><a href="{{ route('fullScorecard', $match_results[0]->id) }}" >Full Scorecard</a></li>
							<li class="active"><a href="{{ route('fullScorecard_overbyover', $match_results[0]->id) }}" >Over by Over Score</a></li>
							<li><a href="{{ route('fullScorecard_chart', $match_results[0]->id) }}" >Charts</a></li>
							</ul>
                       </div>
                       <div class="panel-body">
                           <div class="tab-content">
                               <div class="tab-pane fade " id="tab1default">
                              
											Loading ...
									</div>
                               <div class="tab-pane fade " id="tab2default">
                               
                               
									Loading ...
									</div>
                               <div class="tab-pane fade " id="tab3default">
								
								Loading ...
								</div>
       						   <div class="tab-pane fade " id="tab4default">
								
								Loading ...
								</div>
       					<div class="tab-pane fade in active" id="tab5default">
								<br>
								<div>
	<div class="match-content">
		<div class="row">
		 <!-- <div class="panel-body match-summary-tab">
		        <div class="tab-content summary-list"> -->
						<div class="col-sm-6">
							<div class="match-table-innings">
								<div class="about-table  table-responsive" id="tab1default">
									<table class="table table-bordered">
										<thead>
											<tr style="font-weight: bold; text-align: left;">
												<th colspan="4">{{$teams_one}}
													Batting</th>
											</tr>
											<tr style="font-weight: bold; text-align: center;">
												<td>#</td>
												<td class="text-left">Bowler</td>
												<td>Runs</td>
												<td>Score</td>
											</tr>


											 @php $currnet_over = $scores[0]['overnumber'];
											 $last_over = $scores[0]['overnumber'];
											 $sum_score = 0;
											 $sum_wicket=0;
											 $sum_run = 0;  @endphp
											
											<tr style="text-align: center;">
													<td>{{$scores[0]['overnumber']}} </td>												
													<td class="text-left">{{$player[$scores[0]['bowlerId']]}}<br>
														<ul class="overballsec">
											@foreach($scores as $score)
											@if($score->inningnumber==1)
												@if($currnet_over !== $score['overnumber'])
												
													</ul>
													</td>
													
													<td>{{$sum_run}}</td>
													<td>{{$sum_score}}/{{$sum_wicket}}</td>
												</tr>

												<tr style="text-align: center;">
													<td>{{$score['overnumber']}}</td>												
													<td class="text-left">{{$player[$score['bowlerId']]}}<br>
														<ul class="overballsec">
															@php $sum_run = 0; @endphp
													@endif
															<li class="runs">{{$score['runs']}} {{$score['balltype']}} </li>
													@php $sum_score +=$score['runs'];
														 $sum_run +=$score['runs'];
														 $sum_wicket += ($score['balltype'] == 'Wicket') ? 1 : 0;
													     $currnet_over = $score['overnumber'] @endphp
														 @endif
											@endforeach 
</ul>
													</td>
													
													<td>{{$sum_run}}</td>
													<td>{{$sum_score}}/{{$sum_wicket}}</td>
												</tr>
											
											</thead>
									</table>
								</div>
							</div>
						</div>
						
						
						<div class="col-sm-6">
							<div class="match-table-innings">
								<div class="about-table  table-responsive" id="tab1default">
								<table class="table table-bordered">
										<thead>
											<tr style="font-weight: bold; text-align: left;">
												<th colspan="4">{{$teams_two}}
													Batting</th>
											</tr>
											<tr style="font-weight: bold; text-align: center;">
												<td>#</td>
												<td class="text-left">Bowler</td>
												<td>Runs</td>
												<td>Score</td>
											</tr>


											 @php $currnet_over = $scores[0]['overnumber'];
											 $last_over = $scores[0]['overnumber'];
											 $sum_score = 0;
											 $sum_wicket=0;
											 $sum_run = 0;  @endphp
											
											<tr style="text-align: center;">
													<td>{{$scores[0]['overnumber']}} </td>												
													<td class="text-left">{{$player[$scores[0]['bowlerId']]}}<br>
														<ul class="overballsec">
											@foreach($scores as $score)
											@if($score->inningnumber==2)
												@if($currnet_over !== $score['overnumber'])
												
													</ul>
													</td>
													
													<td>{{$sum_run}}</td>
													<td>{{$sum_score}}/{{$sum_wicket}}</td>
												</tr>

												<tr style="text-align: center;">
													<td>{{$score['overnumber']}}</td>												
													<td class="text-left">{{$player[$score['bowlerId']]}}<br>
														<ul class="overballsec">
															@php $sum_run = 0; @endphp
													@endif
															<li class="runs">{{$score['runs']}} {{$score['balltype']}} </li>
													@php $sum_score +=$score['runs'];
														 $sum_run +=$score['runs'];
														 $sum_wicket += ($score['balltype'] == 'Wicket') ? 1 : 0;
													     $currnet_over = $score['overnumber'] @endphp
														 @endif
											@endforeach 
</ul>
													</td>
													
													<td>{{$sum_run}}</td>
													<td>{{$sum_score}}/{{$sum_wicket}}</td>
												</tr>
											
											</thead>
									</table>
								</div>
							</div>
						</div>
						
						
						
						
						
					</div>
				</div>
				<!-- </div>
				</div> -->
				
</div>

<style>
<!--

-->
.table td{
      vertical-align: middle!important;
          padding: 10px 15px!important;
}
.about-table thead tr th{
 padding: 10px 15px!important;
}
.about-table{
margin: 0px!important;
}
.overballsec{
  margin:3px 0 0 0;
  padding:0;
  text-align: left;
}
.overballsec li{
	display: inline-block;
    background-color: #1f78ad;
    border-radius: 15px;
    padding: 2px 6px !important;
    /* width: 15px; 
    height: 20px;*/
    line-height: 14px;
    text-align: center;
    font-size: 12px;
    font-weight: bold;
    color: white;
   
}
.overballsec li.wicket{
  background-color:#d60f0fcc;
}
</style>


</div>
       				</div>
       			</div>
       		</div>
            </div>
            </div>
            </div>
@stop
