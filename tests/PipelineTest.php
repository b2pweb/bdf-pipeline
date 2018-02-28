<?php

namespace Bdf\Pipeline;

use Bdf\Pipeline\Processor\PipeProcessor;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class PipelineTest extends TestCase
{
    /**
     *
     */
    public function test_empty_pipeline()
    {
        $pipeline = new Pipeline();

        $this->assertSame(1, $pipeline->send(1));
    }

    /**
     *
     */
    public function test_pipeline()
    {
        $pipeline = new Pipeline([new Add(10), new Double]);

        $this->assertSame(22, $pipeline->send(1));
    }

    /**
     *
     */
    public function test_complex_pipeline()
    {
        $pipeline = new Pipeline();
        $pipeline->pipe(new Add(10));
        $pipeline->pipe(new Double);
        $pipeline->pipe(new Square);
        $pipeline->outlet(function($payload) {
            return $payload - 30;
        });

        $this->assertSame(646, $pipeline->send(3));
    }

    /**
     *
     */
    public function test_one()
    {
        $pipeline = new Pipeline();
        $pipeline->pipe(new Add(10));

        $this->assertSame(13, $pipeline->send(3));
    }

    /**
     *
     */
    public function test_one_prepend()
    {
        $pipeline = new Pipeline();
        $pipeline->prepend(new Add(10));

        $this->assertSame(13, $pipeline->send(3));
    }

    /**
     *
     */
    public function test_first_prepend()
    {
        $pipeline = new Pipeline();
        $pipeline->prepend(new Add(10));
        $pipeline->pipe(new Add(2));

        $this->assertSame(15, $pipeline->send(3));
    }

    /**
     *
     */
    public function test_prepend()
    {
        $pipeline = new Pipeline();
        $pipeline->pipe(new Double);
        $pipeline->prepend(new Add(10));

        $this->assertSame(26, $pipeline->send(3));
    }

    /**
     *
     */
    public function test_outlet()
    {
        $pipeline = new Pipeline();
        $pipeline->outlet(function($number) {
            return $number + 10;
        });
        $pipeline->pipe(new Add(2));

        $this->assertSame(15, $pipeline->send(3));
    }

    /**
     *
     */
    public function test_pipe_processor()
    {
        $pipeline = new Pipeline([], new PipeProcessor());
        $pipeline->pipe(new AddPipe(10));
        $pipeline->pipe(new DoublePipe);
        $pipeline->pipe(new SquarePipe);
        $pipeline->outlet(function($payload) {
            return $payload - 30;
        });

        $this->assertSame(646, $pipeline->send(3));
    }
}

//-------------

class Add
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __invoke($next, $number)
    {
        return $next($this->value + $number);
    }
}
class Double
{
    public function __invoke($next, $number)
    {
        return $next($number * 2);
    }
}class Square
{
    public function __invoke($next, $number)
    {
        return $next($number * $number);
    }
}
//-------------

class AddPipe
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __invoke($number)
    {
        return $this->value + $number;
    }
}
class DoublePipe
{
    public function __invoke($number)
    {
        return $number * 2;
    }
}class SquarePipe
{
    public function __invoke($number)
    {
        return $number * $number;
    }
}