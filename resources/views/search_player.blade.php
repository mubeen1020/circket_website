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
                                   <input type="text" id="teamName" name="teamName" class="form-control" style="width:100%;" value=""
                                   
									onblur="if(this.value==='%')this.value='';"
									
                                   >
                                </div>
                            </div>
                        </div>
                    </div>
                       <!--                      <div class="col-lg-4">
	               		 <div class="row">
	               		 	<div class="col-lg-5">
		                    	<div class="form-text">
		                    	   <h5>Team Name<span style="color: brown">**</span></h5>
                                </div>
               				</div>
               				<div class="col-lg-7">
                                 <div class="form-in">
               						<select name="teamName" id="teamName" class="form-control">
               							<option value=""></option>
               						</select>
                                 </div>
                             </div>
               			 </div>
               		</div> -->
                    
                    
                  </div>
                
                <div class="row">
                
                <div class="col-lg-4">
	               		 <div class="row">
	               		 	<div class="col-lg-5">
		                    	<div class="form-text">
                                    <h5>CC Player ID<span style="color: brown">**</span></h5>
                                </div>
		                    </div>
	               		 	 <div class="col-lg-7">
                                 <div class="form-in">
                                 	<input type="number" name="playerCCId" id="playerCCId" value="" class="form-control" style="width:100%;" >
                                 </div>
                             </div>
	               		 </div>
               		</div>
               		
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
                                        <option value="M" >Male</option>
                                        <option value="F" >Female</option>
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
               						<select name="internalClub" id="internalClub" class="form-control">
               						<option value = "" ></option>
               						<option value ="11 Aces Cricket Club">11 Aces Cricket Club</option>
               							<option value ="3FG">3FG</option>
               							<option value ="6IX Vandals Cricket Club">6IX Vandals Cricket Club</option>
               							<option value ="6ixers CC">6ixers CC</option>
               							<option value ="820 CC">820 CC</option>
               							<option value ="A&A CC">A&A CC</option>
               							<option value ="Alpha XI Cricket Club">Alpha XI Cricket Club</option>
               							<option value ="Amazon Kings CC">Amazon Kings CC</option>
               							<option value ="Asian Hawks Cricket Club">Asian Hawks Cricket Club</option>
               							<option value ="Avengers Cricket Club">Avengers Cricket Club</option>
               							<option value ="AZAAD Cricket Club">AZAAD Cricket Club</option>
               							<option value ="Bindaas Cricket Club">Bindaas Cricket Club</option>
               							<option value ="Black Lions">Black Lions</option>
               							<option value ="Blue Sharks Cricket Club">Blue Sharks Cricket Club</option>
               							<option value ="BoomBoom Cricket Club">BoomBoom Cricket Club</option>
               							<option value ="Brampton Avengers Elites CC">Brampton Avengers Elites CC</option>
               							<option value ="Brampton Fighters">Brampton Fighters</option>
               							<option value ="Brampton Knights Cricket Club">Brampton Knights Cricket Club</option>
               							<option value ="Brampton Pacers">Brampton Pacers</option>
               							<option value ="Brampton Strikers Cricket Club">Brampton Strikers Cricket Club</option>
               							<option value ="Bravehearts CC">Bravehearts CC</option>
               							<option value ="Brothers XI">Brothers XI</option>
               							<option value ="BTown XI CRICKET CLUB">BTown XI CRICKET CLUB</option>
               							<option value ="Burlington Warriors Cricket Club">Burlington Warriors Cricket Club</option>
               							<option value ="Cambridge Rising Stars">Cambridge Rising Stars</option>
               							<option value ="Canada Blasters">Canada Blasters</option>
               							<option value ="Canadian Cosmos Cricket Club">Canadian Cosmos Cricket Club</option>
               							<option value ="Canadian Tuskers">Canadian Tuskers</option>
               							<option value ="Cardinal Sports Academy">Cardinal Sports Academy</option>
               							<option value ="Centurion Cricket Club">Centurion Cricket Club</option>
               							<option value ="CIC Spartans Cricket Club">CIC Spartans Cricket Club</option>
               							<option value ="Clifton Boys Cricket Club">Clifton Boys Cricket Club</option>
               							<option value ="Cosmos">Cosmos</option>
               							<option value ="Costco">Costco</option>
               							<option value ="CricRealty Cricket Club">CricRealty Cricket Club</option>
               							<option value ="DesertStorm Cricket Club">DesertStorm Cricket Club</option>
               							<option value ="Dhurham Maple Leaf">Dhurham Maple Leaf</option>
               							<option value ="DOABA Cricket Club">DOABA Cricket Club</option>
               							<option value ="Dubai Spartans Cricket club">Dubai Spartans Cricket club</option>
               							<option value ="Even Eleven Cricket Club">Even Eleven Cricket Club</option>
               							<option value ="Falcon Strikers">Falcon Strikers</option>
               							<option value ="Falcons cc">Falcons cc</option>
               							<option value ="FARMERS CC">FARMERS CC</option>
               							<option value ="Five Rivers Cricket Club">Five Rivers Cricket Club</option>
               							<option value ="Furious XI">Furious XI</option>
               							<option value ="Fusion Cricket Club">Fusion Cricket Club</option>
               							<option value ="Ghaznavi Cricket Club">Ghaznavi Cricket Club</option>
               							<option value ="Gladiators">Gladiators</option>
               							<option value ="GTA Strikers">GTA Strikers</option>
               							<option value ="GTA Sultans">GTA Sultans</option>
               							<option value ="GTA United Cricket Club ">GTA United Cricket Club </option>
               							<option value ="Guardians Cricket Club">Guardians Cricket Club</option>
               							<option value ="Gujarat Giants Cricket Club">Gujarat Giants Cricket Club</option>
               							<option value ="Gujrat Lions Cricket Club">Gujrat Lions Cricket Club</option>
               							<option value ="Hawks United">Hawks United</option>
               							<option value ="Highland Creek Cricket Club">Highland Creek Cricket Club</option>
               							<option value ="Imperials Cricket Club">Imperials Cricket Club</option>
               							<option value ="Indo-Pak United">Indo-Pak United</option>
               							<option value ="International XI">International XI</option>
               							<option value ="Islanders">Islanders</option>
               							<option value ="J. Junaid Jamshed">J. Junaid Jamshed</option>
               							<option value ="Jalsa Knights Cricket Club">Jalsa Knights Cricket Club</option>
               							<option value ="Jammu Elite">Jammu Elite</option>
               							<option value ="Jammu Kings XI">Jammu Kings XI</option>
               							<option value ="Jammu Lions">Jammu Lions</option>
               							<option value ="Jarry Strikers">Jarry Strikers</option>
               							<option value ="Jazba Cricket Club">Jazba Cricket Club</option>
               							<option value ="JK Knight Riders Cricket Club">JK Knight Riders Cricket Club</option>
               							<option value ="Kitchener Bulls Cricket Club">Kitchener Bulls Cricket Club</option>
               							<option value ="Kitchener Kings">Kitchener Kings</option>
               							<option value ="Knights Cricket Club">Knights Cricket Club</option>
               							<option value ="Kryptonite Cricket Club">Kryptonite Cricket Club</option>
               							<option value ="Lion Kings Cricket Club">Lion Kings Cricket Club</option>
               							<option value ="MAM-XI">MAM-XI</option>
               							<option value ="Markhor Raiders">Markhor Raiders</option>
               							<option value ="Masterz">Masterz</option>
               							<option value ="Mavericks Cricket Club">Mavericks Cricket Club</option>
               							<option value ="Melbourne Cricket Club">Melbourne Cricket Club</option>
               							<option value ="Mighty Hawks">Mighty Hawks</option>
               							<option value ="Milton MARVELS Cricket Club">Milton MARVELS Cricket Club</option>
               							<option value ="Milton Sunrisers">Milton Sunrisers</option>
               							<option value ="Mississauga Cricket League">Mississauga Cricket League</option>
               							<option value ="Mississauga Dolphins">Mississauga Dolphins</option>
               							<option value ="Mississauga East Cricket Club">Mississauga East Cricket Club</option>
               							<option value ="Mississauga Leopards cricket club">Mississauga Leopards cricket club</option>
               							<option value ="Mississauga Myrmidons Cricket Club">Mississauga Myrmidons Cricket Club</option>
               							<option value ="Mississauga Qalandars Cricket Club">Mississauga Qalandars Cricket Club</option>
               							<option value ="Mississauga Shaheens">Mississauga Shaheens</option>
               							<option value ="Mississauga South Cricket Club">Mississauga South Cricket Club</option>
               							<option value ="Mississauga Stallions Cricket Club">Mississauga Stallions Cricket Club</option>
               							<option value ="Mississauga Strike 11 Cricket Club">Mississauga Strike 11 Cricket Club</option>
               							<option value ="Mississauga Thunder Cricket Club">Mississauga Thunder Cricket Club</option>
               							<option value ="Mississauga West Cricket Club">Mississauga West Cricket Club</option>
               							<option value ="Nepalese Canadian CC">Nepalese Canadian CC</option>
               							<option value ="Niagara Cricket Club">Niagara Cricket Club</option>
               							<option value ="North Hawks">North Hawks</option>
               							<option value ="Northern Stars United">Northern Stars United</option>
               							<option value ="Odia Warriors">Odia Warriors</option>
               							<option value ="Oneness">Oneness</option>
               							<option value ="Ontario Patriots Cricket Club">Ontario Patriots Cricket Club</option>
               							<option value ="Ontario Stallions Cricket Club">Ontario Stallions Cricket Club</option>
               							<option value ="Panthera Cricket Club">Panthera Cricket Club</option>
               							<option value ="Panthers Cricket Club">Panthers Cricket Club</option>
               							<option value ="Patiala Shahi Panthers">Patiala Shahi Panthers</option>
               							<option value ="Peace Cricket Club">Peace Cricket Club</option>
               							<option value ="Phoenix Cricket Club">Phoenix Cricket Club</option>
               							<option value ="Prime Allstar">Prime Allstar</option>
               							<option value ="Punjab champions">Punjab champions</option>
               							<option value ="Punjab Cricket Club">Punjab Cricket Club</option>
               							<option value ="Punjab lions ">Punjab lions </option>
               							<option value ="Punjab Riders">Punjab Riders</option>
               							<option value ="Punjab United">Punjab United</option>
               							<option value ="Punjabi Royals Cricket Club">Punjabi Royals Cricket Club</option>
               							<option value ="Rangers 11 Cricket Club">Rangers 11 Cricket Club</option>
               							<option value ="Rank XI">Rank XI</option>
               							<option value ="RC99 Cricket Club">RC99 Cricket Club</option>
               							<option value ="Rockers">Rockers</option>
               							<option value ="Royal Canadians Cricket Club">Royal Canadians Cricket Club</option>
               							<option value ="Royal Panthers Toronto CC">Royal Panthers Toronto CC</option>
               							<option value ="Royal Tigers Cricket Club">Royal Tigers Cricket Club</option>
               							<option value ="Rude BoyZ Cricket Club">Rude BoyZ Cricket Club</option>
               							<option value ="SAUGA 6ERS CC ">SAUGA 6ERS CC </option>
               							<option value ="Sauga Cricket Club">Sauga Cricket Club</option>
               							<option value ="Sauga Rams">Sauga Rams</option>
               							<option value ="SG Lions">SG Lions</option>
               							<option value ="Singapore Cricket Association">Singapore Cricket Association</option>
               							<option value ="Singhs XI">Singhs XI</option>
               							<option value ="Sky Cricket Club">Sky Cricket Club</option>
               							<option value ="Sonu's Battalion cricket club">Sonu's Battalion cricket club</option>
               							<option value ="Star XI">Star XI</option>
               							<option value ="Stars Cricket Club">Stars Cricket Club</option>
               							<option value ="Sunrise Cricket Club">Sunrise Cricket Club</option>
               							<option value ="Super 11 Cricket Club">Super 11 Cricket Club</option>
               							<option value ="TDF Cricket Club">TDF Cricket Club</option>
               							<option value ="Team Gladiators Canada">Team Gladiators Canada</option>
               							<option value ="Telugu Titans Cricket Club">Telugu Titans Cricket Club</option>
               							<option value ="Thunderbolts CC">Thunderbolts CC</option>
               							<option value ="Titans Cricket Club">Titans Cricket Club</option>
               							<option value ="Toofan CC">Toofan CC</option>
               							<option value ="Toronto Blues Cricket Club">Toronto Blues Cricket Club</option>
               							<option value ="Toronto Champs">Toronto Champs</option>
               							<option value ="Toronto Jaguars Cricket Club">Toronto Jaguars Cricket Club</option>
               							<option value ="Toronto Knights Cricket Association">Toronto Knights Cricket Association</option>
               							<option value ="Toronto Markhors Cricket Club">Toronto Markhors Cricket Club</option>
               							<option value ="Toronto Mavericks Cricket Club">Toronto Mavericks Cricket Club</option>
               							<option value ="Toronto Metro Cricket Club">Toronto Metro Cricket Club</option>
               							<option value ="Toronto Peshwas">Toronto Peshwas</option>
               							<option value ="Toronto Renegades">Toronto Renegades</option>
               							<option value ="Toronto Titans Cricket Club">Toronto Titans Cricket Club</option>
               							<option value ="Toronto United Cricket Club">Toronto United Cricket Club</option>
               							<option value ="Toronto Wolves">Toronto Wolves</option>
               							<option value ="Toronto Zalmi">Toronto Zalmi</option>
               							<option value ="Trex Dynos">Trex Dynos</option>
               							<option value ="Tuk Tuk Cricket Club">Tuk Tuk Cricket Club</option>
               							<option value ="UK Chargers">UK Chargers</option>
               							<option value ="United Cricket Club">United Cricket Club</option>
               							<option value ="United Mississauga Smashers Cricket Club">United Mississauga Smashers Cricket Club</option>
               							<option value ="Universal Cricket Club">Universal Cricket Club</option>
               							<option value ="UZ Markhor">UZ Markhor</option>
               							<option value ="WarHawks Cricket Club">WarHawks Cricket Club</option>
               							<option value ="Warriors XI">Warriors XI</option>
               							<option value ="Watan Warriors">Watan Warriors</option>
               							<option value ="Whitby Sports Club">Whitby Sports Club</option>
               							<option value ="White Walkers Cricket Club">White Walkers Cricket Club</option>
               							<option value ="Yodhas Cricket Club">Yodhas Cricket Club</option>
               							<option value ="YORK CC">YORK CC</option>
               							<option value ="Yorkshire Cricket Club">Yorkshire Cricket Club</option>
               							<option value ="Young 11">Young 11</option>
               							<option value ="Yukti Cricket Club">Yukti Cricket Club</option>
               							<option value ="Zohaib Memorial Cricket Club">Zohaib Memorial Cricket Club</option>
               							<option value ="Zoroastrian Cricket Club">Zoroastrian Cricket Club</option>
               							</select>
                                 </div>
                             </div>
               			 </div>
               		</div>
               		
               		
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
                                 	<option value="">All</option>
										<option value="Right Handed Batsman"
											>Right Handed Batter</option>
										<option value="Left Handed Batsman"
											>Left Handed Batter</option>
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
               		</div>
               		</div>
               	<div class="row">
               	</div>
               	<div class="row">
               		<div class="col-lg-4">
	               		 <div class="row">
	               		 	<div class="col-lg-5">
		                    	<div class="form-text">
                                    <h5>Status</h5>
                                </div>
		                    </div>
	               		 	 <div class="col-lg-7">
                                 <div class="form-in">
                                        <select name="playerStatus" id="playerStatus" class="form-control">
	                             			<option value="">All</option>
											<option value="1" >Active</option>
											<option value="3" >In-Active</option>
										</select>
                                 </div>
                             </div>
	               		 </div>
               		</div>
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


                <table class="table sortable table-striped dataTable no-footer" id="playersData" role="grid"> 
                    <thead> 
                        <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="playersData" rowspan="1" colspan="1" aria-sort="ascending" aria-label="No : activate to sort column descending" style="width: 65px;"><a href="#" class="sortheader" onclick="ts_resortTable(this, 0);return false;">No <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th style="text-align: left !important; width: 470px;" class="sorting" tabindex="0" aria-controls="playersData" rowspan="1" colspan="1" aria-label="Player Name : activate to sort column ascending"><a href="#" class="sortheader" onclick="ts_resortTable(this, 1);return false;">Player Name <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th style="text-align: left !important; width: 174px;" class="sorting" tabindex="0" aria-controls="playersData" rowspan="1" colspan="1" aria-label="Player Role : activate to sort column ascending"><a href="#" class="sortheader" onclick="ts_resortTable(this, 2);return false;">Player Role <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th><th style="text-align: left !important; width: 356px;" class="spa sorting" tabindex="0" aria-controls="playersData" rowspan="1" colspan="1" aria-label="Team   : activate to sort column ascending"><a href="#" class="sortheader" onclick="ts_resortTable(this, 3);return false;">Team <span class="sortarrow">&nbsp;<img src="/utilsv2/images/arrow-none.gif" alt="↓"></span></a></th></tr> 
                    </thead> 
                    <tbody>  
