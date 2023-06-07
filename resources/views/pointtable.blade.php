@extends('default')
@section('content')

<div class="holder point">
    	<div class="container p-0-sm">
        	<div class="point-table-all">
            <div class="series-drop">
            	<div class="row">
            	<div class="col-sm-6">
                    	<div class="border-heading">
                            <h5 style="text-align: left;"><a href="/MississaugaCricketLeague/viewLeague.do?league=132&amp;clubId=2565"><font color="white">Point Table</font></a></h5>
                        </div>
                    </div>
                     <div class="exportOptions-panel" style="float:right ;position: relative; left: -20px;">
                     &nbsp;
					
                     
                   </div>
                   </div>
                <div class="row"> 
				<form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('pointtable_submit')}}">
                	<div class="col-lg-3 col-sm-6">
					
                @csrf
						<div class="dropdown">
						<select name="year"  id="year" class="form-control" >
								<option value="">All Years</option>
								@for ($year = date('Y'); $year >= 2015; $year--)
                                        <option value="{{ $year }}" >{{ $year }}</option>
                                        @endfor
								</select>
						</div>
					</div>
                    <div class="col-lg-3 col-sm-6 ">
                    	<div class="dropdown">
						<select name="tournament"  id="tournament" class="form-control" >
							<option value="">Career - All Series</option>
							@foreach($tournamentdata as $tournament_id => $tournament_name)
                                    <option value="{{ $tournament_id }}">{{ $tournament_name }}</option>
                                       @endforeach
								</select>
								
								</div>
						

                    </div>

					<div class="col-sm-2 col-xs-6">
					<div class="form-group">
						<div >
							
						<button class="btn btn-primary" >Search</button>
						</div>
					</div>
				</div>
					</form>
					<div class="col-lg-3 col-sm-6">
</div>
<div class="col-lg-3 col-sm-6">
    <button style="color: black; font-size: 20px; float:right;" class="fa fa-info-circle" aria-hidden="true" onclick="showPointsTableInfo('pointsTable_popup')" title="Points Table Calculation"></button>
</div>

<div class="modal fade common-modal-cls" id="pointsTable_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title text-center">Points Table Calculation</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-lg-5">
                <p><b>PTS - </b>(Number of Wins x Win Points) + (Number of Ties x Tie Points) + (Number of NR x Abandon Points) + Bonus Points </p>&nbsp;
                <br>
                <p><b>WIN % - </b>[(Number of Wins x Win Points) + (Number of Ties x Tie Points) ]/[(Total Number of Matches - Abandoned Matches) x Win points]</p>
                <br>
                <p><b>NET RR - </b>[(FOR - AGAINST)]  </p>&nbsp;
                <br>
                <p><b>FOR  - </b>[(Number of Total runs Scored) ]/[(Number of Total Balls Faced)]</p>
                <br>
                <p><b>AGAINST  - </b>[(Number of Total runs Conceded) ]/[(Number of Total Balls Bowled)]</p>
                <br>
            </div>
        </div>
    </div>
</div>

<script>
    function showPointsTableInfo(modalId) {
        $('#' + modalId).modal('show');
    }
</script>

				</div>
                </div>
              <div class="button-pool text-left" style="margin-top: 0px!important;">
                <table style="width: 100%; margin-bottom: 10px;text-align: center;">
	<tbody><tr>
		<td><a class="show-phone" href="#" onclick="javascript:mobileFacebookShare();return false;"> <img src="/utilsv2/images/fb_new.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileTwitterShare();return false;"><img src="/utilsv2/images/twi.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileGoogleShare(); return false;"><img src="/utilsv2/images/goo.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileMailShare(); return false;"><img width="40" src="/utilsv2/images/mail.png"></a></td>
		<td><a class="show-phone whatsapp"><img src="/utilsv2/images/whatsapp.png"></a></td>
	</tr>
</tbody></table></div>
               <!-- Combined Loop Starts -->
             	<!-- Combined Loop Ends -->  

