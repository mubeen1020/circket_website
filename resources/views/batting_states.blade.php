@extends('default')
@section('content')

<div class="sta-main" style="background: white !important; overflow:auto; padding-bottom:200px;overflow-x:hidden;overflow-y:hidden;">

<div style="display: none;">
<label id="lblhide"></label>

</div>

	<div class="sta-sidemenu" style="top: 13px;">
		<h4>Batting stats</h4>
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">Click here for More <i class="fa fa-sort-desc" aria-hidden="true" style=" float: right; margin-right: 2%;"></i></button>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul>
				<li class="active" data-menu="1"><a href="#">Most Runs</a></li>
				<li class="" data-menu="6"><a href="#">Highest Scores</a></li>
				<li class="" data-menu="8"><a href="#">Best Batting Average</a></li>
				<li class="" data-menu="7"><a href="#">Best Batting StrikeRate</a></li>
				<li class="" data-menu="5"><a href="#"> Most Centuries</a></li>
				<li class="" data-menu="4"><a href="#">Most Fifties</a></li>
				<li class="" data-menu="3"><a href="#">Most Sixes</a></li>
				<li class="" data-menu="2"><a href="#">Most Fours</a></li>
			</ul>
		</div>
		</div>

	<div class="sta-content">
		<section class="sta-search-filter">
			<div class="row">
			
			<div class="col-sm-2 col-xs-6">
						<div class="form-group">
						<div class="custom-select">
							<select class="form-control" id="yearDropDown">
							<option>	2023</option>
							
							
								<option value="">All Years</option>
								<option value="">2022</option>
								<option value="">2021</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?year=2020&amp;allSeries=true&amp;league=All&amp;clubId=2565">2020</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?year=2019&amp;allSeries=true&amp;league=All&amp;clubId=2565">2019</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?year=2018&amp;allSeries=true&amp;league=All&amp;clubId=2565">2018</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?year=2017&amp;allSeries=true&amp;league=All&amp;clubId=2565">2017</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?year=2016&amp;allSeries=true&amp;league=All&amp;clubId=2565">2016</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?year=2015&amp;allSeries=true&amp;league=All&amp;clubId=2565">2015</option>
								</select>
						</div>
						</div>
					</div>
			
			<div class="col-sm-2 col-xs-6">
					<div class="form-group">
						<div class="custom-select">
							<select class="form-control" id="seriesdropdown">
							<option value="/MississaugaCricketLeague/allBattingRecords.do?league=all&amp;year=2023&amp;clubId=2565">Career - All Series</option>
								<option value="/MississaugaCricketLeague/battingRecords.do?league=132&amp;year=2023&amp;clubId=2565">2023 MCL50 - Super 6</option>
								<option value="/MississaugaCricketLeague/battingRecords.do?league=131&amp;year=2023&amp;clubId=2565">2023 MCL100 - Super 8</option>
								<option value="/MississaugaCricketLeague/battingRecords.do?league=117&amp;year=2023&amp;clubId=2565">2023 MCL T25</option>
								<option value="/MississaugaCricketLeague/battingRecords.do?league=125&amp;year=2023&amp;clubId=2565">2023 MCL T20</option>
								</select>
						</div>
					</div>
				</div>
			<div class="col-sm-2 col-xs-6">
					<div class="form-group">
						<div class="custom-select">
							<select class="form-control" id="matchdropdown">
								<option>Matches</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=all&amp;internalClubId=null&amp;year=2023&amp;clubId=2565">All Matches</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=all&amp;seriesType=Test&amp;internalClubId=null&amp;year=2023&amp;clubId=2565">Test</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=all&amp;seriesType=One Day&amp;internalClubId=null&amp;year=2023&amp;clubId=2565">1 Day</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=all&amp;seriesType=Twenty20&amp;internalClubId=null&amp;year=2023&amp;clubId=2565">Twenty20</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=all&amp;seriesType=Ten10&amp;internalClubId=null&amp;year=2023&amp;clubId=2565">Ten10</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=all&amp;seriesType=2X&amp;internalClubId=null&amp;year=2023&amp;clubId=2565">2X</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=all&amp;seriesType=Youth&amp;internalClubId=null&amp;year=2023&amp;clubId=2565">Youth</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=all&amp;seriesType=Women&amp;internalClubId=null&amp;year=2023&amp;clubId=2565">Women</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=all&amp;seriesType=Other&amp;internalClubId=null&amp;year=2023&amp;clubId=2565">Other</option>
								</select>
						</div>
					</div>
				</div>
				
				<div class="col-sm-2 col-xs-6">
					<div class="form-group">
						<div class="custom-select">
							<select class="form-control" id="matchtypedropdown">
								<option>Match Type</option>
								
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=All&amp;year=2023&amp;clubId=2565">All</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=All&amp;matchType=l&amp;year=2023&amp;clubId=2565">League</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=All&amp;matchType=sl&amp;year=2023&amp;clubId=2565">Super League</option>
								<option value="/MississaugaCricketLeague/allBattingRecords.do?league=All&amp;matchType=po&amp;year=2023&amp;clubId=2565">Playoff</option>
								</select>
						</div>
					</div>
				</div>
				</div>
		</section>
		<div id="reloaddiv">
		<div class="sta-tables">		
			<div class="flex-top">
				<div class="filter-title">Most Runs</div>
				<div class="text-right">
				<img alt="Download as PDF" title="Download as PDF" class="pdfBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/pdf.png">&nbsp;
				<img alt="Download as CSV" title="Download as CSV" class="csvBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/csvicon.png">&nbsp;
				<img alt="Download as Excel" title="Download as Excel" class="excelBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/excel.png">	
				<img alt="Print" title="Print" class="printBtn" style="cursor:pointer;" width="32" height="32" src="/utilsv2/images/print.png">&nbsp;
				<!-- <div class="btn-group btn-group-sm" role="group">
						<button type="btn" class="btn btn-primary pdfBtn">PDF</button>
						<button type="btn" class="btn btn-primary csvBtn">CSV</button>
						<button type="btn" class="btn btn-primary excelBtn">Excel</button>
						<button type="btn" class="btn btn-primary printBtn">Print</button>
					</div> -->
				</div>
			</div>

			<div class="table-responsive about-table">
				
					<div id="webrecordtable_wrapper" class="dataTables_wrapper no-footer"><div class="dt-buttons">          <button class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="webrecordtable" type="button"><span>Copy</span></button> <button class="dt-button buttons-csv buttons-html5" tabindex="0" aria-controls="webrecordtable" type="button"><span>CSV</span></button> <button class="dt-button buttons-excel buttons-html5" tabindex="0" aria-controls="webrecordtable" type="button"><span>Excel</span></button> <button class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="webrecordtable" type="button"><span>PDF</span></button> <button class="dt-button buttons-print" tabindex="0" aria-controls="webrecordtable" type="button"><span>Print</span></button> </div><div id="webrecordtable_filter" class="dataTables_filter"><label>Search:<input type="search" class="" placeholder="" aria-controls="webrecordtable"></label></div><table class="table table-striped table-active2 playersData sortable dataTable no-footer" id="webrecordtable" role="grid" aria-describedby="webrecordtable_info">
						<thead>
							<tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 0);return false;">#<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th align="left" style="width: 79px; text-align: left !important;" class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="Player: activate to sort column ascending"><a href="#" class="sortheader" onclick="ts_resortTable(this, 1);return false;">Player<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th style="text-align: left !important; width: 71px;" class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="Team: activate to sort column ascending"><a href="#" class="sortheader" onclick="ts_resortTable(this, 2);return false;">Team<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="Mat: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Mat<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="Inns: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 4);return false;">Inns<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="NO: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 5);return false;">NO<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="Runs: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 6);return false;">Runs<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="4's: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 7);return false;">4's<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="6's: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 8);return false;">6's<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="50's: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 9);return false;">50's<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="100's: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 10);return false;">100's<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="HS: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 11);return false;">HS<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="SR: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 12);return false;">SR<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th class="sorting" tabindex="0" aria-controls="webrecordtable" rowspan="1" colspan="1" aria-label="Avg: activate to sort column ascending" style="width: 71px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 13);return false;">Avg<span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th></tr>
						</thead>
						<tbody>
				
							<tr role="row" class="even">
								<td class="sorting_1">1</td>
								<td align="left" title="Rajwant Singh" style="text-align: left;width: 90px;">
									<div>
										<div class="player-img" style="background-image: url('pic.jpg');"></div>
										<a href="viewPlayer.do?playerId=1375981&amp;clubId=2565"> Rajwant Singh</a><br></div>
								</td>
						<td style="text-align: left;font-size: smaller;">Kitchener Bulls</td>
								<td>10</td>
								<td>10</td>
								<td>2</td>
								<td>327</td>
								<td>3</td>
								<td>28</td>
								<td>3</td>
								<td>0</td>
								<td>74</td>
								<td>151.39</td>
								<td>40.88</td>
							</tr></tbody>
					</table><div class="dataTables_info" id="webrecordtable_info" role="status" aria-live="polite">Showing 1 to 200 of 200 entries</div><div class="dataTables_paginate paging_simple_numbers" id="webrecordtable_paginate"><a class="paginate_button previous disabled" aria-controls="webrecordtable" data-dt-idx="0" tabindex="-1" id="webrecordtable_previous">Previous</a><span><a class="paginate_button current" aria-controls="webrecordtable" data-dt-idx="1" tabindex="0">1</a></span><a class="paginate_button next disabled" aria-controls="webrecordtable" data-dt-idx="2" tabindex="-1" id="webrecordtable_next">Next</a></div></div>
				
			</div>
		</div>
</div>

	</div>
</div>
@stop