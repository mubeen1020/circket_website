
@extends('default')
@section('content')
<div class="holder point">
    	<div class="container">
        	<div class="point-table-all border">
        	<form  method="post" name="add-blog-post-form" id="add-blog-post-form" action="{{url('club-team-search-submit')}}">
            @csrf
            <div class="series-drop">
            	<div class="row">
                	<div class="col-lg-2">
                    	<div class="border-heading">
                            <h5>Search Team</h5>
                        </div>
                    </div>
                </div>
                <br>
                    	<div class="row">
                            <div class="col-lg-2">
                                <div class="form-text">
                                    <h5>Team Name<span style="color: brown">**</span></h5>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-in">
                                   <input type="text" id="teamName" name="teamName" class="form-control" required="" value="" >
                                </div>
                            </div>
                             <div class="col-sm-2">
                            <div class="button-umpire text-right">
                            <input type="submit" class="btn btn-ban" value="Search">
                            </div>
                        </div>
                        </div>
                                
                <br>
                <hr>
            </div>
            </form>
            	           	<div class="about-table table-responsive" >
                <table class="table sortable dataTable" > 
                    <thead> 
                        <tr> 
                        <th class="sorting_1" class="sortheader" style="text-align: left !important">S.No <i class="fa-solid fa-arrow-down-short-wide"></i></th>
        <th class="sorting_1" class="sortheader">Team Name  <i class="fa-solid fa-arrow-down-short-wide"></i></th>
        <th class="sorting_1" class="sortheader">Series Name <i class="fa-solid fa-arrow-down-short-wide"></i></th>
        <th class="sorting_1" class="sortheader">Captain<i class="fa-solid fa-arrow-down-short-wide"></i></th>
                            </tr> 
                    </thead> 
                    <tbody>
                    @php
    if(count($result) > 0){
@endphp  
                    @foreach($result as $key => $team )
                    <tr class="even"> 
                        <td>{{$key+1}}</td> 
                        <td><a href="#" target="_blank">{{$team->name}}</a></td>
                        <td>{{$team->tournament}}</td>
                        <td>{{$team->fullname}}</td>
                        <td></td>
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
    @stop