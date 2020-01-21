# Emarsys - Software Developer Homework

### Topic

---
A due date calculator that has two parameters (submit date/time and turnaround time). The response is a date/time when the issue resolved. 

### Installation

---
The algorithm itself is not require any extra installation process as requested in the homework's description.

Despite of that, I provided an example of use in the `index.php` file where I tested the application using PHP's native autoload function along with Composer autoloader.

As for the unit test, it requires installation that you can do by typing `composer install` in you terminal within project's root directory.

### Testing

---
To run unit tests just type `vendor/bin/phpunit`. For the detailed version of it, please use the `--testdox` option.

_Note: Be sure to be in the project's root directory._

#### Additional informations

---
There was a note in the given Homework PDF which said the following: `Do not use any third-party libraries for date/time calculations (e.g. Moment.js, Carbon, Joda, etc.) or hidden functionalities of the built-in methods.`
I was a bit confused about the `hidden functionalities of the built-in methods` part of it. If native `DateTime` and its methods are not considered as hidden functionalities it is good.

Otherwise, I would have decoupled the class of `DueDateCalculator` implementation to something else like `DueDateCalculatorDateTime` and would have made another implementation that is using simple `date()` functions and custom solutions for add/sub dates in a class like `DueDateCalculatorSimple`. Also, I would have extended my factory class with a `$type` argument and instantiate the appropriate class accordingly.


Written in PHP using PhpStorm 2019.3
