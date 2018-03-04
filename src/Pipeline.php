<?php

namespace Bdf\Pipeline;

use Bdf\Pipeline\Processor\StackProcessor;

/**
 * Pipeline
 *
 * @author Johnmeurt
 */
final class Pipeline
{
    /**
     * The pipe processor
     *
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * The first pipe from the chain
     *
     * @var Pipe
     */
    private $first;

    /**
     * The last pipe from the chain
     *
     * @var Pipe
     */
    private $last;

    /**
     * The destination of the last pipe
     *
     * @var callable
     */
    private $outlet;

    /**
     * Pipeline constructor.
     *
     * @param array $pipes
     * @param ProcessorInterface $processor
     */
    public function __construct(array $pipes = [], ProcessorInterface $processor = null)
    {
        $this->processor = $processor ?: new StackProcessor();

        // Set the default outlet
        $this->outlet = function($payload) {
            return $payload;
        };

        foreach ($pipes as $pipe) {
            $this->pipe($pipe);
        }
    }

    /**
     * Add a pipe at the beginning of the chain
     *
     * @param callable $first
     */
    public function prepend($first)
    {
        $this->add($first, true);
    }

    /**
     * Add a pipe
     *
     * @param callable $last
     */
    public function pipe($last)
    {
        $this->add($last);
    }

    /**
     * Add a pipe
     *
     * @param callable $pipe
     * @param boolean  $prepend
     */
    private function add($pipe, $prepend = false)
    {
        // Set the outlet on last pipe
        $pipe = new Pipe($this->processor, $pipe);
        $pipe->setNext($this->outlet);

        // Detect the first pipe
        if ($this->first === null) {
            $this->first = $pipe;
            $this->last = $pipe;
        } elseif ($prepend === false) {
            $this->last->setNext($pipe);
            $this->last = $pipe;
        } else {
            $pipe->setNext($this->first);
            $this->first = $pipe;
        }
    }

    /**
     * Set the pipeline outlet
     *
     * @param callable $outlet
     */
    public function outlet(callable $outlet)
    {
        $this->outlet = $outlet;

        if ($this->last !== null) {
            $this->last->setNext($outlet);
        }
    }

    /**
     * Send the payload into the pipeline.
     *
     * @param array $payload
     *
     * @return mixed
     */
    public function send(...$payload)
    {
        $callback = $this->first ?: $this->outlet;

        return $callback(...$payload);
    }

    /**
     * Clone the pipes
     */
    public function __clone()
    {
        if ($this->first !== null) {
            $this->first = clone $this->first;
        }
    }
}
