@extends('default')
@section('content')
<div class="holder">
	<div class="container">
		<div class="row">
			<div class="col-sm-7 p-sm-0">
				<div id="newsDiv">
				<div class="banner-slider-list"
						style="background-color: #fff; padding: 15px">
						<div class="banner-content" style="height:inherit !important">
							<div id="carousel-banner" class="carousel slide" style="heigth:inherit"
								data-ride="carousel">
								<div class="carousel-inner">
								@foreach($image_slider as $index => $image)
    @if($image->type == 2 )
        <div class="item {{ $index === 0 ? 'active' : '' }}">
            <iframe width="100%" height="347"
                src="{{$image->video_path}}" frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen> </iframe>
            <div class="carousel-content ">
                <div class="container">
                    <div class="carousel-content-panels">
                        <h3>{{$image->title}}</h3>
                    </div>
                    <div class="carousel-content-panel content-sp" style="width: 100%">
                        <p>{{$image->description}}
                            <br>
                            <br>
                            <br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
  
		@elseif($image->type == 1)
        <div class="item {{ $index === 0 ? 'active' : '' }}">
            <a href="#">
                <img src="data:image/png;base64,{{ $image->image }}" alt="{{ $image->title }}" width="100%" class="box">
            </a>
            <div class="carousel-content ">
                <div class="container">
                    <div class="carousel-content-panels">
                        <h3>{{$image->title}}</h3>
                    </div>
                    <div class="carousel-content-panel content-sp" style="width: 100%">
                        <p>{{$image->description}}
                            <br>
                            <br>
                            <br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
   
									

									</div>
								<div class="carousel-nav">
									<ol class="carousel-indicators">
										<li data-target="#carousel-banner" data-slide-to="0"
											class="active"></li>
										<li data-target="#carousel-banner" data-slide-to="1"
											class=""></li>
											<li data-target="#carousel-banner" data-slide-to="1"
											class=""></li>
										</ol>
								</div>
								<div class="carousel-nav">
									<a class="carousel-nav-prev" href="index.html#carousel-banner"
										data-slide="prev">Prev</a> <a class="carousel-nav-next"
										href="index.html#carousel-banner" data-slide="next">Next</a>
									</div>
							</div>
							<br />
						</div>
					</div>
					</div>
				
				<div class="all-tab-table all-row-holder">

					<div id="">
							
									
						<div class="resp-tabs-container hor_1">
							<div class="about-player-all"
											id="92">
											<!-- <img alt="Leagues Summary" title="Leagues Summary" src="utilsv2/images/loading.gif" /> -->
											
											<!-- satar table -->
											<div class="all-tab-table all-row-holder">

					<div id="" style="display: block; width: 100%; margin: 0px;">

					<div id="slider-container">
    <button type="button" class="slick-prev slick-arrow slick-disabled" aria-label="Previous" role="button" aria-disabled="true" style="display: block;">Previous</button>
    <ul class="resp-tabs-list hor_1 common__slider">
        @foreach($tournament_season as $tour_name)
        <li onclick="get_point_table({{$tour_name['id']}}, '{{$tour_name['type']}}')" class="resp-tab-item active hor_1" style="background-color: rgb(255, 255, 255); border-color: rgb(193, 193, 193);">
            {{$tour_name['name']}}
        </li>
        @endforeach
    </ul>
    <button type="button" class="slick-next slick-arrow bgdanger" aria-label="Next" role="button" style="display: block;" aria-disabled="false">Next</button>
							
</div>
</br>

<script>
$(document).ready(function() {
    $('#slider-container .resp-tabs-list').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 6,
        slidesToScroll: 1,
        variableWidth: true,
        prevArrow: '#slider-container .slick-prev',
        nextArrow: '#slider-container .slick-next'
    });

    $('#slider-container .resp-tabs-list li').click(function() {
       
        $('#slider-container .resp-tabs-list li').css('background-color', '#fff');
       
        $(this).css('background-color', '#ccc');
    });
});
</script>




									
						<div class="resp-tabs-container hor_1" style="border-color: rgb(193, 193, 193);">
							<h2 class="resp-accordion hor_1 resp-tab-active" role="tab" aria-controls="hor_1_tab_item-0" style="background: none; border-color: rgb(193, 193, 193);"><span class="resp-arrow"></span></h2>
	       	<div class="about-player-all resp-tab-content hor_1 resp-tab-content-active" id="92" aria-labelledby="hor_1_tab_item-0" style="display:block"><style>

									.group-names li{
										float: none!important;}
									.group-names{
										text-align: right;
										display: flex;
										justify-content: flex-end;
									}

                      </style>


