@extends('layouts.admin')

@section('content')
      <div class="col-md-12">
        <h1>Pending Registrations 
          @if ($users->count() > 0)
            <span class="badge">{!! $users->count() !!}</span>
          @endif
        </h1>
        <hr>
      </div>

    <div class="row">
      <div class="col-md-12">
        @if ($users->count())
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email address</th>
              <th>Date Registered</th>
              <th></th>
            </tr>
          </thead>

          <tbody>
            @foreach ($users as $user)
            <tr>
              <td>{!! link_to_action('AdminRegistrationController@getRegistration',
                $user->name, 
                ['user' => $user]) !!}
              <td>{!! $user->email_address !!}</td>
              <td>{!! DateUtils::format($user->created_at) !!}</td>
              <td>
                @if (empty($user->aleph_id))
  {!! Form::open(
      ['action' => ['AdminRegistrationController@postRegistration', $user],
       'class' => 'form-inline double-padded']) !!}
  <span class="fa fa-square-o"></span> No Aleph ID set 
    {!! Form::hidden('action', 'refresh_ils') !!}
    {!! Form::submit('Look up ID', ['class' => 'btn btn-primary']) !!}
  {!! Form::close() !!}
                @else
<p class="completed-step"><span class="fa fa-check-square-o"></span> Aleph ID verified</p>
                @endif

                @if ($user->verified_user)
<p class="completed-step"><span class="fa fa-check-square-o"></span> Patron ID verified by desk</p>
                @else
<p class="pending-step">
  {!! Form::open(
      ['action' => ['AdminRegistrationController@postRegistration', $user],
       'class' => 'form-inline double-padded']) !!}
  <span class="fa fa-square-o"></span> Check patron's ID
    {!! Form::hidden('action', 'verify') !!}
    {!! Form::submit('Confirm verification', ['class' => 'btn btn-primary']) !!}
  {!! Form::close() !!}
</p>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
          <p>There are currently no registrations pending.</p>
        @endif
      </div>
    </div>
@stop
