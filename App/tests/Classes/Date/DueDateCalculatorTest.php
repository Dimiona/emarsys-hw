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

}
