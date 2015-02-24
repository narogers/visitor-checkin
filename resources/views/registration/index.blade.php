@extends('layouts.master')

@section('content')
       <div class="col-sm-4">
          <figure>
            <img src="{{ URL::asset('images/1915.534.png') }}" alt="" class="img-responsive">
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
          <span class="glyphicn glyphicon-info-sign"></span> {{ Session::get('notice') }}
          </div>
          @endif

          {!! Form::model($registration, ["action" => "RegistrationController@postNew"]) !!}
            <div class="form-group">
              {!! Form::label('name') !!}
              {!! Form::text('name', '', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('email_address') !!}
              {!! Form::text('email_address', '', ['class' => 'form-control']) !!}
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
