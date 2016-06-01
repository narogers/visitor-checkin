@extends('layouts.master')

@section('content')
      <div class="row">
        <div class="col-sm-6">
         <figure>
            <img src="{{ URL::asset('images/1991.134.2.jpg') }}" alt="" class="img-responsive">
         </figure>
         </a>
        </div>
        <div class="col-sm-6">
          <h2>Welcome back, {{ $user["name"] }}</h2>
          <p>You have successfully checked in.</p>
          <hr />
      
          <h3>Visitor type</h3>
          <p>{{ $user->role->role }}</p>
          <span class="btn btn-primary">
            <a href="{{ URL::action('HomeController@index') }}">Reset application</a>
          </span>               
        </div>
      </div>
@stop

@section('scripts')
  <script>
    var delay = 10000; /* Delay in milliseconds before refreshing */
    var refresh_target = '{{ URL::action('HomeController@index') }}' /* Where the page should reload */
    setTimeout(function() {
      window.location = refresh_target;
    }, delay)
  </script>
@stop