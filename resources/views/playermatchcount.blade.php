@extends('default')
@section('content')
<div class="holder point">
	<div class="container">
		<div class="point-table-all border">
			<div class="series-drop">
				<div class="row">
					<div class="col-lg-6">
						<div class="border-heading">
							<h5>
								<a href="/MississaugaCricketLeague/viewLeague.do?league=132&amp;clubId=2565"><font color="white">2023 MCL50 - Super 6</font></a>
								</h5>
						</div>
					</div>
					<div class="col-sm-6 hidden-phone">
					
							<div class="addthis_sharing_toolbox" style="height: 24px; text-align: right;"></div>
											</div>
											</div>
				<div class="row">
				<form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('playermatchcount_submit')}}">
                @csrf
					<div class="col-lg-3">
						<div class="form-in sel">
							<div class="dropdown">
							<select name="tournament"  id="tournament" class="form-control" >
                                 		<option value=""> Select tournament(s)</option>
                                        @foreach($tournament as $tournament_id => $tournament_name)
                                    <option value="{{ $tournament_id }}">{{ $tournament_name }}</option>
                                       @endforeach
									</select>
							</div>
							
						</div>
					</div>
					<div class="col-lg-3">
                    	<button class="btn btn-primary" style="margin-top:3px" id="datesSearch">Search Result</button>
					</div>
                 </form>
			  </div>
				
							
				
			<!--  Record Table Start from Here -->
				<div class="about-table table-responsive" id="tab1default">
					<div id="tablePlayerRankings_wrapper" class="dataTables_wrapper no-footer"><table class="table btn-earth list-table table-hover dataTable no-footer" id="tablePlayerRankings" role="grid">
						<thead>
							<tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="tablePlayerRankings" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 19px;">#</th><th class="width sorting" tabindex="0" aria-controls="tablePlayerRankings" rowspan="1" colspan="1" aria-label="Player
									
										
									
								: activate to sort column ascending" style="width: 185px;">Player
								</th><th class="spa sorting" tabindex="0" aria-controls="tablePlayerRankings" rowspan="1" colspan="1" aria-label="Count
									
										
									
								: activate to sort column ascending" style="width: 34px;">Count
								</th><th class="spa sorting" tabindex="0" aria-controls="tablePlayerRankings" rowspan="1" colspan="1" aria-label="Club Name
									
										
									
								: activate to sort column ascending" style="width: 215px;">Club Name
								</th><th class="spa sorting" tabindex="0" aria-controls="tablePlayerRankings" rowspan="1" colspan="1" aria-label="Home Team
									
										
									
								: activate to sort column ascending" style="width: 169px;">Home Team
								</th><th class="spa sorting" tabindex="0" aria-controls="tablePlayerRankings" rowspan="1" colspan="1" aria-label="Home Division
									
										
									
								: activate to sort column ascending" style="width: 115px;">Home Division
								</th><th class="spa sorting" tabindex="0" aria-controls="tablePlayerRankings" rowspan="1" colspan="1" aria-label="Team Name
									
										
									
								: activate to sort column ascending" style="width: 169px;">Team Name
								</th><th class="spa sorting" tabindex="0" aria-controls="tablePlayerRankings" rowspan="1" colspan="1" aria-label="League Name
									
										
									
								: activate to sort column ascending" style="width: 114px;">League Name
								</th></tr>
						</thead>
						<tbody>

							<tr role="row" class="odd">
								<th class="sorting_1">1</th>
								<th align="left" title="Aayan Mirza"><i class="fa fa-user"></i> <b><a href="viewPlayer.do?playerId=1475892&amp;clubId=2565"> Aayan Mirza</a></b></th>
								<th>1</th>
								<th>Mississauga Qalandars Cricket Club</th>
								<th>Mississauga Qalandars</th>
								<th>2023 MCL50 - Super 6</th>
								<th>Mississauga Qalandars</th>
								<th>2023 MCL50 - Super 6</th>
							</tr></tbody>
					</table></div>
            </div>
					</div>

			</div>
		</div>
	</div>
</div>
@stop