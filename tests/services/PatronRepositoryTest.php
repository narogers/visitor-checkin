<?php

use App\Models\Checkin;
use App\Models\Registration;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class PatronRepositoryTest extends TestCase {
  use DatabaseTransactions;

  public function setUp() {
    parent::setUp();
    $this->repository = App::make(App\Repositories\PatronInterface::class);
  }

  public function tearDown() {
    Mockery::close();
  }

  public function testCreateNewUserWithoutEmail() {
    $model = factory(App\Models\User::class)->make()->toArray();
    unset($model["email_address"]);
     
    $user = $this->repository->createOrFindUser($model);
    
    $this->assertNull($user);
  }

  public function testCreateUser() {
    $model = factory(App\Models\User::class)->make()->toArray();
    $this->assertEquals(0, App\Models\User::count());
    $user = $this->repository->createOrFindUser($model);
 
    $this->assertEquals(1, App\Models\User::count());
    $this->seeInDatabase("users", ["email_address" => $model["email_address"]]);
  }

  public function testFindExistingUser() {
    $template = factory(App\Models\User::class)->create()->toArray();
    $this->assertEquals(1, App\Models\User::count());
    $user = $this->repository->createOrFindUser($template);

    $this->assertEquals(1, App\Models\User::count());
    $this->seeInDatabase("users", ["email_address" => $template["email_address"]]);
  }

  public function testFindWithExtraProperties() {
    $template = factory(App\Models\User::class)->create()->toArray();
    $template["fake_property"] = "This is a test field";

    $user = $this->repository->getUserWhere($template);
 
    $this->seeInDatabase("users", ["email_address" => $template["email_address"]]);
    $this->assertNull($user);
  }

  public function testMatchForPartialName() {
    $test_user = factory(App\Models\User::class)->create(["name" => "Test User"]);
    factory(App\Models\User::class)->create(["name" => "Second User"]);
    $user = $this->repository->getUserWhere(["name" => "User"]);

    $this->assertEquals("Test User", $user->name);
    $this->assertEquals($test_user->email_address, $user->email_address);
  }

  public function testMatchesForPartialName() {
    factory(App\Models\User::class, 3)
      ->create()
      ->each(function($user) {
        $user->name = $user->name . " Ipsum";
        $user->save();
      });

    $users = $this->repository->getUsers(["name" => "Ipsum"]);
  
    $this->assertEquals(3, count($users));
    foreach($users as $user) {
      $this->assertEquals(1, preg_match("/Ipsum$/", $user->name));
    }
  }

  public function testUpdateUser() {
    $user = factory(App\Models\User::class)->create();
    $this->assertEquals(1, App\Models\User::count());

    $fields = ["name" => "PHPUnit Test User",
               "user_id" => 999];
    $this->assertNotEquals(999, $user->id);
    $status = $this->repository->update($user->id, $fields);
    $updated_user = $this->repository->getUserWhere(["email_address" => $user->email_address]);

    $this->assertTrue($status);
    $this->assertEquals($updated_user->id, $user->id);
    $this->seeInDatabase("users", ["name" => "PHPUnit Test User"]);
  }

  public function testCheckin() {
    $user = factory(App\Models\User::class)->create();
    $this->assertEquals(0, $user->checkins()->count());

    Carbon::setTestNow(Carbon::createFromDate(2016, 4, 1));
    $this->repository->checkin($user->id);
    $user = $this->repository->getUser($user->id);

    $this->assertEquals(1, $user->checkins()->count());
    $this->seeInDatabase("checkins", ["user_id" => $user->id]);
    // Reset date for future tests
    Carbon::setTestNow();
  }

  public function testSetKnownRole() {
    $user = factory(App\Models\User::class)->create();
    $this->seed("DatabaseSeeder");

    $this->assertNull($user->role);
    $this->repository->setRole($user->id, "Staff");
    $user = $user->fresh();
    $this->assertEquals($user->role->role, "Staff");
  }

  public function testSetInvalidRole() {
    $user = factory(App\Models\User::class)->create();
    $this->seed("DatabaseSeeder");
 
    $this->assertNull($user->role);
    $this->setExpectedException(ModelNotFoundException::class);
    $this->repository->setRole($user->id, "InvalidRole");
  }

  public function testDefaultRolesExist() {
    $this->seed("DatabaseSeeder");
   
    $this->assertTrue($this->repository->hasRole("Academic"));
    $this->assertTrue($this->repository->hasRole("Docent"));
    $this->assertTrue($this->repository->hasRole("Fellow"));
    $this->assertTrue($this->repository->hasRole("Intern"));
    $this->assertTrue($this->repository->hasRole("Member"));
    $this->assertTrue($this->repository->hasRole("Public"));
    $this->assertTrue($this->repository->hasRole("Staff"));
    $this->assertTrue($this->repository->hasRole("Volunteer"));
  }

  public function testRegisteredUsers() {
    factory(App\Models\User::class, 3)->create(["aleph_id" => null,
      "verified_user" => false]);
    factory(App\Models\User::class, 3)->create(["aleph_id" => null,
      "verified_user" => true]);
    factory(App\Models\User::class, 3)->create(["verified_user" => true]);
    
    $this->assertEquals(3, count($this->repository->getRegisteredUsers()));
  }

  public function testPendingRegistrations() {
    factory(App\Models\User::class, 3)->create(["aleph_id" => null,
      "verified_user" => false]);
    factory(App\Models\User::class, 3)->create(["aleph_id" => null,
      "verified_user" => true]);
    factory(App\Models\User::class, 3)->create(["verified_user" => true]);
  
    $this->assertEquals(6, 
      count($this->repository->getPendingRegistrations()));  
  }
}
