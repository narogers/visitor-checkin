@extends('layouts.master')

@section('content')
       <div class="col-sm-6">
              <a href="{{ URL::action('CheckinController@getIndex') }}">
                <figure>
                  <img src="images/1991.163.jpg" alt="Returning visitors" class="img-responsive">
                  <figcaption class="h2 caption">Returning visitors</figcaption>
                </figure>
              </a>
            </div>
        <div class="col-sm-6">
 	      <a href="{{ action('RegistrationController@getIndex') }}">
              <figure>
                <img src="images/1915.534.jpg" alt="New visitors" class="img-responsive">
                <figcaption class="h2 caption">New visitors</figcaption>
              </figure>
              </a>
            </div>
@stop
