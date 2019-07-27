# NumbersToSerbianWords: Simple class to convert numbers to Serbian Words

A project done by [Sibin Grasic](https://twitter.com/etfovac)

## About

NumbersToSerbianWords is an easy-to-use class for PHP.
It was made to be a class that you could quickly include into a project and have working right away.

## Installation

### Composer

From the Command Line:

```
composer require seebeen/numberstoserbianwords:dev-master
```

In your `composer.json`:

``` json
{
    "require": {
        "seebeen/numberstoserbianwords": "dev-master"
    }
}
```

## Basic Usage

``` php
<?php

require 'vendor/autoload.php';

$number = '12721438261';

$converter = new SeeBeen\SerbianPHP\NumbersToSerbianWords($number);
echo $converter->to_words();
$converter->set_number(23831);
echo $converter->to_words();

```

### Output

```
dvanaest milijardi sedamsto dvadeset jedan milion cetristo trideset osam hiljada dvesta sezdeset jedan
dvadeset tri hiljade osamsto trideset jedan
```

## License

GPLv2

You may copy, distribute and modify the software as long as you track changes/dates in source files. Any modifications to or software including (via compiler) GPL-licensed code must also be made available under the GPL along with build & install instructions.

