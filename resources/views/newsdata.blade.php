@extends('default')
@section('content')
<div class="holder point">
    	<div class="container">
        	<div class="point-table-all">
            <div class="series-drop">
            	<div class="row">
                	<div class="col-sm-2">
                    	<div class="border-heading">
                            <h5>News</h5>
                        </div>
                    </div>
                </div>
            </div>
			@foreach($image_slider as $index => $image)
			@if($image->type == 2 )
            <div class="news-post-row">
            	<div class="row">
				
                	<div class="col-sm-3">
                    	<div class="post-image">
                    	
                    	
							<iframe width="100%" height="200" src="{{$image->video_path}}"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""> </iframe>
							</div>
						</div>
						<div class="col-sm-9">
                    	<div class="post-text">							
							<h5>
							 <a href="#">
							 {{$image->title}}</a>
							</h5>				
							
							<h6><i class="fa fa-calendar"></i> {{date('d M Y', strtotime($image['created_at']))}}</h6>
                            <p>{{$image->description}}</p>
                           
							</div>
						</div>
                </div>
			
				</div>
				@elseif($image->type == 1)
				<div class="news-post-row">
            	<div class="row">
                	<div class="col-sm-3">
                    	<div class="post-image">
                
							<a href="#">
								<div class="news-img" style="background-image:url(data:image/png;base64,{{ $image->image }})"></div>
							</a>
							</div>
                    </div>
                    <div class="col-sm-9">
                    	<div class="post-text">							
							<h5>
							 <a href="#">
							 {{$image->title}}</a>
							</h5>				
							
							<h6><i class="fa fa-calendar"></i> {{date('d M Y', strtotime($image['created_at']))}}</h6>
                            <p>{{$image->description}}</p>
                           
							</div>
						</div>
                </div> 
			</div>
			@endif
@endforeach
                </div>
            </div>
		
    </div>
	




@stop