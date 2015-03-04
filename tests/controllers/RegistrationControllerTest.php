<?php
  use App\Registration;
  use App\Http\Controllers\RegistrationController;

  class RegistrationControllerTest extends TestCase {

  	public function testIndex() {
  		// Make sure the session is purged before you begin the test
  		$this->action('POST', 'RegistrationController@postNew');

  		// If everything went right we should get a default registration with
  		// no values set yet
  		var_dump(Session::all());
  		$this->assertSessionHas('registration');
  		
  		$empty_registration = Session::get('registration');
  		assertNull('empty_registration->name');
  		assertNull('empty_registration->email_address');
  		assertNull('empty_registration->registration_type');
  	}
  }
?>