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
   *   Working hours from.
   * @param \DateTime|NULL $workingHoursTo
   *   Working hours to. Value lesser than 'from' date/time will be ignored.
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
      // Working hours 'to' lesser than 'from' date/time will be ignored.
      // @todo - Should be throw exception instead?
      if ($workingHoursTo < $this->workingHoursFrom) {
        $workingHoursTo = new \DateTime(self::WORKING_HOURS_TO);
      }

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
  final public function isWorkingHours(\DateTime $date): bool {
    return $date >= $this->workingHoursFrom && $date <= $this->workingHoursTo;
  }

  /**
   * {@inheritdoc}
   */
  final public function isWeekend(\DateTime $date): bool {
    $dayOfWeek = $date->format('N');
    return $dayOfWeek > 5;
  }

  /**
   * {@inheritdoc}
   */
  final public function getWorkingHours(): float {
    // @todo - We could you DateTime's diff() method here, maybe.
    return (float) ($this->workingHoursTo->getTimestamp() - $this->workingHoursFrom->getTimestamp()) / (60 * 60);
  }

}
