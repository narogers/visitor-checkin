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
              <td>{!! date("F j, Y", strtotime($user->created_at)) !!}</td>
              <td>
                @if (empty($user->aleph_id))
                  @include('admin.registration.aleph_record_unverified', 
                    ['user' => $user])
                @else
                  @include('admin.registration.aleph_record_verified')
                @endif
                <br />
                @if ($user->verified_user)
                  @include('admin.registration.patron_id_verified')
                @else
                  @include ('admin.registration.patron_id_unverified', 
                    ['user' => $user])
                @endif
                <br />
                @include ('admin.registration.delete_registration');
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