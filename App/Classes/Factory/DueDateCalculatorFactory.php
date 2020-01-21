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
  public static function create(\DateTimeInterface $workingHoursFrom = NULL, \DateTimeInterface $workingHoursTo = NULL): DueDateCalculatorInterface {
    return new DueDateCalculator($workingHoursFrom, $workingHoursTo);
  }

}
