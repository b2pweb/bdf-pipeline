<?php

namespace Bdf\Pipeline\Pipe;

use Bdf\Pipeline\PipeInterface;
use Bdf\Pipeline\ProcessorInterface;

/**
 * A pipe
 *
 * @internal
 *
 * @author Sébastien Tanneux
 */
final class Pipe implements PipeInterface
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
     * Pipe constructor
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function __invoke(...$payload)
    {
        return $this->processor->process($this->callback, $payload, $this->next);
    }

    /**
     * Clone the pipe
     */
    public function __clone()
    {
        if ($this->next instanceof PipeInterface) {
            $this->next = clone $this->next;
        }
    }
}
