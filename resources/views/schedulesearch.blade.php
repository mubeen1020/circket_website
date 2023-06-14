@extends('default')
@section('content')
<div class="holder point">
    	<div class="container p-sm-0">
        	<div class="point-table-all sch">
            <div class="series-drop">
            <div class="row">
                	<div class="col-sm-6">
                    	<div class="border-heading">
                            <h5 style=" text-align: left; ">Schedule</h5>
                        </div>
                    </div>
                    <div class="col-sm-6">
                       <table style="width: 100%">
						
						<tbody><tr class="text-right">
						<td style="display: inline-flex;
						" class="hidden-phone icon-fixture">
						<img alt="Download as Excel" id="excel-download" title="Download as Excel" class="excelBtn" style="cursor:pointer; margin:5px !important;" width="32" height="32" src="/utilsv2/images/excel.png">
						<!-- 	<button type="button" id="excel-download" title="Excel Download" class="btn btn-md hidden-phone"  
							style="font-size:18px;"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button> -->	
							<button type="button" id="list-view" title="List View" class="btn btn-md hidden-phone" style="font-size:18px;"><i class="fa fa-list" aria-hidden="true"></i></button>
							<button type="button" id="grid-view" title="Grid View" class="btn btn-md hidden-phone" style="font-size:18px;"><i class="fa fa-th" aria-hidden="true"></i></button>
						</td>
						
						
							<td style="display: inline-block;
						margin-left: 15px;"><div class="addthis_sharing_toolbox hidden-phone" style="height: 24px; text-align: right;"></div>
							<table style="width: 100%; margin-bottom: 10px;text-align: center;">
	<tbody><tr>
		<td><a class="show-phone" href="#" onclick="javascript:mobileFacebookShare();return false;"> <img src="/utilsv2/images/fb_new.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileTwitterShare();return false;"><img src="/utilsv2/images/twi.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileGoogleShare(); return false;"><img src="/utilsv2/images/goo.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileMailShare(); return false;"><img width="40" src="/utilsv2/images/mail.png"></a></td>
		<td><a class="show-phone whatsapp"><img src="/utilsv2/images/whatsapp.png"></a></td>
	</tr>
</tbody></table></td>
						</tr>
					</tbody></table>
                    </div>
              </div>
              <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('schedulesearch_form_submit')}}">
                @csrf
            	<div class="row">
            	 <div class="col-xs-6 col-sm-2">
						<div class="dropdown">
                        <select name="year"  id="year" class="form-control" >
                                 		<option value=""> Select Year(s)</option>
                                         @for ($year = date('Y'); $year >= 2015; $year--)
                                        <option value="{{ $year }}" >{{ $year }}</option>
                                        @endfor
									</select>
						</div>
					</div>
                    <div class="col-xs-6 col-sm-2" title="Change Series">
						<div class="dropdown">
						
                        <select name="tournament"  id="tournament" class="form-control" >
                                 		<option value=""> Select tournament(s)</option>
                                        @foreach($tournament as $tournament_id => $tournament_name)
                                    <option value="{{ $tournament_id }}">{{ $tournament_name }}</option>
                                       @endforeach
									</select>
						</div>
                    </div>
                 	<div class="col-sm-2 col-xs-6" title="Change Team" id="allTeams">
                    <div class="dropdown">
                    <select name="teams" id="teams" class="form-control">
                                <option value="">Select team(s)</option>
                                @foreach($header_teams as $team_id => $team_name)
                                    <option value="{{ $team_id }}">{{ $team_name }}</option>
                                @endforeach
                            </select>
							</div>
                    </div>
                    
                    <div class="col-sm-2 col-xs-6" title="Change internal club">
                    <div class="dropdown">
                    <select name="club" id="club" class="form-control">
                                <option value="">Select Club</option>
                                @foreach($clubs as $index => $club)
                                    <option value ="{{$index}}">{{$club}}</option>
                                    @endforeach
                            </select>
							</div>
                    </div>
                    <div class="col-sm-2 col-xs-6" title="Change Ground">
                    <div class="dropdown">
                                <select name="grounddata" id="grounddata" class="form-control">
                                <option value="">All Grounds</option>
                                @foreach($ground as $index => $ground_item)
                                    <option value ="{{$index}}">{{$ground_item}}</option>
                                    @endforeach
                            </select>
							</div>
                    </div>                    
                   
                </div>

            </div>
            
            <div class="series-drop">
            
            <div class="row">
            	
					<div class="col-sm-2 col-xs-4" title="From Date">
                    <input type="text" name="created_at" autocomplete="off" placeholder="From Date" value="" align="top" class="calendarBox form-control hasDatepicker" id="created_at">
					</div>
					<div class="col-sm-2 col-xs-4" title="To Date">
                    <input type="text"  name="end_at" autocomplete="off" placeholder="To Date" value="" align="top" class="calendarBox form-control hasDatepicker" id="end_at">
					</div>
					<div class="col-sm-2 col-xs-4" title="Search Dates"> 
                    	<button class="btn btn-primary" id="datesSearch">Search Dates</button>
					</div>
					
					<div class="col-sm-2 col-xs-6" style=" float: right; " title="Search Dates">
                    </div>
					
            	</div>
            </div>
            </form>
            <script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/jszip.min.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/pdfmake.min.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/vfs_fonts.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/buttons.html5.min.js"></script>
