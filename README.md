<h1 align="center">FRT · Find and Run Test</h1>

[![Packagist](https://img.shields.io/packagist/v/tomkyle/find-run-test.svg?style=flat)](https://packagist.org/packages/tomkyle/find-run-test )
[![PHP version](https://img.shields.io/packagist/php-v/tomkyle/find-run-test.svg)](https://packagist.org/packages/tomkyle/find-run-test )
[![PHP Composer](https://github.com/tomkyle/frt/actions/workflows/php.yml/badge.svg)](https://github.com/tomkyle/frt/actions/workflows/php.yml) 
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)

## Installation

```bash
$ composer require tomkyle/find-run-test
```

## Usage

Pass the PHP file of which the source code has changed to the `frt` executable. 

```bash
$ vendor/bin/frt <PHP_FILE>
```

FRT will look for any Phpunit test file which suits the class name of the changed file. For example:

1. You update file `src/MyClass.php`
2. FRT will look for any PhpUnit file inside the project’s tests directory.
3. If there is a test file `MyClassTest.php`, FRT will execute it using PhpUnit’s `--filter` option.

## Development

### Clone repo and install requirements

```bash
$ git clone git@github.com:tomkyle/frt.git
$ composer install
$ npm install
```

### Watch source and run various tests

This will watch changes inside the **src/** and **tests/** directories and run a series of tests:

1. Find and run the according unit test with *PHPUnit*.
2. Find possible bugs and documentation isses using *phpstan*. 
3. Analyse code style and give hints on newer syntax using *Rector*.

```bash
$ npm run watch
```

### Run all tests

```bash
$ npm run phpunit
```
