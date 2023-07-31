@extends('default')
@section('content')
<div class="holder point">
	<div class="container">
		<div class="point-table-all border">
			<div class="series-drop">
				<div class="row">
					<div class="col-sm-11">
						<div class="border-heading">
							<h5>EOSCL SEASON 
								- SPONSORS
								</h5>

								</div>
					</div>
					</div>
			</div>
		
			<div class="row">
            @foreach($seasonsponser as $image)
						
				<div class="col-sm-4 col-md-4" style="display: inline; position: relative; margin-left: 0%; margin-right: 0%;margin-top: 1%;">
					<div class="small-video-all ash-border">
						<div class="svp">
                       
							<div class="small-video ash-border">
								<center class="center-padding">
								<a href="{{ $image->website }}" target="blank" data-rel="prettyPhoto"><img src="data:image/png;base64,{{ $image->image }}" alt="EOSCL_awardTeam.jpg" style="height: 150px;width: 200px;"></a>
								</center>
							</div>
						</div>
						<div class="small-text" style="word-wrap: break-word;">
							<center>
								<p class="descp">{{ $image->website }}</p></center></div>
					</div>
				</div>
								@endforeach
			
              
			
				</div>
		</div>
		</div>



</div>


@stop