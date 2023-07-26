@extends('default')
@section('content')

<div class="point-table-all">
    <div class="series-drop">
        <div class="row">
            <div class="col-sm-6">
                <div class="border-heading" style="margin-bottom: 0px;">
                    <h5>CricClubs
                        Grounds
                    </h5>
                </div>
            </div>
           
            <div class="col-sm-12 text-right">
            </div>

        </div>
    </div>
    <div class="about-table table-responsive" id="tab1default" style="margin-top: 15px;">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ground</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grounds as $index => $ground)
                <tr id="div94">
                    <th>{{ $index + 1 }}</th>
                    <th><a href="#">{{ $ground->name }}</a></th>
                    <th><i class="fa fa-map-marker"></i> {{ $ground->address }}</th>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop