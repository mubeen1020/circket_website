@extends('default')
@section('content')
<div class="holder point">
    	<div class="container p-sm-0">
        	<div class="point-table-all sch">
            <div class="series-drops">
            	<div class="row series-drop">
                	<div class="col-sm-6 col-xs-12">
                    	<div class="border-heading" style="margin-bottom: 0px;">
                            <h5 style="text-align: left!important;">Match Results</h5>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                
                    </div>
                    </div>
<form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('result-form-submit')}}">
                @csrf
                    <div style="margin-top: 15px;" class="row series-drop">
                    
                    <div class="col-sm-3">
                    <div class="dropdown">
                    <select name="year"  id="year" class="form-control" >
                                 		<option value=""> Select Year(s)</option>
                                         @for ($year = date('Y'); $year >= 2015; $year--)
                                         <option <?php if(isset($_POST['year']) && $_POST['year']== $year){ echo 'selected'; } ?> value="{{$year}}">{{$year}}</option>
                                        @endfor
									</select>
                                    </div>
<!-- 
    <div class="dropdown">
        <button class="btn btn-default dropdown-toggle btn-align" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="false">
            Select Year(s) <span class="caret"></span>
        </button>
        <ul id="year-dropdown" class="dropdown-menu dropdown-height" role="menu" aria-labelledby="dropdownMenu1">
            @for ($year = date('Y'); $year >= 2015; $year--)
            <li role="presentation"><a class="dropdown-year" role="menuitem" tabindex="-1" href="#">{{ $year }}</a></li>
            @endfor
        </ul>
    </div> -->
</div>


                    <div class="col-xs-6 col-sm-3">
						<div class="dropdown">
                        <select name="tournament"  id="tournament" class="form-control" >
                                 		<option value=""> Select tournament(s)</option>
                                        @foreach($tournament as $tournament_id => $tournament_name)
                                        <option <?php if(isset($_POST['tournament']) && $_POST['tournament']== $tournament_id){ echo 'selected'; } ?> value="{{$tournament_id}}">{{$tournament_name}}</option>
                                       @endforeach
									</select>
						</div>
                    </div>
                 	<div class="col-xs-6 col-sm-3" title="Change internal club">
                    <div class="dropdown">
                                    <select name="club" id="club" class="form-control">
                                <option value="">Select Club</option>
                                @foreach($clubs as $index => $club)
                                <option <?php if(isset($_POST['club']) && $_POST['club']== $index){ echo 'selected'; } ?> value="{{$index}}">{{$club}}</option>
                                    @endforeach
                            </select>
							</div>
                    </div>
                    <div class="col-xs-6 col-sm-3">
                  
							<div class="dropdown">
                            <select name="teams" id="teams" class="form-control">
                                <option value="">Select team(s)</option>
                                @foreach($teams as $team_id => $team_name)
                                <option <?php if(isset($_POST['teams']) && $_POST['teams']== $team_id){ echo 'selected'; } ?> value="{{$team_id}}">{{$team_name}}</option>
                                @endforeach
                            </select>
							</div>
							
                    </div>
                     <div class="col-sm-12 admins-drop text-right hidden-phone" style="display:flex; justify-content: flex-end;">
               
						</div>
                
            </div>
            <div class="series-drop">
            	<div class="row">
					<div class="col-sm-3 col-xs-4" title="From Date">
                    	<input type="text" name="created_at" autocomplete="off" placeholder="From Date" value="<?php  if(isset($_POST['created_at'])) { echo  $_POST['created_at']; } ?>" align="top" class="calendarBox form-control datepicker" id="created_at">
					</div>
					<div class="col-sm-3 col-xs-4" title="To Date">
                    	<input type="text"  name="end_at" autocomplete="off" placeholder="To Date" value="<?php  if(isset($_POST['end_at'])) { echo  $_POST['end_at']; } ?>" align="top" class="calendarBox form-control datepicker" id="end_at">
					</div>
					<div class="col-sm-2 col-xs-4" title="Search Dates"> 
                    	<button class="btn btn-primary" id="datesSearch">Search Result</button>
                            </div>
					</div>
            	</div>
            </div>
            </form>
    


<style>


  #schedule-table_filter{
    text-align: right;
  }
  .list-table tbody tr td a, .list-table tbody tr td{
  line-height: 26px!important;
  font-size: 13px!important;
  }
  
  .table>thead>tr>th {
    text-align: center;
}
  .list-table tbody tr td a{
      /*display: table-row!important;*/
      padding: 0px!Important;
      }
      .list-drop .dropdown-menu-right .dropdown-menu a {
    margin-right: 0px!important;
    display: block!important;
    background: #fff!important;
    padding: 1px 10px!important;
        text-transform: capitalize;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
}

.dropdown-menu {
    padding: 5px 5px;
}

.display-actions{
    background-color: transparent !important;
}

#schedule-table1 thead tr th:last-child{
text-align:center!important
}
#schedule-table1 tbody tr td:last-child{
text-align:center!important
}


#schedule-table1 tbody tr td{
text-align:left!important;
padding: 10px 20px !important;
}
#schedule-table1 thead tr th{
text-align:left!important
}
#schedule-table1 thead tr th:first-child{
text-align:center !important;
}  
#schedule-table1 tbody tr td:first-child{
text-align:center !important;
}  
       
</style>




