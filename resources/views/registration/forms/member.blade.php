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
          <h2>Member</h2>
          <form action="new-termsofuse.html" method="GET">
            <div class="form-group">
              <label for="street-address">Address</label>
              <input type="text" class="form-control" id="street-address" placeholder="Street Address">

            <label for="city" class="sr-only">City</label>
            <input type="text" class="form-control" id="city" placeholder="City">

            <label for="zipcode" class="sr-only">Zip Code</label>
            <input type="text" class="form-control" id="zipcode" placeholder="Zip code">

            </div>
            <div class="form-group">
            <label for="telephone">Telephone</label>
            <input type="text" class="form-control" id="telephone" placeholder="(xxx)xxx-xxxx">
            </div>
   
            <div class="form-group">
            <label for="membership_number">Membership Number</label>
            <input type="text" class="form-control" id="membership_number">
            </div>
 
            <input type="submit" class="btn btn-primary" value="Continue">
            <span class="btn btn-primary"><a href="new-visitor.html">Go back</a></span>
        </form>
      </div>
    </div>
  </body>
  <script src="js/jquery-2.1.3.js" type="text/js"></script>
  <script src="js/bootstrap.js"></script>
</html>
