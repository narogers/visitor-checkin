<?php
use App\Models\User;
use \Mockery;

class UserTest extends TestCase {
  protected $aleph;

  public function setUp() {
    parent::setUp();
  }

  public function tearDown() {
  }

  public function testCompleteStatus() {
    $u = factory(App\Models\User::class)->make(["verified_user" => true]);
    $this->assertTrue($u->isComplete());
    $this->assertFalse($u->isIncomplete());
  }

  public function testIncompleteStatus() {
    $u = factory(App\Models\User::class)->make(["verified_user" => false]);
    $this->assertTrue($u->isIncomplete());
    $this->assertFalse($u->isComplete());
  }
}
?>
