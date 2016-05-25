@extends('layouts.admin')

@section('content')
      <div class="col-md-12">
        <h1>Pending Registration 
          @if (sizeof($users) > 0)
            <span class="badge">{!! sizeof($users) !!}</span>
          @endif
        </h1>
        <hr>
      </div>

    <div class="row">
      <div class="col-md-12">
        @if (sizeof($users) > 0)
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
              <td>{!! $user->formattedCreationDate() !!}</td>
              <td>
                @if (empty($user->aleph_id))
  {!! Form::open(
      ['action' => ['AdminRegistrationController@postRegistration', $user],
       'class' => 'form-inline pending-step']) !!}
  <span class="fa fa-square-o"></span> No Aleph ID set 
    {!! Form::hidden('event', 'refresh_aleph_id') !!}
    {!! Form::submit('Refresh record', ['class' => 'btn btn-primary']) !!}
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
       'class' => 'form-inline']) !!}
  <span class="fa fa-square-o"></span> Check patron's ID
    {!! Form::hidden('event', 'verify_id') !!}
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
