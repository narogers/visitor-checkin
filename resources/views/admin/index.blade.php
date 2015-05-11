@extends('layouts.admin')

@section('content')
<div class="col-md-12">
        <h1>Visitor Checkin Administration</h1>
        <hr />
          <h2><span class="glyphicon glyphicon-stats" aria-hidden="true"></span><a href="{{ URL::action('AdminController@getIndex') }}"> Library usage</a></h2>
        
          <p>See visitor counts for today, this week, and the last two months. Records are retained for the last 45 days before being archived.</p>
 
          <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span><a href="{{ URL::action('AdminRegistrationController@getIndex') }}"> Pending registrations</a></h2>
          <p>See and process pending registrations</p>
      </div>
@stop

 
