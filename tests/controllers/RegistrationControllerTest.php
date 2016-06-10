<?php
  
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class RegistrationControllerTest extends TestCase {
  use DatabaseTransactions;

  public function setUp() {
    parent::setUp();
    $this->seed("DatabaseSeeder");
    Carbon::setTestNow(Carbon::createFromDate(2015, 3, 19));
  }

  public function testRegistrationWithNoEmail() {
    $response = $this->action("POST", "RegistrationController@postNew",
      [], ["name" => "Test User", "role" => "Member"]);
    $this->assertSessionHasErrors("email_address");
  }

  public function testRegistrationWithInvalidRole() {
    $response = $this->action("POST", "RegistrationController@postNew",
      [], ["name" => "Test User", "email_address" => "t.user@example.org"]);
    $this->assertSessionHasErrors("role");
  }

  public function testNewRegistration() {
    $response = $this->action("POST", "RegistrationController@postNew",
      [], ["name" => "Test User", "email_address" => "t.user@example.org",
      "role" => "Staff"]);

    $this->seeInDatabase("users", ["email_address" => "t.user@example.org"]);
    $this->assertSessionHas("uid");
    $this->assertRedirectedToAction("RegistrationController@getDetails", 
      ["role" => "staff"]);
    $this->see("Staff");
  }

  public function testRegistrationForExistingUser() {
    factory(App\Models\User::class)
      ->create(["email_address" => "xxx@example.org"]);
    $response = $this->action("POST", "RegistrationController@postNew",
      [], ["name" => "Test User", "email_address" => "xxx@example.org",
        "role" => "Docent"], 
        ["HTTP_REFERER" => "http://localhost/registration"]);
   
    $this->assertRedirectedToAction("RegistrationController@getIndex");
  }

  public function testPartiallyCompleteRegistration() {
    $patrons = App::make("App\Repositories\PatronInterface");
    $response = $this->action("POST", "RegistrationController@postNew",
      [], ["name" => "Test User", "email_address" => "xxx@example.org",
      "role" => "Intern"]);
    $patron = $patrons->getUser(1);
    
    $this->assertRedirectedToAction("RegistrationController@getDetails",
      ["role" => "intern"]);
    $this->assertTrue($patron->isIncomplete());
    $this->assertSessionHas("uid", $patron->id);
  }

  public function testLoadProperFormForRole() {
    $patron = factory(App\Models\User::class)
     ->create(["aleph_id" => null, "verified_user" => false]);
    Session::put("uid", $patron->id);
    $response = $this->action("GET", "RegistrationController@getDetails",
      ["role" => "Academic"]);

    $this->assertSessionHas("role", "Academic");
    $this->see("Academic");
  }

  public function testSaveRegistrationDetails() {
    $patron = factory(App\Models\User::class)
      ->create(["aleph_id" => null, "verified_user" => false]);
    $registration = factory(App\Models\Registration::class, "member")
      ->make()
      ->toArray();
    $patrons = App::make(App\Repositories\PatronInterface::class);

    Session::put("uid", $patron->id);
    Session::put("role", "Member");

    $response = $this->action("POST", "RegistrationController@postDetails",
      [], $registration);
    $patron = $patrons->getUser($patron->id);

    $this->seeInDatabase("registrations", ["user_id" => $patron->id]);
    $this->assertEquals($patron->role->role, "Member");    
    $this->assertRedirectedToAction("RegistrationController@getTermsOfUse");
  }

  public function testAgreeToTermsOfUse() {
    // 1. Set up data
    $patrons = App::make(App\Repositories\PatronInterface::class);
    $patron = factory(App\Models\User::class)->create();
    $registration = factory(App\Models\Registration::class, "fellow_or_staff")
      ->create();
    $patron->registration()->save($registration);
    $patrons->setRole($patron->id, "Fellow");

    // 2. Make calls
    Session::put("uid", $patron->id);
    $signature = str_repeat("x", 512);
    $response = $this->action("POST", "RegistrationController@postTermsOfUse",
      [], ["signature_data" => $signature]);
    $patron = $patrons->getUser($patron->id);
 
    // 3. Validate results
    $this->assertEquals($user->signature, $signature);
    $this->assertRedirectedToAction("RegistrationController@getConfirmation"); 
  }
 
  public function testConfirmationDetailsAreAccurate() {
    $patron = factory(App\Models\User::class)->create();
    $registration = factory(App\Models\Registration::class)->create();
    $patron->registration()->save($registration);
    Session::put("uid", $patron->id);
   
    $response = $this->action("GET", "RegistrationController@getConfirmation");
    
    $this->assertSessionHas("uid");
    $this->assertViewHas("user");
  }

  public function tearDown() {
    Carbon::setTestNow();
  }
}
