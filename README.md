# Script runner

[![Software License][ico-license]](LICENSE.txt)
[![Build Status][ico-travis]][link-travis]
[![codecov][ico-codecov]][link-codecov]
[![Codacy Badge][ico-codacy]][link-codacy]

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
[ico-codecov]: https://codecov.io/gh/php-strict/script-runner/branch/master/graph/badge.svg
[link-codecov]: https://codecov.io/gh/php-strict/script-runner
[ico-codacy]: https://app.codacy.com/project/badge/Grade/7c78e2d59fce46e78fb65160dc530bd3
[link-codacy]: https://www.codacy.com/gh/php-strict/script-runner?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=php-strict/script-runner&amp;utm_campaign=Badge_Grade
