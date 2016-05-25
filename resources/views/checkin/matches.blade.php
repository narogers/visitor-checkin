@foreach ($users as $user)
  {!! Form::open(['action' => 'CheckinController@postNew'],
    ['class' => 'form-inline']) !!}
    <div class="col-sm-6 double-padded">
	    <span class="fa fa-user"></span>
	    {{ $user->name }}
	    (<em>{{ $user->masked_email() }} </em>)

      {!! Form::hidden('query', $user->email_address) !!}
    </div>
    <div class="col-sm-1 double-padded">
      {!! Form::submit('Check in',
    	  ['class' => 'btn btn-small']) !!}
  {!! Form::close() !!}
    </div>
@endforeach
