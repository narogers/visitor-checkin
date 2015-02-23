@extends('layouts.master')

@section('content')  
      <div class="col-sm-4">
          <figure>
            <img src="{{ URL::asset('images/1915.534.png') }}"
                 alt=""
                 class="img-responsive">
          </figure>
        </div>
        <div class="col-sm-6">
          <h2>{{ Session::get('label') }}</h2>
          {!! Form::open(['action' => 
                'RegistrationController@postTermsOfUse']) !!}
            {!! $registration_form !!}

            {!! Form::submit('Go back', 
                  ['class' => 'btn btn-primary',
                   'name' => 'go_back']) !!}
            {!! Form::submit('Continue',
                  ['class' => 'btn btn-primary',
                   'name' => 'submit']) !!}
          {!! Form::close() !!}
        </div>
@stop