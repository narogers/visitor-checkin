@extends('layouts.master')

@section('content')
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
@stop
