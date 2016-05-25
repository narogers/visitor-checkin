@extends('layouts.master')

@section('content')
      <div class="row">
        <div class="col-sm-4">
            <figure>
              <img src="{{ URL::asset('images/1942.638.jpg') }}" alt="" class="img-responsive">
            </figure>
          </div>
          <div class="col-sm-8">
            <h2>{!! Lang::get($message_key . '.title') !!}</h2>
            <p>{!! Lang::get($message_key . '.message') !!}</p>

            @if (isset($user) && ($user->count() > 1))
              @include('checkin.matches', ['users' => $user])
            @endif

              {!! Form::open(['action' => 'CheckinController@postNew']) !!}
              <div class="form-group col-sm-6">
                  {!! Form::label('query', null, ['class' => 'sr-only']) !!}
                  {!! Form::text('query', null, ['class' => 'form-control col-md-6', 'placeholder' => 'Name or email address']) !!}
              </div>
              <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Retry check in">
              </div>
              {!! Form::close() !!}
          </div>
        </div>
     </div>
@stop
