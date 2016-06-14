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
          <h2><span class="fa fa-book"></span> Welcome back, {{ $user["name"] }}</h2>
      
          <a href="{{ URL::action('HomeController@index') }}">
            <span class="btn btn-primary btn-lg"><span class="fa fa-refresh"></span> Reset</span>
          </a>
        </div>
      </div>
@stop

@section('scripts')
  <script>
    var delay = 10000; /* Delay in milliseconds before refreshing */
    var refresh_target = '{{ URL::action('HomeController@index') }}' /* Where the page should reload */
    //setTimeout(function() {
    //  window.location = refresh_target;
    //}, delay)
  </script>
@stop
