# Script runner

[![Software License][ico-license]](LICENSE.txt)
[![Build Status][ico-travis]][link-travis]

Class for running PHP CLI script in several separate processes.

## Requirements

*   PHP >= 7.1

## Install

Use class as standalone:

```php
require 'ScriptRunner.php';
use PhpStrict\ScriptRunner\ScriptRunner;
```

Install with [Composer](http://getcomposer.org):
    
```bash
composer require php-strict/script-runner
```

## Usage

```php
use PhpStrict\ScriptRunner\ScriptRunner;

//params: path_to_script, processes count (if omitted then system CPU cores count will be used)
$sr = new ScriptRunner('script.php', 4);
$sr->run();
```

May be used with [CooperativeWorker](https://github.com/php-strict/cooperative-worker)
to split one sequential process into several separated processes and keep them from collisions 
and making the same job twice.
For eaxample it is possible to convert process of parsing log files from cycle (where log files parsing one-by-one)
into several separated processes where each process take job from common temporary storage (queue).

## Tests

To execute the test suite, you'll need [Codeception](https://codeception.com/).

```bash
vendor/bin/codecept run
```

[ico-license]: https://img.shields.io/badge/license-GPL-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/php-strict/script-runner/master.svg?style=flat-square
[link-travis]: https://travis-ci.org/php-strict/script-runner
