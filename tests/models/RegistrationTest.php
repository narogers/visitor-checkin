<?php
use App\Registration;

class RegistrationTest extends TestCase {
	public function testTelephoneConversion() {
		$reg = new Registration;
		/**
		 * 4 digits should remain 4 digits
		 */
		$formatted_number = $reg->getTelephoneAttribute('1234');
		$this->assertEquals('1234', $formatted_number);
		/**
		 * 7 digits should have a hyphen inserted
		 */
		$formatted_number = $reg->getTelephoneAttribute('1234567');
		$this->assertEquals('123-4567', $formatted_number);
		/**
		 * 10 digits should appear as (xxx)xxx-xxxx
		 */
		$formatted_number = $reg->getTelephoneAttribute('1234567890');
		$this->assertEquals('(123)456-7890', $formatted_number);
	}
}
?>