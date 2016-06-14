@extends('layouts.master')

@section('content')
       <div class="col-sm-4">
          <figure>
            <img src="{{ URL::asset('images/1915.534.jpg') }}" alt="" class="img-responsive">
          </figure>
        </div>
        <div class="col-sm-8">
          <h2>Registration Complete</h2>

          <p>Your registration has been saved. Please have identification ready when
              you visit Ingalls Library.</p>
         <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $user->name }}</dd>
          
            <dt>Email address</dt>
            <dd>{{ $user->email_address }}</dd>

            <dt>Registration Type</dt>
            <dd>{{ $user->role->role }}</dd>
          </dl>
         <span class="btn btn-default btn-lg">
            <a href="{{ URL::action('HomeController@index') }}">Reset</a>
          </span>
@stop

@section('scripts')
  <script>
     setTimeout(function() {
        window.location = "{!! url('/') !!}";
     }, 15000); 
  </script>
@stop