<div class="row">
                                	<div class="col-sm-12 sp">
                                    	<div class="panel with-nav-tabs panel-default">
                                            <div class="panel-heading">
	                                    	<div class="about-player">
	                            <div class="row">
	                                                <div class="col-sm-4">
	                                                    <div class="about-heading">
	                                                        <h4>Points Table</h4>
	                                                    </div>
	                                                </div>
								<div class="col-sm-8">
								<div style="overflow-x: auto; overflow-y: hidden; text-align: -webkit-right;">
									<ul class="nav nav-tabs group-names" style="width: max-content;">
												<li><a href="#" data-toggle="tab" style="padding: 10px 5px;"><small>Group :</small></a></li>
												<li style="display:flex;flex-direction:row" id="groupname"><a href="#92tabGroup1" data-toggle="tab">All</a></li>
														</ul>
											</div>
										</div>
							</div>
                                            </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="tab-content">
                                            <div class="tab-pane fade in active" id="92tabGroup1">
                                                    <div class="about-table sp1  table-responsive grp-list">
                                                <table class="table"> 
                                                    <thead> 
                                                        <tr> 
                                                        <th>#</th> 
                                                        <th>Team</th>
                                                        <th>Mat</th>
                                                        <th>Won</th> 
                                                        <th>Lost</th>
                                                        <th>N/R</th>
                                                        <th>Pts</th> 
                                                        <!-- <th class="hidden-phone">Net RR</th> -->
                                                        </tr> 
                                                    </thead> 
                                                    <tbody id="point_table"> 
                                                 
                                                   
                                                       <!-- Any remaining teams -->
					          	                    </tbody>
                                                </table>
                                                <div class="about-complete text-center">
                                                	<a href="#">More Details</a>
                                                </div>
                                            </div>
                                            </div>
                                            </div>
                                            </div>
                                            
                                		</div>
                                        <br>
                                    </div>
                         </div>
                                <div class="row player-new-stat">
                                	<div class="col-sm-4 sp">
                                    	<div class="about-player">
                                        	<div class="about-heading">
                                            	<h4>Batting</h4>
                                            </div>
                                            <div class="about-table table-responsive">
                                            	<table class="table"> 
                                                    <thead> 
                                                        <tr> 
                                                        <th>Player</th> 
                                                       <!--   <th>M</th>-->
                                                        <th class="ls">Runs</th>
                                                        </tr> 
                                                    </thead> 
                                                    <tbody id="topbatsman"> 
                                                   
                                                    
                                                     </tbody>
                                                </table>
                                                <div class="about-complete text-center">
                                                	<a href="#">Complete List</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 sp">
                                    	<div class="about-player bowling-player">
                                        	<div class="about-heading">
                                            	<h4>Bowling</h4>
                                            </div>
                                            <div class="about-table  table-responsive">
                                            	<table class="table"> 
                                                    <thead> 
                                                        <tr> 
                                                        <th>Player</th> 
                                                        <!--   <th>M</th>-->
                                                        <th class="ls">Wkts</th>
                                                        </tr> 
                                                    </thead> 
                                                    <tbody id="topbowler"> 
                                            
                                                     </tbody>
                                                </table>
                                                <div class="about-complete text-center">
                                                	<a href="#">Complete List</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 sp">
                                    	<div class="about-player">
                                        	<div class="about-heading">
                                            	<h4>Ranking</h4>
                                            </div>
                                            <div class="about-table table-responsive">
                                            	<table class="table"> 
                                                    <thead> 
                                                        <tr> 
                                                        <th>Player</th> 
                                                       <!--   <th>M</th>-->
                                                        <th class="ls">Points</th>
                                                        </tr> 
                                                    </thead> 
                                                    <tbody> 
                                                    <tr> 
                                                        <th><table><tbody><tr><td style="padding-right:5px;min-width:35px">
														<img src="https://cricclubs.com/documentsRep/profilePics/2c42f3e9-437d-4233-b69b-e4832bbdf2e3.jpg" class="img-responsive img-circle" style="width: 30px; height: 30px;">
														</td>
                                                        <td><a href="#"> Vijay Subramanya (SCC)</a></td></tr></tbody></table></th> 
                                                       <!--  <th>12</th>-->
                                                        <th class="ls">2000</th>
                                                     </tr> 
                                                     <tr> 
                                                        <th><table><tbody><tr><td style="padding-right:5px;min-width:35px">
														<img src="https://cricclubs.com/documentsRep/profilePics/daa17ece-3476-47d3-9fd9-d653ffdd357b.jpg" class="img-responsive img-circle" style="width: 30px; height: 30px;">
														</td>
                                                        <td><a href="#"> Yasir Abbasi (RC9)</a></td></tr></tbody></table></th> 
                                                       <!--  <th>9</th>-->
                                                        <th class="ls">1903</th>
                                                     </tr> 
                                                     <tr> 
                                                        <th><table><tbody><tr><td style="padding-right:5px;min-width:35px">
														<img src="https://cricclubs.com/documentsRep/profilePics/052853ab-cd4d-435a-aca7-7abd382346dd.png" class="img-responsive img-circle" style="width: 30px; height: 30px;">
														</td>
                                                        <td><a href="#"> Varun Sehdev (RPT)</a></td></tr></tbody></table></th> 
                                                       <!--  <th>7</th>-->
                                                        <th class="ls">1879</th>
                                                     </tr> 
                                                     <tr> 
                                                        <th><table><tbody><tr><td style="padding-right:5px;min-width:35px">
														<img src="https://cricclubs.com/documentsRep/profilePics/1a0f1178-665f-4e84-994b-ef4717ab51dc.jpeg" class="img-responsive img-circle" style="width: 30px; height: 30px;">
														</td>
                                                        <td><a href="#"> Manik Sharma (LCC)</a></td></tr></tbody></table></th> 
                                                       <!--  <th>10</th>-->
                                                        <th class="ls">1671</th>
                                                     </tr> 
                                                     <tr> 
                                                        <th><table><tbody><tr><td style="padding-right:5px;min-width:35px">
														<img src="https://cricclubs.com/documentsRep/profilePics/206f3b84-ba02-4cf4-a10e-a98d4975e46b.jpg" class="img-responsive img-circle" style="width: 30px; height: 30px;">
														</td>
                                                        <td><a href="viewPlayer.do?playerId=1242838&amp;clubId=2565"> Abubaker Kalair (MQ)</a></td></tr></tbody></table></th> 
                                                       <!--  <th>12</th>-->
                                                        <th class="ls">1418</th>
                                                     </tr> 
                                                     </tbody>
                                                </table>
                                                <div class="about-complete text-center">
                                                	<a href="#">Complete List</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="runs-slide-details-all">
                                	<div class="row">
                                	<div class="col-sm-3 sp">
                                    	 <div class="runs">
                                            <div id="carousel-runs" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="item">
                                                        <h4>Runs</h4>
                                                        <h2><a style="color: white;" href="#">12,070</a></h2>
                                                    </div>
                                                    <div class="item">
                                                        <h4>Balls</h4>
                                                        <h2><a style="color: white;" href="#">17,338</a></h2>
                                                    </div>
                                                    <div class="item active">
                                                        <h4>Wickets</h4>
                                                        <h2><a style="color: white;" href="#">628</a></h2>
                                                    </div>
                                                </div>
                                                <div class="carousel-nav">
                                                        <ol class="carousel-indicators">
                                                        <li data-target="#carousel-runs" data-slide-to="0" class=""></li>
                                                        <li data-target="#carousel-runs" data-slide-to="1" class=""></li>
                                                        <li data-target="#carousel-runs" data-slide-to="2" class="active"></li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 sp d-inline-block-count">
                                    	<div class="fifty">
                                            <div id="carousel-fifty" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="item">
                                                        <h4>100's</h4>
                                                        <h2><a style="color: white;" href="#">8</a></h2>
                                                    </div>
                                                    <div class="item active left">
                                                        <h4>50's</h4>
                                                        <h2><a style="color: white;" href="#">40</a></h2>
                                                    </div>
                                                    <div class="item next left">
                                                        <h4>25's</h4>
                                                        <h2><a style="color: white;" href="#">114</a></h2>
                                                    </div>
                                                </div>
                                                <div class="carousel-nav">
                                                        <ol class="carousel-indicators">
                                                        <li data-target="#carousel-fifty" data-slide-to="0" class=""></li>
                                                        <li data-target="#carousel-fifty" data-slide-to="1" class=""></li>
                                                        <li data-target="#carousel-fifty" data-slide-to="2" class="active"></li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 sp d-inline-block-count">
                                    	<div class="six">
                                            <div id="carousel-six" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="item active left">
                                                        <h4>5 Wickets</h4>
                                                        <h2><a style="color: white;" href="#">10</a></h2>
                                                    </div>
                                                    <div class="item next left">
                                                        <h4>4 Wickets</h4>
                                                        <h2><a style="color: white;" href="#">20</a></h2>
                                                    </div>
                                                    <div class="item">
                                                        <h4>Hat-trick</h4>
                                                        <h2><a style="color: white;" href="#">0</a></h2>
                                                    </div>
                                                </div>
                                                <div class="carousel-nav">
                                                        <ol class="carousel-indicators">
                                                        <li data-target="#carousel-six" data-slide-to="0" class=""></li>
                                                        <li data-target="#carousel-six" data-slide-to="1" class="active"></li>
                                                         <li data-target="#carousel-six" data-slide-to="2" class=""></li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  <div class="col-sm-3 sp d-inline-block-count">
                                    	<div class="players">
                                            <div id="carousel-players" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="item active">
                                                        <h4>players</h4>
                                                        <h2><a style="color: white;" href="#">265</a></h2>
                                                    </div>
                                                </div>
                                                <div class="carousel-nav">
                                                        <ol class="carousel-indicators">
                                                        <li data-target="#carousel-players" data-slide-to="0" class="active"></li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                	<div class="col-sm-3 sp d-inline-block-count">
                                    	<div class="maidens">
                                            <div id="carousel-maidens" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="item active">
                                                        <h4>Fours</h4>
                                                        <h2><a style="color: white;" href="#">982</a></h2>
                                                    </div>
                                                    <div class="item">
                                                        <h4>Sixers</h4>
                                                        <h2><a style="color: white;" href="#">209</a></h2>
                                                    </div>
                                                </div>
                                                <div class="carousel-nav">
                                                        <ol class="carousel-indicators">
                                                        <li data-target="#carousel-maidens" data-slide-to="0" class="active"></li>
                                                        <li data-target="#carousel-maidens" data-slide-to="1" class=""></li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 sp d-inline-block-count">
                                    	<div class="fives">
                                            <div id="carousel-fives" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="item">
                                                        <h4>Catches</h4>
                                                        <h2><a style="color: white;" href="#">314</a></h2>
                                                    </div>
                                                    <div class="item active">
                                                        <h4>Runouts</h4>
                                                        <h2><a style="color: white;" href="#">30</a></h2>
                                                    </div>
                                                    <div class="item">
                                                        <h4>Ducks</h4>
                                                        <h2><a style="color: white;" href="#">96</a></h2>
                                                    </div>
                                                </div>
                                                <div class="carousel-nav">
                                                        <ol class="carousel-indicators">
                                                        <li data-target="#carousel-fives" data-slide-to="0" class=""></li>
                                                        <li data-target="#carousel-fives" data-slide-to="1" class="active"></li>
                                                        <li data-target="#carousel-fives" data-slide-to="2" class=""></li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 sp d-inline-block-count">
                                    	<div class="no-balls">
                                            <div id="carousel-no-balls" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="item">
                                                        <h4>maidens</h4>
                                                        <h2><a style="color: white;" href="#">210</a></h2>
                                                    </div>
                                                    <div class="item active">
                                                        <h4>wides</h4>
                                                        <h2><a style="color: white;" href="#">1,377</a></h2>
                                                    </div>
                                                    <div class="item">
                                                        <h4>no-balls</h4>
                                                        <h2><a style="color: white;" href="#">94</a></h2>
                                                    </div>
                                                </div>
                                                <div class="carousel-nav">
                                                        <ol class="carousel-indicators">
                                                        <li data-target="#carousel-no-balls" data-slide-to="0" class=""></li>
                                                        <li data-target="#carousel-no-balls" data-slide-to="1" class="active"></li>
                                                        <li data-target="#carousel-no-balls" data-slide-to="2" class=""></li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 sp d-inline-block-count">
                                    	<div class="players">
                                            <div id="carousel-players" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="item active" style="height: 86px;  display: flex; align-items: center;">
                                                        
                                                        <h4><a style="color: white;" href="#">view More <i class="fa fa-arrow-circle-o-right"></i></a></h4>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            <!-- <div class="weekly-leader-board" style="margin:-15px;">
                                        <div class="weekly-content">
                                                <div class="border-heading">
                                                    <h5>Weekly Leader Board </h5>
                                                </div>
                                                
                                            	There are no matches played in the last week.
                                            	</div>
                                       
                                        </div> -->
                         
                            </div>
									<!-- <h2 class="resp-accordion hor_1" role="tab" aria-controls="hor_1_tab_item-1" style="background-color: rgb(245, 245, 245); border-color: rgb(193, 193, 193);"><span class="resp-arrow"></span>2022 MCLT20</h2><div class="about-player-all resp-tab-content hor_1" id="93" aria-labelledby="hor_1_tab_item-1" style="border-color: rgb(193, 193, 193);">
											<img alt="Leagues Summary" title="Leagues Summary" src="/utilsv2/images/loading.gif">
											<br>Loading league summary data...
										</div>
									<h2 class="resp-accordion hor_1" role="tab" aria-controls="hor_1_tab_item-2" style="background-color: rgb(245, 245, 245); border-color: rgb(193, 193, 193);"><span class="resp-arrow"></span>2022 MCL100 - Super 8</h2><div class="about-player-all resp-tab-content hor_1" id="91" aria-labelledby="hor_1_tab_item-2" style="border-color: rgb(193, 193, 193);">
											<img alt="Leagues Summary" title="Leagues Summary" src="/utilsv2/images/loading.gif">
											<br>Loading league summary data...
										</div>
									<h2 class="resp-accordion hor_1" role="tab" aria-controls="hor_1_tab_item-3" style="background-color: rgb(245, 245, 245); border-color: rgb(193, 193, 193);"><span class="resp-arrow"></span>2022 MCLT25</h2><div class="about-player-all resp-tab-content hor_1" id="100" aria-labelledby="hor_1_tab_item-3" style="border-color: rgb(193, 193, 193);">
											<img alt="Leagues Summary" title="Leagues Summary" src="/utilsv2/images/loading.gif">
											<br>Loading league summary data...
										</div> -->
									</div>
					</div>

				</div>







											
										</div>
									<!-- <div class="about-player-all"
											id="93">
											<img alt="Leagues Summary" title="Leagues Summary" src="utilsv2/images/loading.gif" />
											<br>Loading league summary data...
										</div>
									<div class="about-player-all"
											id="91">
											<img alt="Leagues Summary" title="Leagues Summary" src="utilsv2/images/loading.gif" />
											<br>Loading league summary data...
										</div>
									<div class="about-player-all"
											id="100">
											<img alt="Leagues Summary" title="Leagues Summary" src="utilsv2/images/loading.gif" />
											<br>Loading league summary data...
										</div> -->
									</div>
					</div>

				</div>
