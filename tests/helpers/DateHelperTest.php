<?php

use App\Helpers\DateHelper;
use Carbon\Carbon;

class DateHelperTest extends TestCase {
  public function setUp() {
    Carbon::setTestNow(Carbon::createFromDate(2016, 2, 5));
    parent::setUp();
  }

  public function teardown() {
    Carbon::setTestNow();
  }

  public function testLabelForToday() {
     $label = DateHelper::labelFor("today");
     $this->assertEquals($label, "February 5th, 2016");
  }

  public function testLabelForWeek() {
    $label = DateHelper::labelFor("week");
    $this->assertEquals($label, "February 1 to February 5th, 2016");
  }

  public function testLabelForMonth() {
    $label = DateHelper::labelFor("month");
    $this->assertEquals($label, "February 1 to February 5th, 2016");
  }

  public function testLabelForLastMonth() {
    $label = DateHelper::labelFor("lastmonth");
    $this->assertEquals($label, "January 1 to January 31st, 2016");
  }

  public function testRangeForToday() {
    $actual_range = DateHelper::rangeFor("today");
    $expected_range = [Carbon::now(), Carbon::now()];

    $this->assertTrue($expected_range[0]->isSameDay($actual_range[0]));
    $this->assertTrue($expected_range[1]->isSameDay($actual_range[1]));
  }

  public function testRangeForWeek() {
    $actual_range = DateHelper::rangeFor("week");
    $expected_range = [Carbon::now()->startOfWeek(), Carbon::now()];

    $this->assertTrue($expected_range[0]->isSameDay($actual_range[0]));
    $this->assertTrue($expected_range[1]->isSameDay($actual_range[1]));
  }

  public function testRangeForMonth() {
    $actual_range = DateHelper::rangeFor("month");
    $expected_range = [Carbon::now()->startOfMonth(), Carbon::now()];

    $this->assertTrue($expected_range[0]->isSameDay($actual_range[0]));
    $this->assertTrue($expected_range[1]->isSameDay($actual_range[1]));
  }

  public function testRangeForLastMonth() {
    $actual_range = DateHelper::rangeFor("lastmonth");
    $expected_range = [Carbon::now()->subMonth(1)->startOfMonth(), 
      Carbon::now()->subMonth()->endOfMonth()];

    $this->assertTrue($expected_range[0]->isSameDay($actual_range[0]));
    $this->assertTrue($expected_range[1]->isSameDay($actual_range[1]));
  }

  public function testDefaultFormat() {
    $output = DateHelper::format(Carbon::now());
    $this->assertEquals("February 5th", $output);
  }

  public function testCustomFormat() {
    $output = DateHelper::format(Carbon::now(), "Ymd");
    $this->assertEquals("20160205", $output); 
  }
}
