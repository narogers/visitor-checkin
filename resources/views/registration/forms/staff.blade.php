@extends('layouts.role')

@section('title', 'Staff')

@section('form')
           <div class="form-group">
              {!! Form::label('department', 'Department') !!}
              {!! Form::text('department', null, 
                    ['class' => 'form-control']) !!}
          </div>

          <div class="form-group">
              {!! Form::label('job_title', 'Title') !!}
              {!! Form::text('job_title', null, 
                    ['class' => 'form-control']) !!}
          </div>

          <div class="form-group">
              {!! Form::label('extension', 'Extension') !!}
              <div class="input-group">
                <div class="input-group-addon">x</div>
                {!! Form::text('extension', null, 
                      ['class' => 'form-control']) !!}
              </div>
          </div>
@endsection