<script type="text/javascript" src="/utilsv2/js/pdf-excel-plugin/buttons.print.min.js"></script>       
        
     
<style>
.btn-earth{
display: inline-block;
}
.admins-drop{
margin-top: 15px;}
  #schedule-table_length{
    text-align: right;
  }
  #schedule-table_filter{
    text-align: right;
  }
  .list-table tbody tr td a, .list-table tbody tr td{
  line-height: 26px!important;
  font-size: 13px!important;
  }
  .list-table tbody tr td a{
     /* display: table-row!important;*/
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

.display-actions{
	background-color: transparent !important;
}
       
</style>



        <div class="loader" style="display: none;"></div>
          
                
                <div class="month-all listView">
                <div class="row">
          <div class="col-sm-9 col-xs-9">
          <div class="text-left" style="display: inline-block;">
               <span class="btn btn-earth">UPCOMING MATCHES </span>
          </div>
                </div>

@php
    if(count($results) > 0){
@endphp

                         @foreach($results as $data)
						
                       	@if($data['running_inning'] == 0)
           
                </div>
                  <input type="hidden" name="upcoming_selected" value="5973">
                    <input type="hidden" name="removeumpire_selected" value="5973">
                      <div class="schedule-all">
                      <div class="row team-data deleteRow5973" id="deleteRow5973">
                          <div class="col-xs-4 col-sm-1 sp h-90 mobile-b" style="padding-left: 15px;text-align: center;">
                              <div class="sch-time text-center">
                              
                              <h4>Saturday</h4>
                              <h2>{{date('d', strtotime($data['match_startdate']))}}</h2>
                              <h5>{{date('M Y', strtotime($data['match_startdate']))}}</h5>
                              <h5>{{ date('h:i:s a', strtotime($data['match_starttime'])) }}</h5>

                              </div>
                            </div>
                            <div class="col-xs-8 col-sm-3 p-sm-0 mobile-b">
                              <div class="schedule-logo text-center h-90">
                                  <ul class="list-inline" style="color: #fff;">
                                  <li><a href="#">
                                  <img src="https://cricclubs.com/documentsRep/teamLogos/654d8ff3-3e3f-4137-8f4c-13063ce902b1.jpg" class="img-responsive img-circle" style="width: 70px;height: 70px;"></a></li>
                                      
                                  <li><a href="">
                                  <img src="https://cricclubs.com/documentsRep/teamLogos/61443e16-7f18-452a-a807-040ce00e712d.jpg" class="img-responsive img-circle" style="width: 70px;height: 70px;"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-5">
                              <div class="schedule-text">
                              <p style="color: #fff; margin-bottom: 3px;">
                              @if (!empty($data['tournament_id']) && isset($tournament[$data['tournament_id']]))
        {{$tournament[$data['tournament_id']]}}
    @else
       ""
    @endif
                            </p>
                                  <h3><a style="color: inherit;" href="">{{ $header_teams[$data['team_id_a']]}}</a> 
                    <span class="v">v</span>  <a style="color: inherit;" href="">{{ $header_teams[$data['team_id_b']]}}</a> 
                    </h3>
                      <h4>L @  <a style="color: inherit;" href="" target="_new">{{ $ground2[$data['ground_id']] }}</a>
                    
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Umpires:
                  </h4>
                  <p style="color:#fff;font-size:13px"> </p>
                   <p style="color:#fff;font-size:13px"> </p>
                   </div>
                            </div>
                          
                            @endif
       							@endforeach
        
    

 
 @php
}
@endphp
                            <div class="col-xs-12 col-sm-3 ball-score">
                                 <div class="score-share text-center hidden-phone">
                                </div>
                                </div>
                        </div>
                    </div>
                </div>
          
          <!-- Table View for Upcomming Matches Starts -->
          <div class="month-all gridView" style="display: none;">
                <div class="row">
          <div class="col-sm-9 col-xs-9">
          <div class="text-left" style="display: inline-block;">
               <span class="btn btn-earth">UPCOMING MATCHES </span>
          </div>
                </div>
                <div class="col-sm-3 col-xs-3  admins-drop text-right">
                  </div>
                </div>
              
                  <div id="schedule-table1_wrapper" class="dataTables_wrapper no-footer"><table id="schedule-table1" class="table list-table table-hover dataTable no-footer" style="color: #fff!important; background: #423256;" role="grid">
                  <thead>
                    <tr role="row"><th nowrap="nowrap" class="sorting_asc" tabindex="0" aria-controls="schedule-table1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 0px;">#</th><th nowrap="nowrap" class="sorting" tabindex="0" aria-controls="schedule-table1" rowspan="1" colspan="1" aria-label="Match Type: activate to sort column ascending" style="width: 0px;">Match Type</th><th class="sorting" tabindex="0" aria-controls="schedule-table1" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 0px;">Date</th><th class="sorting" tabindex="0" aria-controls="schedule-table1" rowspan="1" colspan="1" aria-label="Time: activate to sort column ascending" style="width: 0px;">Time</th><th class="sorting" tabindex="0" aria-controls="schedule-table1" rowspan="1" colspan="1" aria-label="Team 1 (Home): activate to sort column ascending" style="width: 0px;">Team 1 (Home)</th><th class="sorting" tabindex="0" aria-controls="schedule-table1" rowspan="1" colspan="1" aria-label="Team 2: activate to sort column ascending" style="width: 0px;">Team 2</th><th class="sorting" tabindex="0" aria-controls="schedule-table1" rowspan="1" colspan="1" aria-label="Ground: activate to sort column ascending" style="width: 0px;">Ground</th><th class="sorting" tabindex="0" aria-controls="schedule-table1" rowspan="1" colspan="1" aria-label="Umpire1: activate to sort column ascending" style="width: 0px;">Umpire1</th><th class="sorting" tabindex="0" aria-controls="schedule-table1" rowspan="1" colspan="1" aria-label="Umpire2: activate to sort column ascending" style="width: 0px;">Umpire2</th><th class="sorting" tabindex="0" aria-controls="schedule-table1" rowspan="1" colspan="1" aria-label="Scorecard: activate to sort column ascending" style="width: 0px;">Scorecard</th></tr>
                    </thead>
                  
                    <tbody>
                    @php
    if(count($results) > 0){
@endphp

                         @foreach($results as $key => $data)
						
                       	@if($data['running_inning'] == 0)
                    <tr id="deleteRow5973" role="row" class="odd">
                      
                     <td class="sorting_1">{{$key+1}}</td>

                      <td>L</td>
                      <td>{{date('d M Y', strtotime($data['match_startdate']))}}</td>
                      <td nowrap="nowrap">{{ date('h:i:s a', strtotime($data['match_starttime'])) }}</td>
                      <td>
                        <div style="display: flex;">
                        <a href="#">{{ $header_teams[$data['team_id_a']]}}</a> 
                       <a target="new" href="#"><img alt="Print Roster" title="Print Roster" width="12px" height="12px" src="/utilsv2/images/roster2.png"></a>
                       </div>
                        </td>
                      <td>
                        <div style="display: flex;">
                        <a href="#">{{ $header_teams[$data['team_id_b']]}}</a>
                        <a target="new" href="#"><img alt="Print Roster" title="Print Roster" width="12px" height="12px" src="/utilsv2/images/roster2.png"></a>
                        </div>
                        </td>
                      <td>
                        <a href="#" target="_new">{{ $ground2[$data['ground_id']] }}</a>
                        </td>
                      <td></td>
                      <td></td>
                      <td> </td>

                      </tr>
                      @endif
       							@endforeach
        
    

 
 @php
}
@endphp
                    </tbody></table></div>
              </div>
          
          <!-- Table View for Upcomming Matches Ends -->
          
          
          
          <div class="month-all listView">
          <div class="row">
          <div class="col-sm-9 col-xs-9">
          <div class="button-pool text-left" styel="display: inline-block">
               <span class="btn btn-earth"> PAST MATCHES </span>
          </div>
          </div>

       

                <div class="col-sm-3 col-xs-3 admins-drop text-right">
                                </div>
                                </div>
                                @php
    if(count($results) > 0){
@endphp

                         @foreach($results as $data)
						
                       	@if($data['running_inning'] == 3)
                        <input type="hidden" name="removeumpire_selected" value="5960">
                                    <div class="schedule-all">
                                    <div class="row team-data deleteRow5960" id="deleteRow5960">

                         
                                        <div class="col-xs-4 col-sm-1 sp mobile-b h-90" style="padding-right: 15px;
                                             text-align: center;">
                                                                    <div class="sch-time text-center">
                                                                    <h4>Saturday</h4>
                                                    <h2>{{date('d', strtotime($data['match_startdate']))}}</h2>
                                                    <h5>{{date('M Y', strtotime($data['match_startdate']))}}</h5>
                                                    <h5>{{ date('h:i:s a', strtotime($data['match_starttime'])) }}</h5>
                                                                    </div>
                                                                    </div>
                                                                    <div class="col-xs-8 col-sm-3 p-sm-0 mobile-b">
                                                    <div class="schedule-logo text-center h-90">
                                                        <ul class="list-inline" style="color: #fff;">
                                                        <li><a href="/MississaugaCricketLeague/viewTeam.do?teamId=1341&amp;clubId=2565">
                                                        <img src="https://cricclubs.com/documentsRep/teamLogos/3771b13e-3281-4038-a063-75e2a277f1b0.jpg" class="img-responsive img-circle" style="width: 80px;height: 80px;"></a></li>
                                                                v 
                                                        <li><a href="/MississaugaCricketLeague/viewTeam.do?teamId=1342&amp;clubId=2565">
                                                        <img src="https://cricclubs.com/documentsRep/teamLogos/61443e16-7f18-452a-a807-040ce00e712d.jpg" class="img-responsive img-circle" style="width: 80px;height: 80px;"></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-5">
                                                    <div class="schedule-text">
                                                    <p style="color: #fff; margin-bottom: 3px;">{{$tournament[$data['tournament_id']]}}</p>
                                                        <h3><a style="color: inherit;" href="/MississaugaCricketLeague/viewTeam.do?teamId=1341&amp;clubId=2565">{{ $header_teams[$data['team_id_a']]}}</a> <span class="v"> v </span>  <a style="color: inherit;" href="/MississaugaCricketLeague/viewTeam.do?teamId=1342&amp;clubId=2565">{{ $header_teams[$data['team_id_b']]}}</a> </h3>

                                                            <h4>L @  <a style="color: inherit;" href="/MississaugaCricketLeague/viewGround.do?groundId=8&amp;clubId=2565" target="_new">{{ $ground2[$data['ground_id']] }}</a>
                                            
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Umpires: 
                                        <a style="color: inherit;" href="/MississaugaCricketLeague/viewUmpire.do?umpireUId=1717088&amp;clubId=2565">Sandeep Mehta</a>,<a style="color: inherit;" href="/MississaugaCricketLeague/viewUmpire.do?umpireUId=2224359&amp;clubId=2565">Mcl Umpire</a></h4>
                                        
                                        <p style="color:#fff;font-size:13px"> </p>
                                        <p style="color:#fff;font-size:13px"> </p>
                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-3 ball-score">
                                                        <div class="score-share text-center hidden-phone">
                                                        </div>
                                                        <div class="score-share text-center" style="display: inline-block;">
                                                        <ul class="list-inline">
                                                            <li><a href="{{ url('fullScorecard/' . $data['id']) }}" class="btn btn-sc"><i class="fa fa-calendar-check-o"></i>  Scorecard</a></li>
                                                            </ul>
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
 
           
          <!-- Table view for past matches -->
          
          <div class="month-all gridView" style="display: none;">
          <div class="row">
          <div class="col-sm-9 col-xs-9">
          <div class="button-pool text-left" styel="display: inline-block">
               <span class="btn btn-earth"> PAST MATCHES </span>
          </div>
          </div>
                <div class="col-sm-3 col-xs-3 admins-drop text-right">
                  </div>
                </div>
                
                <div id="schedule-table_wrapper" class="dataTables_wrapper no-footer"><table id="schedule-table" class="table list-table table-hover dataTable no-footer" style="color: #fff!important; background: #423256;" role="grid">
                <thead>
                  <tr role="row"><th nowrap="nowrap" class="sorting_asc" tabindex="0" aria-controls="schedule-table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 0px;">#</th><th nowrap="nowrap" class="sorting" tabindex="0" aria-controls="schedule-table" rowspan="1" colspan="1" aria-label="Match Type: activate to sort column ascending" style="width: 0px;">Match Type</th><th class="sorting" tabindex="0" aria-controls="schedule-table" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 0px;">Date</th><th class="sorting" tabindex="0" aria-controls="schedule-table" rowspan="1" colspan="1" aria-label="Time: activate to sort column ascending" style="width: 0px;">Time</th><th class="sorting" tabindex="0" aria-controls="schedule-table" rowspan="1" colspan="1" aria-label="Team 1 (Home): activate to sort column ascending" style="width: 0px;">Team 1 (Home)</th><th class="sorting" tabindex="0" aria-controls="schedule-table" rowspan="1" colspan="1" aria-label="Team 2: activate to sort column ascending" style="width: 0px;">Team 2</th><th class="sorting" tabindex="0" aria-controls="schedule-table" rowspan="1" colspan="1" aria-label="Ground: activate to sort column ascending" style="width: 0px;">Ground</th><th class="sorting" tabindex="0" aria-controls="schedule-table" rowspan="1" colspan="1" aria-label="Umpire1: activate to sort column ascending" style="width: 0px;">Umpire1</th><th class="sorting" tabindex="0" aria-controls="schedule-table" rowspan="1" colspan="1" aria-label="Umpire2: activate to sort column ascending" style="width: 0px;">Umpire2</th><th class="sorting" tabindex="0" aria-controls="schedule-table" rowspan="1" colspan="1" aria-label="Scorecard: activate to sort column ascending" style="width: 0px;">Scorecard</th></tr>
                  </thead>
                  <tbody>
                  @php
    if(count($results) > 0){
@endphp

                         @foreach($results as $key => $data)
						
                       	@if($data['running_inning'] == 3)
                    <tr id="deleteRow5973" role="row" class="odd">
                      
                     <td class="sorting_1">{{$key+1}}</td>

                      <td>L</td>
                      <td>{{date('d M Y', strtotime($data['match_startdate']))}}</td>
                      <td nowrap="nowrap">{{ date('h:i:s a', strtotime($data['match_starttime'])) }}</td>
                      <td>
                        <div style="display: flex;">
                        <a href="#">{{ $header_teams[$data['team_id_a']]}}</a> 
                       <a target="new" href="#"><img alt="Print Roster" title="Print Roster" width="12px" height="12px" src="/utilsv2/images/roster2.png"></a>
                       </div>
                        </td>
                      <td>
                        <div style="display: flex;">
                        <a href="#">{{ $header_teams[$data['team_id_b']]}}</a>
                        <a target="new" href="#"><img alt="Print Roster" title="Print Roster" width="12px" height="12px" src="/utilsv2/images/roster2.png"></a>
                        </div>
                        </td>
                      <td>
                        <a href="#" target="_new">Danville North</a>
                        </td>
                      <td></td>
                      <td></td>
                      <td> </td>

                      </tr>
                      @endif
       							@endforeach
        
    

 
 @php
}
@endphp</tbody></table></div>
              
            </div>
          
          <!-- Table view for past matches -->
          
          
                
        
        
        
        
        
        
  
<script>
  $('.gridView').hide();
  $('.listView').show();
  $('#list-view').click(function() {
    localStorage.setItem("view", "list");
    gridList();
  })
  $('#grid-view').click(function() {
    localStorage.setItem("view", "grid");
    gridList();
  })
  function gridList() {
    if (localStorage.view == "grid") {
      $('.gridView').show();
      $('.listView').hide();
    } else if (localStorage.view == "list") {
      $('.gridView').hide();
      $('.listView').show();
    }
  }
  gridList();
</script>

<script>

$(document).ready(function() {
  $('#attTable').DataTable({
  paging : false,
  scrollX : true,
  aaSorting : []
});
});

function fnExcelReport()
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('attTable'); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"TeamViewPlayers.xlsx");
    }  
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
}

