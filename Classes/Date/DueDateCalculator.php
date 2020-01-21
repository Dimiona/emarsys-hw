<?php

namespace Classes\Date;

use Classes\Exception\TurnaroundTimeException;
use Classes\Exception\WorkingHoursException;

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
    if (!$this->isWorkingHours($submitDate)) {
      throw new WorkingHoursException('The submission date is out of working hours.');
    }

    if ($turnaroundTime < 1) {
      throw new TurnaroundTimeException('Invalid turnaround time. It must be greater or equal than 1.');
    }

    $workingHours = $this->getWorkingHours();
    $resolveDate = clone $submitDate;
    while ($turnaroundTime > 0) {
      $hours = (int) $workingHours;
      $minutes = ($workingHours - $hours) * 60;

      $addition = new \DateInterval('PT' . $hours . 'H' . $minutes . 'M');
      $resolveDate->add($addition);

      $turnaroundTime -= $workingHours;
    }

    return $resolveDate;
  }

}
