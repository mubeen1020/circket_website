@extends('default')
@section('content')

    <div class="holder point">
    	<div class="container">
        	<div class="point-table-all border">
        	<!-- <form action="https://www.mississaugacricketleague.ca/MississaugaCricketLeague/searchPlayer.do" method="post" id="searchPlayer"> -->

            <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('searchplayer-form-submit')}}">
                @csrf

            <div class="series-drop">
            	<div class="row">
                	<div class="col-sm-6">
                    	<div class="border-heading">
                            <h5>Search player</h5>
                        </div>
                    </div>
                    <div class="col-sm-6">
                	 <div align="right" style="padding-bottom:20px;color: brown" ><span > ** Enter at-least one.</span></div>
                	 </div>
                </div>
               
                <div class="row">
                	<div class="col-lg-4">
                	
                    	<div class="row">
                            <div class="col-lg-5">
                                <div class="form-text">
                                    <h5>Name <span style="color: brown">**</span></h5>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-in">
                                   <input type="text" id="firstName" name="fullname" class="form-control" style="width:100%;" value="<?php  if(isset($_POST['fullname'])) { echo  $_POST['fullname']; } ?>" >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                    	<div class="row">
                            <div class="col-lg-5">
                                <div class="form-text">
                                    <h5>Team Name<span style="color: brown">**</span></h5>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-in">
                                   <select name="team_name" id="team_name" class="form-control">
                                    <option  ></option>
                               @foreach($teams_only as $index => $team_only)
                               <option <?php if(isset($_POST['team_name']) && $_POST['team_name']== $index) { echo 'selected'; } ?> value="{{$index}}">{{$team_only}}</option>
                                    @endforeach
                                        </select>
                                </div>
                            </div>
                        </div>
                    </div>
                       
                    
                    
                  </div>
                
                <div class="row">
                

               		
               		<div class="col-lg-4">
	               		 <div class="row">
	               		 	<div class="col-lg-5">
		                    	<div class="form-text">
                                    <h5>Email<span style="color: brown">**</span></h5>
                                </div>
		                    </div>
	               		 	 <div class="col-lg-7">
                                 <div class="form-in">
                                 	<input type="email" name="emailId" id="emailId" value="<?php  if(isset($_POST['emailId'])) { echo  $_POST['emailId']; } ?>" class="form-control" style="width:100%;" >
                                 </div>
                             </div>
	               		 </div>
               		</div>
                
                	<div class="col-lg-4">
	               		 <div class="row">
	               		 	<div class="col-lg-5">
		                    	<div class="form-text">
                                    <h5>Gender</h5>
                                </div>
		                    </div>
	               		 	 <div class="col-lg-7">
                                 <div class="form-in">
                                 	<select name="gender" id="gender" class="form-control" >
                                 		<option <?php if(isset($_POST['gender']) &&  $_POST['gender']=='All') { echo 'selected'; }  ?> value="">All</option>
                                        <option <?php if(isset($_POST['gender']) &&  $_POST['gender']=='Male') { echo 'selected'; }  ?> value="Male" >Male</option>
                                        <option <?php if(isset($_POST['gender']) &&  $_POST['gender']=='Female') { echo 'selected'; }  ?> value="Female" >Female</option>
									</select>
                                 </div>
                             </div>
	               		 </div>
               		</div>
               	</div>
                
                   <div class="row">
                   <div class="col-lg-4">
	               		 <div class="row">
	               		 	<div class="col-lg-5">
		                    	<div class="form-text">
                                    <h5>Batting Style</h5>
                                </div>
		                    </div>
	               		 	 <div class="col-lg-7">
                                 <div class="form-in">
                                 	<select name="battingStyle" id="battingStyle" class="form-control">
                                 	<option value=""></option>
                                    <option  <?php if(isset($_POST['battingStyle']) &&  $_POST['battingStyle']=='All') { echo 'selected'; }  ?>  value="All">All</option>
										<option <?php if(isset($_POST['battingStyle']) &&  $_POST['battingStyle']=='Right Handed Batsman') { echo 'selected'; }  ?> value="Right Handed Batsman">Right Handed Batter</option>
										<option <?php if(isset($_POST['battingStyle']) &&  $_POST['battingStyle']=='Left Handed Batsman') { echo 'selected'; }  ?> value="Left Handed Batsman">Left Handed Batter</option>
									</select>
                                 </div>
                             </div>
	               		 </div>
               		</div>



                       <div class="col-lg-4">
	               		 <div class="row">
	               		 	<div class="col-lg-5">
		                    	<div class="form-text">
                                    <h5>Bowling Style</h5>
                                </div>
		                    </div>
	               		 	 <div class="col-lg-7">
                                 <div class="form-in">
                                 		
                                        <select name="bowlingStyle" id="bowlingStyle" class="form-control">
                                        <option value=""></option>
	                             			<option <?php if(isset($_POST['bowlingStyle']) &&  $_POST['bowlingStyle']=='All') { echo 'selected'; }  ?> value="All">All</option>
											<option <?php if(isset($_POST['bowlingStyle']) &&  $_POST['bowlingStyle']=='Right Arm Medium') { echo 'selected'; }  ?> value="Right Arm Medium">Right Arm Medium</option>
											<option <?php if(isset($_POST['bowlingStyle']) &&  $_POST['bowlingStyle']=='Right Arm Fast') { echo 'selected'; }  ?> value="Right Arm Fast">Right Arm Fast</option>
											<option <?php if(isset($_POST['bowlingStyle']) &&  $_POST['bowlingStyle']=='Right Arm Off Spin') { echo 'selected'; }  ?> value="Right Arm Off Spin">Right Arm Off Spin</option>
											<option <?php if(isset($_POST['bowlingStyle']) &&  $_POST['bowlingStyle']=='Right Arm Leg Spin') { echo 'selected'; }  ?> value="Right Arm Leg Spin">Right Arm Leg Spin</option>
											<option <?php if(isset($_POST['bowlingStyle']) &&  $_POST['bowlingStyle']=='Left Arm Fast') { echo 'selected'; }  ?> value="Left Arm Fast">Left Arm Fast</option>
											<option <?php if(isset($_POST['bowlingStyle']) &&  $_POST['bowlingStyle']=='Left Arm Medium') { echo 'selected'; }  ?> value="Left Arm Medium">Left Arm Medium</option>
											<option <?php if(isset($_POST['bowlingStyle']) &&  $_POST['bowlingStyle']=='Left Arm Off Spin') { echo 'selected'; }  ?> value="Left Arm Off Spin">Left Arm Off Spin</option>
											<option <?php if(isset($_POST['bowlingStyle']) &&  $_POST['bowlingStyle']=='Left Arm Leg Spin') { echo 'selected'; }  ?> value="Left Arm Leg Spin">Left Arm Leg Spin</option>
										</select>
                                 </div>
                             </div>
	               		 </div>
               		</div>

                   </div>

                <div class="row">
                <div class="col-lg-4">
	               		 <div class="row">
	               		 	<div class="col-lg-5">
		                    	<div class="form-text">
		                    	   <h5>Club Name<span style="color: brown">**</span></h5>
                                </div>
               				</div>
               				<div class="col-lg-7">
                                 <div class="form-in">
               						<select name="club" id="club" class="form-control">
                                <option  ></option>
                               @foreach($clubs as $index => $club)
                                    <option <?php if(isset($_POST['club']) &&  $_POST['club']== $index) { echo 'selected'; }  ?> value="{{$index}}" >{{$club}}</option>
                                    @endforeach
               							</select>   
                                 </div>
                             </div>
               			 </div>
               		</div>
               		</div>
               	<div class="row">
               	</div>
               	<div class="row">
               		
               		<!-- new role addn -->
               		</div>
               
                <div class="series-drop">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="button-umpire text-center">
                            <input type="submit" class="btn btn-form" value="Search" onclick="return verifyInput()" / style="margin: 0px auto;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-lg-12">
                    		<div align="center" style="padding-top:0px;color: brown" ><span style="display:none" id="searchError"> Please enter at-least one search criteria.</span></div>
                    		 </div>
                    </div>
            	</div>
               
            </div>
            </form>
            <div class="about-table table-responsive" id="tab1default">
                <div id="playersData_wrapper" class="dataTables_wrapper no-footer"><div class="dt-buttons">         </div>


        
            
            
            
            
            
            
           
                <table class="table sortable table-striped dataTable no-footer">
    <thead>
        <th class="sorting_1" class="sortheader" style="text-align: left !important">S.No <i class="fa-solid fa-arrow-down-short-wide"></i></th>
        <th class="sorting_1" class="sortheader">Player ID  <i class="fa-solid fa-arrow-down-short-wide"></i></th>
        <th class="sorting_1" class="sortheader">Player Name  <i class="fa-solid fa-arrow-down-short-wide"></i></th>
        <th class="sorting_1" class="sortheader">Email <i class="fa-solid fa-arrow-down-short-wide"></i></th>
        <th class="sorting_1" class="sortheader">Contact Number <i class="fa-solid fa-arrow-down-short-wide"></i></th>
        <th class="sorting_1" class="sortheader">Team <i class="fa-solid fa-arrow-down-short-wide"></i></th>
    </thead>
    <tbody>
    @php
    if(count($result) > 0){
@endphp
                         @foreach($result as $key => $player )
                           
      
                        <tr role="row" class="even"> 
                            <th class="sorting_1" style="text-align:left!important"><a>{{$key +1}}</a></th> 
                            <th class="sorting_1" style="text-align:left!important"><a href="{{ url('playerview/' . $player['id']) }}">{{$player['id']}}</a></th> 
                            <th style="text-align:left!important"><a href="{{ url('playerview/' . $player['id']) }}">
                             {{$player['fullname']}} </a></th>
                            <th style="text-align:left!important"><a href="{{ url('playerview/' . $player['id']) }}"> {{$player['email']}} </a></th>
                            <th style="text-align:left!important"><a href="{{ url('playerview/' . $player['id']) }}"> {{$player['contact']}} </a></th>
                                    <th><table>
                        <tbody><tr class="even">
                            <td>
                            <img src="https://eoscl.ca/admin/public/Team/{{$player['team_id']}}.png" class="img-responsive img-circle" style="width: 20px; height: 20px;">
                                    </td>
                                        <td style="text-align:left!important">&nbsp;{{$player['name']}}</td>
                                    </tr>
                                </tbody></table></th>
                        </tr>
                         @endforeach

@php
}
@endphp
            </tbody>
            </table>
            
            </div>
            </div>
            	         	</div>

        </div>
    </div>

<div id="fb-root"></div>

 <script type="text/javascript" src="../utilsv2/js/duplicate.js"></script>
 <script type="text/javascript" src="../utilsv2/js/forms.js"></script>

  
        
    </body>
</html>
@stop
