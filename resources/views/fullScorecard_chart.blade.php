@extends('default')
@section('content')

<div class="container p-sm-0">
	<div class="profile-in-container">
       
       <table style="width: 100%; margin-bottom: 10px;text-align: center;">
	<tbody><tr>
		<td><a class="show-phone" href="#" onclick="javascript:mobileFacebookShare();return false;"> <img src="/utilsv2/images/fb_new.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileTwitterShare();return false;"><img src="/utilsv2/images/twi.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileGoogleShare(); return false;"><img src="/utilsv2/images/goo.png"></a></td>
		<td><a class="show-phone" href="#" onclick="javascript:mobileMailShare(); return false;"><img width="40" src="/utilsv2/images/mail.png"></a></td>
		<td><a class="show-phone whatsapp"><img src="/utilsv2/images/whatsapp.png"></a></td>
	</tr>
</tbody></table><div class="show-phone">
			</div>

       	<div class="score-tab">            
           	<div class="complete-list">
           	 	<div class="panel with-nav-tabs panel-default">
                       <div class="panel-heading score-tabs">
                           <ul class="nav nav-tabs">
  							<li><a href="{{ route('balltoballScorecard', $id) }}" >Ball By Ball</a></li>
							<li><a href="{{ route('fullScorecard', $id) }}" >Full Scorecard</a></li>
							<li><a href="{{ route('fullScorecard_overbyover', $id) }}" >Over by Over Score</a></li>
							<li class="active"><a href="{{ route('fullScorecard_chart', $id) }}" >Charts</a></li>
							</ul>
                       </div>
                   </div>
               </div>
           </div>
                       
	<div class="match-content">
		<div class="row">
		
						
						<div class="col-sm-6">
												<div class="stat-image-all">
													<div class="border-heading">
														<h5>BATTING: RUNS PER INNINGS</h5>
													</div>



        										            <div class="col-lg-8">
		                                            <canvas id="userChart" class="rounded shadow"></canvas>
                  									</div>

											</div>
										</div>
						
						
					</div>
				</div>
				<!-- </div>
				</div> -->
				
</div>

</div>
@stop
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script> -->
<script type="text/javascript" src="{!! asset('utilsv2/js/chart.js') !!}"></script>
   

<script>
	$( document ).ready(function() {

console.log(@json($sum_inning_one))
		    // Data for the chart
        var data = {
            labels: @json($over),
            datasets: [
                {
                    label: "{{$teams_one}}",
                    data: @json($sum_inning_one), // Replace with your actual data for Dataset 1
                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                },
                {
                    label:  "{{$teams_two}}",
                    data: @json($sum_inning_two), // Replace with your actual data for Dataset 2
                    backgroundColor: "rgba(255, 99, 132, 0.5)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    borderWidth: 1
                }
            ]
        };

        // Configuration options
        var options = {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Create the chart
        var ctx = document.getElementById("userChart").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "bar",
            data: data,
            options: options
        });
});
</script>