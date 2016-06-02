@extends('layouts.master')

@section('content')
      <div class="row">
        <div class="col-sm-4">
            <figure>
              <img src="{{ URL::asset('images/1942.638.jpg') }}" alt="" class="img-responsive">
            </figure>
          </div>
          <div class="col-sm-8">
            <h2><span class="fa fa-group"></span> Multiple registrations found</h2>
            <p>More than one visitor was found in the system. Select your registration from the list below to complete check in. If you do not see yourself try entering your complete name instead.</p>

            @foreach($users as $user) 
              @include('checkin/_select_user', ["user" => $user])
            @endforeach
            @include('checkin/_form')
         </div>
        </div>
     </div>
@stop
