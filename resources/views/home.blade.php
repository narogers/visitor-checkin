@extends('layouts.master')

@section('content')
       <div class="col-sm-6">
              <a href="returning-visitor.html">
                <figure>
                  <img src="images/1991.163.png" alt="Returning visitors" class="img-responsive">
                </figure>
                <h2 class="caption">Returning visitors</h2>
              </a>
            </div>
        <div class="col-sm-6">
 	      <a href="{{ action('RegistrationController@index') }}">
              <figure>
                <img src="images/1915.534.png" alt="New visitors" class="img-responsive">
              </figure>
              </a>
              <h2 class="caption">New visitors</h2>
            </div>
@stop
