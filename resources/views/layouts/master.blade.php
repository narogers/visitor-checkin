<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width="device-width, initial-scale=1, user-scalable=no">
    <title>Visitor Checkin</title>

    {!! HTML::style('css/app.css') !!}
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="./">
            <img alt="Ingalls Library" src="{{ URL::asset('images/ingalls-library-banner.png')}}">
          </a>
       </div>
          <a href="{{ link_to('/') }}"><img src="{{ URL::asset('images/reset-button.png') }}" class="pull-right"></a>
       </div>
    </nav>
    <div class="container-fluid">
     <div class="row">
       @yield('content') 
     </div>
    </div>
  </body>
  
  {!! HTML::script('js/jquery-2.1.3.js') !!}
  {!! HTML::script('js/bootstrap.min.js') !!}
  @yield('scripts')
</html>
