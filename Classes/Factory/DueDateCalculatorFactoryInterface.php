<?php

namespace Classes\Factory;

use Classes\Date\DueDateCalculatorInterface;

/**
 * Interface DueDateCalculatorFactoryInterface.
 *
 * @package Classes\Factory
 */
interface DueDateCalculatorFactoryInterface {

  /**
   * Instantiates a due date calculator.
   *
   * @return \Classes\Date\DueDateCalculatorInterface
   */
  public static function create(): DueDateCalculatorInterface;

}