<!-- Groups Loop Starts -->             
             <div class="about-table table-responsive">

                <table id="point-table" class="table btn-earth list-table table-hover" style="margin-top: 0px;"> 
					<thead>
						<tr>
							<th>#</th>
							<th style="text-align: left !important;">TEAM</th>
							<th>MAT</th>
							<th>WON</th>
							<th>LOST</th>
							<th>TIE</th>
							<th>PTS</th>
							<th>NET RR</th>
							
							</tr>
					</thead>
					<tbody>
						
						<tr id="matchesBySeries1342_132_2565" style="display: none;">
							<td style="text-align: center !important; display: none;" id="matchesBySeriesData1342_132_2565" colspan="15"><style>


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
	
	
	 $('.gridView').show();
     $('.listView').hide();
	
	
	$("#dialog-confirm" ).dialog({
	      resizable: false,
	      autoOpen: false,
	      modal: true,
	      buttons: {
	        "Delete": function() {
	        	var matchId = $("#deleteId").val();
	        	var ajaxUrl = '/MississaugaCricketLeague/deleteMatch.do?clubId=2565&matchId=' + matchId;
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
	        	var ajaxUrl = '/MississaugaCricketLeague/lockMatch.do?clubId=2565&matchId=' + matchId;
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
	        	var ajaxUrl = '/MississaugaCricketLeague/unLockMatch.do?clubId=2565&matchId=' + matchId;
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
<div class="month-all listView" style="display: none;">
       <div class="schedule-all">
           <div class="row team-data" id="deleteRow3465">
               <div class="col-xs-3 col-sm-1 sp mobile-b">
                   <div class="sch-time text-center h-90">
                       <h5><strong>League</strong></h5>
                       <h2>27</h2>
                       <h5>May 2023</h5>
                   </div>
               </div>
               <div class="col-xs-9 col-sm-4 p-sm-0 mobile-b">
                   <div class="schedule-logo text-center h-90">
                   <ul class="list-inline">
		                           <li class="lose"><span>216/10</span> <br> 
		                           <p>48.3/50</p>
		                           </li>
		                           <li><a href="/MississaugaCricketLeague/viewTeam.do?teamId=1342&amp;clubId=2565">
		                           <img src="https://cricclubs.com/documentsRep/teamLogos/61443e16-7f18-452a-a807-040ce00e712d.jpg" class="img-responsive img-circle" style="width: 70px;height: 70px;"></a></li>
		                           <li><a href="/MississaugaCricketLeague/viewTeam.do?teamId=1343&amp;clubId=2565">
		                           <img src="https://cricclubs.com/documentsRep/teamLogos/e9fdac3c-9ffa-46de-817c-7457907efff1.jpg" class="img-responsive img-circle" style="width: 70px;height: 70px;"></a></li>
		                          <li class="win">
		                          		<span>217/9</span> <br> 
		                          			<p>48.3/50</p>
				                           </li>
	                      	 </ul>	
                         </div>
               </div>
             <div class="col-xs-12 col-sm-4">
                   <div class="schedule-text">
                   <h4>2023 MCL50 - Super 6</h4>
                       <h3>Royal Panthers Toronto<span class="v"> v </span>  Mississauga Dolphins</h3>
                       <h4>Mississauga Dolphins won by 1 Wkt(s)</h4>
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
                          
                           <a href="/MississaugaCricketLeague/viewScorecard.do?matchId=3465&amp;clubId=2565" class="btn btn-sc"><i class="fa fa-calendar-check-o"></i>Scorecard</a>
                       			
								
							</li>
                       </ul>
                   </div>
                   			</div>
           </div>
       </div>
       </div>
        
       <div class="month-all listView" style="display: none;">
       <div class="schedule-all">
           <div class="row team-data" id="deleteRow3375">
               <div class="col-xs-3 col-sm-1 sp mobile-b">
                   <div class="sch-time text-center h-90">
                       <h5><strong>League</strong></h5>
                       <h2>14</h2>
                       <h5>May 2023</h5>
                   </div>
               </div>
               <div class="col-xs-9 col-sm-4 p-sm-0 mobile-b">
                   <div class="schedule-logo text-center h-90">
                   <ul class="list-inline">
		                           <li class="win"><span>361/5</span> <br> 
		                           <p>50.0/50</p>
		                           </li>
		                           <li><a href="/MississaugaCricketLeague/viewTeam.do?teamId=1342&amp;clubId=2565">
		                           <img src="https://cricclubs.com/documentsRep/teamLogos/61443e16-7f18-452a-a807-040ce00e712d.jpg" class="img-responsive img-circle" style="width: 70px;height: 70px;"></a></li>
		                           <li><a href="/MississaugaCricketLeague/viewTeam.do?teamId=1341&amp;clubId=2565">
		                           <img src="https://cricclubs.com/documentsRep/teamLogos/3771b13e-3281-4038-a063-75e2a277f1b0.jpg" class="img-responsive img-circle" style="width: 70px;height: 70px;"></a></li>
		                          <li class="lose">
		                          		<span>126/10</span> <br> 
		                          			<p>32.1/50</p>
				                           </li>
	                      	 </ul>	
                         </div>
               </div>
             <div class="col-xs-12 col-sm-4">
                   <div class="schedule-text">
                   <h4>2023 MCL50 - Super 6</h4>
                       <h3>Royal Panthers Toronto<span class="v"> v </span>  RC 99</h3>
                       <h4>Royal Panthers Toronto won by 235 Run(s)</h4>
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
                          
                           <a href="/MississaugaCricketLeague/viewScorecard.do?matchId=3375&amp;clubId=2565" class="btn btn-sc"><i class="fa fa-calendar-check-o"></i>Scorecard</a>
                       			
								
							</li>
                       </ul>
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

</script></td>
						</tr>
						
						
						@foreach($result as $key => $item)
						<tr>
						<th>{{$key+1}}</th>
							<th style="text-align: left !important;"><img src="https://cricclubs.com/documentsRep/teamLogos/74a6a0bc-b4f2-4b75-aa83-b4046766d03f.jpg" class="left-block img-circle" style="width: 25px;height: 25px;">
							<a href="#">{{$item['team_name']}}</a></th>
							<th>{{$item['total_matches']}}</th>
							<th>{{$item['wins']}}</th>
							<th>{{$item['losses']}}</th>
							<th>{{$item['draws']}}</th>
							<th>{{$item['teambonusPoints']}}</th>
							<th>{{ number_format($item['net_rr'], 3) }}</th>

							</tr>
						@endforeach
						</tbody>
				</table>
				</div>
				<br>
				<!-- Groups Loop Ends -->             
<!-- Super Teams Loop -->             
				<!-- Super Teams Loop Ends -->				
				<br>
            </div>
            </div>
            
            </div>
@stop