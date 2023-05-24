@extends('default')
@section('content')
<div class="holder point">
    	<div class="container p-sm-0">
        	<div class="point-table-all">
            <div class="series-drop">
            	<div class="row">
                	<div class="col-sm-2">
                    	<div class="border-heading">
                            <h5>Articles</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div id="dynArticle"><div>No Articles</div></div>
            <div style="text-align:center">
            	<button class="btn btn-default" id="loadMore" style="display: none;">Load More</button>
            </div>
        </div>
    </div>
    </div>




@stop