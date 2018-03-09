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
    private $pipes = [];

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
     * @param CallableFactoryInterface $factory
     */
    public function __construct(CallableFactoryInterface $factory = null)
    {
        $this->factory = $factory ?: new StackCallableFactory();
    }

    /**
     * Set the pipes
     *
     * @param callable[] $pipes
     *
     * @return $this
     */
    public function setPipes(array $pipes)
    {
        $this->pipes = $pipes;

        return $this;
    }

    /**
     * Add a pipe at the beginning of the chain
     *
     * @param callable $first
     *
     * @return $this
     */
    public function prepend($first)
    {
        array_unshift($this->pipes, $first);

        return $this;
    }

    /**
     * Add a pipe
     *
     * @param callable $last
     *
     * @return $this
     */
    public function pipe($last)
    {
        $this->pipes[] = $last;

        return $this;
    }

    /**
     * Set the pipeline outlet
     *
     * @param callable $outlet
     *
     * @return $this
     */
    public function outlet(callable $outlet)
    {
        $this->outlet = $outlet;

        return $this;
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
