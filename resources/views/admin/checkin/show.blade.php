<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visitor Checkin</title>

     <link href="../css/bootstrap.min.css" rel="stylesheet">
     <link href="../css/bootstrap-overrides.css" rel="stylesheet">
     <link href="../css/admin-overrides.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="http://library.clevelandart.org">
            <img alt="Ingalls Library home page" src="../images/cma-logo.png">
          </a>
       </div>
       <!-- Since there are only three links run them across the top -->
       <ul class="nav navbar-nav">
         <li class="active"><a href="index.html">Home</a></li>
         <li><a href="usage-home.html">Library usage</a></li>
         <li><a href="pending-home.html">Pending registrations</a></li>
       </ul>
  </nav>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ol class="breadcrumb">
          <li><a href="index.html">Home</a></li>
          <li><a href="usage-home.html">Library Usage</a></li>
          <li class="active">Jane Smith</li>
        </ol>
      </div>
      <div class="col-lg-12">
        <h1>Visitor Details</h1>
        <hr />
        <h2>Jane Smith (j.smith@clevelandart.org)</h2>
        <p><span class="glyphicon glyphicon-education"></span>Academic Guest</p>
        
        <ul class="list-group col-lg-4"> 
          <li class="list-group-item disabled"><strong>Total Checkins <span class="badge">4</span></li>
          <li class="list-group-item">February 9, 2015</li>
          <li class="list-group-item">February 7, 2015</li>
          <li class="list-group-item">February 1, 2015</li>
          <li class="list-group-item">January 28, 2015</li>
        </ul>
    </div>
    </div>
  </div>
 </body>
  <script src="../js/jquery-2.1.3.js"></script>
  <script src="../js/bootstrap.js"></script>
</html>
