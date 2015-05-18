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
      <!-- Begin the main page which just lists all checkins without any sort of pagination. If this
           gets unwieldly adding pagination should be fairly straightforward -->
      <div class="col-md-10">
        @foreach ($roles as $role)
        <div class="panel panel-default">
          <div class="panel-heading">
            <a href="#{!! $role->role !!}" data-toggle='collapse' 
               data-target='#{!! $role->role !!}-table'
               aria-expanded='false' 
               aria-controls="{!! $role->role !!}-table">{!! $role->role !!}
               <span class="badge">{!! $role->users->count() !!}</span>
               <span class="caret pull-right"></span>
            </a>
          </div>

          <table class="table table-striped collapse" 
                 id="{!! $role->role !!}-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Checkins</th>
              <th>Last checkin</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><a href="usage-visitordetails.html">[NAME]</a></td>
              <td>[CHECKINS]</td>
              <td>[LAST CHECKIN]</td>
            </tr>
          </tbody>
          </table>
        </div>
        @endforeach
@stop
