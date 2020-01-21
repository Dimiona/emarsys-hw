<?php

namespace Classes\Date;

/**
 * Interface DueDateCalculatorInterface.
 *
 * @package Classes\Date
 */
interface DueDateCalculatorInterface {

  const WORKING_HOURS_FROM = '09:00:00';

  const WORKING_HOURS_TO = '17:00:00';

  /**
   * Calculates due date.
   *
   * @param \DateTimeInterface $submitDate
   *   Date/time of the submission.
   * @param int $turnaroundTime
   *   Turnaround time in hours (e.g. 2 days equal 16 hours).
   *
   * @return \DateTimeInterface
   *   Returns the date/time when the issue is resolved.
   */
  public function CalculateDueDate(\DateTimeInterface $submitDate, int $turnaroundTime): \DateTimeInterface;

  /**
   * Checks whether the given date is working hours or not.
   *
   * @param \DateTimeInterface $date
   *   A date to check for.
   *
   * @return bool
   *   Is working hours or not.
   */
  public function isWorkingHours(\DateTimeInterface $date): bool;

  /**
   * Checks whether the given date is weekend or not.
   *
   * @param \DateTimeInterface $date
   *   A date to check for.
   *
   * @return bool
   *   Is weekend or not.
   */
  public function isWeekend(\DateTimeInterface $date): bool;

  /**
   * Gets the duration of working hours.
   *
   * @return float
   *   Working hours.
   */
  public function getWorkingHours(): float;

}
