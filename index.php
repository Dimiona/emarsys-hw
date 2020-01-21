<?php

use Classes\Factory\DueDateCalculatorFactory;

// Basic autoload.
spl_autoload_register();

// Example of use.
$dueDateCalculator = DueDateCalculatorFactory::create();

// Current datetime a.k.a 'now'.
$submitDate = new \DateTime();
$turnaroundTime = 48;
$dueDateCalculator->CalculateDueDate($submitDate, $turnaroundTime);
