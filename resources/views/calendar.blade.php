
@extends('default')
@section('content')


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />  
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />   -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>   -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>   -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>  
    <script>  
      
     $(document).ready(function() {  
          const jsObjects = @json($jsObjects);
          console.log(jsObjects);
      var date = new Date();  
      var d = date.getDate();  
      var m = date.getMonth();  
      var y = date.getFullYear();  
      
      var calendar = $('#calendar').fullCalendar({  
       editable: false,  
       header: {  
        left: 'prev,next today',  
        center: 'title',  
        right: 'month,agendaWeek,agendaDay'  
       },  
      
       events: jsObjects,  
      
       eventRender: function(event, element, view) {  
        if (event.allDay === 'true') {  
         event.allDay = true;  
        } else {  
         event.allDay = false;  
        }  
       },  
       selectable: false,  
       selectHelper: false,  
       select: function(start, end, allDay) {  
       var title = prompt('Event Title:');  
      
       if (title) {  
       var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");  
       var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");  
       $.ajax({  
           url: 'add_events.php',  
           data: 'title='+ title+'&start='+ start +'&end='+ end,  
           type: "POST",  
           success: function(json) {  
           alert('Added Successfully');  
           }  
       });  
       calendar.fullCalendar('renderEvent',  
       {  
           title: title,  
           start: start,  
           end: end,  
           allDay: allDay  
       },  
       true  
       );  
       }  
       calendar.fullCalendar('unselect');  
       },  
      
       editable: false,  
       eventDrop: function(event, delta) {  
       var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");  
       var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");  
       $.ajax({  
           url: 'update_events.php',  
           data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,  
           type: "POST",  
           success: function(json) {  
            alert("Updated Successfully");  
           }  
       });  
       },  
       eventClick: function(event) {  
        var decision = confirm("Do you really want to do that?");   
        if (decision) {  
        $.ajax({  
            type: "POST",  
            url: "delete_event.php",  
            data: "&id=" + event.id,  
             success: function(json) {  
                 $('#calendar').fullCalendar('removeEvents', event.id);  
                  alert("Updated Successfully");}  
        });  
        }  
        },  
       eventResize: function(event) {  
           var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");  
           var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");  
           $.ajax({  
            url: 'update_events.php',  
            data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,  
            type: "POST",  
            success: function(json) {  
             alert("Updated Successfully");  
            }  
           });  
        }  
         
      });  
        
     });  
      
    </script>  
    <style> 

     #calendar {  
      width: 650px;  
      margin: 0 auto;  
      }  
    </style>  
    <div class="score-top sp text-center">
            <div class="container"> 
                <div class="point-table-all sch">


    <div class="series-drop">
            <div class="row">
                    <div class="col-sm-6">
                        <div class="border-heading">
                            <h5 style=" text-align: left; ">Schedule</h5>
                        </div>
                    </div>
                </div>
    <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('fixturesCalendar')}}">
                @csrf
                <div class="row">
                 <div class="col-xs-6 col-sm-2">
                        <div class="dropdown">
                        <select name="year"  id="year" class="form-control" >
                                        <option value=""> Select Year(s)</option>
                                         @for ($year = date('Y'); $year >= 2015; $year--)
                                        <option value="{{ $year }}" >{{ $year }}</option>
                                        @endfor
                                    </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-2" title="Change Series">
                        <div class="dropdown">
                        
                        <select name="tournament"  id="tournament" class="form-control" >
                                        <option value=""> Select tournament(s)</option>
                                        @foreach($tournament as $tournament_id => $tournament_name)
                                    <option value="{{ $tournament_id }}">{{ $tournament_name }}</option>
                                       @endforeach
                                    </select>
                        </div>
                    </div>
                    <div class="col-sm-2 col-xs-6" title="Change Team" id="allTeams">
                    <div class="dropdown">
                    <select name="teams" id="teams" class="form-control">
                                <option value="">Select team(s)</option>
                                @foreach($header_teams as $team_id => $team_name)
                                    <option value="{{ $team_id }}">{{ $team_name }}</option>
                                @endforeach
                            </select>
                            </div>
                    </div>
                    
                    <div class="col-sm-2 col-xs-6" title="Change internal club">
                    <div class="dropdown">
                    <select name="club" id="club" class="form-control">
                                <option value="">Select Club</option>
                                @foreach($clubs as $index => $club)
                                    <option value ="{{$index}}">{{$club}}</option>
                                    @endforeach
                            </select>
                            </div>
                    </div>
                    <div class="col-sm-2 col-xs-6" title="Change Ground">
                    <div class="dropdown">
                                <select name="grounddata" id="grounddata" class="form-control">
                                <option value="">All Grounds</option>
                                @foreach($ground as $index => $ground_item)
                                    <option value ="{{$index}}">{{$ground_item}}</option>
                                    @endforeach
                            </select>
                            </div>
                    </div>                    
                   
                </div>

            </div>
            
            <div class="series-drop">
            
            <div class="row">
                
                    <div class="col-sm-2 col-xs-4" title="From Date">
                    <input type="text" name="created_at" autocomplete="off" placeholder="From Date" value="" align="top" class="calendarBox form-control hasDatepicker" id="created_at">
                    </div>
                    <div class="col-sm-2 col-xs-4" title="To Date">
                    <input type="text"  name="end_at" autocomplete="off" placeholder="To Date" value="" align="top" class="calendarBox form-control hasDatepicker" id="end_at">
                    </div>
                    <div class="col-sm-2 col-xs-4" title="Search Dates"> 
                        <button class="btn btn-primary" id="datesSearch">Search Dates</button>
                    </div>
                    
                    <div class="col-sm-2 col-xs-6" style=" float: right; " title="Search Dates">
                    </div>
                    
                </div>
            </div>
            </form>
    
    
      
    <!-- <div class="score-top sp text-center"> -->
        <!-- <div class="container"> -->
             <h2> Canlendar View</h2>  
             <br/>  
             <div id='calendar'></div>  
            
    </div></div>
</div>
    @stop