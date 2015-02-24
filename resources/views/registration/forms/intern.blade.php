            <div class="form-group">
              {!! Form::label('street_address', 'Address') !!}
              {!! Form::text('street_address', '', 
                    ['class' => 'form-control',
                     'placeholder' => 'Street address']) !!}

              {!! Form::label('city', '', 
                    ['class' => 'sr-only']) !!}
              {!! Form::text('city', '', 
                    ['class' => 'form-control',
                     'placeholder' => 'City']) !!}

              {!! Form::label('zip_code', '', 
                    ['class' => 'sr-only']) !!}
              {!! Form::text('zip_code', '', 
                    ['class' => 'form-control',
                     'maxlength' => 5,
                     'placeholder' => 'Zip code']) !!}
           </div>

           <div class="form-group">
              {!! Form::label('department', 'Department') !!}
              {!! Form::text('department', '', 
                    ['class' => 'form-control']) !!}
          </div>

          <div class="form-group">
              {!! Form::label('supervisor', 'Supervisor') !!}
              {!! Form::text('supervisor', '', 
                    ['class' => 'form-control']) !!}
          </div>

          <div class="form-group">
              <label for='ending_date'>Date</label>
              <input type="date" name="ending_date" id="ending_date" 
                     class="form-control col-sm-4">
          </div>