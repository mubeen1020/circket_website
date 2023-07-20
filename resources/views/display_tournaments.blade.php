@extends('default')
@section('content')
<div class="holder point">
    <div class="container p-0-sm">
        <div class="point-table-all border">
            <div class="series-drop">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="border-heading">
                            @if (isset($select_tournament_name[0]->name))
                            <h5>{{ $select_tournament_name[0]->name}}</h5>
                            @else
                            <h5>N/A</h5>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="series-deatails">
                <h4>Series Details:</h4>
                <div class="row">
                    <div class="col-sm-4">
                        <ul class="list-inline">
                            <li class="width1"><a href="#">Start Date</a></li>
                            <li class="width2">:</li>
                            @if (isset($select_tournament_name[0]->tournamentstartdate))
                            <li><a href="#">{{ $select_tournament_name[0]->tournamentstartdate}}</a></li>
                            @else
                            <li><a href="#">N/A</a></li>
                            @endif

                        </ul>

                        <ul class="list-inline">
                            <li class="width1"><a href="#">End Date</a></li>
                            <li class="width2">:</li>
                            @if (isset($select_tournament_name[0]->tournamentenddate))
                            <li><a href="#">{{ $select_tournament_name[0]->tournamentenddate}}</a></li>
                            @else
                            <li><a href="#">N/A</a></li>
                            @endif

                        </ul>
                      
                        <ul class="list-inline">
                            <li class="width1"><a href="#">Max Overs</a></li>
                            <li class="width2">:</li>
                            <li><a href="#">{{$tournamentdata??0}}</a></li>
                        </ul>

                    </div>
                    <div class="col-sm-4">


                        <ul class="list-inline">
                            <li class="width1"><a href="#">Description</a></li>
                            <li class="width2">:</li>
                            @if (isset($select_tournament_name[0]->description))
                            <li><a href="#">{{ $select_tournament_name[0]->description}}</a></li>
                            @else
                            <li><a href="#">N/A</a></li>
                            @endif
                        </ul>

                        <ul class="list-inline">
                            <li class="width1"><a href="#">Winner</a></li>
                            <li class="width2">:</li>
                            <li><a href="#"></a></li>
                        </ul>
                        <ul class="list-inline">
                            <li class="width1"><a href="#">More Info</a></li>
                            <li class="width2">:</li>
                            <li><a href="{{ route('newsdata') }}">News</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="series-deatails">
                <h4 style="margin-top: 0px;">Quick Links :</h4>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <ul class="series-d">

                        <li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="{{ url('show_team/' . $select_tournament_name[0]->id) }}">Teams</a></li>
                        <li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="{{ url('show_point_table/' . $select_tournament_name[0]->id) }}">Points Table</a></li>
                        <li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="{{ url('show_batting_records/' . $select_tournament_name[0]->id) }}">Batting Records</a></li>
                    </ul>
                </div>
                <div class="col-sm-4">
                    <ul class="series-d">
                        <li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="{{ url('show_bowling_records/' . $select_tournament_name[0]->id) }}">Bowling Records</a></li>
                        <li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="{{ url('show_fielding_records/' . $select_tournament_name[0]->id) }}">Fielding Records</a></li>
                        <li nowrap="nowrap" valign="top" style="padding-left: 10px;"><a href="{{ url('show_player_ranking/' . $select_tournament_name[0]->id) }}">Player Rankings</a></li>
                    </ul>
                </div>


            </div>
        </div>
    </div>
</div>
@stop