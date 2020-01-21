<?php

use Classes\Factory\DueDateCalculatorFactory;

// Basic autoload.
spl_autoload_register();

// Example of use.
$submitDate = new \DateTime('2020-01-21 16:12:00');
$turnaroundTime = 25;

$dueDateCalculator = DueDateCalculatorFactory::create();
$result = $dueDateCalculator->CalculateDueDate($submitDate, $turnaroundTime);
