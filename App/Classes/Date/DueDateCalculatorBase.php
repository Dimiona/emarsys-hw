<?php

namespace App\Classes\Date;

/**
 * Class DueDateCalculatorBase.
 *
 * @package App\Classes\Date
 */
abstract class DueDateCalculatorBase implements DueDateCalculatorInterface {

  /**
   * Datetime of 'from' working hours.
   *
   * @var \DateTimeInterface
   */
  protected $workingHoursFrom;

  /**
   * Datetime of 'to' working hours.
   *
   * @var \DateTimeInterface
   */
  protected $workingHoursTo;

  /**
   * DueDateCalculatorBase constructor.
   *
   * @param \DateTimeInterface|NULL $workingHoursFrom
   *   Working hours from.
   * @param \DateTimeInterface|NULL $workingHoursTo
   *   Working hours to. Value lesser than 'from' date/time will be ignored.
   *
   * @throws \Exception
   */
  public function __construct(\DateTimeInterface $workingHoursFrom = NULL, \DateTimeInterface $workingHoursTo = NULL) {
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
  abstract function CalculateDueDate(\DateTimeInterface $submitDate, int $turnaroundTime): \DateTimeInterface;

  /**
   * {@inheritdoc}
   */
  final public function isWorkingHours(\DateTimeInterface $date): bool {
    // The two datetime object must match in year, month and day.
    $contextDate = clone $date;
    $contextDate->setDate(
      $this->workingHoursFrom->format('Y'),
      $this->workingHoursFrom->format('m'),
      $this->workingHoursFrom->format('d')
    );

    return ($contextDate >= $this->workingHoursFrom && $contextDate <= $this->workingHoursTo);
  }

  /**
   * {@inheritdoc}
   */
  final public function isWeekend(\DateTimeInterface $date): bool {
    return $date->format('N') > 5;
  }

  /**
   * {@inheritdoc}
   */
  final public function getWorkingHours(): float {
    // @todo - We could you DateTime's diff() method here, maybe.
    return (float) ($this->workingHoursTo->getTimestamp() - $this->workingHoursFrom->getTimestamp()) / (60 * 60);
  }

}
