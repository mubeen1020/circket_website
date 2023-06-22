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
										 @if(!empty($tournamentname))
										@foreach($tournamentname as $tournament_id => $tournament_name)
											<option <?php if(isset($_POST['tournament']) && $_POST['tournament']== $tournament_id){ echo 'selected'; } ?> value="{{$tournament_id}}">{{$tournament_name}}</option>
										@endforeach
										@endif

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
								</th><th class="spa sorting" tabindex="0" aria-controls="tablePlayerRankings" rowspan="1" colspan="1" aria-label="Team Name
									
										
									
								: activate to sort column ascending" style="width: 169px;">Team Name
								</th><th class="spa sorting" tabindex="0" aria-controls="tablePlayerRankings" rowspan="1" colspan="1" aria-label="League Name
									
										
									
								: activate to sort column ascending" style="width: 114px;">League Name
								</th></tr>
						</thead>
						<tbody>
              @foreach($result as $key => $data)
							<tr role="row" class="odd">
								<th class="sorting_1">{{$key+1}}</th>
								<th align="left" title="Aayan Mirza"><i class="fa fa-user"></i> <b><a href="{{ route('playerview', $data->player_id) }}"> {{$player[$data->player_id]}}</a></b></th>
								<th>{{ $match_count[$data->team_id]??0 }} </th>
								<th>{{$data->clubname}}</th>
								<th><a href="{{ url('team-view', $data->team_id . '_' . $data->tournament_id) }}">{{$teams[$data->team_id]}}<a></th>
								
								<th><a href="{{ url('team-view', $data->team_id . '_' . $data->tournament_id) }}">{{$teams[$data->team_id]}}</a></th>
								<th>{{$tournament[$data->tournament_id]??''}}</th>
							</tr>
						@endforeach
						</tbody>
					</table></div>
            </div>
					</div>

			</div>
		</div>
	</div>
</div>
@stop