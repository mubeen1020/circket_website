@extends('default')
@section('content')

<div class="container1 p-sm-01">
    <div class="profile-in-container1">
       
      <div class="show-phone">
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
                       
    <div class="match-content1">
        <div class="row">
        
                        
                        <div class="col-sm-6">
                                                <div class="stat-image-all">
                                                    <div class="border-heading">
                                                        <h5>BATTING: RUNS PER Over</h5>
                                                    </div>



                                                            <div class="col-lg-8">
                                                    <canvas id="userChart" class="rounded shadow"></canvas>
                                                    </div>

                                            </div>
                        </div>
                        
                        
            </div>
            <div class="row">
        
                        
                        <div class="col-sm-6">
                                                <div class="stat-image-all">
                                                    <div class="border-heading">
                                                        <h5>BATTING: RUNS PER INNINGS</h5>
                                                    </div>



                                                            <div class="col-lg-8">
                                                    <canvas id="chartline_worm" class="rounded shadow"></canvas>
                                                    </div>

                                            </div>
                        </div>
                        
                        
            </div> <!--- row end -->
            <div class="row">
        
                        
                        <div class="col-sm-6">
                                                <div class="stat-image-all">
                                                    <div class="border-heading">
                                                        <h5>Type of Runs</h5>
                                                    </div>



                                                            <div class="col-lg-8">
                                                    <canvas id="charttype_of_run" class="rounded shadow"></canvas>
                                                    </div>

                                            </div>
                        </div>
                        
                        
            </div><!--- row end -->
            <div class="row">
                <div class="col-sm-6" style="height: 100%;">
                                                <div class="stat-image-all">
                                                    <div class="border-heading">
                                                        <h5>batsman </h5>
                                                    </div>



                                            <div class="col-lg-8" style="height: 100%;">
                                                    <canvas id="chartt_player_1" class="rounded shadow"></canvas>
                                                    </div>

                                            </div>
                        </div>
                <div class="col-sm-6" style="height: 100%;">
                                                <div class="stat-image-all">
                                                    <div class="border-heading">
                                                        <h5>Type of Runs</h5>
                                                    </div>



                                                <div class="col-lg-8" style="height: 100%;">
                                                    <canvas id="chartt_player_2" class="rounded shadow"></canvas>
                                                    </div>

                                            </div>
                        </div>            
            </div><!--- row end -->

            <div class="row">
                <div class="col-sm-6" style="height: 100%;">
                                                <div class="stat-image-all">
                                                    <div class="border-heading">
                                                        <h5>Bowler </h5>
                                                    </div>



                                            <div class="col-lg-8" style="height: 100%;">
                                                    <canvas id="chartt_bowler_1" class="rounded shadow"></canvas>
                                                    </div>

                                            </div>
                        </div>
                <div class="col-sm-6" style="height: 100%;">
                                                <div class="stat-image-all">
                                                    <div class="border-heading">
                                                        <h5>Bowler</h5>
                                                    </div>



                                                <div class="col-lg-8" style="height: 100%;">
                                                    <canvas id="chartt_bowler_2" class="rounded shadow"></canvas>
                                                    </div>

                                            </div>
                        </div>            
            </div><!--- row end -->

            <div class="row">
                <div class="col-sm-6" style="height: 100%;">
                                                <div class="stat-image-all">
                                                    <div class="border-heading">
                                                        <h5>Extra </h5>
                                                    </div>



                                            <div class="col-lg-8" style="height: 100%;">
                                                    <canvas id="chartt_extra_pie_1" class="rounded shadow"></canvas>
                                                    </div>

                                            </div>
                        </div>

                                        <div class="col-sm-6" style="height: 100%;">
                                                <div class="stat-image-all">
                                                    <div class="border-heading">
                                                        <h5>Extra </h5>
                                                    </div>



                                            <div class="col-lg-8" style="height: 100%;">
                                                    <canvas id="chartt_extra_pie_2" class="rounded shadow"></canvas>
                                                    </div>

                                            </div>
                        </div>
          
            </div><!--- row end -->
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
//////////////////////////////////////// line chat cumutative over by over/////////////////
        const xValues = [50,60,120,130,150];