<style>

.common__slider .slick-prev:before, .common__slider .slick-next:before{
color:#333;
}
.common__slider .slick-prev {
    left: -15px;
}
.common__slider .slick-next {
    right: 5px !important;
}

</style>


				<div class="articles all-row-holder">
					<div class="head-top">
						<div class="row">
							<div class="col-sm-6 col-xs-6">
								<div class="border-heading">
									<h5>ARTICLES</h5>
								</div>
							</div>
							<div class="col-sm-6 col-xs-6">
								<div class="view text-right">
									<a
										href="EOSCLCricketLeague/articles.do%3FclubId=2565.html">View
										ALL <i class="fa fa-arrow-circle-o-right"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="article-row">
						<div class="row">
							<p class="col-sm-12">No Articles Found.</p>
							</div>
					</div>
				</div>
			</div>
			
			
			<div class="col-sm-5 p-sm-0">
			<div id="resultsDiv">
					<div class="complete-list">
						<div class="panel with-nav-tabs panel-default list-tab-mobile">
							<div class="panel-heading">
								<ul class="nav nav-tabs">
									<li class=""
										style="margin-right: 2px;"><a href="index.html#tab1default"
										data-toggle="tab">Live</a></li>
									<li class="center active"
										style="margin-left: 2px; margin-right: 2px;"><a
										href="index.html#tab2default" data-toggle="tab">Results</a></li>
									<li class=""
										style="margin-left: 2px; margin-right: 2px;"><a
										href="index.html#tab3default" data-toggle="tab">Schedule</a></li>
								</ul>
							</div>
							<div class="panel-body">
								<div class="tab-content">
									<div class="tab-pane fade "
										id="tab1default">
										<div id="live-score">

										
										<center><br><br>There are no Live matches available now <br><br><br></center><div class="complete text-center">
											<a
												href="#">Complete
												list</a>
												</div>
										</div>
									</div>
									<div class="tab-pane fade in active"
										id="tab2default">
									@foreach($match_results as $match_result)



										<div class="team-vs-team">
											<div class="row list-slign">
												<div class="col-sm-4 col-xs-4">
													<div class="vsteam-image">
														<a
															title="03/19/2023"
															href="{{ url('fullScorecard/' . $match_result->id) }}">
															<ul class="list-inline">
																<li><img
																	src="https://cricclubs.com/documentsRep/teamLogos/9deaa818-6154-4a1a-8eaf-89bb6e54f83d.jpg"
																	class="img-responsive img-circle"
																	style="width: 40px; height: 40px;" /></li>
																<li><img
																	src="https://cricclubs.com/documentsRep/teamLogos/57dddf01-13bf-48f5-bb38-bffc5faa2d67.jpeg"
																	class="img-responsive img-circle"
																	style="width: 40px; height: 40px;" /></li>
															</ul>
														</a>
													</div>
												</div>
												<div class="col-sm-8 col-xs-8">
													<div class="vsteam-text">
														<h4>
															<a class="list-score" style="color: inherit;"
																href="{{ url('fullScorecard/' . $match_result->team_id_a) }}">L:
																{{$teams[$match_result->team_id_a]}} - vs - {{$teams[$match_result->team_id_b]}}</a>
															<img alt="Ball by Ball" title="Ball by Ball" style="float: right;
