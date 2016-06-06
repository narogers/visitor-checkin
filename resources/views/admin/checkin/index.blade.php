@extends('layouts.admin')

@section('content')
<!--
 {!! $checkins !!}
 -->
      <div class="col-md-8">
        <h1>Library Visits for {!! DateUtils::labelFor($range) !!}</h1>
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
        @if(!$checkins->isEmpty())

        @foreach ($checkins->sortBy("user.role.role")->groupBy("user.role.role") as $role => $checkins_for_role)
        <table class="table table-striped" 
                 id="{!! $role !!}-table">
          <caption class="h2">{!! $role !!}</h2>
                    </caption>
          <thead>
            <tr>
              <th class="col-md-2">
                <a href="#{!! $role !!}" data-toggle='collapse' 
                   data-target='#{!! $role !!}-entries'
                   aria-expanded='true' 
                   aria-controls="{!! $role !!}-entries">
                   <span class="caret"></span>
                   Name 
                   <span class="badge">
                     {!! $checkins_for_role->count() !!}</span>
                </a>
              </th>
              <th class="col-md-1">Checkins</th>
              <th class="col-md-2">Last checkin</th>
            </tr>
          </thead>
          <tbody class='collapse in' id="{!! $role !!}-entries">
            @foreach($checkins_for_role->groupBy("user.name") as $name => $checkins_for_user)
            <tr>
              <td><a href="{!! URL::action('AdminCheckinController@getCheckins', [$checkins_for_user->first()->user, $range]) !!}" alt="Checkin activity for {!! $name !!}">{!! $name !!}</a></td>
              <td>{!! $checkins_for_user->count() !!}</td>
              <td>{!! DateUtils::format($checkins_for_user->first()->updated_at) !!}</td>
            </tr>
            @endforeach
          </tbody>
          </table>
        @endforeach
        @else
          <div class="jumbotron">
            <h2>No activity found for this range</h2>
            <p>No checkins have been recorded for {!! DateUtils::labelFor($range) !!}</p>
          </div>
        @endif
        </div>

@stop
