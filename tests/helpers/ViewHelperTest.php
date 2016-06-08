<?php

use App\Helpers\ViewHelper;

class ViewHelperTest extends TestCase {
  public function testMaskForGMail() {
    $mask = ViewHelper::mask("me@gmail.com");
    $this->assertEquals("me******@gm******.com", $mask);
  }

  public function testMaskForCountry() {
    $mask = ViewHelper::mask("queen@royalpalace.co.uk");
    $this->assertEquals("qu******@ro******.uk", $mask);
  }

  public function testInvalidMask() {
    $mask = ViewHelper::mask("NotAnEmailAddress");
    $this->assertNull($mask);
  }

  public function testLabelForLowerCase() {
    $label = ViewHelper::labelFor("the quick brown fox");
    $this->assertEquals("The Quick Brown Fox", $label);
  }

  public function testLabelForUpperCase() {
    $label = ViewHelper::labelFor("THE QUICK BROWN FOX");
    $this->assertEquals("The Quick Brown Fox", $label);
  }
  
  public function testLabelForMixedCase() {
    $label = ViewHelper::labelFor("The QUICK brOWN fOX");
    $this->assertEquals("The Quick Brown Fox", $label);
  }
}
