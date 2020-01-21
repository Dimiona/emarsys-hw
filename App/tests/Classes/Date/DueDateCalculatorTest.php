<?php

namespace App\Test\Classes\Date;

use App\Classes\Factory\DueDateCalculatorFactory;
use PHPUnit\Framework\TestCase;

class DueDateCalculatorTest extends TestCase {

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

  public function testCalculateDueDateResponse() {
    $submitDate = new \DateTime('2020-01-21 14:12:00');
    $turnaroundTime = 16;
    $resolveDate = $this->calculator->CalculateDueDate($submitDate, $turnaroundTime);

    $this->assertInstanceOf(\DateTimeInterface::class, $resolveDate);
  }

  public function test2DaysTurnaroundTime() {
    $submitDate = new \DateTime('2020-01-21 14:12:00');
    $turnaroundTime = 16;
    $resolveDate = $this->calculator->CalculateDueDate($submitDate, $turnaroundTime);

    $this->assertEquals('2020-01-23 14:12:00', $resolveDate->format('Y-m-d H:i:s'));
  }

  public function testHasOneWeekendInIt() {
    $submitDate = new \DateTime('2020-01-21 16:12:00');
    $turnaroundTime = 25;
    $resolveDate = $this->calculator->CalculateDueDate($submitDate, $turnaroundTime);

    $this->assertEquals('2020-01-27 09:12:00', $resolveDate->format('Y-m-d H:i:s'));
  }

  public function testHasTwoWeekendsInIt() {
    $submitDate = new \DateTime('2020-01-21 16:12:00');
    $turnaroundTime = 65;
    $resolveDate = $this->calculator->CalculateDueDate($submitDate, $turnaroundTime);

    $this->assertEquals('2020-02-03 09:12:00', $resolveDate->format('Y-m-d H:i:s'));
  }

}
