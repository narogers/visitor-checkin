@extends('layouts.admin')

@section('content')
        <ol class="breadcrumb">
          <li><a href="{!! URL::action('AdminController@getIndex') !!}">Home</a></li>
          <li><a href="{!! URL::action('AdminRegistrationController@getIndex') !!}">Pending Registrations</a></li>
          <li class="active">{!! $user->name !!}</li>
        </ol>
      </div>
      @if(Session::has('alert')) 
      <div class="col-md-12">
        <div class="alert alert-success"><span class="glyphicon glypicon-ok"></span> {!! Session::get('alert') !!}</div>
      </div>
      @endif
      @if(Session::has('error')) 
      <div class="col-md-12">
        <div class="alert alert-warning"><span class="fa fa-exclamation-circle"></span> {!! Session::get('error') !!}</div>
      </div>
      @endif 

      <div class="col-md-12">
        <h1>{!! $user->name !!} ({!! $user->email_address !!})</h1>

      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <h2><span class="glyphicon glyphicon-picture"></span> {!! $user->role->role !!}</h2>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <form class="form-horizontal">
            <div class="col-md-2">
              <label class="control-label" for="aleph_id">Aleph ID</label>
            </div>

            <div class="col-md-10">
              @if($user->aleph_id)
              <p class="form-control-static" id="aleph_id">
                {!! $user->aleph_id !!}
              </p>
              @else
                {!! Form::open(
                  ['action' => ['AdminRegistrationController@postRegistration', 
                                $user],
                   'class' => 'form-inline']) !!}
                {!! Form::hidden('event', 'verify_id') !!}
                {!! Form::submit('Look up ID', 
                     ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
              @endif
            </div>
            @if($user->registration->address_street)
            <div class="col-md-2">
              <label class="control-label" for="address">Address</label>
            </div>

            <div class="col-md-10">
              <p class="form-control-static" id="address">
                {!! $user->registration->address_street !!}
                <br />
                {!! $user->registration->address_city !!},
                {!! $user->registration->address_state !!} 
                {!! $user->registration->address_zipcode !!}
              </p>
            </div>
            @endif

            @if($user->registration->telephone)
            <div class="col-md-2">
              <label class="control-label" for=
              "telephone">Telephone</label>
            </div>

            <div class="col-md-10">
              <p class="form-control-static" id="telephone">
                {!! $user->registration->telephone !!}
              </p>
            </div>
            @endif

            @if($user->registration->department)
            <div class="col-md-2">
              <label class="control-label" for="department">Department</label>
            </div>

            <div class="col-md-10">
              <p class="form-control-static" id="department">
                {!! $user->registration->department !!}
            </div>
            @endif

            @if($user->registration->job_title)
            <div class="col-md-2">
              <label class="control-label" for="title">Title</label>
            </div>

            <div class="col-md-10">
              <p class="form-control-static" id="title">
                {!! $user->registration->job_title !!}
            </div>
            @endif

            @if($user->registration->extension)
            <div class="col-md-2">
              <label class="control-label" for="extension">Extension</label>
            </div>

            <div class="col-md-10">
              <p class="form-control-static" id="extension">
                x{!! $user->registration->extension !!}
            </div>
            @endif

            @if($user->registration->supervisor)
            <div class="col-md-2">
              <label class="control-label" for="supervisor">Supervisor</label>
            </div>

            <div class="col-md-10">
              <p class="form-control-static" id="supervisor">
                {!! $user->registration->supervisor !!}
            </div>
            @endif

            @if($user->registration->expires_on)
            <div class="col-md-2">
              <label class="control-label" for="internship_end_date">Internship End Date</label>
            </div>

            <div class="col-md-10">
              <p class="form-control-static" id="internship_end_date">
                {!! $user->registration->expires_on !!}
            </div>
            @endif
        </form>
        <hr class="col-md-12">
        <img class="img-responsive" src="{!! $user->signature !!}" />
        <hr />
        <a class="btn btn-primary" href="{!! URL::action('AdminRegistrationController@getIndex') !!}">&laquo; Back to pending registrations</a> 
      </div>
@stop