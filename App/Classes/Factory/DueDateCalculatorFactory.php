<?php

namespace App\Classes\Factory;

use App\Classes\Date\DueDateCalculator;
use App\Classes\Date\DueDateCalculatorInterface;

/**
 * Class DueDateCalculatorFactory.
 *
 * @package App\Classes\Factory
 */
final class DueDateCalculatorFactory implements DueDateCalculatorFactoryInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(): DueDateCalculatorInterface {
    return new DueDateCalculator();
  }

}
