{!! Form::open(['action' => 'CheckinController@postIndex']) !!}
  <div class="form-group col-sm-6 double-padded">
      {!! Form::label('name', null, ['class' => 'sr-only']) !!}
      {!! Form::text('name', null, 
        ['class' => 'form-control col-sm-6', 'placeholder' => 'Name']) !!}
  </div>
  <div class="form-group col-sm-1 double-padded">
      <input type="submit" class="btn btn-primary" value="Check in">
  </div>
{!! Form::close() !!}
