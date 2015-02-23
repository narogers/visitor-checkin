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
        <div class="col-sm-6">
          <figure>
            <img src="images/1915.534.png" alt="" class="img-responsive">
          </figure>
        </div>
        <div class="col-sm-6">
          <h2>New Visitor Registration</h2>
          <form action="new-member.html" method="GET">
            <div class="form-group">
            <label for="identifier">Name</label>
            <input type="text" class="form-control" id="identifier" placeholder="Name">
            </div>
            <div class="form-group">
            <label for="email">Email address</label>
            <input type="text" class="form-control" id="email">
            </div>
 
            <div class="form-group">
            <label for="visitor_type">Visitor type</label>
            <select class="form-control" id="visitor_type">
            <optgroup label="Visitor type">
              <option disabled selected>Select one</option>
              <option>Academic</option>
              <option>Docent</option>
              <option>Fellow</option>
              <option>Intern</option>
              <option>Member</option>
              <option>Public</option>
              <option>Staff</option>
              <option>Volunteer</option>
            </optgroup>
          </select>
          </div>
          <input type="submit" class="btn btn-primary" value="Continue">
        </form>
      </div>
    </div>
  </body>
  <script src="js/jquery-2.1.3.js" type="text/js"></script>
  <script src="js/bootstrap.js"></script>
</html>
