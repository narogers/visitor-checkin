@extends('layouts.master')

@section('content')
       <div class="col-sm-4">
          <figure>
            <img src="{{ URL::asset('images/1915.534.png') }}" alt="" class="img-responsive">
          </figure>
        </div>
        <div class="col-sm-8">
          <h2>New Visitor Registration</h2>

          <p>Your registration has been saved. Please have identification ready when
              you visit Ingalls Library.</p>
          <span class="btn btn-primary">
            <a href="{{ URL::action('HomeController@index') }}">Reset application</a>
          </span>

          <hr>
          <dl>
            <dt>Name</dt>
            <dd>{{ $user->name }}</dd>
          
            <dt>Email address</dt>
            <dd>{{ $user->email_address }}</dd>

            <dt>Visitor Type</dt>
            <dd>{{ $user->role->role }}</dd>
          </dl>
@stop

@section('scripts')
  <script>
    /**
     * When the page loads check to see if you are internal or external. If internal
     * add a timer that refreshes the page automatically after 10 seconds. Otherwise
     * this script shell will be empty.
     */
     @if ($internal_ip) {
        setTimeout(10000, function() {
          window.location = "{!! link_to('/') !!}";
        }) 
     @endif
  </script>
@stop
