<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width="device-width, initial-scale=1, user-scalable=no">
    <title>Visitor Checkin</title>

    {!! HTML::style('css/bootstrap.min.css') !!}
    {!! HTML::style('css/checkin.css') !!}
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="./">
            <img alt="Ingalls Library" src="images/cma-logo.png">
          </a>
       </div>
          <h1>Ingalls Library</h1>
          <span class="btn btn-primary pull-right"><a href="./">Reset</a></span>
       </div>
    </nav>
    <div class="container-fluid">
     <div class="row">
        <div class="col-sm-6">
              <a href="returning-visitor.html">
                <figure>
                  <img src="images/1991.163.png" alt="Returning visitors" class="img-responsive">
                </figure>
                <h2 class="caption">Returning visitors</h2>
              </a>
            </div>
        <div class="col-sm-6">
              <a href="new-visitor.html">
              <figure>
                <img src="images/1915.534.png" alt="New visitors" class="img-responsive">
              </figure>
              </a>
              <h2 class="caption">New visitors</h2>
            </div>
      </div>
    </div>
  </body>
  <script src="js/jquery-2.1.3.js" type="text/js"></script>
  <script src="js/bootstrap.js"></script>
</html>
