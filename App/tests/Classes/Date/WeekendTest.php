<?php

namespace App\Test\Classes\Date;

use App\Classes\Factory\DueDateCalculatorFactory;
use PHPUnit\Framework\TestCase;

class WeekendTest extends TestCase {

  /**
   * DueDateCalculator object.
   *
   * @var \App\Classes\Date\DueDateCalculatorInterface|NULL
   */
  protected $calculator;

  protected function setUp() {
    $this->calculator = DueDateCalculatorFactory::create();
  }

  protected function tearDown() {
    $this->calculator = NULL;
  }

  public function testIsNotWeekend() {
    $date = new \DateTime('2020-01-21 14:12:00');
    $result = $this->calculator->isWeekend($date);

    $this->assertEquals(FALSE, $result);
  }

  public function testIsWeekend() {
    // Saturday midnight.
    $date = new \DateTime('2020-01-25 00:00:00');
    $result = $this->calculator->isWeekend($date);

    $this->assertEquals(TRUE, $result);

    // Right before Monday midnight.
    $date = new \DateTime('2020-01-26 23:59:59');
    $result = $this->calculator->isWeekend($date);

    $this->assertEquals(TRUE, $result);

    // Monday midnight.
    $date = new \DateTime('2020-01-27 00:00:00');
    $result = $this->calculator->isWeekend($date);

    $this->assertEquals(FALSE, $result);
  }

}
