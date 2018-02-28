# B2PWeb Pipeline

A PHP Pipeline pattern.

[![Build Status](https://travis-ci.org/b2pweb/pipeline.svg?branch=master)](https://travis-ci.org/b2pweb/pipeline)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/b2pweb/bdf-pipeline/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/b2pweb/bdf-pipeline/?branch=master)

## Install via composer
```bash
$ composer require b2pweb/bdf-pipeline
```

## Usage Instructions

A basic and classic use of pipeline with a pipe processor.

```PHP
use Bdf\Pipeline\Pipeline;
use Bdf\Pipeline\Processor\PipeProcessor;

$pipeline = new Pipeline([], new PipeProcessor());
$pipeline->pipe(function($value) {
    return $value + 2;
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
    // Do something
    ...
    
    $result = $next($foo, $bar);
    
    // Do something else
    ...
    
    return $result;
});
$pipeline->outlet(function($foo, $bar) {
    return "${foo}.${bar}";
});
// Manage multiple parameters
echo $pipeline->send('foo', 'bar'); // Print foo.bar
```

## Ok, So ?

You can use this package as a classic pipe, but it was designed to be easily extended:

```PHP
$pipeline->pipe(new LogCommand());
$pipeline->outlet(new CreateUserHandler());

...

$pipeline->prepend(new TransactionnalCommand());
$pipeline->send(new CreateUserCommand());
```

```PHP
class TransactionnalCommand
{
    public function __invoke($next, $command)
    {
        try {
            $result = $next($command);
            
            // Commit and return the result
            ...
            return $result;
        } catch (\Throwable $exception) {
            // Rollback and propagate exception
            throw $exception;
        }
    }
}
```
