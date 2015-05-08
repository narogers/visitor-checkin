<hr />

@foreach ($users as $user)
  {!! Form::open(['action' => 'CheckinController@postNew'],
    ['class' => 'form-inline']) !!}
	    <span class="glyphicon glyphicon-user"></span>
	    {{ $user->name }}
	    (<em>{{ $user->email_address }} </em>)

      {!! Form::hidden('query', $user->name) !!}
      {!! Form::submit('Check in now',
    	  ['class' => 'btn btn-primary']) !!}
  {!! Form::close() !!}
@endforeach
<hr />