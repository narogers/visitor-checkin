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
		$this->aleph->shouldReceive('getPatronID')->with('registered_id_active')->andReturn('registered_id_active');
		$this->aleph->shouldReceive('isActive')->with('registered_id_active')->andReturn(true);

		$this->aleph->shouldReceive('getPatronID')->with('registered_id_expired')->andReturn('registered_id_expired');
		$this->aleph->shouldReceive('isActive')->with('registered_id_expired')->andReturn(false);

		$this->aleph->shouldReceive('getPatronID')->with('123456')->andReturn('123456');
		$this->aleph->shouldReceive('isActive')->with('123456')->andReturn(true);

		$this->aleph->shouldReceive('getPatronID')->with('654321')->andReturn('654321');
		$this->aleph->shouldReceive('isActive')->with('654321')->andReturn(false);

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
		$this->assertTrue($patron_status);
		$this->assertEquals('John Smith (Local)', $u->name);
		$this->assertEquals('count@sesame-street.org', $u->email_address);
		$this->assertEquals('the_count', $u->aleph_id);
  }

  public function testExpiredBarcode() {
  	$u = new User;
  	$u->setAlephClient($this->aleph);

		$patron_status = $u->isActive('654321');
		$this->assertFalse($patron_status);
		$this->assertEquals('John Smith (Expired)', $u->name);
		$this->assertEquals('count2@sesame-street.org', $u->email_address);
		$this->assertEquals('the_count2', $u->aleph_id);
  }
}
?>