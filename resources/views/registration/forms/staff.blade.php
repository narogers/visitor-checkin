<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width="device-width, initial-scale=1, user-scalable=no">
    <title>Patron Checkin</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-overrides.css" rel="stylesheet">
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
        <div class="col-sm-4">
          <figure>
            <img src="images/1915.534.png" alt="" class="img-responsive">
          </figure>
        </div>
        <div class="col-sm-6">
          <h2>Museum Staff</h2>
          <form action="new-termsofuse.html" method="GET">
            <div class="form-group">
              <label for="department">Department</label>
              <input type="text" class="form-control" id="department">
            </div>
 
            <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title">
            </div>

            <div class="form-group">
            <label for="extension">Extension</label>
            <input type="text" class="form-control" id="extension">
            </div>

            <div class="form-group">
            <label for="status">Status</label>
            <select id="status" class="form-control">
              <option value="full-time">Full time</option>
              <option value="part-time">Part time</option>
              <option value="temporary">Temporary</option>
            </select>
            </div>

            <input type="submit" class="btn btn-primary" value="Continue">
            <span class="btn btn-primary"><a href="index.html">Go back</a></span>
        </form>
      </div>
    </div>
  </body>
  <script src="js/jquery-2.1.3.js" type="text/js"></script>
  <script src="js/bootstrap.js"></script>
</html>
