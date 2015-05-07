<?php
use App\Role;

class RoleTest extends TestCase {
	public function setUp() {
		parent::setUp();
		Artisan::call('db:seed');
	}

	public function testAlephMappings() {
		$r = Role::ofType('CWRU Joint Program');
		$this->assertEquals('Academic', $r->first()->role);

		$r = Role::ofType('Museum Staff');
		$this->assertEquals('Staff', $r->first()->role);

		$r = Role::ofType('Unknown Role');
		$this->assertNull($r->first());
	}
}
?>