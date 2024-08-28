<h1 align="center">FRT · Find and Run Test</h1>

[![Packagist](https://img.shields.io/packagist/v/tomkyle/find-run-test.svg?style=flat)](https://packagist.org/packages/tomkyle/find-run-test )
[![PHP version](https://img.shields.io/packagist/php-v/tomkyle/find-run-test.svg)](https://packagist.org/packages/tomkyle/find-run-test )
[![PHP Composer](https://github.com/tomkyle/frt/actions/workflows/php.yml/badge.svg)](https://github.com/tomkyle/frt/actions/workflows/php.yml) 
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)

## About

**FRT automatically executes the associated PHPUnit test for a changed source code file.**

It takes a different approach to [spatie/phpunit-watcher](https://packagist.org/packages/spatie/phpunit-watcher), a mature und well-developed testing tool. Its goal is to automatically re-execute the entire PHPUnit test suite when source code files are changed. The `phpunit-watcher watch` command starts an own watch process which executes the *full* test suite once a file has changed. However, the executed tests can, however, be restricted to *specific* tests using the  `--filter` option. This means that regardless of which file is changed or saved, only the specified unit tests are executed.

```bash
$ vendor/bin/phpunit-watcher watch
$ vendor/bin/phpunit-watcher watch --filter=certain_test
```

Compared to this package, *tomkyle/find-run-test* takes a different approach: After saving a file, only the PHPUnit test associated with the modified file is executed. 

| COMPARISON                             | tomkyle/frt                        | spatie/phpunit-watcher         |
| -------------------------------------- | ---------------------------------- | ------------------------------ |
| Watch process                          | via *chokidar* in package.json     | *phpunit-watcher* command      |
| Configuration                          | CLI options                        | Config file and CLI options    |
| Customization                          | basic                              | better                         |
| Chaining with phpstan, phpcs or rector | via built-in shell `&&` operators  | none                           |
| Open shells needed                     | One                                | Two                            |
| Tests run                              | Those associated with changed file | *All* or *all specified* tests |

## Installation

Install FRT with Composer:

```bash
$ composer require tomkyle/find-run-test
```

## How it works

The `vendor/bin/frt` executable expects the PHP file of which the source code has changed as argument. It will  then look for any PHPUnit test file which suits the class name of the changed file and run the associated test. For example:

1. You update file `src/MyClass.php` and pass that file as argument to FRT.
   ```bash
   $ vendor/bin/frt src/MyClass.php
   ```

2. FRT will look for any PHPUnit file inside the project’s `tests` directory.

3. If there is a test file `MyClassTest.php`, FRT will execute it using PHPUnit’s `--filter` option.

## Usage

Our goal is to automate testing as much as possible, and so do we with running unit tests. In the Node-based app developing world, [Chokidar](https://www.npmjs.com/package/chokidar) is one of the go-to file watching libraries, most often used for frontend building with *webpack, gulp, workbox,* and such. We will utilize it for FRT. 

Install *Chokidar* with NPM:

```bash
$ npm install --dev chokidar-cli
```

Create a `watch` task inside the project root’s **package.json**, for example:

```json
{
  "name": "my-app",
  "devDependencies": {
    "chokidar-cli": "^3.0.0"
  },
  "scripts": {
    "watch": "chokidar \"src/**/*.php\" -c \"./vendor/bin/frt\"",
  }
}
```

Back to our project root: Start the watch process with `npm run watch`. Chokidar will now report any PHP source code change and hand over the changed file to FRT, which in turn will call PHPUnit:

```bash
$ npm run watch
# Output will be like ...

Watching "src/**/*.php" ..
```

Assuming we just edited `src/SomeNamespace/MyClass.php`, we get this:

```
change:src/SomeNamespace/MyClass.php
PHPUnit 11.3.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.10
Configuration: /Users/john_doe/vendor-project/phpunit.dist.xml

....  4 / 4 (100%)

Time: 00:00.016, Memory: 10.00 MB

My Class (tests\Unit\MyClassTest)
 ✔ Construct
 ✔ Get styles
 ✔ Get colors
 ✔ Get throws exception for invalid color

OK (4 tests, 5 assertions)
```

In case no associated PHPUnit test can be found, FRT will report this:

```
No test available: src/SomeInterface.php
```

## Chain with other test tools

Install the PHP development tools using Composer:

```bash
$ composer require --dev phpstan/phpstan
$ composer require --dev rector/rector
$ compsoer require --dev friendsofphp/php-cs-fixer
# If not already
$ composer require --dev phpunit/phpunit
```

Add the PHP tools as NPM scripts to package.json – see the full example in [examples/package.json](./examples/package.json)

```json
{
  "name": "my-app",
  "devDependencies": {
    "chokidar-cli": "^3.0.0",
    "npm-run-all": "^4.1.5"
  },
  "scripts": {

    "watch": "npm-run-all -p watch:*",
    "watch:src": "chokidar \"src/**/*.php\" -c \"./vendor/bin/frt {path} && npm run phpstan {path} && npm run rector {path}\"",
    "watch:tests": "chokidar \"tests/**/*.php\" -c \"npm run phpunit:short {path}\"",

    "phpstan": "./vendor/bin/phpstan --no-progress analyse",

    "phpcs"       : "./vendor/bin/php-cs-fixer fix --verbose --diff --dry-run",
    "phpcs:apply" : "./vendor/bin/php-cs-fixer fix --verbose --diff",

    "rector": "./vendor/bin/rector process --dry-run",
    "rector:apply": "./vendor/bin/rector process",

    "phpunit": "./vendor/bin/phpunit",
    "phpunit:short": "npm run phpunit -- --no-coverage",
  }
}
```

Start watching:

```bash
$ npm run watch
# Output will be like ...

Watching "src/**/*.php" ..
```



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
