@extends('layouts.admin')

@section('content')
      <div class="col-md-8">
        <h1>Library Visits for {!! $label !!}</h1>
      </div>   
      <div class="col-md-4">
        <div class="dropdown">
          <button class="btn btn-default dropdown-toggle" type="button" id="date_range" data-toggle="dropdown" aria-expanded="true">
            Select range
            <span class="caret"></span>
          </button>

          <ul class="dropdown-menu" role="menu" aria-labelledby="date_range">
            <li role="presentation"><a role="menuItem" tabindex="-1" href="{!! URL::action('AdminCheckinController@getIndex') !!}/today">Today</a></li>
            <li role="presentation"><a role="menuItem" tabindex="-1" href="{!! URL::action('AdminCheckinController@getIndex') !!}/week">This week</a></li>
            <li role="presentation"><a role="menuItem" tabindex="-1" href="{!! URL::action('AdminCheckinController@getIndex') !!}/month">This month</a></li>
            <li role="presentation"><a role="menuItem" tabindex="-1" href="{!! URL::action('AdminCheckinController@getIndex') !!}/lastmonth">Previous month</a></li>
          </ul>
        </div>
      </div>  
    </div>

   <div class="row">
      <!-- Begin the main page which just lists all checkins without any sort of pagination. If thisS
           gets unwieldly adding pagination should be fairly straightforward -->
      <div class="col-md-10">
        @if($users->count())
        @foreach ($users as $group)
          <?php $role = $group[0]->role; ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>
              <a href="#{!! $role->role !!}" data-toggle='collapse' 
               data-target='#{!! $role->role !!}-table'
               aria-expanded='true' 
               aria-controls="{!! $role->role !!}-table">
               <span class="caret"></span>
               {!! $role->role !!}
               <span class="badge">{!! $role->checkinCountFor($range) !!}</span>
              </a>
            </strong>
          </div>

          <table class="table table-striped collapse in" 
                 id="{!! $role !!}-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Checkins</th>
              <th>Last checkin</th>
            </tr>
          </thead>
          <tbody>
            @foreach($group as $user)
            <tr>
              <td><a href="{!! URL::action('AdminCheckinController@getCheckins', [$user, $range]) !!}" alt="Checkin activity for {!! $user->name !!}">{!! $user->name !!}</a></td>
              <td>{!! $user->checkinCountFor($range) !!}</td>
              <td>{!! $user->formattedLastCheckin($range) !!}</td>
            </tr>
            @endforeach
          </tbody>
          </table>
        </div>
        @endforeach
        @else
          <div class="jumbotron">
            <h2>No activity found for this range</h2>
            <p>No checkins have been recorded during {!! $label !!}</p>
          </div>
        @endif
        </div>

@stop
