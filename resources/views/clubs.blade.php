@extends('default')
@section('content')
<div class="holder point ">
    	<div class="container p-0-sm">
        	<div class="point-table-all border about-table">
            <div class="series-drop">
            	<div class="row">
                	<div class="col-sm-8">
                    	<div class="border-heading" style="margin-bottom: 0px;">
                            <h5>Event Ontario Softball Circket
 Clubs</h5>
                        </div>
                    </div>
                    	<div class="col-sm-4">
                    	<div class="shareDiv hidden-phone">
							<!-- Go to www.addthis.com/dashboard to customize your tools -->
							<div class="addthis_sharing_toolbox" style="height: 24px; text-align: right;"></div>
						</div>
                    	</div>
                </div>
            </div>
            <div class="row" style="display: flex; justify-content: flex-end;">
						   <div class="col-sm-3 admins-drop col-xs-12">
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
</tbody></table><div class="table-responsive">
				<table class="table" id="anyid">
					<thead>
						<tr>
							<th style="width: 4%; ">#</th>
							<th nowrap="nowrap">Club Name</th>						
							<th nowrap="nowrap">Address</th>
							<th nowrap="nowrap">Email</th>							
							<th nowrap="nowrap">Admin 1 Name</th>
							<th nowrap="nowrap">Admin 2 Name</th>
							</tr>
					</thead>
					<tbody>
                    @foreach($clubs as $index => $club)
						<tr id="row107">						
							<td style="text-align: left;">{{$index+1}}</td>
							<td nowrap="nowrap"><a href="#">{{$club->clubname}}</a></td>
							<td align="left"><a href="#">{{$club->address}}</a></td>
							<td align="left"><a href="#">{{$club->email}}</a></td>							
							<td align="left"><a href="#">{{$club->owner}}</a></td>
							<td align="left"><a href="#"></a></td>
							</tr>
                            @endforeach
						
						</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>


@stop