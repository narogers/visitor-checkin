<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddle;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\DomCrawler\Crawler;

class AdminRegistrationControllerTest extends TestCase {
  use DatabaseTransactions;

  public function setUp() {
    parent::setUp();
    $this->seed("DatabaseSeeder");
    Carbon::setTestNow(Carbon::createFromDate(2015, 3, 19));
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

  public function testShowRegistration() {
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
  }

  public function tearDown() {
    Carbon::setTestNow();
  }
}
