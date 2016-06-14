<?php

use App\ILS\AlephService;
use Carbon\Carbon;
use Mockery;
use phpmock\mockery\PHPMockery;

class AlephServiceTest extends TestCase {
	public function setUp() {
	  parent::setUp();
	}

	public function tearDown() {
      Carbon::setTestNow();
      Mockery::close();
	}
		
	public function testIdentifier() {
      $ilsService = new AlephService;
      $identifiers = $ilsService->getIdentifiers("John SMITH");

      $this->assertContains("J.SMITH", $identifiers);
      $this->assertContains("JSMITH", $identifiers); 
	}
  
    public function testIdentifierWithComma() {
      $ilsService = new AlephService;
      $identifiers = $ilsService->getIdentifiers("User, Test");

      $this->assertContains("T.USER", $identifiers);
      $this->assertContains("TUSER", $identifiers);
    }

    public function testIdentifierWithHyphen() {
      $ilsService = new AlephService;
      $identifiers = $ilsService->getIdentifiers("John Smith-Jones");

      $this->assertContains("J.SMITHJONES", $identifiers);
      $this->assertContains("JSMITHJONES", $identifiers);
    }

    public function testIdentifierWithPrefix() {
      $ilsService = new AlephService;
      $identifiers = $ilsService->getIdentifiers("John McSmith");

      $this->assertContains("J.MCSMITH", $identifiers);
      $this->assertContains("JMCSMITH", $identifiers);
    }

    public function testIdentificationOfBadId() {
      $xml_document =  file_get_contents(
        base_path() . "/tests/fixtures/idNotFound.xml");
      $this->webServiceMock = PHPMockery::mock("App\ILS", "file_get_contents")
        ->andReturn($xml_document);

      $ilsService = new AlephService;
      $ilsID = $ilsService->getILSId("mock.user");
      
      $this->assertNull($ilsID);
    }

    public function testGetIdentifierFromAleph() {
      $xml_document =  file_get_contents(
        base_path() . "/tests/fixtures/idVerification.xml");
      $this->webServiceMock = PHPMockery::mock("App\ILS", "file_get_contents")
        ->andReturn($xml_document);

      $ilsService = new AlephService;
      $ilsID = $ilsService->getILSId("mock.user");
      
      $this->assertEquals("M.USER", $ilsID);
    }

    public function testQueryForActiveUser() {
      $xml_document = file_get_contents(
        base_path() . "/tests/fixtures/validStatus.xml");
      $this->webServiceMock = PHPMockery::mock("App\ILS", "file_get_contents")
        ->andReturn($xml_document);
      Carbon::setTestNow(Carbon::createFromDate(2016, 02, 12));
      $this->webServiceMock = PHPMockery::mock("App\ILS", "time")
        ->andReturn(Carbon::now()->getTimestamp());
      $ilsService = new AlephService;
      
      $this->assertTrue($ilsService->isActive("mock.user"));
      $this->assertFalse($ilsService->isExpired("mock.user"));
    }

    public function testStatusForExpiredUser() {
      $xml_document = file_get_contents(
        base_path() . "/tests/fixtures/validStatus.xml");
      $this->webServiceMock = PHPMockery::mock("App\ILS", "file_get_contents")
        ->andReturn($xml_document);
      Carbon::setTestNow(Carbon::createFromDate(2019, 05, 21));
      $this->webServiceMock = PHPMockery::mock("App\ILS", "time")
        ->andReturn(Carbon::now()->getTimestamp());
      $ilsService = new AlephService;
      
      $this->assertFalse($ilsService->isActive("mock.user"));
      $this->assertTrue($ilsService->isExpired("mock.user"));
    }

    public function testStatusForInvalidUser() {
      $xml_document = file_get_contents(
        base_path() . "/tests/fixtures/invalidResponse.xml");
      $this->webServiceMock = PHPMockery::mock("App\ILS", "file_get_contents")
        ->andReturn($xml_document);
      Carbon::setTestNow(Carbon::createFromDate(2019, 05, 21));
      $this->webServiceMock = PHPMockery::mock("App\ILS", "time")
        ->andReturn(Carbon::now()->getTimestamp());
      $ilsService = new AlephService;
      
      $this->assertFalse($ilsService->isActive("mock.user"));
      $this->assertTrue($ilsService->isExpired("mock.user"));
    }

    public function testRetrieveDetailsForValidUser() {
      $details_xml = file_get_contents(
        base_path() . "/tests/fixtures/validAddress.xml");
      $status_xml = file_get_contents(
        base_path() . "/tests/fixtures/validStatus.xml");
      $this->webServiceMock = PHPMockery::mock("App\ILS", "file_get_contents")
        ->andReturn($details_xml, $status_xml, null);
      
      $ilsService = new AlephService;
      $details = $ilsService->getPatronDetails("mock.user");

      $this->assertNotNull($details);
      $this->assertEquals("Mock User", $details["name"]);
      $this->assertEquals("mock.user@example.org", $details["email"]);
      $this->assertEquals("Museum Staff", $details["role"]);
      $this->assertEquals("mock.user", $details["id"]);
    }

    public function testRetrieveDetailsForInvalidId() {
      $error_xml = file_get_contents(
        base_path() . "/tests/fixtures/invalidResponse.xml");
      $this->webServiceMock = PHPMockery::mock("App\ILS", "file_get_contents")
        ->andReturn($error_xml);
      
      $ilsService = new AlephService;
      $details = $ilsService->getPatronDetails("no.user");

      $this->assertNull($details);
    }
}
