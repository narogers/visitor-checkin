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
          
          {!! Form::model(new App\Http\Requests\RegistrationDetailsRequest, 
                ['action' => 'RegistrationController@postTermsOfUse']) !!}
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