<?php

namespace Classes\Date;

/**
 * Class DueDateCalculatorBase.
 *
 * @package Classes\Date
 */
abstract class DueDateCalculatorBase implements DueDateCalculatorInterface {

  /**
   * Datetime of 'from' working hours.
   *
   * @var \DateTime
   */
  protected $workingHoursFrom;

  /**
   * Datetime of 'to' working hours.
   *
   * @var \DateTime
   */
  protected $workingHoursTo;

  /**
   * DueDateCalculatorBase constructor.
   *
   * @param \DateTime|NULL $workingHoursFrom
   * @param \DateTime|NULL $workingHoursTo
   *
   * @throws \Exception
   */
  public function __construct(\DateTime $workingHoursFrom = NULL, \DateTime $workingHoursTo = NULL) {
    if (!is_null($workingHoursFrom)) {
      $this->workingHoursFrom = $workingHoursFrom;
    }
    else {
      $this->workingHoursFrom = new \DateTime(self::WORKING_HOURS_FROM);
    }

    if (!is_null($workingHoursTo)) {
      $this->workingHoursTo = $workingHoursTo;
    }
    else {
      $this->workingHoursTo = new \DateTime(self::WORKING_HOURS_TO);
    }
  }

  /**
   * {@inheritdoc}
   */
  abstract function CalculateDueDate(\DateTime $submitDate, int $turnaroundTime): \DateTime;

  /**
   * {@inheritdoc}
   */
  public function isWorkingHours(\DateTime $date): bool {
    return $date >= $this->workingHoursFrom && $date <= $this->workingHoursTo;
  }

  /**
   * {@inheritdoc}
   */
  public function isWeekend(\DateTime $date): bool {
    $dayOfWeek = $date->format('N');
    return $dayOfWeek > 5;
  }

}
