@extends('default')
@section('content')

<div class="point-table-all">
			<div class="series-drop">
				<div class="row">
					<div class="col-sm-6">
						<div class="border-heading">
							<h5>All Series</h5>
						</div>
					</div>
					<div class="col-sm-6 text-right">
							<div class="shareDiv hidden-phone">
								<!-- Go to www.addthis.com/dashboard to customize your tools -->
								<div class="addthis_sharing_toolbox" style="height: 24px; text-align: right;"></div>
							</div>
						</div>
					</div>
			</div>
			<div class="about-table table-responsive" id="tab1default">

				<table class="table">
				<thead>
					<tr>
						<th> Series Name</th>
						<th>Start Date</th>
						<th>Champion</th>
						<th>Runner-up</th>
						</tr>
				</thead>
				<tbody>

				@foreach($tournament_name as $tname)


				<tr>
					<th><a href="/MississaugaCricketLeague/viewLeague.do?league=132&amp;clubId=2565">{{  $tname->name}}</a></th>
					<th>{{  $tname->tournamentstartdate}}</th>
					<th><table><tbody><tr><td style="padding-right: 5px;">
					</td><td><a href="/MississaugaCricketLeague/viewTeam.do?teamId=0&amp;clubId=2565"></a> </td>
					</tr></tbody></table></th>
					<th><table><tbody><tr><td style="padding-right: 5px;">
					</td><td><a href="/MississaugaCricketLeague/viewTeam.do?teamId=0&amp;clubId=2565"></a> </td>
				    </tr></tbody></table></th>
				    </tr>


@endforeach




		
		
				</tbody>
				</table>
				</div>
		</div>


@stop