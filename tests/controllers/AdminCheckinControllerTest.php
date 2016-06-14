<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\DomCrawler\Crawler;

class AdminCheckinControllerTest extends TestCase {
  use DatabaseTransactions;

  public function setUp() {
    parent::setUp();
    $this->seed("DatabaseSeeder");
    Carbon::setTestNow(Carbon::createFromDate(2015, 3, 19));
  }

  public function testIndexWithToday() {
    $this->seed("TestDatabaseSeeder");
    $response = $this->action("GET", "AdminCheckinController@getIndex");
    $view = $response->original;
    $crawler = new Crawler($view->__toString());

    $this->assertEquals("today", $view["range"]);  
    $this->assertCount(8, $crawler->filter("tbody[id*=entries] > tr"));
  } 

  public function testIndexWithMonth() {
    $this->seed("TestDatabaseSeeder");
    $response = $this->action("GET", "AdminCheckinController@getIndex",
      ["range" => "month"]);
    $view = $response->original;
    $crawler = new Crawler($view->__toString());
 
    $this->assertViewHas("range", "month");
    $this->assertCount(24, $crawler->filter("tbody[id*=entries] > tr"));
  }

  public function testCheckins() {
    $repo = App::make("App\Repositories\PatronInterface");
    $user = factory(App\Models\User::class)->create();
    $repo->setRole($user->id, "Docent");
    $repo->checkin($user->id, Carbon::now());
    $repo->checkin($user->id, Carbon::now()->subDay(4));
    $repo->checkin($user->id, Carbon::now()->subMonth(2));

    $response = $this->action("GET", "AdminCheckinController@getCheckins",
      ["range" => "month", $uid = $user->id]);
    $view = $response->original;
    $crawler = new Crawler($view->__toString());

    $this->assertEquals($view["user"]->id, $user->id);
    $this->assertViewHas("range", "month");
    $this->see($user->name);
    $this->see("Docent");
    $this->assertCount(2, $crawler->filter(".table-striped > tr"));
  }

  public function tearDown() {
    Carbon::setTestNow();
  }
}

