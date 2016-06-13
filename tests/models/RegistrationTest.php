<?php
use App\Models\Registration;

class RegistrationTest extends TestCase {
  public function setUp() {
    parent::setUp();
    $this->reg = new Registration;
  }

  public function testConversionOfPhoneExtension() {
	/**
	 * 4 digits should remain 4 digits
	 */
    $this->reg->telephone = "1234";
	$this->assertEquals('1234', $this->reg->telephone);
    $this->reg->save();
    $this->seeInDatabase("registrations", ["telephone" => "1234"]);
  }

   public function testConversionOfPhoneNumber() {
	/**
	 * 7 digits should have a hyphen inserted
	 */
	$this->reg->telephone = "1234567";
    $this->reg->save();
	$this->assertEquals('123-4567', $this->reg->telephone);	
    $this->seeInDatabase("registrations", ["telephone" => "1234567"]);
  }

  public function testNumberWithAreaCode() {
   /**
	 * 10 digits should appear as (xxx)xxx-xxxx
	 */
    $this->reg->telephone = "1234567890";
    $this->reg->save();
	$this->assertEquals('(123)456-7890', $this->reg->telephone);
    $this->seeInDatabase("registrations", ["telephone" => "1234567890"]);
  }
}
?>
