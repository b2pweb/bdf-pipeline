<?php

namespace Bdf\Pipeline;

use Bdf\Pipeline\Processor\StackProcessor;

/**
 * Pipeline
 *
 * @author Sébastien Tanneux
 */
final class Pipeline
{
    /**
     * The pipes processor
     *
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * The pipes
     *
     * @var callable[]
     */
    private $pipes;

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
        $this->pipes = $pipes;
    }

    /**
     * Add a pipe at the beginning of the chain
     *
     * @param callable $first
     */
    public function prepend($first)
    {
        array_unshift($this->pipes, $first);
    }

    /**
     * Add a pipe
     *
     * @param callable $last
     */
    public function pipe($last)
    {
        $this->pipes[] = $last;
    }

    /**
     * Set the pipeline outlet
     *
     * @param callable $outlet
     */
    public function outlet(callable $outlet)
    {
        $this->outlet = $outlet;
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
        return $this->processor->process($this->pipes, $payload, $this->outlet);
    }

    /**
     * Pipeline invokation
     *
     * @param array $payload
     *
     * @return mixed
     */
    public function __invoke(...$payload)
    {
        // TODO works only with PipeProcessor
        return $this->send(...$payload);
    }

    /**
     * Clone the processor and clear its cache
     */
    public function __clone()
    {
        $this->processor = clone $this->processor;
        $this->processor->clearCache();
    }
}
