<?php

namespace App\Test\Classes;

use App\Classes\Factory\DueDateCalculatorFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class CalculatoreTestCase.
 *
 * @package App\Test\Classes
 */
class CalculatoreTestCase extends TestCase {

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

}
