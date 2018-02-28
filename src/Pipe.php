<?php

namespace Bdf\Pipeline;

/**
 * Pipe
 *
 * @internal
 *
 * @author Johnmeurt
 */
final class Pipe
{
    /**
     * The callback processor
     *
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * The pipe callback
     *
     * @var callable
     */
    private $callback;

    /**
     * The pipe chain
     *
     * @var callable
     */
    private $next;

    /**
     * PipeInterface constructor
     *
     * @param ProcessorInterface $processor
     * @param callable $callback
     */
    public function __construct(ProcessorInterface $processor, callable $callback)
    {
        $this->processor = $processor;
        $this->callback = $callback;
    }

    /**
     * Set the next pipe
     *
     * @param callable $pipe
     */
    public function setNext(callable $pipe)
    {
        $this->next = $pipe;
    }

    /**
     * Get the next pipe
     *
     * @return callable
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Invoke pipe
     *
     * @param array $payload
     *
     * @return mixed
     */
    public function __invoke(...$payload)
    {
        return $this->processor->process($this->callback, $payload, $this->next);
    }
}
