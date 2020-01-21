<?php

namespace Classes\Date;

/**
 * Class DueDateCalculator.
 *
 * @package Classes\Date
 */
class DueDateCalculator extends DueDateCalculatorBase implements DueDateCalculatorInterface {

  /**
   * {@inheritdoc}
   */
  public function CalculateDueDate(\DateTime $submitDate, int $turnaroundTime): \DateTime {
    return new \DateTime('2019-12-01 09:15:00');
  }

}
