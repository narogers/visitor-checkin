
            <div class="form-group">
              {!! Form::label('street-address', 'Address') !!}
              {!! Form::text('street-address', '', 
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
              {!! Form::label('telephone', 'Telephone Number') !!}
              {!! Form::text('telephone', '', 
                    ['class' => 'form-control']) !!}
          </div>

          <div class="form-group">
              {!! Form::label('drivers_license', "Driver's License") !!}
              {!! Form::text('drivers_license', '', 
                    ['class' => 'form-control']) !!} 
          </div>