@php
    if(count($results) > 0){
@endphp

                         @foreach($results as $data)
						
                       	@if($data['running_inning'] == 3)
                       	
<div class="month-all sp">
<div class="month-all listView">
       <div class="schedule-all">
           <div class="row team-data" id="deleteRow3257">
               <div class="col-xs-3 col-sm-1 sp mobile-b">
                   <div class="sch-time text-center h-90">
                       <h5><strong>{{$data['match_description']}}</strong></h5>
                       <h2>{{date('d', strtotime($data['match_startdate']))}}</h2>
                       <h5>{{date('M Y', strtotime($data['match_startdate']))}}</h5>
                   </div>
               </div>
               <div class="col-xs-9 col-sm-4 p-sm-0 mobile-b">
                   <div class="schedule-logo text-center h-90">
                   <ul class="list-inline">
				   <li class="lose">
                                        <span>
                                            @if (isset($total_runs[$data->id][0]))
                                                {{ $total_runs[$data->id][0] }}
                                            @else
                                                N/A
                                            @endif
                                            / 
                                            @if (isset($total_wicket_fixture[$data->id][0]))
                                                {{ $total_wicket_fixture[$data->id][0] }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                        <br>
                                        <p>
                                        @if (isset($total_over_fixture[$data->id][0]))
    @php
        $totalBalls = isset($total_ball_fixture[$data->id][0]) ? $total_ball_fixture[$data->id][0] : 0;
        $overs = floor($totalBalls / 6);
        $ballsInCurrentOver = $totalBalls % 6;
    @endphp
    <th class="hidden-phone">
        @if ($ballsInCurrentOver == 0)
            {{ $total_over_fixture[$data->id][0] }}.{{ $ballsInCurrentOver }}
        @else
            {{ $total_over_fixture[$data->id][0] - 1 }}.{{ $ballsInCurrentOver }} 
        @endif
    </th>
@else
    N/A
@endif
/{{ $data['numberofover'] }}

                                        </p>
                                    </li>
				   
	
		                           <li><a href="">
		                           <img src="https://eoscl.ca/admin/public/Team/{{$data['first_inning_team_id']}}.png" class="img-responsive img-circle" style="width: 70px;height: 70px;"></a></li>
		                           <li><a href="">
		                           <img src="https://eoscl.ca/admin/public/Team/{{$data['second_inning_team_id']}}.png" class="img-responsive img-circle" style="width: 70px;height: 70px;"></a></li>
		                        
										   <li class="lose">
                                        <span>
                                            @if (isset($total_runs[$data->id][1]))
                                                {{ $total_runs[$data->id][1] }}
                                            @else
                                                N/A
                                            @endif
                                            / 
                                            @if (isset($total_wicket_fixture[$data->id][1]))
                                                {{ $total_wicket_fixture[$data->id][1] }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                        <br>
                                        <p>
                                        @if (isset($total_over_fixture[$data->id][1]))
    @php
        $totalBalls = isset($total_ball_fixture[$data->id][1]) ? $total_ball_fixture[$data->id][1] : 0;
        $overs = floor($totalBalls / 6);
        $ballsInCurrentOver = $totalBalls % 6;
    @endphp
    <th class="hidden-phone">
        @if ($ballsInCurrentOver == 0)
            {{ $total_over_fixture[$data->id][1] }}.{{ $ballsInCurrentOver }}
        @else
            {{ $total_over_fixture[$data->id][1] - 1 }}.{{ $ballsInCurrentOver }} 
        @endif
    </th>
@else
    N/A
@endif
/{{ $data['numberofover'] }}

                                        </p>
                                    </li>
	                      	 </ul>	
                         </div>
               </div>
             <div class="col-xs-12 col-sm-4">
                   <div class="schedule-text">
                   @if(isset($tournament[$data['tournament_id']]))
    <h4>{{$tournament[$data['tournament_id']]}}</h4>
@else
    <h4>Tournament Not Found</h4>
@endif
                       <h3>{{ $teams[$data['first_inning_team_id']]??''}}<span class="v"> v </span> {{ $teams[$data['second_inning_team_id']]??''}}</h3>
                       <h4>{{$data['match_result_description']}}</h4>
                        </div>
               </div>
               <div class="col-xs-12 col-sm-3 ball-score" style="padding-right: 22px;">
               
               
                   <div class="live-score text-center ">
                  
                       <h5 class="text-right">
                      
                        
                        
								&nbsp;
								 <img src="/utilsv2/theme2-static/images/cric-ball.png"> Ball By Ball </h5>
                   </div>
                  
                   <div class="score-share text-right" style="display: inline-block;">
                   
                       <ul class="list-inline">
                           <li>
                          
                           <a  href="{{ url('fullScorecard/' . $data['id']) }}" class="btn btn-sc"><i class="fa fa-calendar-check-o"></i>Scorecard</a>
                       			
								
							</li>
                       </ul>
                   </div>
                   			</div>
           </div>
       </div>
       </div>
        
    
       </div>
	   @endif
       							@endforeach
        
    

 
 @php
}
@endphp

      
        </div>
      
                             

                      




<script>
  $('#list-view').click(function() {
	  $('.gridView').hide();
      $('.listView').show();
  })
  $('#grid-view').click(function() {
	  $('.gridView').show();
      $('.listView').hide();
  })

</script></div>
</div>
</div>
</div>
           @stop