@php
    if(count($result) > 0){
@endphp
                         @foreach($result as $player)


                        <tr role="row" class="even"> 
                            <th class="sorting_1">1</th> 
                            <th style="text-align:left!important"><a href="/MississaugaCricketLeague/viewPlayer.do?playerId=2659121&amp;clubId=2565"> {{$player['fullname']}} </a> <img alt="Verified" title="Verified" src="/utilsv2/images/ok.png" style="width: 16px;height: 16px;margin: 0px;">
                                </th>
                                <th style="text-align:left!important">All Rounder</th>
                                    <th><table>
                        <tbody><tr class="even">
                            <td>
                            <img src="https://cricclubs.com/documentsRep/teamLogos/24166e99-e7a3-42ec-9078-33110d9b82cb.jpeg" class="img-responsive img-circle" style="width: 20px; height: 20px;">
                                    </td>
                                        <td style="text-align:left!important">&nbsp;Mississauga Shaheens</td>
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

  
			<div id="dialog-confirm-TnC" title="Mississauga Cricket League - Terms and Conditions" style="display: none;">
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
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=745734118815722";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>    	
		
 <!-- For mobile share-->
<script type="text/javascript">
$(document).ready(function() {
	
/////// Mobile Install app Ends	
	var linkOfApp = "https://play.google.com/store/apps/details?id=com.cricclubs&hl=en_US";
	$("#open-app-confirm").dialog({
	      resizable: false,
	      autoOpen: false,
	      modal: true,
	      buttons: {
	        "Install": function() {
	        	window.location = linkOfApp;
	          $( this ).dialog( "close" );
	        },
	        "Never Ask": function() {
	        	localStorage.setItem("dontAskMobileApp", true);
	         	$( this ).dialog( "close" );
	        },
	        Cancel: function() {
	          $( this ).dialog( "close" );
	        }
	      }
	    });

	function openInApp(appStoreName){
		$("#shoqAppStoreName").html(appStoreName);
		$("#open-app-confirm").dialog("open");
	}
	function checkAndOpenForMobileUser() {
		  var userAgent = navigator.userAgent || navigator.vendor || window.opera;
		
		  if( userAgent.match( /iPad/i ) || userAgent.match( /iPhone/i ) || userAgent.match( /iPod/i ) )
		  {
			  linkOfApp = "https://apps.apple.com/us/app/cricclubs/id978682715"
			  openInApp('App Store');
		
		  }
		  else if( userAgent.match( /Android/i ) )
		  {
			  linkOfApp = 'https://play.google.com/store/apps/details?id=com.cricclubs&hl=en_US;';
			  openInApp('Play Store');
		  }
	} 
	
	 
/////// Mobile Install app Ends	
	
	$("#dialog-confirm-TnC" ).dialog({
	      resizable: false,
	      autoOpen: false,
	      modal: true,
	      width : '80%'
	    });
	

var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
$(".calendarBox").datepicker();



 $(document).on("click", '.whatsapp', function() {
        if( isMobile.any() ) {
            var text = document.title;
            var url = window.location.href;
            var message = encodeURIComponent(text) + " - " + encodeURIComponent(url);
            var whatsapp_url = "whatsapp://send?text=" + message;
            window.location.href = whatsapp_url;
        } else {
            alert("Please share this article in mobile device");
        }
    });
	
});
function showTerms(){
  	$("#dialog-confirm-TnC").dialog("open");
  }
function mobileFacebookShare(){
	window.open('https://www.facebook.com/sharer/sharer.php?u='+window.location.href , 'facebookWindow', 'width=600, height=300, left=64, top=44, scrollbars, resizable'); 
}
function mobileTwitterShare(){
	window.open('https://twitter.com/intent/tweet?url='+window.location.href+'&text='+document.title , 'twitterWindow', 'width=600, height=300, left=64, top=44, scrollbars, resizable'); 
}
function mobileGoogleShare(){
	window.open('https://plus.google.com/share?url='+window.location.href , 'googleWindow', 'width=600, height=300, left=64, top=44, scrollbars, resizable'); 
}
function mobileMailShare(){
	window.location.href = 'mailto:?Subject='+ document.title+'&Body='+window.location.href; 
}

var addthis_share = {
		   url: window.location.href,
		   title: document.title
		}
function getURLOfPage(){
	return window.location.href;
}

function getTitleofPage(){
	return document.title;
}

function resizeScroll(){
	$('html').getNiceScroll().remove();
	var nice = $("html").niceScroll({zindex:1000000,cursorborder:"",cursorborderradius:"2px",cursorwidth:"14px",cursorcolor:"#191919",cursoropacitymin:.5}); 
}	
$(document).ready(function (){
	
	   
		    
	 	});
	 	
	 	

</script>
 <script type="text/javascript" src="../utilsv2/js/duplicate.js"></script>
 <script type="text/javascript" src="../utilsv2/js/forms.js"></script>

  
        
    </body>
</html>
@stop
