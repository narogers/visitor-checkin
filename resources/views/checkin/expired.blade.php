@extends('layouts/master')

@section('content')
   <div class="aler alert-info" role="alert">
     <span class="fa fa-exclamation-circle"></span> Sign the terms of use to complete the check in process.
   </div>

        @include('shared/_terms_of_use')

        {!! Form::open(['action' => 'CheckinController@postExpired']) !!}
          <div id="signature" class="double-padded"></div>

          {!! Form::hidden('signature_data', '',
                ['id' => 'signature_data']) !!}
          <div class="row">
            <div class="col-sm-1">
               {!! Form::submit('Check In <span class="fa fa-pencil-square-o"></span>',
                ['class' => 'btn btn-primary btn-lg']) !!}
            </div>
            <div class="col-sm-1 col-sm-offset-1">
               {!! Form::button('Clear',
                ['class' => 'btn btn-default btn-lg',
                 'name' => 'reset_signature',
                 'id' => 'reset']) !!}
            </div>
          </div>
        {!! Form::close() !!}
@stop

@section('scripts')
  <!-- Fire up the signature panel and inject it into the page. We don't
       need to rely on FlashCanvas because we do not care about browsers
      such as Internet Explorer 7 and 8 for this use case -->
  {!! HTML::script('js/signature.js') !!}
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
      })

      $('#reset').click(function() {
        $('#signature').jSignature('reset');
        return false;
      })
    })
  </script>
@stop
