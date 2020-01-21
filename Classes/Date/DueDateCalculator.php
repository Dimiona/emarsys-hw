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
   * Calculates due date.
   *
   * @param \DateTimeInterface $submitDate
   *   Date/time of the submission.
   * @param int $turnaroundTime
   *   Turnaround time in hours (e.g. 2 days equal 16 hours).
   *
   * @return \DateTimeInterface
   *   Returns the date/time when the issue is resolved.
   *
   * @throws \Classes\Exception\TurnaroundTimeException
   * @throws \Classes\Exception\WorkingHoursException
   * @throws \Exception
   */
  public function CalculateDueDate(\DateTimeInterface $submitDate, int $turnaroundTime): \DateTimeInterface {
    if (!$this->isWorkingHours($submitDate)) {
      throw new WorkingHoursException('The submission date is out of working hours.');
    }

    if ($turnaroundTime < 1) {
      throw new TurnaroundTimeException('Invalid turnaround time. It must be greater or equal than 1.');
    }

    $resolveDate = clone $submitDate;
    // Makes our job/life easier and get rid of float numbers.
    $resolveDate->setTime($submitDate->format('H'), 0);

    $this->calculate($submitDate, $resolveDate, $turnaroundTime);

    return $resolveDate;
  }

  /**
   * Calculates due date.
   *
   * @param \DateTimeInterface $submitDate
   *   Date/time of the submission.
   * @param \DateTimeInterface $resolveDate
   *   Date/time object of when the issue will be resolved.
   * @param int $turnaroundTime
   *   Turnaround time in hours (e.g. 2 days equal 16 hours).
   *
   * @throws \Exception
   */
  protected function calculate(\DateTimeInterface $submitDate, \DateTimeInterface $resolveDate, int $turnaroundTime) {
    $backupMinutes = new \DateInterval('PT' . $submitDate->format('i') . 'M');
    $workingHours = $this->getWorkingHours();

    while ($turnaroundTime > 0) {
      // Pristine date of the actual cycle.
      $pristineDate = clone $resolveDate;

      if ($hadWeekend = $this->handleWeekend($resolveDate)) {
        continue;
      }

      if ($hadWeekend && $this->handleMondayAfterWeekend($resolveDate)) {
        continue;
      }

      $deductionTime = $workingHours;
      if ($turnaroundTime <= $deductionTime) {
        $deductionTime = $turnaroundTime;
        $deductionTime += ((int) $backupMinutes->format('%i') / 60);
      }

      $hours = (int) $deductionTime;
      $minutes = ($deductionTime - $hours) * 60;
      $addition = new \DateInterval('PT' . $hours . 'H' . $minutes . 'M');
      $resolveDate->add($addition);

      $deductionTime = $this->handleTimeOverflow($resolveDate, $pristineDate, $deductionTime);

      $turnaroundTime -= $deductionTime;
    }

    $this->handleRemainingDays($resolveDate);
  }

  /**
   * Handles weekend.
   *
   * @param \DateTimeInterface $resolveDate
   *   Date/time object of when the issue will be resolved.
   *
   * @return bool
   *   Weekend handled or not.
   *
   * @throws \Exception
   */
  protected function handleWeekend(\DateTimeInterface $resolveDate): bool {
    if ($this->isWeekend($resolveDate)) {
      $addition = new \DateInterval('PT24H');
      $resolveDate->add($addition);

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Handles hour and minute of resolve date if it's Monday after weekend.
   *
   * @param \DateTimeInterface $resolveDate
   *   Date/time object of when the issue will be resolved.
   *
   * @return bool
   *   Monday handled or not.
   */
  protected function handleMondayAfterWeekend(\DateTimeInterface $resolveDate): bool {
    if ($resolveDate->format('N') == 1) {
      $resolveDate->setTime(
        $this->workingHoursFrom->format('H'),
        $this->workingHoursFrom->format('i')
      );

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Handles time overflow.
   *
   * @param \DateTimeInterface $resolveDate
   *   Date/time object of when the issue will be resolved.
   * @param \DateTimeInterface $pristineDate
   *   Pristine date of the actual cycle.
   * @param float $deductionTime
   *   Deduction time.
   *
   * @return float
   *   Modified deduction time if time overflown.
   *
   * @throws \Exception
   */
  protected function handleTimeOverflow(\DateTimeInterface $resolveDate, \DateTimeInterface $pristineDate, float $deductionTime): float {
    if ($this->isWorkingHours($resolveDate)) {
      return $deductionTime;
    }

    $difference = $this->getDatesDifference($pristineDate, $resolveDate);

    // Deducts from resolve date by the difference of overflow.
    $deductionHours = (int) $difference;
    $deductionMinutes = (int) ($difference - $deductionHours) * 60;
    $deduction = new \DateInterval('PT' . $deductionHours . 'H' . $deductionMinutes . 'M');
    $resolveDate->sub($deduction);

    $deductionTime -= $deductionHours;
    $deductionTime -= ($deductionMinutes / 100);

    // Addition to next working day.
    $toNextDay = 24 - $this->getWorkingHours();
    $toNextDayHours = (int) $toNextDay;
    $toNextDayMinutes = (int) ($toNextDay - $toNextDayHours) * 60;
    $addition = new \DateInterval('PT' . $toNextDayHours . 'H' . $toNextDayMinutes . 'M');
    $resolveDate->add($addition);

    return $deductionTime;
  }

  /**
   * Gets the difference of two dates in hours.
   *
   * @param \DateTimeInterface $fromDate
   *   From date.
   * @param \DateTimeInterface toDate
   *   To date.
   *
   * @return float
   *   Difference in hours.
   */
  protected function getDatesDifference(\DateTimeInterface $fromDate, \DateTimeInterface $toDate) {
    $contextDate = clone $fromDate;
    $contextDate->setTime(
      $this->workingHoursTo->format('H'),
      $this->workingHoursTo->format('i')
    );

    return (float) abs($toDate->getTimestamp() - $contextDate->getTimestamp()) / (60 * 60);
  }

  /**
   * Handles remaining days.
   *
   * @param \DateTimeInterface $resolveDate
   *   Date/time object of when the issue will be resolved.
   *
   * @throws \Exception
   */
  protected function handleRemainingDays(\DateTimeInterface $resolveDate) {
    $done = FALSE;
    while ($done !== TRUE) {
      if ($this->handleWeekend($resolveDate)) {
        continue;
      }

      $done = TRUE;
    }
  }

}
