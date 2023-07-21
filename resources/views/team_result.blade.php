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
												@foreach($team_resultData1 as $data)
													@if ($data['iscaptain'] == 1)
														{{ $player[$data['player_id']] }}
													@endif
												@endforeach

											</p>
											 
											<p>
												<span>Player Count</span> :
												{{$teamPlayerCount}}
											</p>
											<p>
												<span>Home Ground</span> : <b><a href="">Wet n Wild</a></b>
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
<div class="all-tab-table team">
			<div class="container p-sm-0">
				<div class="score-tab">
					<div class="complete-list">
						<div class="panel with-nav-tabs panel-default">
								<div class="panel-heading tabs-team">
                               
									<ul class="nav nav-tabs">
										<li><a href="{{ url('team-view', $team_id_data . '_' . $tournament_ids)  }}"  >Team
												Info</a></li>
										<li class="active"><a href="{{ url('team_result', $team_id_data . '_' . $tournament_ids)  }}">Results</a></li>
										<li><a href="{{ url('team_schedule', $team_id_data . '_' . $tournament_ids)  }}">Schedule</a></li>
										<li><a href="{{ url('team_batting', $team_id_data . '_' . $tournament_ids)  }}">Batting</a></li>
										<li><a href="{{ url('team_bowling', $team_id_data . '_' . $tournament_ids)  }}">Bowling</a></li>
										<li><a href="{{ url('team_fielding', $team_id_data . '_' . $tournament_ids)  }}">Fielding</a></li>
										<li><a href="{{ url('team_ranking', $team_id_data . '_' . $tournament_ids)  }}">Ranking</a></li>
										</ul>
                                 
								</div>
								<div class="panel-body">
									<div class="tab-content">
						<div class="tab-pane fade " id="team">
											
											<p></p>
											
											Loading ...
											</div>
										<div class="tab-pane fade in active" id="results">
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

