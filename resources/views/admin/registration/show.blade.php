@extends('layouts.admin')

@section('content')
        <ol class="breadcrumb">
          <li><a href="{!! URL::action('AdminController@getIndex') !!}">Home</a></li>
          <li><a href="{!! URL::action('AdminRegistrationController@getIndex') !!}">Pending Registrations</a></li>
          <li class="active">{!! $user->name !!}</li>
        </ol>
      </div>
      @if(Session::has('alert')) 
      <div class="col-md-10">
        <div class="alert alert-success"><span class="fa fa-check-circle-o fa-2x"></span> {!! Session::get('alert') !!}</div>
      </div>
      @endif
      @if(Session::has('errors')) 
      <div class="col-md-10">
        <div class="alert alert-danger"><span class="fa fa-exclamation-circle fa-2x"></span> {!! Session::get('errors') !!}</div>
      </div>
      @endif 

      <div class="col-md-10">
        <h1>{!! $user->name !!} ({!! $user->email_address !!})</h1>

      </div>
    </div>

    <div class="col-md-12">
      <h2><span class="fa fa-user"></span> {!! $user->role->role !!}</h2>
    </div>

    <div class="clearfix double-padded">
      <div class="col-md-2">
        <label class="control-label" for="aleph_id">Aleph ID</label>
      </div>

      <div class="col-md-6">
      @if ($user->aleph_id)
        <p class="form-control-static" id="aleph_id">{!! $user->aleph_id !!}</p>
      @else
        {!! Form::open(
          ['action' => ['AdminRegistrationController@postRegistration', 
                        $user],
           'class' => 'form-inline']) !!}
        {!! Form::hidden('action', 'refresh_ils') !!}
        {!! Form::text("ils_id", '',
              ['class' => 'form-control']) !!}
        {!! Form::submit('Look up ID',
             ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
      @endif
      </div>
    </div>
              
    <div class="clearfix double-padded">
      <div class="col-md-2">
        <label class="control-label" for="verified">Status</label>
      </div>

      <div class="col-md-8">
        @if ($user->verified_user)
          <span class="fa fa-check-square-o"></span> Verified
        @else
          {!! Form::open(
             ["action" => ["AdminRegistrationController@postRegistration",
               $user],
             "class" => "form-inline"]) !!}
            <span class="fa fa-square-o"></span> Unverified
            {!! Form::hidden("action", "verify_id") !!}
            {!! Form::submit("Verify now",
              ["class" => "btn"]) !!}
          {!! Form::close() !!}
        @endif
      </div>
    </div>

    @if (1 == $user->registration()->count()) 
    @if ($user->registration->address_street)
      @include("admin/_field_summary", 
        ["field" => "address",
        "value" => $user->registration->address_street . "<br />" .
          $user->registration->address_city . ", " .
          $user->registration->address_state . " " .
          $user->registration->address_zip])
    @endif

    @if ($user->registration->telephone)
      @include("admin/_field_summary",
        ["field" => "telephone", "value" => $user->registration->telephone])
    @endif

    @if($user->registration->department)
      @include("admin/_field_summary",
        ["field" => "department", "value" => $user->registration->department])
    @endif

    @if($user->registration->job_title)
      @include("admin/_field_summary",
        ["field" => "job_title", "value" => $user->registration->job_title])
    @endif

    @if($user->registration->extension)
      @include("admin/_field_summary",
        ["field" => "extension", "value" => $user->registration->extension])
    @endif

    @if($user->registration->supervisor)
      @include("admin/_field_summary",
        ["field" => "supervisor", "value" => $user->registration->supervisor])
    @endif

    @if($user->registration->expires_on)
      @include("admin/_field_summary",
        ["field" => "expires_on", 
         "value" => DateUtils::labelFor($user->registration->expires_on)])
    @endif
  @endif

  <div class="clearfix">
    <div class="col-md-8 double-padded">
      <img class="img-responsive" id="signature"
           src="{!! $user->signature !!}" />
    </div>
  </div>

  <div class="col-md-12">
    <a class="btn btn-primary" href="{!! URL::action('AdminRegistrationController@getIndex') !!}">&laquo; Back to pending registrations</a>
  </div> 
</div>
@stop
