<?php

namespace Classes\Factory;

use Classes\Date\DueDateCalculator;
use Classes\Date\DueDateCalculatorInterface;

/**
 * Class DueDateCalculatorFactory.
 *
 * @package Classes\Factory
 */
final class DueDateCalculatorFactory implements DueDateCalculatorFactoryInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(): DueDateCalculatorInterface {
    return new DueDateCalculator();
  }

}
