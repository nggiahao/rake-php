# RAKE PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nggiahao/rake-php.svg?style=flat-square)](https://packagist.org/packages/nggiahao/rake-php)
[![Total Downloads](https://img.shields.io/packagist/dt/nggiahao/rake-php.svg?style=flat-square)](https://packagist.org/packages/nggiahao/rake-php)
[![License](https://poser.pugx.org/donatello-za/rake-php-plus/license)](https://packagist.org/packages/donatello-za/rake-php-plus)

PHP implementation of Rapid Automatic Keyword Exraction algorithm (RAKE)

## Currently Supported Languages
- English US
- Spanish

## Installation

You can install the package via composer:

```bash
composer require nggiahao/rake-php
```

## Usage

``` php
use Nggiahao\RakePhp\RakePhp;

$text = "The Criteria of compatibility of a system of linear Diophantine equations, strict inequations, and nonstrict inequations are considered the.
         Upper bounds for components of a minimal set of solutions and algorithms of construction of minimal generating sets of solutions for all types of systems are given.";

$stop_words = new English();
$keywords = RakePhp::create($stop_words)->extract($text)->sortByScore('desc')->keywords();

```

```
array:16 [
  "linear diophantine equations" => 9.0
  "minimal generating sets" => 8.5
  "minimal set" => 4.5
  "strict inequations" => 4.0
  "nonstrict inequations" => 4.0
  "upper bounds" => 4.0
  "criteria" => 1.0
  "compatibility" => 1.0
  "considered" => 1.0
  "components" => 1.0
  "solutions" => 1.0
  "algorithms" => 1.0
  "construction" => 1.0
  "types" => 1.0
  "systems" => 1.0
  "given" => 1.0
]

```

### Testing

``` bash
composer test
```

## Credits

- [Nguyen Gia Hao](https://github.com/nggiahao)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).