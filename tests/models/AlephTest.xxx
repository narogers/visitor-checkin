<?php
use App\User;
use App\Services\AlephClient as AlephClient;
use \Mockery;

# Tests the generation of Aleph IDs to cover a number of cases that
# might come up during the process of using the system
class AlephTest extends TestCase {
	protected $aleph;

	public function setUp() {
	  parent::setUp();
		Artisan::call('db:seed');

		$this->aleph = Mockery::mock('App\Services\AlephClient');
	}

	public function tearDown() {
		Mockery::close();
	}
		
	public function testFirstNameLastName() {
		$u = new User;
		$u->name = "Sample Patron";
		$aleph_keys = $u->getAlephKeys();

		$this->assertEquals("S.PATRON", $aleph_keys[0]);
		$this->assertEquals("SPATRON", $aleph_keys[1]);
	}

	public function testNameWithComma() {
		$u = new User;
		$u->name = "Patron, Sample";
		$aleph_keys = $u->getAlephKeys();

		$this->assertEquals("S.PATRON", $aleph_keys[0]);
		$this->assertEquals("SPATRON", $aleph_keys[1]);
	}

	public function testLowerCaseName() {
		$u = new User;
		$u->name = "sample patron";
		$aleph_keys = $u->getAlephKeys();

		$this->assertEquals("S.PATRON", $aleph_keys[0]);
		$this->assertEquals("SPATRON", $aleph_keys[1]);
	}

	public function testParentheticalName() {
		$u = new User;
		$u->name = "Sample (Local) Patron";
		$aleph_keys = $u->getAlephKeys();

		$this->assertEquals("S.PATRON", $aleph_keys[0]);
		$this->assertEquals("SPATRON", $aleph_keys[1]);
	}

	public function testHypenatedName() {
		$u = new User;
		$u->name = "Sample Hyphenated-Patron";
		$aleph_keys = $u->getAlephKeys();

		$this->assertEquals("S.HYPHENATED", $aleph_keys[0]);
		$this->assertEquals("SHYPHENATED", $aleph_keys[1]);
	}

	public function testReserveHyphenatedName() {
		$u = new User;
		$u->name = "hyphenated-patron, sample";
		$aleph_keys = $u->getAlephKeys();

		$this->assertEquals("S.HYPHENATED", $aleph_keys[0]);
		$this->assertEquals("SHYPHENATED", $aleph_keys[1]);
	}

	public function testFullLegalName() {
		$u = new User;
		$u->name = "Sample Mock Patron";
		$aleph_keys = $u->getAlephKeys();

		$this->assertEquals("S.PATRON", $aleph_keys[0]);
		$this->assertEquals("SPATRON", $aleph_keys[1]);
	}

	public function testExistingAlephKeys() {
		$u = new User;
		$u->name = "Sample Patron";
		$u->aleph_id = "sample.patron";
		$u->barcode = "123456789";

		$aleph_keys = $u->getAlephKeys();

		$this->assertEquals("sample.patron", $aleph_keys[0]);
		$this->assertEquals("123456789", $aleph_keys[1]);
	}
}
?>