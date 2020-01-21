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
   * @param \DateTime $submitDate
   *   Date/time of the submission.
   * @param int $turnaroundTime
   *   Turnaround time in hours (e.g. 2 days equal 16 hours).
   *
   * @return \DateTime
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

    $backupMinutes = new \DateInterval('PT' . $submitDate->format('i') . 'M');
    $workingHours = $this->getWorkingHours();
    $hadWeekend = FALSE;
    while ($turnaroundTime > 0) {
      $tempDate = clone $resolveDate;

      // Handles weekend.
      if ($this->isWeekend($resolveDate)) {
        $addition = new \DateInterval('PT24H');
        $resolveDate->add($addition);

        $hadWeekend = TRUE;

        continue;
      }

      // After weekend(s), starts Monday from the time of working hours.
      if (
        $hadWeekend
        && $resolveDate->format('N') == 1
      ) {
        $resolveDate->setTime(
          $this->workingHoursFrom->format('H'),
          $this->workingHoursFrom->format('i')
        );

        $hadWeekend = FALSE;

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

      if (!$this->isWorkingHours($resolveDate)) {
        $difference = $this->getDatesDifference($tempDate, $resolveDate);

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
      }

      $turnaroundTime -= $deductionTime;
    }

    $done = FALSE;
    while ($done !== TRUE) {
      // Handles weekend.
      if ($this->isWeekend($resolveDate)) {
        $addition = new \DateInterval('PT24H');
        $resolveDate->add($addition);

        continue;
      }

      $done = TRUE;
    }

    return $resolveDate;
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

}
