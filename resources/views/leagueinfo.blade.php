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
<div class="league">
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
					@foreach($rulesandregulations as $rule)
                    <table class="table" style="width: 100%;">
                    	<tbody><tr>
                    		<th valign="top">Address: </th>
                    		<th>Milton Sports Centre<br>605 Santa Maria Blvd, Milton, ON L9T<br>6J5, Canada</th>
                    	</tr>
                    	<tr>
                    		<th>Established: </th>
                    		<th>2016</th>
                    	</tr>
                    	<tr>
                    		<th valign="top">EOSCL Rules and Guidlines: </th>
                    		<th><strong>EOSCL</strong> <strong>- Event Ontario Softball Circket Rules and Guidlines</strong><br/> {!! $rule->content !!}.<br>&nbsp;</th>
                    	</tr>
						<tr>
                    		<th valign="top">About: </th>
                    		<th><strong>EOSCL</strong> <strong>- Event Ontario Softball Circket </strong> Event Ontario is a full service global event management company specializing in providing platforms to upcoming business to promote and showcase their products and services. The company vision is to bring communities together and engage them in several events which are related to business, social and trait.

We are very proud to have you on board with us in Event Ontario Softball Cricket League (EOSCL); it is a different type of event where we are engaging miltonians through the game of cricket. Cricket is a bat-and-ball game played between two teams of 11 players each on a field at the centre of which is a rectangular 22-yard-long pitch. The game is played by 120 million players in many countries, making it the world's second most popular sport. Each team takes its turn to bat, attempting to score runs, while the other team fields. Each turn is known as an inning. There are two umpires that are required to take decisions during the game.

In this league we will be using softball (Tennis ball with tape), to avoid any injury and make the cost low to participants which will help to engage more people to the game..<br>&nbsp;</th>
                    	</tr>
						<tr>
						<th valign="top">Vision: </th>
							<th> The vision of Event Ontario Softball Circket is to promote interest and participation in the sport of cricket on a sustainable basis at all levels ? junior, adult, recreational, social, and competitive in Peel Region; empower members to adopt an active and healthy lifestyle as well as network with each other for games, jobs and business and promote multiculturalism in the society.<br></th>
                        </tr>	
						<tr>
						<th valign="top">Goal: </th>
							<th>The goal of EOSCL is to become the central governing body for cricket and act as focal point for all the cricket needs in Peel Region.</th>
                        </tr>		
                    </tbody></table>
					@endforeach
                  </div>
                    </div>
                </div>
            </div>
            </div>
        </div>



@stop