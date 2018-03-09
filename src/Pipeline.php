<?php

namespace Bdf\Pipeline;

use Bdf\Pipeline\CallableFactory\StackCallableFactory;

/**
 * Pipeline
 *
 * @author Sébastien Tanneux
 */
final class Pipeline
{
    /**
     * The callable factory
     *
     * @var CallableFactoryInterface
     */
    private $factory;

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
     * The built callable
     *
     * @var callable
     */
    private $callable;

    /**
     * Pipeline constructor.
     *
     * @param array $pipes
     * @param CallableFactoryInterface $factory
     */
    public function __construct(array $pipes = [], CallableFactoryInterface $factory = null)
    {
        $this->factory = $factory ?: new StackCallableFactory();
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
        if ($this->callable === null) {
            $this->callable = $this->factory->createCallable($this->pipes, $this->outlet);
        }

        $callable = $this->callable;
        return $callable(...$payload);
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
        // TODO works only with LinkedCallableFactory
        return $this->send(...$payload);
    }

    /**
     * Clear the callable
     */
    public function __clone()
    {
        $this->callable = null;
    }
}
