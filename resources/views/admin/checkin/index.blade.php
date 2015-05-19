@extends('layouts.admin')

@section('content')
      <div class="col-md-6">
        <h1>Library Visits for [DATE]</h1>
      </div>   
      <div class="col-md-6 pull-left">
        <div class="dropdown">
          <button class="btn btn-default dropdown-toggle" type="button" id="date_range" data-toggle="dropdown" aria-expanded="true">
            Select range
            <span class="caret"></span>
          </button>

          <ul class="dropdown-menu" role="menu" aria-labelledby="date_range">
            <li role="presentation"><a role="menuItem" tabindex="-1" href="?range=daily">Today (DATE)</a></li>
            <li role="presentation"><a role="menuItem" tabindex="-1" href="?range=week">This week (DATE)</a></li>
            <li role="presentation"><a role="menuItem" tabindex="-1" href="?range=month">This month (DATE)</a></li>
            <li role="presentation"><a role="menuItem" tabindex="-1" href="?range=lastmonth">Previous month (DATE)</a></li>
          </ul>
        </div>
      </div>  
    </div>

   <div class="row">
      <!-- Begin the main page which just lists all checkins without any sort of pagination. If thisS
           gets unwieldly adding pagination should be fairly straightforward -->
      <div class="col-md-10">
        @if($users->count())
        @foreach ($users as $user_group)
          <?php $role = $user_group[0]->role->role; ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <a href="#{!! $role !!}" data-toggle='collapse' 
               data-target='#{!! $role !!}-table'
               aria-expanded='false' 
               aria-controls="{!! $role !!}-table">{!! $role !!}
               <span class="badge">{!! '12' !!}</span>
               <span class="caret pull-right"></span>
            </a>
          </div>

          <table class="table table-striped collapse" 
                 id="{!! $role !!}-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Checkins</th>
              <th>Last checkin</th>
            </tr>
          </thead>
          <tbody>
            @foreach($user_group as $user)
            <tr>
              <td>{!! $user->name !!}</td>
              <td>-</td>
              <td>{!! $user->lastCheckinDate() !!}</td>
            </tr>
            @endforeach
          </tbody>
          </table>
        @endforeach
        @else
          <div class="jumbotron">
            <h2>No activity found for this range</h2>
            <p>No checkins have been recorded during [DATE]</p>
          </div>
        @endif
        </div>

@stop
