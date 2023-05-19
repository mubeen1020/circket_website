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
                                   <input type="text" id="firstName" name="fullname" class="form-control" style="width:100%;" value=""
                                   
									onblur="if(this.value==='%')this.value='';"
									
                                   >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4" style="display:none">
                    	<div class="row">
                            <div class="col-lg-5">
                                <div class="form-text">
                                    <h5>Last name <span style="color: brown">**</span></h5>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-in">
                                   <input type="text" id="lastName" name="lastName" class="form-control" style="width:100%;" value=""
                                   
									onblur="if(this.value==='%')this.value='';"
									
                                   >
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
                                    <option value ="{{$index}}">{{$team_only}}</option>
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
                                 	<input type="email" name="emailId" id="emailId" value="" class="form-control" style="width:100%;" >
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
                                 		<option value="">All</option>
                                        <option value="Male" >Male</option>
                                        <option value="Female" >Female</option>
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
                                    <option value ="{{$index}}">{{$club}}</option>
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
                <div id="playersData_wrapper" class="dataTables_wrapper no-footer"><div class="dt-buttons">          <button class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="playersData" type="button"><span>Copy</span></button> <button class="dt-button buttons-csv buttons-html5" tabindex="0" aria-controls="playersData" type="button"><span>CSV</span></button> <button class="dt-button buttons-excel buttons-html5" tabindex="0" aria-controls="playersData" type="button"><span>Excel</span></button> <button class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="playersData" type="button"><span>PDF</span></button> <button class="dt-button buttons-print" tabindex="0" aria-controls="playersData" type="button"><span>Print</span></button> </div>



<table>
    <thead>
        <th>car</th>
        <th>scire</th>
        <th>scire</th>
    </thead>
    <tbody>
    @php
    if(count($result) > 0){
@endphp
                         @foreach($result as $player)

      
                         
            </tbody>
            </table>






                <table class="table1 sortable1 table-striped1 dataTable1 no-footer1" id="playersData" > 
                    <thead> 
                        <tr role="row"><th >No </a></th>

                        <th style="text-align: left !important; width: 470px;" class="sorting" tabindex="0" aria-controls="playersData" rowspan="1" colspan="1" aria-label="Player Name : activate to sort column ascending"><a href="#" class="sortheader" >Player Name <span class="sortarrow">&nbsp;</span></a></th>


                        <th style="text-align: left !important; width: 356px;" class="spa sorting" tabindex="0" aria-controls="playersData" rowspan="1" colspan="1" aria-label="Team   : activate to sort column ascending"><a href="#" class="sortheader" >Team <span class="sortarrow">&nbsp;</span></a></th></tr> 
                    </thead> 
                    <tbody>  


                        <tr role="row" class="even"> 
                            <th class="sorting_1">1</th> 
                            <th style="text-align:left!important"><a href=""> {{$player['fullname']}} </a> 

                                </th>

                                    <th><table>
                        <tbody><tr class="even">
                            <td>
                            <img src="https://cricclubs.com/documentsRep/teamLogos/24166e99-e7a3-42ec-9078-33110d9b82cb.jpeg" class="img-responsive img-circle" style="width: 20px; height: 20px;">
                                    </td>
                                        <td style="text-align:left!important">&nbsp;{{$player['team_name']}}</td>
                                    </tr>
                                </tbody></table></th>
                        </tr>

                    

                            </tbody>
                </table></div>
            </div>
            	         	</div>

        </div>
    </div>
  
 

  
			<div id="dialog-confirm-TnC" title="" style="display: none;">
								  </div>
							<div id="open-app-confirm" title="Install App?" style="display: none;">
	Install Cricclubs App for better experince ? It will Redirect to <span id="shoqAppStoreName"> </span>
</div>
<!-- Go to www.addthis.com/dashboard to customize your tools -->

<div id="fb-root"></div>


  
        
    </body>
</html>
@stop