<script>
$(document).ready(function() {	
	
	
	  $('.gridView').hide();
      $('.listView').show();
	
	
	$("#dialog-confirm" ).dialog({
	      resizable: false,
	      autoOpen: false,
	      modal: true,
	      buttons: {
	        "Delete": function() {
	        	var matchId = $("#deleteId").val();
	        	var ajaxUrl = '' + matchId;
	        	$.ajax({url:ajaxUrl,
	        		success:function(result){
	        			$("#deleteRow"+matchId).remove();
	        			$('#displyMessage').html("Scorecard Deleted");
	        			$('#displyMessage').dialog("open");
	        			window.location.reload(true);
	        			//window.location.href=window.location.href;
	        			//window.location.reload();
	        	  }});
	          $( this ).dialog( "close" );
	        },
	        Cancel: function() {
	          var matchId = $("#deleteId").val();
	          //$("#deleteButton"+matchId).button( "enable" );
	          $( this ).dialog( "close" );
	        }
	      }
	    });
	
	  $( "#displyMessage" ).dialog({
			autoOpen: false,
		    modal: true
		    });

	$("#dialog-confirm1" ).dialog({
	      resizable: false,
	      autoOpen: false,
	      modal: true,
	      buttons: {
	        "Lock": function() {
	        	var matchId = $("#lockId").val();
	        	var ajaxUrl = '' + matchId;
	        	$.ajax({url:ajaxUrl,success:function(result){
	        			if(result == 'success'){
	        				 $("#lockLink"+matchId).attr('href', "javascript:unLockMatch("+matchId+",\""+ $("#lockMessage").html() + "\");");
	        				 $("#lockLink"+matchId).text('Unlock');
	        				 window.location.href=window.location.href;	        				
	        				// $(".linkAsButton").button();
	        			}else{
	        				alert("We could not process your request please contact support@cricclubs.com");
	        			}
	        			
	        	  }});
	          $( this ).dialog( "close" );
	        },
	        Cancel: function() {
	          var matchId = $("#lockId").val();
	         // $("#lockButton"+matchId).button( "enable" );
	          $( this ).dialog( "close" );
	        }
	      }
	    });

	$("#dialog-confirm2" ).dialog({
	      resizable: false,
	      autoOpen: false,
	      modal: true,
	      buttons: {
	        "UnLock": function() {
	        	var matchId = $("#unLockId").val();
	        	var ajaxUrl = '' + matchId;
	        	$.ajax({url:ajaxUrl,success:function(result){

	        		if(result == 'success'){
        				 $("#lockLink"+matchId).attr("href", "javascript:lockMatch("+matchId+",\""+ $("#unLockMessage").html() + "\");");
        				 $("#lockLink"+matchId).text('Lock');
        				 window.location.href=window.location.href;
        				 //$(".linkAsButton").button();
	       			}else{
	       				alert("We could not process your request please contact support@cricclubs.com");
	       			}
	        			
	        	  }});
	          $( this ).dialog( "close" );
	        },
	        Cancel: function() {
	          var matchId = $("#unLockId").val();
	         // $("#unLockButton"+matchId).button( "enable" );
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
	
});

function openLiveVideoLink(livevideolink){
	enable = 0;
	$(".dialogOpenVideoLink").dialog("option","title","Live Streaming...");
	var videoid = livevideolink.split("/watch?v=");
	$("#playVideo")[0].src="//www.youtube.com/embed/"+videoid[1]+"?autoplay=1";
	$("#dialogOpenVideoLink").dialog("open");
}

function deleteMatch(matchId,name){
	
	$("#deleteMessage").html(name);
	$("#deleteId").val(matchId);
	$("#dialog-confirm" ).dialog("open");
}

function lockMatch(matchId,name){
	
	$("#lockMessage").html(name);
	$("#lockId").val(matchId);
	$("#dialog-confirm1" ).dialog("open");
}

function unLockMatch(matchId,name){
	
	$("#unLockMessage").html(name);
	$("#unLockId").val(matchId);
	$("#dialog-confirm2" ).dialog("open");
}




</script>

<div class="month-all sp">
<div class="month-all listView">
@if($team_resultData->count() > 0)
@foreach($team_resultData as $data)
@if($data['running_inning'] == 3)
       <div class="schedule-all">
           <div class="row team-data" id="deleteRow2414">
               <div class="col-xs-3 col-sm-1 sp mobile-b">
                   <div class="sch-time text-center h-90">
                       <h5><strong>League</strong></h5>
                       <h2>{{date('d', strtotime($data['created_at']))}}</h2>
                       <h5>{{date('M Y', strtotime($data['created_at']))}}</h5>
                   </div>
               </div>
               <div class="col-xs-9 col-sm-4 p-sm-0 mobile-b">
                   <div class="schedule-logo text-center h-90">
                   <ul class="list-inline">
								   @if (isset($total_runs[$data->id][0])) 
					<li class="win"><span>{{ $total_runs[$data->id][0] }}/{{ $total_wicket_fixture[$data->id][0] }}</span> <br> 
					<p>{{ $total_run_fixture[$data->id][0] }}/{{$data['numberofover']}}</p>
		                           </li>
				@else 
				<li class="win"><span>0/0</span> <br> 
					<p>0/0</p>
		                           </li>
				@endif
				<li><a href="">
		                           <img src="https://eoscl.ca/admin/public/Team/{{$data['first_inning_team_id']}}.png" class="img-responsive img-circle" style="width: 70px;height: 70px;"></a></li>
		                           <li><a href="">
		                           <img src="https://eoscl.ca/admin/public/Team/{{$data['second_inning_team_id']}}.png" class="img-responsive img-circle" style="width: 70px;height: 70px;"></a></li>	
				@if (isset($total_runs[$data->id][0])) 
		                          <li class="lose">
		                          		<span>{{ $total_runs[$data->id][1] }}/{{ $total_wicket_fixture[$data->id][1] }}</span> <br> 
		                          			<p>{{ $total_run_fixture[$data->id][0] }}/{{$data['numberofover']}}</p>
				                           </li>
				@else 
				<li class="lose">
		                          		<span>0/0</span> <br> 
		                          			<p>0/0</p>
				                           </li>
				@endif	 
	                      	 </ul>	
                         </div>
               </div>
             <div class="col-xs-12 col-sm-4">
                   <div class="schedule-text">
                   <h4>{{$tournament[$data['tournament_id']]}}</h4>
                       <h3>{{ $header_teams[$data['first_inning_team_id']]}}<span class="v"> v </span>  {{ $header_teams[$data['second_inning_team_id']]}}</h3>
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
                          
                           <a href="{{ url('fullScorecard/' . $data['id']) }}" class="btn btn-sc"><i class="fa fa-calendar-check-o"></i>Scorecard</a>
                       			
								
							</li>
                       </ul>
                   </div>
                   			</div>
           </div>
       </div>
       @endif
    @endforeach
	@else
          <p>Not record Found.</p>
          @endif
        
       </div>
        

               </div>
              
           </div>
       </div>
       </div>
        
      

       </div>
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
<div class="tab-pane fade " id="umpiringSchedule">
											
											Loading ...
											</div>
										<div class="tab-pane fade " id="batting">
											
											Loading ...
											</div>
										<div class="tab-pane fade " id="bowling">
											
											Loading ...
											</div>
										<div class="tab-pane fade " id="fielding">
											
											Loading ...
											</div>
										<div class="tab-pane fade " id="ranking">
											
											Loading ...
											</div>
										 <div class="tab-pane fade " id="stats">
											
											Loading ...
											</div> 
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
        @stop