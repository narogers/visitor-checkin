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
           {{ Session::get('notice') }}
          </div>
          @endif

          {!! Form::open(["action" => "RegistrationController@postIndex"]) !!}
            <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" placeholder="Name" name="name">
            </div>
            <div class="form-group">
            <label for="email_address">Email address</label>
            <input type="text" class="form-control" id="email_address" name="email_address">
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
