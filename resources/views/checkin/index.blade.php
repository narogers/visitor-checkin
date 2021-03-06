@extends('layouts.master')

@section('content')
        <div class="col-sm-6">
          <div class="row">
            <div class="col-sm-12">
              <a href="p2spro://scan?formats=CODABAR,CODE39&amp;callback={{ URL::action('CheckinController@getCheckin') }}%3Fcode%3DCODE%26type%3DFORMAT">
              </figure>
                <img src="{{ URL::asset('images/1991.134.2.jpg') }}" alt="CMA Badge Holders" class="img-responsive">
                <figcaption class="h2 caption">Scan your ID</figcaption>
              </figure>
              </a>
            </div>
         </div>
        </div>
        <div class="col-sm-6">
          <div class="row">
            <div class="col-sm-12">
              <figure>
                <img src="{{ URL::asset('images/1942.638.jpg') }}" alt="Check in manually" class="img-responsive">
                <div class="h2 caption">
                 {!! Form::open(['action' => 'CheckinController@postIndex']) !!}
                   <div class="input-group">
                   {!! Form::label('name', 'Name', ['class' => 'sr-only']) !!}
                   {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                     <span class="input-group-btn">
                       <input type="submit" class="btn btn-primary">Check in <span class="fa fa-sign-in"></span></input>
                     </span>
                   </div>
                 {!! Form::close() !!}
                </div>
              </figure>
            </div>
          </div>
        </div>
      </div>
@stop
