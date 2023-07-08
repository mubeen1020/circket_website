@extends('default')
@section('content')
<div>
<div class="holder point">
    	<div class="container">
        	<div class="point-table-all border">
            <div class="series-drop">
            	<div class="row">
                	<div class="col-sm-6">
                    	<div class="border-heading">
				           <h5>UMPIRES/COACHES/SCORERS</h5>
							
                        </div>
                    </div>
                    <div class="col-sm-6 hidden-phone">
                    <div class="addthis_sharing_toolbox" style="height: 24px; text-align: right;"></div>
                    </div>
                    <div class="col-sm-12 text-right">
                 
				                       	</div>
                </div>
            </div>

		<div class="complete-list">	
	<div class="panel with-nav-tabs panel-default" style="padding: 0px;">
	         <div class="panel-heading">				
				<ul class="nav nav-tabs">
					<li class="active"><a href="#players" role="tab" data-toggle="tab" onclick="javascript:resizeScroll();">Grid View</a></li>
					<li><a href="#teams" role="tab" data-toggle="tab" onclick="javascript:resizeScroll();">List View</a></li>
				</ul>
			</div>
	<div class="panel-body">
		<div class="tab-content">
		
			<div class="tab-pane fade in about-table" id="teams">
				
				<table class="table" style="margin-top: 15px;"> 
                    <thead> 
                        <tr> 
                            <th>#</th> 
                            <th>Name</th>
                            <th>Certified</th>
                            <th>Role</th>
                            <th>Level</th>
                            <th>Phone</th>
                            <th>Address </th>
                            </tr> 
                    </thead> 
                    <tbody>
                    @foreach($umpire_matchoffcial as $key => $umpire)
                    <tr> 
                        <th align="left">{{$key+1}}</th>
							<th align="left"><a href="#">{{$umpire->name}}</a></th>
							<th align="left">
                            @if($umpire->is_certified == 1)
                            <img alt="Verified" title="Not Verified" src="https://cdn-icons-png.flaticon.com/512/7595/7595571.png" style="width: 30px;height: 30px;margin: 0px;">
                            @elseif($umpire->is_certified == 0)
                            <img alt="Verified" title="Not Verified" src="https://www.mississaugacricketleague.ca/utilsv2/images/question.png" style="width: 22px;
    height: 21px;
    margin-left: 4px;">
                            @endif
                        </th>
							<th>Umpire &amp; Scorer</th>
							<th align="left"> &nbsp;{{$umpire->level}}</th>
							<th align="left"><a href="#">{{$umpire->contact}}</a></th>
							<th align="left"><a href="#">{{$umpire->address}}</a></th>
							</tr>
                            @endforeach
                    </tbody>
                </table>
                </div>
			<div class="tab-pane fade active in" id="players">
				<div class="row">
                    @foreach($umpire_matchoffcial as $umpire)
					<div class="col-sm-3 col-xs-6">
                           
                            <div class="team-player-all">
                                            <div class="team-player-image-all">
                                                <div class="team-player-image">
                                                    <img src="https://cricclubs.com/documentsRep/profilePics/no_image.png" class="img-responsive center-block" style="max-width:200px; max-height:auto;">
                                                </div>
                                            </div>
                                            <div class="team-player-text text-center">
                                                <h4>{{$umpire->name}}</h4>
                                                <h5>Umpire &amp; Scorer</h5>
                                                <a href="#" class="btn btn-team">View Profile  <i class="fa fa-chevron-circle-right"></i></a>
                                            </div>
                                        </div>
                    </div>  
                    @endforeach
             </div>  
             
             </div>
                            </div>
@stop