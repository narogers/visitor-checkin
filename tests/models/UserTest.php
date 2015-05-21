<?php
use App\User;
use App\Services\AlephClient as AlephClient;
use \Mockery;

class UserTest extends TestCase {
	protected $aleph;

	public function setUp() {
		parent::setUp();
		Artisan::call('db:seed');

		$this->aleph = Mockery::mock('App\Services\AlephClient');
		$this->aleph->shouldReceive('getPatronID')
		  ->andReturnUsing(function($patron_id) {
		  	# List the IDs that should return themselves
		  	$valid_ids = ['registered_id_active', 'registered_id_expired',
		  								'123456', '654321'];
		  	if (in_array($patron_id, $valid_ids)) {
		  		return $patron_id;
		  	} else {
		  		return null;
		  	}
		  });

		$this->aleph->shouldReceive('isActive')
		  ->andReturnUsing(function($patron_id) {
		  	$active_ids = ['registered_id_active', '123456'];
		  	$expired_ids = ['registered_id_expired', '654321'];
		  	if (in_array($patron_id, $active_ids)) {
		  		return true;
		  	} else if (in_array($patron_id, $expired_ids)) {
		  		return false;
		  	} else {
		  		return null;
		  	}
		  });

		# Mock up responses for the getPatronDetails function to save making XML 
		# requests and remove variability from the process since we are not actually
		# testing Aleph itself
		$this->aleph->shouldReceive('getPatronDetails')->with('123456')
			->andReturn(['name'=>'John Smith (Aleph)', 'email'=>'count@sesame-street.org',
									 'role'=>'Docent', 'aleph_id'=>'the_count']);
		$this->aleph->shouldReceive('getPatronDetails')->with('654321')
			->andReturn(['name'=>'John Smith (Expired)', 
				           'email'=>'count2@sesame-street.org',
									 'role'=>'Docent', 'aleph_id'=>'the_count2']);

		# Create a new User record to represent an existing user - stub out the 
		# signature which is required but not important for this test. The same goes
		# for the role.
		$u = new User(['name'=>'John Smith (Local)', 
			             'email_address' => 'count@sesame-street.org',
									 'aleph_id' => null]);
		$u->role_id = 1;
		$u->signature = '';
		$u->save();
	}

	public function tearDown() {
		Mockery::close();
	}

	public function testActiveAlephId() {
		$u = new User;
		$u->setAlephClient($this->aleph);
		
		$patron_status = $u->isActive('registered_id_active');
		print $patron_status;
		$this->assertTrue($patron_status);
	}

	public function testExpiredAlephId() {
		$u = new User;
		$u->setAlephClient($this->aleph);

		$patron_status = $u->isActive('registered_id_expired');
		print $patron_status;
		$this->assertFalse($patron_status);
	}

	/**
	 * Test a record which already exists in the database and should only
	 * update the barcode and aleph ID
	 */
  public function testActiveBarcode() {
  	$u = User::where('email_address', 'count@sesame-street.org')->first();
  	$u->setAlephClient($this->aleph);

  	$this->assertEquals('John Smith (Local)', $u->name);
  	$this->assertNull($u->aleph_id);

		$patron_status= $u->isActive('123456');
		$u->importPatronDetails('123456');
		$u->save();

		$this->assertTrue($patron_status);
		$this->assertEquals('John Smith (Local)', $u->name);
		$this->assertEquals('count@sesame-street.org', $u->email_address);
		$this->assertEquals('the_count', $u->aleph_id);
  }

  public function testExpiredBarcode() {
  	$u = new User;
  	$u->setAlephClient($this->aleph);

		$patron_status = $u->isActive('654321');
		$u->importPatronDetails('654321');
		$u->save();

		$this->assertFalse($patron_status);
		$this->assertEquals('John Smith (Expired)', $u->name);
		$this->assertEquals('count2@sesame-street.org', $u->email_address);
		$this->assertEquals('the_count2', $u->aleph_id);
  }

  public function testMissingRecord() {
  	$u = new User;
  	$u->setAlephClient($this->aleph);

  	$patron_status = $u->isActive('no-such-user');
  	$this->assertFalse($patron_status);
  }

  public function testOneCheckinPerDay() {
    $u = User::where('email_address', 'count@sesame-street.org')->first();
  	$u->setAlephClient($this->aleph);

  	$this->assertEquals(0, sizeof($u->checkins));
  	$u->addCheckin();

  	# Need to reload the model to ensure the change
  	$u = User::where('email_address', 'count@sesame-street.org')->first();
  	$this->assertEquals(1, sizeof($u->checkins));
  	$u->addCheckin();

    $u = User::where('email_address', 'count@sesame-street.org')->first();
  	$this->assertEquals(1, sizeof($u->checkins));
  }
}
?>