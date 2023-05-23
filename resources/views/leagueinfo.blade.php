@extends('default')
@section('content')
<div class="holder point">
    	<div class="container">
        	<div class="point-table-all border">
            <div class="series-drop">
            	<div class="row">
                	<div class="col-sm-6">
                    	<div class="border-heading">
                            <h5>Event Ontario Softball Circket</h5>
                           
                        </div>
                    </div>
                        <div class="col-lg-6 hidden-phone">
                        
                       <div class="addthis_sharing_toolbox" style="height: 24px;text-align: right;"></div>
                        </div>
                        <div class="col-lg-12 text-right">
                        <div class="form-in sel" style="display: inline-block;">
                            <div class="dropdown">
                        	 </div>
							</div>
                        </div>
                    
                </div>
            </div>
<table style="width: 100%; margin-bottom: 10px;text-align: center;">
	<tbody><tr>
		<td><a class="show-phone" href="#" onclick="javascript:mobileFacebookShare();return false;"> <img src="/utilsv2/images/fb_new.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileTwitterShare();return false;"><img src="/utilsv2/images/twi.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileGoogleShare(); return false;"><img src="/utilsv2/images/goo.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileMailShare(); return false;"><img width="40" src="/utilsv2/images/mail.png"></a></td>
		<td><a class="show-phone whatsapp"><img src="/utilsv2/images/whatsapp.png"></a></td>
	</tr>
</tbody></table><div class="league">
            	<div class="row about-table">
                	<div class="col-sm-3">
                    	<div class="league-image">
                        	<a href="{{ route('home')}}"><img
                                                    src="{{ asset('utilsv2/img/others/eoscl-logo.png') }}" border="0"
                                                    style='width:137px;height:100px;'
                                                    class="img-responsive center-block img-circle" /></a>
										</div>
                    </div>
                   <div class="col-sm-9">
                    <table class="table" style="width: 100%;">
                    	<tbody><tr>
                    		<th valign="top">Address: </th>
                    		<th>20-2355 xorr Road west<br>Eoscl,Ontario<br>Canada - L909</th>
                    	</tr>
                    	<tr>
                    		<th>Established: </th>
                    		<th>2014</th>
                    	</tr>
                    	<tr>
                    		<th nowrap="nowrap">Current Series: </th>
                    		<th><a href="#">2023 EOSCL Season 8</a> </th>
                    	</tr>
                    	<tr>
                    		<th valign="top">About: </th>
                    		<th><strong>EOSCL</strong> <strong>- Event Ontario Softball Circket</strong> facilitates Peel Region Community Cricket Clubs and the School Cricket in the Peel District School Board and the Peel-Catholic District School Board.<br>&nbsp;<br><strong>Vision</strong>: The vision of Event Ontario Softball Circket is to promote interest and participation in the sport of cricket on a sustainable basis at all levels ? junior, adult, recreational, social, and competitive in Peel Region; empower members to adopt an active and healthy lifestyle as well as network with each other for games, jobs and business and promote multiculturalism in the society.<br>&nbsp;<br><strong>Goal</strong>: The goal of EOSCL is to become the central governing body for cricket and act as focal point for all the cricket needs in Peel Region.</th>
                    	</tr>
                    </tbody></table>
                  </div>
                    </div>
                </div>
            </div>
            </div>
        </div>



@stop