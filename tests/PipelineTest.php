<?php

namespace Bdf\Pipeline;

use Bdf\Pipeline\CallableFactory\LinkedCallableFactory;
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
    public function test_set_pipes()
    {
        $pipeline = new Pipeline();
        $pipeline->setPipes([new Add(10), new Double]);

        $this->assertSame(22, $pipeline->send(1));
    }

    /**
     *
     */
    public function test_complex_pipeline()
    {
        $pipeline = (new Pipeline())
            ->pipe(new Add(10))
            ->pipe(new Double)
            ->pipe(new Square)
            ->outlet(function($payload) {
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
    public function test_linked_callable()
    {
        $pipeline = new Pipeline(new LinkedCallableFactory());
        $pipeline->pipe(new AddPipe(10));
        $pipeline->pipe(new DoublePipe);
        $pipeline->pipe(new SquarePipe);
        $pipeline->outlet(function($payload) {
            return $payload - 30;
        });

        $this->assertSame(646, $pipeline->send(3));
    }

    /**
     *
     */
    public function test_multi_args()
    {
        $pipeline = new Pipeline();
        $pipeline->pipe(function($next, $foo, $bar) {
            return $next($foo, $bar);
        });
        $pipeline->outlet(function($foo, $bar) {
            return "$foo.$bar";
        });

        $this->assertSame('foo.bar', $pipeline->send('foo', 'bar'));
    }

    /**
     *
     */
    public function test_clone_empty_pipe()
    {
        $pipeline = new Pipeline();
        $new = clone $pipeline;

        $pipeline->pipe(new Add(30));

        $this->assertNotSame($new, $pipeline);
        $this->assertSame(1, $new->send(1));
        $this->assertSame(31, $pipeline->send(1));
    }

    /**
     *
     */
    public function test_clone_pipe()
    {
        $pipeline = new Pipeline();
        $pipeline->pipe(new Add(10));
        $pipeline->pipe(new Add(20));

        $new = clone $pipeline;

        $pipeline->pipe(new Add(30));

        $this->assertSame(31, $new->send(1));
        $this->assertSame(61, $pipeline->send(1));
    }

    /**
     *
     */
    public function test_chain_pipeline()
    {
        $embedded = new Pipeline(new LinkedCallableFactory());
        $embedded->pipe(new AddPipe(10));
        $embedded->pipe(new AddPipe(20));
        $embedded->outlet(function($value) {
            // Will not be called
            return -1 * $value;
        });

        $pipeline = new Pipeline(new LinkedCallableFactory());
        $pipeline->pipe(new AddPipe(30));
        $pipeline->pipe($embedded);
        $pipeline->pipe(new AddPipe(40));
        $embedded->pipe(new AddPipe(40));

        $this->assertSame(-61, $pipeline->send(1));
    }

    /**
     *
     */
    public function test_clone_chain_pipeline()
    {
        $embedded = new Pipeline(new LinkedCallableFactory());
        $embedded->pipe(new AddPipe(10));
        $embedded->pipe(new AddPipe(20));

        $pipeline = new Pipeline(new LinkedCallableFactory());
        $pipeline->pipe(new AddPipe(30));
        $pipeline->pipe($embedded);
        $pipeline->pipe(new AddPipe(40));

        $new = clone $pipeline;

        $pipeline->pipe(new AddPipe(40));

        $this->assertSame(101, $new->send(1));
        $this->assertSame(141, $pipeline->send(1));
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

    public function setValue($value)
    {
        $this->value = $value;
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