margin-right: 10px;
    margin-top: -3px; cursor: pointer;
     "
																src="utilsv2/theme2-static/images/cric-ball.png" width="15px" height="15px"/>
															</h4>
														<h5>
															<a class="list-score" 
																href="{{ url('fullScorecard/' . $match_result->id) }}">
																{{$match_result->match_result_description}}</a>
														<a style="float: right;font-size: 0.85rem;background: #2098d1;
    padding: 4px 9px;
    border-radius: 5px;
    color: #fff;
    margin-right: 1rem;" href="{{ url('fullScorecard/' . $match_result->id) }}"> Scorecard</a>
														</h5>
														
														
													</div>
												</div>
											</div>
										</div>

 @endforeach

										<div class="complete text-center">
											<a
												href="#">Complete
												list</a>
										</div>

									</div>

									<div class="tab-pane fade "
										id="tab3default">
										@foreach($upcoming_match as $upcoming_match)
	
										<div class="team-vs-team">
											<div class="row list-slign">
												<div class="col-sm-4 col-xs-4">
													<div class="vsteam-image">
														<ul class="list-inline">
															<li><img
																src="https://cricclubs.com/documentsRep/teamLogos/95c38746-679e-45d2-804d-2971933b0169.jpg"
																class="img-responsive img-circle"
																style="width: 40px; height: 40px;" /></li>
															<li><img
																src="https://cricclubs.com/documentsRep/teamLogos/a2530a09-334e-4d16-9206-1dfb63857ac8.jpg"
																class="img-responsive img-circle"
																style="width: 40px; height: 40px;" /></li>
														</ul>
													</div>
												</div>
												<div class="col-sm-8 col-xs-8">
													<div class="vsteam-text">
														<h4>L:
															<a
															href="{{ url('team-view/' . $upcoming_match->team_id_a.'_'.$upcoming_match->tournament_id) }}">
																{{$teams[$upcoming_match->team_id_a]}}</a>
															
															- vs -
															<a
															href="{{ url('team-view/' . $upcoming_match->team_id_b.'_'.$upcoming_match->tournament_id) }}">
																{{$teams[$upcoming_match->team_id_b]}}</a>
															</h4>
														<h5>
															
															at <a
																href="#"
																target="_new">{{$ground[$upcoming_match->ground_id]}}</a>
															<span><i class="fa fa-clock-o"></i> {{$upcoming_match->match_startdate->format('d-m-Y')}}    {{$upcoming_match->match_starttime->format('H:i:s')}}</span>
														</h5>
													</div>
												</div>
											</div>
										</div>
										@endforeach

										<div class="complete text-center">
											<a
												href="#">Complete
												list</a>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div style="text-align: center;background: #fff; padding-bottom: 15px;" class="col-sm-12">
					<!-- Hot Star Changes -->
					<!-- Hot star chagnes ends -->
					<!-- cric stores Changes -->
					<!-- Cric Stores chagnes ends -->
					</div>
				<div class="articles">
					<div class="head-top">
						<div class="row">
							<div class="col-sm-6 col-xs-6">
								<div class="border-heading">
									<h5>Gallery</h5>
								</div>
							</div>
							<div class="col-sm-6 col-xs-6">
								<div class="view text-right">
									<a href="#">View
										ALL <i class="fa fa-arrow-circle-o-right"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="gallery-image-row">
						<div class="row">
							<div class="col-sm-6 sp">
								
							@foreach($image_gallery as $image)
						@if($image->type == 1)
								<div class="gallery-image-all">
									<a title="EOSCL AWARDS 2018"
										href="#">
										<div class="gallery-image">
								
											<img
											src="data:image/png;base64,{{ $image->image }}" alt="{{ $image->title }}"
												class="img-responsive center-block" />
										</div>
										
									</a>
									<div class="gallery-text">

											<!-- <i class="fa fa-camera-retro" style="font-size: 14px;"></i> --> <b
												style="font-size: 14px;">{{ $image->title }}</b>

											
										</div>
								</div>
								@endif
								@endforeach
								

							
							</div>
							</div>						
					</div>
				</div>
				<!-- <div class="app-in">				
					<div class="border-heading">
						<h5>DOWNLOAD</h5>
					</div>
				
					<div class="sponser-content">
						<div class="row">
							<div class="col-sm-1 col-xs-1">
							</div>
							<div class="col-sm-5 col-xs-5">
                               <div class="spon-image">
									<a href="https://play.google.com/store/apps/details?id=com.cricclubs" target="_blank" >
                                        <img src="https://cricclubs-static.s3.amazonaws.com/cricclubshotstar/images_jul_7_2020/google.png"
                                        class="img-responsive center-block" />
                                    </a>                               	
                               </div>
                            </div>
                            <div class="col-sm-5 col-xs-5">
                                <div class="spon-image">
									<a href="https://apps.apple.com/us/app/cricclubs/id978682715" target="_blank" >
                                        <img src="https://cricclubs-static.s3.amazonaws.com/cricclubshotstar/images_jul_7_2020/apple.png"
                                        class="img-responsive center-block" />
                                    </a>                               	
                               </div>
                           </div>
					</div>
				</div>
				</div> -->
				<div class="facebook">
					<div class="border-heading sp">
						<h5>Facebook</h5>
					</div>
					<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%EOSCLeague%2F&amp;tabs=timeline&amp;width=340&amp;height=500&amp;small_header=true&amp;adapt_container_width=true&amp;hide_cover=false&amp;show_facepile=false&amp;appId=745734118815722" target="_top" width="340" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
				</div>
				<div class="facebook">
					<div class="border-heading sp">
						<h5>Twitter</h5>
					</div>
					<div style=" overflow-y: scroll; max-height: 500px; ">
					<a class="twitter-timeline" href="https://twitter.com/EEoscl?ref_src=twsrc%5Etfw%7Ctwcamp%5Etweetembed%7Ctwterm%5E1003005776129150976%7Ctwgr%5E2eef71c9b1181f3aed0a5fb71c24b561abaad9b9%7Ctwcon%5Es1_&ref_url=http%3A%2F%2Fwww.eoscl.com%2FDefault.aspx">Tweets by MissCricLeague</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></div>
				</div>
				
				</div>
		
		
	</div>
