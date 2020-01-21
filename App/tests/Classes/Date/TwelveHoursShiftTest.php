<?php

namespace App\Test\Classes\Date;

use App\Classes\Factory\DueDateCalculatorFactory;
use PHPUnit\Framework\TestCase;

class TwelveHoursShiftTest extends TestCase {

  /**
   * DueDateCalculator object.
   *
   * @var \App\Classes\Date\DueDateCalculatorInterface|NULL
   */
  protected $calculator;

  protected function setUp() {
    // Set up a twelve hours shift.
    $workingHoursFrom = new \DateTime('08:00:00');
    $workingHoursTo = new \DateTime('20:00:00');
    $this->calculator = DueDateCalculatorFactory::create($workingHoursFrom, $workingHoursTo);
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
    $submitDate = new \DateTime('2020-01-21 08:00:00');
    $turnaroundTime = 16;
    $resolveDate = $this->calculator->CalculateDueDate($submitDate, $turnaroundTime);

    $this->assertEquals('2020-01-22 12:00:00', $resolveDate->format('Y-m-d H:i:s'));
  }

  public function testHasOneWeekendInIt() {
    $submitDate = new \DateTime('2020-01-21 14:00:00');
    $turnaroundTime = 49;
    $resolveDate = $this->calculator->CalculateDueDate($submitDate, $turnaroundTime);

    $this->assertEquals('2020-01-27 15:00:00', $resolveDate->format('Y-m-d H:i:s'));
  }

  public function testHasTwoWeekendsInIt() {
    $submitDate = new \DateTime('2020-01-21 14:00:00');
    $turnaroundTime = 65;
    $resolveDate = $this->calculator->CalculateDueDate($submitDate, $turnaroundTime);

    $this->assertEquals('2020-01-28 19:00:00', $resolveDate->format('Y-m-d H:i:s'));
  }

}
