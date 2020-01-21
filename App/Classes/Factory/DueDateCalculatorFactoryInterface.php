<?php

namespace App\Classes\Factory;

use App\Classes\Date\DueDateCalculatorInterface;

/**
 * Interface DueDateCalculatorFactoryInterface.
 *
 * @package App\Classes\Factory
 */
interface DueDateCalculatorFactoryInterface {

  /**
   * Instantiates a due date calculator.
   *
   * @return \App\Classes\Date\DueDateCalculatorInterface
   */
  public static function create(): DueDateCalculatorInterface;

}
