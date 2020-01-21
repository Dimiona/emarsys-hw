<?php

use App\Classes\Factory\DueDateCalculatorFactory;

// Basic autoload.
spl_autoload_register();

// Autoload with composer.
// require_once 'vendor/autoload.php';

// Example of use.
$submitDate = new \DateTime('2020-01-21 14:12:00');
$turnaroundTime = 16;

$dueDateCalculator = DueDateCalculatorFactory::create();
$result = $dueDateCalculator->CalculateDueDate($submitDate, $turnaroundTime);
