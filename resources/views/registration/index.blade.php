@extends('layouts.master')

@section('content')
       <div class="col-sm-4">
          <figure>
            <img src="{{ URL::asset('images/1915.534.jpg') }}" alt="" class="img-responsive">
          </figure>
        </div>
        <div class="col-sm-8">
          <h2>New Visitor Registration</h2>
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="list-unstyled">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
            </ul>
          </div>
          @endif

          @if (Session::has('notice')) 
          <div class="alert alert-danger">
          <span class="fa fa-exclamation-circle"></span> {{ Session::get('notice') }}
          </div>
          @endif

          {!! Form::open(["action" => "RegistrationController@postNew"]) !!}
            <div class="form-group">
              {!! Form::label('name') !!}
              {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('email_address') !!}
              {!! Form::text('email_address', null, ['class' => 'form-control']) !!}
            </div>
 
            <div class="form-group">
            <label for="role">Visitor type</label>
            <select class="form-control" id="role" name="role">
            <optgroup label="Visitor type">
              <option disabled selected>Select one</option>
              <option>Academic</option>
              <option>Docent</option>
              <option>Fellow</option>
              <option>Intern</option>
              <option>Member</option>
              <option>Public</option>
              <option>Staff</option>
              <option>Volunteer</option>
            </optgroup>
          </select>
          </div>
          {!! Form::submit('Continue', 
                ['class' => 'btn btn-primary',
                 'name' => 'submit']) !!}
        {!! Form::close() !!}
      </div>
@stop
