@extends('default')
@section('content')

<div class="holder point">
	<div class="container p-0-sm">
		<div class="point-table-all about-table">
			<div class="series-drop">
            <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('clubviewteams_submit')}}">
                @csrf
				<div class="row">
					<div class="col-sm-6">
						<div class="border-heading">
							<h5>
								<a href="/MississaugaCricketLeague/viewLeague.do?league=118&amp;clubId=2565"><font color="white">EVENT ONTARIO SOFTBALL CIRCKET CLUBS</font></a>
							</h5>
						</div>
					</div>
					<div class="col-sm-6">
					<div class="shareDiv hidden-phone">
						
						<div class="addthis_sharing_toolbox" style="height: 24px; text-align: right;"></div>
					</div>
					</div>
					</div>
					<div class="row">
					
						<div class="col-sm-3">
						<div class="dropdown">
                        <select name="year"  id="year" class="form-control" >
                                 		<option value=""> Select Year(s)</option>
                                         @for ($year = date('Y'); $year >= 2015; $year--)
                                        <option value="{{ $year }}" >{{ $year }}</option>
                                        @endfor
									</select>
						</div>
					</div>
					
					<div class="col-sm-3">
						<div class="dropdown">
                        <select name="tournament"  id="tournament" class="form-control" >
                                 		<option value=""> Select tournament(s)</option>
                                        @foreach($tournament as $tournament_id => $tournament_name)
                                    <option value="{{ $tournament_id }}">{{ $tournament_name }}</option>
                                       @endforeach
									</select>
						</div>
					</div>
					<div class="col-sm-3">
					<button class="btn btn-primary" id="datesSearch">Search Result</button>
					</div>
					<div class="col-sm-4 col-xs-6">
					</div>
					<div class="col-sm-2 col-xs-6" style="text-align: right;">
					</div>
				</div>
              </form>
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
</tbody></table><div class="about-table table-responsive" id="tab1default">
				<table class="table" id="anyid">
					<thead>
						<tr>
							<th>#</th>
							<th nowrap="nowrap">Club Name</th>
							<th class="header">Team Name (Players)</th>
							<th class="header">Captain</th>
							<th class="header">Tournaments</th>			
							<th class="header">Home Ground</th>
							</tr>
					</thead>
					<tbody>
                    @php
    if(count($results) > 0){
@endphp

                         @foreach($results as $key => $data)
						
                       	
						<tr id="row1165">
						<th>{{$key+1}}</th>
							<th nowrap="nowrap"><a href="">{{$data->clubname}}</a></th>
							<th nowrap="nowrap"><table><tbody><tr><td style="padding-right: 5px;"><img src="https://cricclubs.com/documentsRep/teamLogos/68b4bd29-e486-4066-a74f-c5173df3e8ec.jpg" class="left-block img-circle" style="width: 25px;height: 25px;"></td><td><a href="/MississaugaCricketLeague/viewTeam.do?teamId=1165&amp;league=118&amp;clubId=2565">{{$data->name}}</a></td></tr></tbody></table></th>
							<th><a href="">{{$data->fullname}}</a></th>
							<th>
							 <a href="">{{$data->tournamentname}}</a>
                            </th>
							<th>
							 <a href="">Malton - Paul Coffey Arena (Turf &amp; Matting)</a>
                            </th>
							</tr>
                           
       							@endforeach
        
    

								   
 @php
}
@endphp

						</tbody>
				</table>
				</div>
				<br>
				</div>
		</div>
	</div>
</div>

@stop                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               