var data = {
            labels: @json($over),
            datasets: [
                {
                    label: xValues,
                    data: @json($cumulativeScores_ining1), // Replace with your actual data for Dataset 1
                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                },
                {
                    label:  "{{$teams_two}}",
                    data: @json($cumulativeScores_ining2), // Replace with your actual data for Dataset 2
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

        var ctx_line = document.getElementById("chartline_worm").getContext("2d");
        // var myChart = new Chart(ctx, {
        //     type: "line",
        //     data: data,
        //     options: options
        // });



        // const xValues = [50,60,120,130,150];
const yValues = [7,18,28,39,49,59,60,71,84,94,115];

new Chart(ctx_line, {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{
      fill: false,
      lineTension: 0,
      backgroundColor: "rgba(0,0,255,1.0)",
      borderColor: "rgba(0,0,255,   0.1)",
      data: yValues
    },{
      data: [1600,1700,1700,1900,2000,2700,4000,5000,6000,7000],
      borderColor: "green",
      fill: false
    }]
  },
  options: {
    legend: {display: false},
    scales: {
      yAxes: [{ticks: {min: 6, max:16}}],
    }
  }
});
///////////////// horizantal bar 

const DATA_COUNT = 7;
const NUMBER_CFG = {count: DATA_COUNT, min: -0, max: 100};

const labels_bar = @json($over);
const data_bar = {
  labels: labels_bar,
  datasets: [
    {
      label: 'Dataset 1',
      data: xValues,
      borderColor: 'red',

    },
    {
      label: 'Dataset 2',
      data: yValues,
        backgroundColor: "rgba(255, 99, 132, 0.5)",
                    borderColor: "rgba(255, 99, 132, 1)",

    }
  ]
};


  var options_bar= {
    indexAxis: 'Y',
    // Elements options apply to all of the options unless overridden in a dataset
    // In this case, we are setting the border of each horizontal bar to be 2px wide
    elements: {
      bar: {
        borderWidth: 1,
      }
    },
    scales: {
            x: {
                categorySpacing: 1.8, // Adjust the spacing as needed
            }
        },
    responsive: true,
    
  };


var ctx_bar = document.getElementById("charttype_of_run").getContext("2d");

  
        const runType1Data = {!! json_encode($ran_type_1) !!};
        console.log(runType1Data);
        const runType2Data = {!! json_encode($ran_type_2) !!};
        let runsLabels = runType1Data.map(score => score.runs);
        runsLabels = runsLabels.map(label => label + 's'); // Concatenate 's' with runs

        const runType1TotalRuns = runType1Data.map(score => score.total_runs);
        const runType2TotalRuns = runType2Data.map(score => score.total_runs);
        console.log(runType1TotalRuns);
        console.log("runType1TotalRuns")


        var myChart_bar = new Chart(ctx_bar, {
                    type: 'horizontalBar',
                    data: {
                        labels: runsLabels,
                        datasets: [{
                            label: '{{$teams_one}}',
                            data: runType1TotalRuns,
                            backgroundColor: 'blue',
                            borderWidth: 1,
                        },
                        {
                            label: '{{$teams_two}}',
                            data: runType2TotalRuns,
                            backgroundColor: 'red',
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                beginAtZero: true,
                            },
                            y: {
                                beginAtZero: true,
                            }
                        }
                    },
                });



//////////////////////////// Batman inning 1


var ctx_bar_batsman1 = document.getElementById("chartt_player_1").getContext("2d");

  
        const ran_batsman_1Data = {!! json_encode($ran_batsman_1) !!};
        console.log(ran_batsman_1Data);

        // runsLabels = runsLabels.map(label => label + 's'); // Concatenate 's' with runs

        const runType1TotalRuns_batsman_1 = ran_batsman_1Data.map(score => score.total_runs);
        const runType1balls_batsman_1 = ran_batsman_1Data.map(score => score.balls);
        const runsLabels_batsman_1 = ran_batsman_1Data.map(score => score.fullname);
        console.log(runsLabels_batsman_1);
        console.log("runType1TotalRuns")


        var myChart_bar = new Chart(ctx_bar_batsman1, {
                    type: 'horizontalBar',
                    data: {
                        labels: runsLabels_batsman_1,
                        datasets: [{
                            label: 'Runs',
                            data: runType1TotalRuns_batsman_1,
                            backgroundColor: 'blue',
                            borderWidth: 1,
                        },
                        {
                            label: 'Balls',
                            data: runType1balls_batsman_1,
                            backgroundColor: 'red',
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                beginAtZero: true,
                            },
                            y: {
                                beginAtZero: true,
                            }
                        }
                    },
                });

//////////////////////////// Batman inning 1


var ctx_bar_batsman2 = document.getElementById("chartt_player_2").getContext("2d");

  
        const ran_batsman_2Data = {!! json_encode($ran_batsman_2) !!};
        console.log("ran_batsman_2Data====1");
        console.log(ran_batsman_2Data);
        console.log("ran_batsman_2Data====2");

        // runsLabels = runsLabels.map(label => label + 's'); // Concatenate 's' with runs

        const runType1TotalRuns_batsman_2 = ran_batsman_2Data.map(score => score.total_runs);
        const runType1balls_batsman_2 = ran_batsman_2Data.map(score => score.balls);
        const runsLabels_batsman_2 = ran_batsman_2Data.map(score => score.fullname);
        console.log(runsLabels_batsman_2);
        console.log("runType1TotalRuns")


        var myChart_bar = new Chart(ctx_bar_batsman2, {
                    type: 'horizontalBar',
                    data: {
                        labels: runsLabels_batsman_2,
                        datasets: [{
                            label: 'Runs',
                            data: runType1TotalRuns_batsman_2,
                            backgroundColor: 'blue',
                            borderWidth: 1,

                        },{
                            label: 'Balls',
                            data: runType1balls_batsman_2,
                            backgroundColor: 'red',
                            borderWidth: 1,

                        }]
                    },
                    options: {
                            barPercentage: 0.1, // Adjust the gap between bars
                            categoryPercentage: 0.2, 
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                beginAtZero: true,
                            },
                            y: {
                                beginAtZero: true,
                            }
                        },
                        plugins: {
                    legend: {
                        position: 'right',
                    },
                    barSpacing: {
                        desiredBarSpacing: 1, // Adjust the desired gap between bars in pixels
                    },
                },
                    },
                });
        /////////////////////////// bowler RUNS

