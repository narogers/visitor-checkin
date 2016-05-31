@extends('layouts.role')

@section('title', 'Intern')

@section('form')
            <div class="form-group">
              {!! Form::label('address_street', 'Address') !!}
              {!! Form::text('address_street', null, 
                    ['class' => 'form-control',
                     'placeholder' => 'Street address']) !!}

              {!! Form::label('address_city', '', 
                    ['class' => 'sr-only']) !!}
              {!! Form::text('address_city', null, 
                    ['class' => 'form-control',
                     'placeholder' => 'City']) !!}

              {!! Form::label('address_zip', '', 
                    ['class' => 'sr-only']) !!}
              {!! Form::text('address_zip', null, 
                    ['class' => 'form-control',
                     'maxlength' => 5,
                     'placeholder' => 'Zip code']) !!}
           </div>

           <div class="form-group">
              {!! Form::label('department', 'Department') !!}
              {!! Form::text('department', null, 
                    ['class' => 'form-control']) !!}
          </div>

          <div class="form-group">
              {!! Form::label('supervisor', 'Supervisor') !!}
              {!! Form::text('supervisor', null, 
                    ['class' => 'form-control']) !!}
          </div>

          <div class="form-group">
              <label for='ending_date'>Ending date</label>
              {!! Form::input('date', 'expires_on',
                    null, ['class' => 'form-control col-sm-4']) !!}
          </div>
@endsection
