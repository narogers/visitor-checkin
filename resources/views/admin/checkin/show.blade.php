@extends('layouts.admin')

@section('content')
      <div class="col-lg-12">
        <ol class="breadcrumb">
          <li><a href="{!! URL::action('AdminController@getIndex') !!}">Home</a></li>
          <li><a href="{!! URL::action('AdminCheckinController@getIndex') !!}/{!! $range !!}">Library Usage</a></li>
          <li class="active">{!! $user->name !!}</li>
        </ol>
      </div>
      <div class="col-lg-12">
        <h2>{!! $user->name !!} ({!! $user->email_address !!})</h2>
        <h3><span class="glyphicon glyphicon-picture"> {!! $user->role->role !!}</span></h3>
        
      @if (0 < $user->checkinCountFor($range))
        <ul class="list-group col-lg-4"> 
          <li class="list-group-item disabled"><strong>Checkin Activity <span class="badge">{!! $user->checkinCountFor($range) !!}</span></li>
            @foreach($user->checkinsDuring($range) as $checkin)
              <li class="list-group-item">{!! $checkin->formattedCheckinDate() !!}</li>
            @endforeach
        </ul>
      @else
        <h4>There has been no activity over this period.</h4>
      @endif

    </div>
    </div>
 @stop