var ctx_bar_bowler_1 = document.getElementById("chartt_bowler_1").getContext("2d");

  
        const ran_bowler_1Data = {!! json_encode($ran_bowler_1) !!};
        console.log(ran_bowler_1Data);

        // runsLabels = runsLabels.map(label => label + 's'); // Concatenate 's' with runs

        const runType1TotalRuns_bowler_1 = ran_bowler_1Data.map(score => score.total_runs);
        const runType1balls_bowler_1 = ran_bowler_1Data.map(score => score.balls);
        const runsLabels_bowler_1 = ran_bowler_1Data.map(score => score.fullname);
        console.log(runsLabels_bowler_1);
        console.log("runType1TotalRuns")


        var myChart_bar = new Chart(ctx_bar_bowler_1, {
                    type: 'horizontalBar',
                    data: {
                        labels: runsLabels_bowler_1,
                        datasets: [{
                            label: 'Runs',
                            data: runType1TotalRuns_bowler_1,
                            backgroundColor: 'blue',
                            borderWidth: 1,
                        },
                        {
                            label: 'Balls',
                            data: runType1balls_bowler_1,
                            backgroundColor: 'red',
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                beginAtZero: true,
                            },
                            y: {
                                beginAtZero: true,
                            }
                        }
                    },
                });

//////////////////////////// bowler inning 2


var ctx_bar_bowler2 = document.getElementById("chartt_bowler_2").getContext("2d");

  
        const ran_bowler_2Data = {!! json_encode($ran_bowler_2) !!};
        console.log("ran_batsman_2Data====1");
        console.log(ran_bowler_2Data);
        console.log("ran_batsman_2Data====2");

        // runsLabels = runsLabels.map(label => label + 's'); // Concatenate 's' with runs

        const runType1TotalRuns_bowler_2 = ran_bowler_2Data.map(score => score.total_runs);
        const runType1balls_bowler_2 = ran_bowler_2Data.map(score => score.balls);
        const runsLabels_bowler_2 = ran_bowler_2Data.map(score => score.fullname);
        console.log(runsLabels_bowler_2);
        console.log("runType1TotalRuns")


        var myChart_bar = new Chart(ctx_bar_bowler2, {
                    type: 'horizontalBar',
                    data: {
                        labels: runsLabels_bowler_2,
                        datasets: [{
                            label: 'Runs',
                            data: runType1TotalRuns_bowler_2,
                            backgroundColor: 'blue',
                            borderWidth: 1,

                        },{
                            label: 'Balls',
                            data: runType1balls_bowler_2,
                            backgroundColor: 'red',
                            borderWidth: 1,

                        }]
                    },
                    options: {
                            barPercentage: 0.1, // Adjust the gap between bars
                            categoryPercentage: 0.2, 
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                beginAtZero: true,
                            },
                            y: {
                                beginAtZero: true,
                            }
                        },
                        plugins: {
                    legend: {
                        position: 'right',
                    },
                    barSpacing: {
                        desiredBarSpacing: 1, // Adjust the desired gap between bars in pixels
                    },
                },
                    },
                });
        ///////////// Bolwer run end 
        ///////////////////// Extra//////////////
         const extraData = {!! json_encode($extra_runs_1) !!};

        var chartt_extra_pie = document.getElementById("chartt_extra_pie_1").getContext("2d");
              var chartId = new Chart(chartt_extra_pie, {
            type: 'pie',
            data: {
                labels: ['No Balls', 'Wide', 'Byes'],
                datasets: [{
                    data: [
                        extraData[0].NoBalls,
                        extraData[0].Wide,
                        extraData[0].Byes
                    ],
                    backgroundColor: ['blue', 'green', 'red'], // Set the background color for each segment
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                },
            },
        });


                       const extraData_2 = {!! json_encode($extra_runs_2) !!};
                       console.log(extraData_2)
                       console.log("extraData_2")

        var chartt_extra_pie_2 = document.getElementById("chartt_extra_pie_2").getContext("2d");
              var chartId = new Chart(chartt_extra_pie_2, {
            type: 'pie',
            data: {
                labels: ['No Balls', 'Wide', 'Byes'],
                datasets: [{
                    data: [
                        extraData_2[0].NoBalls,
                        extraData_2[0].Wide,
                        extraData_2[0].Byes
                    ],
                    backgroundColor: ['blue', 'green', 'red'], // Set the background color for each segment
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                },
            },
        });
 });


</script>