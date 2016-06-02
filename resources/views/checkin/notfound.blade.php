@extends('layouts.master')

@section('content')
      <div class="row">
        <div class="col-sm-4">
            <figure>
              <img src="{{ URL::asset('images/1942.638.jpg') }}" alt="" class="img-responsive">
            </figure>
          </div>
          <div class="col-sm-8">
            <h2><span class="fa fa-exclamation-circle"></span> No registration found</h2>
            <p>No registration could be found. Please try entering your complete name instead.</p>

            @include('checkin/_form')
         </div>
        </div>
     </div>
@stop
