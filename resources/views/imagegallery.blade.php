@extends('default')
@section('content')
<div class="holder point">
	<div class="container">
		<div class="point-table-all border">
			<div class="series-drop">
				<div class="row">
					<div class="col-sm-11">
						<div class="border-heading">
							<h5>EOSCL AWARDS 
								- Photos
								</h5>

								</div>
					</div>
					</div>
			</div>
		
			<div class="row">
            @foreach($image_gallery as $image)
    @if($image->type == 1)
        <div class="col-sm-3 col-md-3" style="display: inline; position: relative; margin-left: 0%; margin-right: 0%;margin-top: 1%;">
            <div class="small-video-all ash-border">
                <div class="svp">
                    <div class="small-video ash-border">
                        <center class="center-padding">
                            <a href="data:image/png;base64,{{ $image->image }}" data-rel="prettyPhoto[gallery]">
                                <img src="data:image/png;base64,{{ $image->image }}" alt="MCL_awardTeam.jpg" style="height: 150px;width: 200px;">
                            </a>
                        </center>
                    </div>
                </div>
                <div class="small-text" style="word-wrap: break-word;">
                    <center>
                        <p class="descp">EOSCL_awardTeam.jpg</p>
                    </center>
                </div>
            </div>
        </div>
    @endif
@endforeach
<!-- Add this script at the end of your layout file -->
<script>
    $(document).ready(function(){
        $("a[data-rel^='prettyPhoto']").prettyPhoto();
    });
</script>

			
                @foreach($image_gallery as $image)
						@if($image->type == 2)
				<div class="col-sm-3 col-md-3" style="display: inline; position: relative; margin-left: 0%; margin-right: 0%;margin-top: 1%;">
					<div class="small-video-all ash-border">
						<div class="svp">
							<div class="small-video ash-border">
								<center class="center-padding">
								<iframe width="200px" height="150px"
                src="{{$image->video_path}}" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen> </iframe>
								</center>
							</div>
						</div>
						<div class="small-text" style="word-wrap: break-word;">
							<center>
								<p class="descp">{{$image->video_path}}</p></center></div>
					</div>
				</div>
                @endif
				@endforeach
			
				</div>
		</div>
		</div>



</div>


@stop