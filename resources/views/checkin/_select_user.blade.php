{!! Form::open(['action' => 'CheckinController@postIndex'],
    ['class' => 'form-inline']) !!}
    <div class="col-sm-6 double-padded">
	    <span class="fa fa-user"></span>
	    {{ $user["name"] }}
	    (<em>{{ Utilities::mask($user["email_address"]) }} </em>)

      {!! Form::hidden('name', $user["aleph_id"]) !!}
    </div>
    <div class="col-sm-1 double-padded">
      {!! Form::submit('Check in',
    	  ['class' => 'btn btn-small']) !!}
  {!! Form::close() !!}
</div>
