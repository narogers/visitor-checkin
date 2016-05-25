<ul class="list-unstyled">
@foreach ($users as $user)
  <li class="list-padded">
  {!! Form::open(['action' => 'CheckinController@postNew'],
    ['class' => 'form-inline']) !!}
	    <span class="fa fa-user"></span>
	    {{ $user->name }}
	    (<em>{{ $user->masked_email() }} </em>)

      {!! Form::hidden('query', $user->name) !!}
      {!! Form::submit('Check in now',
    	  ['class' => 'btn btn-small']) !!}
  {!! Form::close() !!}
  </li>  
@endforeach
</ul>
