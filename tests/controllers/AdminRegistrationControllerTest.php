<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddle;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Symfony\Component\DomCrawler\Crawler;

class AdminRegistrationControllerTest extends TestCase {
  use DatabaseTransactions;

  public function setUp() {
    parent::setUp();
    $this->seed("DatabaseSeeder");
    $this->mock = Mockery::mock('App\ILS\ILSInterface');
    Carbon::setTestNow(Carbon::createFromDate(2015, 3, 19));

    $this->mockZip = Mockery::mock(App\Services\ZipCodeInterface::class);
    $this->mockZip
      ->shouldReceive("lookup")
      ->andReturn(["city" => "Saint Louis", "state" => "XX"]);
    $this->app->instance(App\Services\ZipCodeInterface::class, $this->mockZip);
  } 

  public function testIndex() {
    $this->seed("TestDatabaseSeeder");
    $response = $this->action("GET", "AdminRegistrationController@getIndex");
    $view = $response->original;
    $crawler = new Crawler($view->__toString());

    $this->see("Pending Registrations");
    $this->assertEquals(8, $crawler->filter("span.badge")->text());
    $this->assertCount(8, $crawler->filter(".table-striped > tbody > tr"));
    // TODO: Test that completed steps are indicated properly
  }

  public function testDefaultRegistration() {
    // 1. Set up test
    $repo = App::make("App\Repositories\PatronInterface");
    $user = factory(App\Models\User::class)->create();
    $repo->setRole($user->id, "Public");
    $repo->setRegistration($user->id, 
      factory(App\Models\Registration::class)->create()->toArray());
    // 2. Call view
    $response = $this->action("GET", 
      "AdminRegistrationController@getRegistration",
      ["uid" => $user->id]);
    $view = $response->original;
    $crawler = new Crawler($view->__toString());
    // 3. Do testing
    $this->see($user->name);
    $this->see($user->email_address);
  
    $this->assertCount(1, $crawler->filter("p#address"));
    $this->assertCount(1, $crawler->filter("p#telephone"));
    $this->assertCount(1, $crawler->filter("#signature"));
  }

  public function testRegistrationForIntern() {
    // 1. Set up test
    $repo = App::make("App\Repositories\PatronInterface");
    $user = factory(App\Models\User::class)->create();
    $repo->setRole($user->id, "Intern");
    $repo->setRegistration($user->id, 
      factory(App\Models\Registration::class, "intern")->create()->toArray());
    // 2. Call view
    $response = $this->action("GET", 
      "AdminRegistrationController@getRegistration",
      ["uid" => $user->id]);
    $view = $response->original;
    $crawler = new Crawler($view->__toString());
    // 3. Do testing
    $this->see($user->name);
    $this->see($user->email_address);

    $this->assertCount(1, $crawler->filter("p#address"));
    $this->assertCount(1, $crawler->filter("p#department"));
    $this->assertCount(1, $crawler->filter("p#supervisor"));
    # Is this still a required field?
    #$this->assertCount(1, $crawler->filter("p#expires_on"));
    $this->assertCount(1, $crawler->filter("#signature"));
  }

  public function testRegistrationForFellowOrStaff() {
    // 1. Set up test
    $repo = App::make("App\Repositories\PatronInterface");
    $user = factory(App\Models\User::class)->create();
    $repo->setRole($user->id, "Staff");
    $repo->setRegistration($user->id, 
      factory(App\Models\Registration::class, "fellow_or_staff")->create()->toArray());
    // 2. Call view
    $response = $this->action("GET", 
      "AdminRegistrationController@getRegistration",
      ["uid" => $user->id]);
    $view = $response->original;
    $crawler = new Crawler($view->__toString());
    // 3. Do testing
    $this->see($user->name);
    $this->see($user->email_address);
  
    $this->assertCount(1, $crawler->filter("p#department"));
    $this->assertCount(1, $crawler->filter("p#job_title"));
    $this->assertCount(1, $crawler->filter("p#extension")); 
    $this->assertCount(1, $crawler->filter("#signature"));
  }

  /**
   * Tests default IDs and that neither is found
   */
  public function testIdsNotFound() {
    // 1. Set up test
    $repo = App::make("App\Repositories\PatronInterface");
    $user = factory(App\Models\User::class)->create(["aleph_id" => null]);
    $repo->setRole($user->id, "Staff");
    $repo->setRegistration($user->id, 
      factory(App\Models\Registration::class, "fellow_or_staff")->create()->toArray());

    $this->mock
      ->shouldReceive("getIdentifiers")
      ->andReturn(["tuser", "t.user"]);
    $this->mock
      ->shouldReceive("getPatronDetails")
      ->andReturn(null);
    $this->app
      ->instance("App\ILS\ILSInterface", $this->mock);

    // 2. Call view
    $response = $this->action("GET", 
      "AdminRegistrationController@postRegistration",
      ["uid" => $user->id, "action" => "refresh_ils"]);
    $view = $response->original;
    $crawler = new Crawler($view->__toString());

    // 3. Do testing
    $this->assertNull($user->aleph_id);
  }

  /**
   * Tests passing in an ID which is valid
   */
  public function testCustomILSIdFound() {
     // 1. Set up test
    $repo = App::make("App\Repositories\PatronInterface");
    $user = factory(App\Models\User::class)->create();
    $repo->setRole($user->id, "Intern");
    $repo->setRegistration($user->id, 
      factory(App\Models\Registration::class)->create()->toArray());

    $this->mock
      ->shouldReceive("getPatronDetails")
      ->andReturn([
          "name" => $user->name,
          "email_address" => $user->email_address,
          "id" => "t.user",
          "role" => "Intern",
        ]);
    $this->app
      ->instance("App\ILS\ILSInterface", $this->mock);

    // 2. Call view
    $response = $this->action("POST", 
      "AdminRegistrationController@postRegistration",
      ["uid" => $user->id, "action" => "refresh_ils", 
       "ils_id" => "t.user"]);
    $view = $response->original;
    $crawler = new Crawler($view->__toString());
    $user = $repo->getUser($user->id);

    // 3. Do testing
    $this->assertEquals("t.user", $user->aleph_id);
  }

  /**
   * Tests passing in an ID which is valid
   */
  public function testSetRegistrationAsVerified() {
     // 1. Set up test
    $repo = App::make("App\Repositories\PatronInterface");
    $user = factory(App\Models\User::class)->create();
    $repo->setRole($user->id, "Public");
    $repo->setRegistration($user->id, 
      factory(App\Models\Registration::class)->create()->toArray());

    // 2. Call view
    $response = $this->action("POST", 
      "AdminRegistrationController@postRegistration",
      ["uid" => $user->id, "action" => "verify_id"]);
    $view = $response->original;
    $crawler = new Crawler($view->__toString());

    // 3. Do testing
    $this->assertTrue($user->verified_user);
    $this->assertViewHas("user");
  }

  public function tearDown() {
    Carbon::setTestNow();
  }
}
