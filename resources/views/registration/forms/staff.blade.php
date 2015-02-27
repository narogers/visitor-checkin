           <div class="form-group">
              {!! Form::label('department', 'Department') !!}
              {!! Form::text('department', null, 
                    ['class' => 'form-control']) !!}
          </div>

          <div class="form-group">
              {!! Form::label('title', 'Title') !!}
              {!! Form::text('title', null, 
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