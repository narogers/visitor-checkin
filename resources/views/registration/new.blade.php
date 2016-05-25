@extends('layouts.master')

@section('content')  
      <div class="col-sm-4">
          <figure>
            <img src="{{ URL::asset('images/1915.534.jpg') }}"
                 alt=""
                 class="img-responsive">
          </figure>
        </div>
        <div class="col-sm-6">
          <h2>{{ $label }}</h2>
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="list-unstyled">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
            </ul>
          </div>
          @endif
          
          {!! Form::model('App\Registration',
                ['action' => 'RegistrationController@postTermsOfUse']) !!}
            @include($registration_form)

            {!! Form::submit('Continue &raquo;',
                  ['class' => 'btn btn-primary btn-lg',
                   'name' => 'next_step']) !!}
          {!! Form::close() !!}
        </div>
@stop
