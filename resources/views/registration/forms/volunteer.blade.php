
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
              {!! Form::label('telephone', 'Telephone number') !!}
              {!! Form::text('telephone', null, 
                    ['class' => 'form-control']) !!}
          </div>
