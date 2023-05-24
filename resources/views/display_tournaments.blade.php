@extends('default')
@section('content')
<div class="holder point">
    	<div class="container p-0-sm">
        	<div class="point-table-all border">
            <div class="series-drop">
            	<div class="row">
                	<div class="col-sm-12">
                    	<div class="border-heading">
                            <h5>{{  $select_tournament_name[0]->name}}</h5>
                        </div>
                    </div>
		                    </div>
		                    </div>
             <div class="series-deatails">
            	<h4>Series Details:</h4>
               <div class="row">
               <div class="col-sm-4">
                <ul class="list-inline">
                	<li class="width1"><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">Start Date</a></li>
                    <li class="width2">:</li>
                    <li><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">{{  $select_tournament_name[0]->tournamentstartdate}}</a></li>
                </ul>
                
                <ul class="list-inline">
                	<li class="width1"><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">End Date</a></li>
                    <li class="width2">:</li>
                    <li><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">{{  $select_tournament_name[0]->tournamentenddate}}</a></li>
                </ul>
                <ul class="list-inline">
                	<li class="width1"><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">Series Type</a></li>
                    <li class="width2">:</li>
                    <li><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">Ten10</a></li>
                </ul>
                <ul class="list-inline">
                	<li class="width1"><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">Max Overs</a></li>
                    <li class="width2">:</li>
                    <li><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">10</a></li>
                </ul>
              
                 </div>
               <div class="col-sm-4">
               
               
                <ul class="list-inline">
                	<li class="width1"><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">Description</a></li>
                    <li class="width2">:</li>
                    <li><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">{{  $select_tournament_name[0]->description}}</a></li>
                </ul>
                <ul class="list-inline">
                	<li class="width1"><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">Season</a></li>
                    <li class="width2">:</li>
                    <li><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">{{ $season[0]->season_name }}</a></li>
                </ul>
                <ul class="list-inline">
                	<li class="width1"><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">Winner</a></li>
                    <li class="width2">:</li>
                    <li><a href="https://www.mississaugacricketleague.ca/MississaugaCricketLeague/viewTeam.do?teamId=0&amp;clubId=2565"></a></li>
                </ul>
                 <ul class="list-inline">
                	<li class="width1"><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#">More Info</a></li>
                    <li class="width2">:</li>
                    <li><a href="viewLeague.do%3Fleague=116&amp;clubId=2565.html#"></a></li>
                </ul>
                </div>
               </div>
            </div>
            
            	<div class="series-deatails">
	            <h4 style="margin-top: 0px;">Quick Links :</h4>
	            </div>
	            <div class="row">
	               <div class="col-sm-4">
						<ul class="series-d">
							<li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="https://www.mississaugacricketleague.ca/MississaugaCricketLeague/viewTeams.do?league=116&amp;clubId=2565">Teams</a></li>
						
							<li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="https://www.mississaugacricketleague.ca/MississaugaCricketLeague/viewPointsTable.do?league=116&amp;clubId=2565">Points Table</a></li>
					
							<li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="https://www.mississaugacricketleague.ca/MississaugaCricketLeague/battingRecords.do?league=116&amp;clubId=2565">Batting Records</a></li>
						</ul>
						</div>
						<div class="col-sm-4">
						<ul class="series-d">
							<li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="https://www.mississaugacricketleague.ca/MississaugaCricketLeague/bowlingRecords.do?league=116&amp;clubId=2565">Bowling Records</a></li>
						
							<li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="https://www.mississaugacricketleague.ca/MississaugaCricketLeague/fieldingRecords.do?league=116&amp;clubId=2565">Fielding Records</a></li>
							
						 <li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="https://www.mississaugacricketleague.ca/MississaugaCricketLeague/playerRankings.do?league=116&amp;clubId=2565">Player Rankings</a></li>
						 </ul>
					</div>
	            </div>
			</div>
        </div>
    </div>
@stop