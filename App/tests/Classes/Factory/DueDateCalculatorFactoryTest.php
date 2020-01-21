<?php

namespace App\Test\Classes\Factory;

use App\Classes\Date\DueDateCalculatorInterface;
use App\Classes\Factory\DueDateCalculatorFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class DueDateCalculatorFactoryTest.
 *
 * @package App\Test
 */
class DueDateCalculatorFactoryTest extends TestCase {

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

  /**
   * Test that is it an object.
   */
  public function testIsObject() {
    $this->assertIsObject($this->calculator);
  }

  /**
   * Test that is instance of DueDateCalculatorInterface.
   */
  public function testIsInstanceOfDuedatecalculator() {
    $this->assertInstanceOf(DueDateCalculatorInterface::class, $this->calculator);
  }

}
