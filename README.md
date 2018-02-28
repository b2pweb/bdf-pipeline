## B2PWeb Pipeline

[![Build Status](https://travis-ci.org/b2pweb/pipeline.svg?branch=master)](https://travis-ci.org/b2pweb/pipeline)


### Usage Instructions

A basic and classic use of pipeline with a pipe processor.

```PHP
use Bdf\Pipeline\Pipeline;
use Bdf\Pipeline\Processor\PipeProcessor;

$pipeline = new Pipeline([], new PipeProcessor());
$pipeline->pipe(function($value) {
    return $value + 2;
});
$pipeline->outlet(function($value) {
    return $value;
});

// Returns 12
$pipeline->send(10);
```

The pipeline lib comes with an advanced processor (used by default).

```PHP
use Bdf\Pipeline\Pipeline;
use Bdf\Pipeline\Processor\PipeProcessor;

$pipeline = new Pipeline([], new StackProcessor());
$pipeline->pipe(function($next, $foo, $bar) {
    // do something
    return $next($foo, $bar);
});
```