</div>
<div id="content" style="display: none; visible: false;">

	<a id="example7"
		href="#"
		title="Welcome to EOSCL"></a>
	<div id="inline1" style="width: 500px; height: 300px; overflow: auto;">
		Welcome to EOSCL</div>

</div>

<div id="dialogOpenVideoLink" class="dialogOpenVideoLink"
	title="Ball Video" style="display: none;">
	<center>

		<iframe class="playVideo" id="playVideo" src="index.html" width="640px"
			height="360px" allowfullscreen></iframe>

	</center>
</div>

<script>
	$(document).ready(function() {
		
    	
    	(function($){
    		  $.fn.extend({ 
    		    onShow: function(callback, unbind){
    		      return this.each(function(){
    		        var _this = this;
    		        var bindopt = (unbind==undefined)?true:unbind; 
    		        if($.isFunction(callback)){
    		          if($(_this).is(':hidden')){
    		            var checkVis = function(){
    		              if($(_this).is(':visible')){
    		                callback.call(_this);
    		                if(bindopt){
    		                  $('body').unbind('click keyup keydown', checkVis);
    		                }
    		              }                         
    		            }
    		            $('body').bind('click keyup keydown', checkVis);
    		          }
    		          else{
    		            callback.call(_this);
    		          }
    		        }
    		      });
    		    }
    		  });
    		})(jQuery);
    	    	
    
        		
	});
