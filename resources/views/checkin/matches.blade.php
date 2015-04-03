<hr />

@foreach ($users as $user)
	<span class="glyphicon glyphicon-user"></span>
	{{ $user->name }}
	(<em>{{ $user->email_address }} </em>)
  {!! Form::open(['action' => 'CheckinController@postNew']) !!}
    {!! Form::hidden('query', $user->name) !!}
    <input type="submit" id="submit" value="Check in now">
  {!! Form::close() !!}
@endforeach