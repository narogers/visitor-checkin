<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddle;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\DomCrawler\Crawler;

class AdminCheckinControllerTest extends TestCase {
  use DatabaseTransactions;

  public function setUp() {
    parent::setUp();
    $this->seed("TestDatabaseSeeder");
    Carbon::setTestNow(Carbon::createFromDate(2015, 3, 19));
  }

  public function testIndexWithToday() {
    $response = $this->action("GET", "AdminCheckinController@getIndex");
    $view = $response->original;
    $crawler = new Crawler($view->__toString());

    $this->assertEquals("today", $view["range"]);  
    $this->assertCount(8, $crawler->filter("tbody[id*=entries] > tr"));
  } 

  public function testIndexWithMonth() {
    $response = $this->action("GET", "AdminCheckinController@getIndex",
      ["range" => "month"]);
    $view = $response->original;
    $crawler = new Crawler($view->__toString());
 
    $this->assertEquals("month", $view["range"]);
    $this->assertCount(24, $crawler->filter("tbody[id*=entries] > tr"));
  }

  public function testShowWithUser() {
    // WIP: Pick up here
    // 
    // 1. Add three more checkins for User 1 for the month of March
    // 2. Look at table and assert four checkins are present
  }

  public function tearDown() {
    Carbon::setTestNow();
  }
}

