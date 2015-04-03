@extends('layouts.master')

@section('content')
        <div class="col-sm-6">
          <div class="row">
            <div class="col-sm-12">
              <a href="p2spro://scan?formats=CODABAR,CODE39&callback={{ URL::action('CheckinController@getNew') }}?code=CODE">
                <img src="{{ URL::asset('images/1991.134.2.png') }}" alt="CMA Badge Holders" class="img-responsive">
              </figure>
              </a>
              <h2 class="caption">Scan your ID</h2>
            </div>
            <div class="col-sm-12">
              <p>Scan your CMA Badge or member's card to continue.</p>
            </div>
         </div>
        </div>
        <div class="col-sm-6">
          <div class="row">
            <div class="col-sm-12">
              <figure>
                <img src="{{ URL::asset('images/1942.638.png') }}" alt="Check in manually" class="img-responsive">
              </figure>
              <h2 class="caption">Type your name</h2>
            </div>
            <div class="col-sm-12">
              {!! Form::open(['action' => 'CheckinController@postNew']) !!}
                <div class="form-group">
                  {!! Form::label('query', 'Name or email address', ['class' => 'sr-only']) !!}
                  {!! Form::text('query', null, ['class' => 'form-control', 'placeholder' => 'Name or email address']) !!}
               </div>
               <div class="form-group">
                 <input type="submit" class="btn btn-primary" value="Check in">
               </div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
@stop