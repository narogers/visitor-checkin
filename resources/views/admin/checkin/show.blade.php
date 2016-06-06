@extends('layouts.admin')

@section('content')
      <div class="col-sm-12">
        <ol class="breadcrumb">
          <li><a href="{!! URL::action('AdminController@getIndex') !!}">Home</a></li>
          <li><a href="{!! URL::action('AdminCheckinController@getIndex', ["range" => $range]) !!}">Library Usage</a></li>
          <li class="active">{!! $user->name !!}</li>
        </ol>
      </div>
    
    <div class="col-md-8">
      @include("admin/_user_details")
    </div>

    </div>    
      @if (0 < $checkins->count())
        <h2><span class="fa fa-pencil-square-o"></span> Checkin Activity for {!! DateUtils::labelFor($range) !!} <span class="badge">{!! $checkins->count() !!}</h2>
        <div class="col-md-6">
        <table class="table table-striped">
          <thead>
            <th class="col-md-2">Date</th>
            <th class="col-md-2">Time</th>
          </thead>
          @foreach($checkins->sortBy("updated_at") as $checkin)
          <tr>
            <td>{!! DateUtils::format($checkin->updated_at) !!}</td>
            <td>{!! DateUtils::format($checkin->updated_at, "h:i:s A") !!}</td>
          </tr>
          @endforeach
        </table>
        </div>
      @else
        <h4>There has been no activity over this period.</h4>
      @endif
    </div>
 @stop
