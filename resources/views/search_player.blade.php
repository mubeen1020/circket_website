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
               		
               		
<!--                		 <div class="col-lg-4">
	               		 <div class="row">
	               		 	<div class="col-lg-5">
		                    	<div class="form-text">
                                    <h5>Batting Style</h5>
                                </div>
		                    </div>
	               		 	 <div class="col-lg-7">
                                 <div class="form-in">
                                 	<select name="battingStyle" id="battingStyle" class="form-control">
                                 	<option value="">All</option>
										<option value="Right Handed Batsman"
											>Right Handed Batter</option>
										<option value="Left Handed Batsman"
											>Left Handed Batter</option>
									</select>
                                 </div>
                             </div>
	               		 </div>
               		</div> -->
                
<!--                 <div class="col-lg-4">
	               		 <div class="row">
	               		 	<div class="col-lg-5">
		                    	<div class="form-text">
                                    <h5>Bowling Style</h5>
                                </div>
		                    </div>
	               		 	 <div class="col-lg-7">
                                 <div class="form-in">
                                 		
                                        <select name="bowlingStyle" id="bowlingStyle" class="form-control">
	                             			<option value="">All</option>
											<option value="Right Arm Medium"
												>Right Arm Medium</option>
											<option value="Right Arm Fast"
												>Right Arm Fast</option>
											<option value="Right Arm Off Spin"
												>Right Arm Off Spin</option>
											<option value="Right Arm Leg Spin"
												>Right Arm Leg Spin</option>
											<option value="Left Arm Fast"
												>Left Arm Fast</option>
											<option value="Left Arm Medium"
												>Left Arm Medium</option>
											<option value="Left Arm Off Spin"
												>Left Arm Off Spin</option>
											<option value="Left Arm Leg Spin"
												>Left Arm Leg Spin</option>
										</select>
                                 </div>
                             </div>
	               		 </div>
               		</div> -->
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
                <div id="playersData_wrapper" class="dataTables_wrapper no-footer"><div class="dt-buttons">          <button class="dt-button buttons-copy buttons-html5" tabindex="0" aria-controls="playersData" type="button"><span>Copy</span></button> <button class="dt-button buttons-csv buttons-html5" tabindex="0" aria-controls="playersData" type="button"><span>CSV</span></button> <button class="dt-button buttons-excel buttons-html5" tabindex="0" aria-controls="playersData" type="button"><span>Excel</span></button> <button class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="playersData" type="button"><span>PDF</span></button> <button class="dt-button buttons-print" tabindex="0" aria-controls="playersData" type="button"><span>Print</span></button> </div>


                <table class="table1 sortable1 table-striped1 dataTable1 no-footer1" id="playersData" > 
                    <thead> 
                        <tr role="row"><th >No </a></th>

                        <th style="text-align: left !important; width: 470px;" class="sorting" tabindex="0" aria-controls="playersData" rowspan="1" colspan="1" aria-label="Player Name : activate to sort column ascending"><a href="#" class="sortheader" onclick="ts_resortTable(this, 1);return false;">Player Name <span class="sortarrow">&nbsp;</span></a></th>


                        <th style="text-align: left !important; width: 356px;" class="spa sorting" tabindex="0" aria-controls="playersData" rowspan="1" colspan="1" aria-label="Team   : activate to sort column ascending"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Team <span class="sortarrow">&nbsp;</span></a></th></tr> 
                    </thead> 
                    <tbody>  
@php
    if(count($result) > 0){
@endphp
                         @foreach($result as $player)


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

                            @endforeach

                            @php
                    }
                    @endphp

                            </tbody>
                </table></div>
            </div>
            	         	</div>

        </div>
    </div>
  
    <script type="text/javascript">
        
    $(document).ready(function() {
    	console.log("opern");
        $('#playersData').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
            iDisplayLength: -1
            
        });
    });

    $(document).on("click", ".pdfBtn", function(){
    	console.log("opern");
        $('.buttons-pdf').click();
    });
    $(document).on("click", ".excelBtn", function(){
        $('.buttons-excel').click();
    });
    $(document).on("click", ".csvBtn", function(){
        $('.buttons-csv').click();
    });
    $(document).on("click", ".printBtn", function(){
        $('.buttons-print').click();
    });
</script>

  
			<div id="dialog-confirm-TnC" title="" style="display: none;">
								  </div>
							<div id="open-app-confirm" title="Install App?" style="display: none;">
	Install Cricclubs App for better experince ? It will Redirect to <span id="shoqAppStoreName"> </span>
</div>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
	<script async="async" type="text/javascript" src="https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5400c8d21856f56e"></script>
	<!-- For mobile share-->
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push([ '_setAccount', 'UA-22738381-1' ]);
	_gaq.push([ '_trackPageview' ]);

	(function() {
		var ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl'
				: 'http://www')
				+ '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);
	})();
</script>


<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '802686347040044');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=802686347040044&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"mluzr1O7kI20L7", domain:"cricclubs.com",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://certify-js.alexametrics.com/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="https://certify.alexametrics.com/atrk.gif?account=mluzr1O7kI20L7" style="display:none" height="1" width="1" alt="" /></noscript>
<!-- End Alexa Certify Javascript -->

<script type="text/javascript">
	window.fbAsyncInit = function() {
	  FB.init({
	    appId      : '529159335529995',
	    cookie     : true,  // enable cookies to allow the server to access 
	                        // the session
	    xfbml      : true,  // parse social plugins on this page
	    version    : 'v2.6' // use version 2.1
	  });
	
	  // Now that we've initialized the JavaScript SDK, we call 
	  // FB.getLoginStatus().  This function gets the state of the
	  // person visiting this page and can return one of three states to
	  // the callback you provide.  They can be:
	  //
	  // 1. Logged into your app ('connected')
	  // 2. Logged into Facebook, but not your app ('not_authorized')
	  // 3. Not logged into Facebook and can't tell if they are logged into
	  //    your app or not.
	  //
	  // These three cases are handled in the callback function.
	
/*  						  FB.getLoginStatus(function(response) {
	    statusChangeCallback(response);
	  });  */
	
	  };
	
</script>
<div id="fb-root"></div>

 <script type="text/javascript" src="../utilsv2/js/duplicate.js"></script>
 <script type="text/javascript" src="../utilsv2/js/forms.js"></script>

  
        
    </body>
</html>
@stop
