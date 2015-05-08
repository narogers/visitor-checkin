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

            @if ($user && (2 == $user->count()))
              @include('checkin.matches', ['users' => $user])
            @endif

              {!! Form::open(['action' => 'CheckinController@postNew']) !!}
              <div class="form-group">
                  {!! Form::label('query', null, ['class' => 'sr-only']) !!}
                  {!! Form::text('query', null, ['class' => 'form-control', 'placeholder' => 'Name or email address']) !!}
              </div>
              <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Retry check in">
                  <span class="span-4 btn btn-primary"><a href="p2spro://scan?formats=CODABAR,CODE39&amp;callback={{ URL::action('CheckinController@getNew') }}%3Fcode%3DCODE%26type%3DFORMAT">Scan badge</span>
              </div>
            </form>
          </div>
        </div>
     </div>
@stop