</script>

<script type="text/javascript">
	 $(document).ready(function() {
            setInterval(get_live_score, 5000);
        });
	
		function get_live_score() {
    $.ajax({
        url: "{{ url('/live_score/')}}",
        type: 'GET',
        dataType: 'json',
        success: function(data) {
			let _fixture_ids = []
			data.map(i=>i.fixture_id).forEach(function(item, index) {
				(!_fixture_ids.includes(item))?_fixture_ids.push(item):"";				
			});


		let final = _fixture_ids.map(i=>	 data.filter(d => d.fixture_id == i)	)

			const liveScoreDiv = document.getElementById('live-score');
			if (data.length === 0) {
				liveScoreDiv.innerHTML = `
					<center><br><br>There are no Live matches available now <br><br><br></center>
					<div class="complete text-center">
						<a href="EOSCLCricketLeague/listMatches.do%3FclubId=2565.html">Complete list</a>
					</div>
				`;
			} else {
            liveScoreDiv.innerHTML = '';
			console.log("final ==> ", final)
			final.forEach(function(item, index) {
                console.log(final[index],"final[index][1].max_ball");
                // console.log(final[index][1].max_ball/6);

                liveScoreDiv.innerHTML += `
				
                <div class="team-vs-team">
                    <div class="row list-slign">
                        <div class="col-sm-4 col-xs-4">
                            <div class="vsteam-image" >
							<a  href="{{ url('fullScorecard/${item[0].fixture_id}') }}" >
                                <ul class="list-inline" >
                                    <li><img
                                        src="https://cricclubs.com/documentsRep/teamLogos/95c38746-679e-45d2-804d-2971933b0169.jpg"
                                        class="img-responsive img-circle"
                                        style="width: 40px; height: 40px;" /></li>
                                    <li><img
                                        src="https://cricclubs.com/documentsRep/teamLogos/a2530a09-334e-4d16-9206-1dfb63857ac8.jpg"
                                        class="img-responsive img-circle"
                                        style="width: 40px; height: 40px;" /></li>
                                </ul>
								</a>
                            </div>
                        </div>
						<a  href="{{ url('fullScorecard/${item[0].fixture_id}') }}" >
                        <div class="col-sm-8 col-xs-8">
                            <div class="vsteam-text ">
							
                            <h4>
  <p>
    <span style="color:red;font-weight:bold;float:right;padding-right:10px">Live</span>
    ${item[0].tournaments_name} at ${item[0].ground_name}
  </p>
  <br/>
  L:
  <a href="{{ url('team-view/${item[0].team_id_a}_${item[0].tournamentID}') }}">
    ${item[0].team_a_name}
  </a>
  - vs -
  <a href="{{ url('team-view/${item[0].team_id_b}_${item[0].tournamentID}') }}">
    ${item[0].team_b_name}
  </a>
</h4>
<h5>
  <a href="#" target="_new"></a>
  <p>
    ${
      final[index][0].inningnumber === 1
        ? ` <i class="fa-sharp fa-solid fa-circle-dot text-danger"></i>`
        : `&nbsp;&nbsp;&nbsp;&nbsp;`
    }
    ${item[0].team_a_name}
    <span style="float:right;font-weight:bold;padding-right:10px;font-size:15px">
      ${final[index][0].inningnumber === 1 ? final[index][0].total_runs : 0}
      /${final[index][0].inningnumber === 1 ? final[index][0].total_wickets : 0}
      Overs
      ${
        final[index][0].inningnumber === 1
          ? Math.floor(item[0].max_ball / 6) +
            (item[0].max_ball % 6 !== 0 ? "." + (item[0].max_ball % 6) : "")
          : 0
      }
      /${item[0].numberofover}
    </span>
  </p>
  <br/>
</h5>

<h5>
                                        
                                        <p>
  ${
    final[index][1] && final[index][1].inningnumber === 2
      ? ` <i class="fa-sharp fa-solid fa-circle-dot text-danger"></i>`
      : `&nbsp;&nbsp;&nbsp;&nbsp;`
  }
  ${item[0].team_b_name}
  <span style="float:right;font-weight:bold;padding-right:10px;font-size:15px">
    ${
      final[index][1] && final[index][1].inningnumber === 2
        ? final[index][1].total_runs
        : 0
    }
    /${
      final[index][1] && final[index][1].inningnumber === 2
        ? final[index][1].total_wickets
        : 0
    } Overs ${
      final[index][1] && final[index][1].inningnumber === 2
        ? Math.floor(item[1].max_ball / 6) +
          (item[1].max_ball % 6 === 0 ? "" : "." + (item[1].max_ball % 6))
        : 0
    }/${item[0].numberofover}
  </span>
</p>
</h5>


                                </h5>
                            </div>
                        </div>
					</a>
                    </div>
					
                </div>
				
				
                `;


            });}

           
        }
    });
}


