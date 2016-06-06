<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1">
    <title>Visitor Checkin Administration</title>

    {!! HTML::style('css/app.css') !!}
    {!! HTML::script('js/application.js') !!}
  </head

  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand">
            <img alt="Ingalls Library" src="{{ URL::asset('images/ingalls-library-banner.png')}}">
          </a>

          <ul class="nav navbar-nav">
            <li><a href="{{ URL::action('AdminController@getIndex') }}">Home</a></li>
            <li><a href="{{ URL::action('AdminRegistrationController@getIndex') }}">Registrations</a></li>
            <li><a href="{{ URL::action('AdminCheckinController@getIndex') }}">Usage</a></li>
         </ul>
       </div>
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