//export csv
$('#excel-download').click(function() {
  var titles = [];
  var data = [];

  /*
   * Get the table headers, this will be CSV headers
   * The count of headers will be CSV string separator
   */
  $('#attTable th').each(function() {
    titles.push($(this).text());
  });

  /*
   * Get the actual data, this will contain all the data, in 1 array
   */
  $('#attTable td').each(function() {
    var pushdata = $.trim($(this).text());
    data.push(pushdata);
  });
  
  /*
   * Convert our data to CSV string
   */
  var CSVString = prepCSVRow(titles, titles.length, '');
  CSVString = prepCSVRow(data, titles.length, CSVString);

  /*
   * Make CSV downloadable
   */
  var downloadLink = document.createElement("a");
  var blob = new Blob(["\ufeff", CSVString]);
  var url = URL.createObjectURL(blob);
  downloadLink.href = url;
  
	  downloadLink.download = "2023 MCL50 - Super 6.xls";
  
  
 

  /*
   * Actually download CSV
   */
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
});

   /*
* Convert data array to CSV string
* @param arr {Array} - the actual data
* @param columnCount {Number} - the amount to split the data into columns
* @param initial {String} - initial string to append to CSV string
* return {String} - ready CSV string
*/
function prepCSVRow(arr, columnCount, initial) {
  var row = ''; // this will hold data
  var delimeter = ','; // data slice separator, in excel it's `;`, in usual CSv it's `,`
  var newLine = '\r\n'; // newline separator for CSV row

  /*
   * Convert [1,2,3,4] into [[1,2], [3,4]] while count is 2
   * @param _arr {Array} - the actual array to split
   * @param _count {Number} - the amount to split
   * return {Array} - splitted array
   */
  function splitArray(_arr, _count) {
    var splitted = [];
    var result = [];
    _arr.forEach(function(item, idx) {
      if ((idx + 1) % _count === 0) {
        splitted.push(item);
        result.push(splitted);
        splitted = [];
      } else {
        splitted.push(item);
      }
    });
    return result;
  }
  var plainArr = splitArray(arr, columnCount);
  // don't know how to explain this
  // you just have to like follow the code
  // and you understand, it's pretty simple
  // it converts `['a', 'b', 'c']` to `a,b,c` string
  plainArr.forEach(function(arrItem) {
    arrItem.forEach(function(item, idx) {
      row += item + ((idx + 1) === arrItem.length ? '' : delimeter);
    });
    row += newLine;
  });
  return initial + row;
}

//csv ends 


if($('#schedule-table').length >= 1){
     
  /* $('#schedule-table').DataTable(); */
     
     
     $(function () {
         $("#schedule-table").DataTable({"bPaginate": false, "bFilter": false, "bInfo": false });
     });
     
     
   }
if($('#schedule-table1').length >= 1){
  
     /* $('#schedule-table1').DataTable(); */
     
     
     $(function () {
         $("#schedule-table1").DataTable({"bPaginate": false, "bFilter": false, "bInfo": false });
     });
     
     
   }
   

   
   
</script>       
        
        </div>
		</div>
	</div>

@stop