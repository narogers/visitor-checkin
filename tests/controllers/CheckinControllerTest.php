<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Symfony\Component\DomCrawler\Crawler;

class CheckinControllerTest extends TestCase {
  use DatabaseTransactions;

  public function setUp() {
    parent::setUp();
    Carbon::setTestNow(Carbon::createFromDate(2015, 3, 19));
    $this->mockPatron = Mockery::mock("App\Repositories\PatronInterface");
    $this->mockILS = Mockery::mock("App\ILS\ILSInterface");
    
    // DO not mock up the patron in the same way since the underlying database
    // is ephemeral
    $this->app->instance("App\ILS\ILSInterface", $this->mockILS);
  }

  public function testBarcodeNotFound() {
    // 1. Set up mocks
    $patrons = App::make("App\Repositories\PatronInterface");
    $this->mockPatron
      ->shouldReceive("getRegisteredUsers")
      ->with("1234567890")
      ->andReturn(null);

    // 2. Make calls
    $response = $this->action("POST", "CheckinController@postIndex",
      ["barcode" => "1234567890"]);

    // 3. See if things happened
    $this->assertRedirectedToAction("CheckinController@getNotFound");
  }

  public function testNameWithExactMatch() {
    // 1. Set up mocks
    $patron = factory(App\Models\User::class)
      ->create(["name" => "John Smith", "aleph_id" => "j.smith"]);
      
    $this->mockILS
      ->shouldReceive("isActive")
      ->with("j.smith")
      ->andReturn(true);

    // 2. Make calls
    $response = $this->action("POST", "CheckinController@postIndex",
     ["name" => "John Smith"]);

    // 3. Test results
    $this->assertRedirectedToAction("CheckinController@getConfirmation");
    $this->assertSessionHas("uid", $patron->id);
  }

  public function testCheckinWithMultipleMatches() {
    // 1. Set up mocks
    $patrons = [
      factory(App\Models\User::class)->create(["name" => "Test User"]),
      factory(App\Models\User::class)->create(["name" => "Second Test User"])
    ];

    // 2. Make calls
    $respose = $this->action("POST", "CheckinController@postIndex",
      ["name" => "User"]);
  
    // 3. Test results
    $this->assertRedirectedToAction("CheckinController@getSelect");
    $this->assertSessionHas("users");
  }

  public function testTooManyMatchesFound() {
    // 1. Set up mocks
    factory(App\Models\User::class, 15)
      ->create()
      ->each(function($u) {
        $u->name .= " Duplicate";
        $u->save();
      });
    
    // 2. Make calls
    $response = $this->action("POST", "CheckinController@postIndex",
      ["name" => "Duplicate"]);  
 
    // 3. Test results
    $this->assertRedirectedToAction("CheckinController@getNotFound");
  }

  public function testRedirectWithBarcode() {
    $response = $this->action("GET", "CheckinController@getCheckin",
      ["barcode" => "1234567890"]);
    $this->assertRedirectedToAction("CheckinController@postIndex");
  }

  public function testRedirectWithString() {
    $response = $this->action("GET", "CheckinController@getCheckin",
      ["name" => "Nobody Anywhere"]);
    $this->assertRedirectedToAction("CheckinController@postIndex");  
  }

  public function testCheckinRedirectionWithNullParameter() {
    $response = $this->action("GET", "CheckinController@getCheckin");
    $this->assertRedirectedToAction("CheckinController@getIndex");
  }

  public function testTermsOfUseRenewal() {
    // 1. Set up test
    $patrons = App::make("App\Repositories\PatronInterface");
    $patron = factory(App\Models\User::class)->create();
    $signature = str_repeat("x", 512);

    // 2. Make call
    $this->session(["uid" => $patron->id]);
    $response = $this->action("POST", "CheckinController@postExpired",
      [], ["signature_data" => $signature]);
      
    // fresh() does not work here 
    $patron = $patrons->getUser($patron->id);

    // 3. Validate results against expectations
    $this->assertEquals($patron->signature, $signature);
    $this->assertSessionHas("uid");
    $this->assertRedirectedToAction("CheckinController@getConfirmation");
  }

  public function testConfirmationResetsSession() {
    $patron = factory(App\Models\User::class)->create();
    
    $this->session(["uid" => $patron->id]);
    $response = $this->action("GET", "CheckinController@getConfirmation");
    
    $this->assertResponseStatus(200);
    $this->assertSessionMissing("uid");
  }

  public function tearDown() {
    Carbon::setTestNow();
    Mockery::close();
  }
}