function get_point_table(tornament_season_id,type) {
    $.ajax({
        url: "{{ url('/get_point_table/')}}/"+tornament_season_id+'/'+type,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
    var match_count = data[0];
    var team_name = data[1];
    var point_table_data = document.getElementById('point_table');
    point_table_data.innerHTML = '';
    data.sort((a, b) => b.teambonusPoints - a.teambonusPoints);
    data.forEach((item, index) => {
        point_table_data.innerHTML += `
        <tr>
            <th>${index + 1}</th>
            <th>
                <table>
                    <tbody>
                        <tr>
                            <td><img src="https://cricclubs.com/documentsRep/teamLogos/624fee3a-e918-4e39-ab90-ff1b1c07e5d2.jpg" class="img-responsive img-circle" style="width: 20px; height: 20px;"></td>
                            <td>&nbsp; <a href="{{ url('team-view/${item.team_id}_${item.tournament_id}') }}">${item.team_name}</a></td>
                        </tr>
                    </tbody>
                </table>
            </th>
            <th><a href="#">${item.total_matches}</a></th>
            <th>${item.wins}</th>
            <th>${item.losses}</th>
            <th>${item.draws}</th>
            <th style="font-weight: bold;padding-right: 15px; text-align: left;"><a href="#"><span title="">${item.teambonusPoints}</span></a></th>
        </tr>
        `;
    });
},

	});
	
	get_season_group(tornament_season_id, type);
	get_top_scorers(tornament_season_id);
	get_top_bowler(tornament_season_id)


}




