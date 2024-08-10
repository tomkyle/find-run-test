<h1 align="center">FRT · Find and Run Test</h1>





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
