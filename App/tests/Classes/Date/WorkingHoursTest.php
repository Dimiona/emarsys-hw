<?php

namespace App\Test\Classes\Date;

use App\Classes\Factory\DueDateCalculatorFactory;
use PHPUnit\Framework\TestCase;

class WorkingHoursTest extends TestCase {

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

  public function testIsBetweenWorkingHours() {
    $date = new \DateTime('2020-01-21 14:12:00');
    $result = $this->calculator->isWorkingHours($date);

    $this->assertEquals(TRUE, $result);
  }

  public function testIsBeforeWorkingHours() {
    $date = new \DateTime('2020-01-21 08:59:59');
    $result = $this->calculator->isWorkingHours($date);

    $this->assertEquals(FALSE, $result);
  }

  public function test9amIsJobStart() {
    $date = new \DateTime('2020-01-21 09:00:00');
    $result = $this->calculator->isWorkingHours($date);

    $this->assertEquals(TRUE, $result);
  }

  public function testIsAfterWorkingHours() {
    $date = new \DateTime('2020-01-21 17:00:01');
    $result = $this->calculator->isWorkingHours($date);

    $this->assertEquals(FALSE, $result);
  }

  public function test5pmIsStillWorkingHours() {
    $date = new \DateTime('2020-01-21 17:00:00');
    $result = $this->calculator->isWorkingHours($date);

    $this->assertEquals(TRUE, $result);
  }

  public function testCalculateDueDateResponse() {
    $submitDate = new \DateTime('2020-01-21 14:12:00');
    $turnaroundTime = 16;
    $resolveDate = $this->calculator->CalculateDueDate($submitDate, $turnaroundTime);

    $this->assertInstanceOf(\DateTimeInterface::class, $resolveDate);
  }

}