function get_season_group(tornament_season_id,type) {
    $.ajax({
        url: "{{ url('/get_season_group/')}}/" + tornament_season_id+'/'+type ,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const groups = document.getElementById('groupname');
            groups.innerHTML = '';
			if (data.length === 0) {
				groups.innerHTML = `
				<a href="#92tabGroup1" data-toggle="tab">All</a>
				`;
			} else {
            data.forEach(item => {
                groups.innerHTML += `
				<a href="#92tabGroup1" data-toggle="tab" onclick="get_group_team(${item.group_id}, ${tornament_season_id},'${type}')" > ${item.groupname} </a>
				`
            });
		}
        },
        error: function(error) {
            console.log(error);
        }
    });
}


function get_group_team(group_id,tournamnet_id,type) {
    $.ajax({
        url: "{{ url('/get_group_team/')}}/"  + group_id+'/'+tournamnet_id+'/'+type ,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const point_table_data = document.getElementById('point_table');
		point_table_data.innerHTML = '';
		data.forEach(item => {
		point_table_data.innerHTML +=`
		<tr>	
		<th>1</th> 
			<th>
			<table>
			<tbody>
				<tr>
					<td><img src="https://cricclubs.com/documentsRep/teamLogos/624fee3a-e918-4e39-ab90-ff1b1c07e5d2.jpg" class="img-responsive img-circle" style="width: 20px; height: 20px;"></td>
					<td>&nbsp; <a href="{{ url('team-view/${item.team_id}_${item.tournament_id}') }}">${item.team_name}</a></td>
				</tr>
			</tbody>
			</table>
			</th>
			<th><a ">${item.total_matches}</a></th>
			<th>${item.wins}</th> 
			<th>${item.losses}</th>
			<th>${item.draws}</th>
			<th style="font-weight: bold;padding-right: 15px; text-align: left;"><a href="#"><span title="">${item.teambonusPoints}</span></a></th> 
			
			</tr>
			`
			})
		}
    });
}

function get_top_scorers(tournament_season_id) {
    $.ajax({
        url: "{{ url('/get_top_scorers/')}}/" + tournament_season_id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const scores = document.getElementById('topbatsman');
            scores.innerHTML = ''; // Clear the previous data before appending new data
            data.forEach(item => {
                scores.innerHTML += `
                    <tr>
                        <th>
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="padding-right:5px;min-width:35px">
                                            <img src="https://cricclubs.com/documentsRep/profilePics/206f3b84-ba02-4cf4-a10e-a98d4975e46b.jpg" class="img-responsive img-circle" style="width: 30px; height: 30px;">
                                        </td>
                                        <td>
                                            <a href="viewPlayer.do?playerId=1242838&amp;clubId=2565">${item.fullname}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </th> 
                        <th class="ls">
                            <a style="font-size: 17px;" class="linkStyle" href="">${item.total_runs}</a>
                        </th>
                    </tr>
                `;
            });
        },
        error: function(error) {
            console.log(error);
        }
    });
}


function get_top_bowler(tournament_season_id) {
    $.ajax({
        url: "{{ url('/get_top_bowler/')}}/" + tournament_season_id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const scores = document.getElementById('topbowler');
            scores.innerHTML = ''; // Clear the previous data before appending new data
            data.forEach(item => {
                scores.innerHTML += `
                    <tr>
                        <th>
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="padding-right:5px;min-width:35px">
                                            <img src="https://cricclubs.com/documentsRep/profilePics/206f3b84-ba02-4cf4-a10e-a98d4975e46b.jpg" class="img-responsive img-circle" style="width: 30px; height: 30px;">
                                        </td>
                                        <td>
                                            <a href="viewPlayer.do?playerId=1242838&amp;clubId=2565">${item.fullname}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </th> 
                        <th class="ls">
                            <a style="font-size: 17px;" class="linkStyle" href="">${item.total_wickets}</a>
                        </th>
                    </tr>
                `;
            });
        },
        error: function(error) {
            console.log(error);
        }
    });
}


</script><style >
				.footer-bottom{
					width: 100%;
   			  position: fixed;
   			  bottom: 0;
				}
			</style>
@stop
