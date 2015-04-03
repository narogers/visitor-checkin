@extends('layouts.master')

@section('content')
       <div class="col-sm-6">
              <a href="{{ URL::action('CheckinController@getIndex') }}">
                <figure>
                  <img src="images/1991.163.png" alt="Returning visitors" class="img-responsive">
                </figure>
                <h2 class="caption">Returning visitors</h2>
              </a>
            </div>
        <div class="col-sm-6">
 	      <a href="{{ action('RegistrationController@getIndex') }}">
              <figure>
                <img src="images/1915.534.png" alt="New visitors" class="img-responsive">
              </figure>
              </a>
              <h2 class="caption"><a href="{{ action('RegistrationController@getIndex') }}">New visitors</a></h2>
            </div>
@stop
