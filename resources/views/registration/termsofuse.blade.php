@extends('layouts/master')

@section('content')

        @include('shared/_terms_of_use')

        {!! Form::open(['action' => 'RegistrationController@postWelcome']) !!}
          <div id="signature"></div>

          {!! Form::hidden('signature_data', '',
                ['id' => 'signature_data']) !!}
          {!! Form::submit('&laquo; Go back', 
                ['class' => 'btn btn-primary btn-lg pull-left',
                 'name' => 'previous_step']) !!}
          {!! Form::submit('Register &raquo;',
                ['class' => 'btn btn-primary btn-lg pull-right',
                 'name' => 'next_step']) !!}
        {!! Form::close() !!}
@stop

@section('scripts')
  <!-- Fire up the signature panel and inject it into the page. We don't
       need to rely on FlashCanvas because we do not care about browsers
      such as Internet Explorer 7 and 8 for this use case -->
  {!! HTML::script('js/jSignature.min.js') !!}
  <script>
    $(function() {
      $('#signature').jSignature();

      /**
       * Initialize the form so that when it is submitted the SVG signature
       * gets captured as base64
       */
      $('form').submit(function() {
        $img_data = $('#signature').jSignature('getData', 'svgbase64');
        $('#signature_data').val('data:' + $img_data[0] + ', ' + $img_data[1]);
        // Does anything else need to be done?
      })
    })
  </script>
@stop
