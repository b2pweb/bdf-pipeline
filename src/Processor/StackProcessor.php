<?php

namespace Bdf\Pipeline\Processor;

use Bdf\Pipeline\ProcessorInterface;

/**
 * StackProcessor
 *
 * @author Sébastien Tanneux
 */
class StackProcessor implements ProcessorInterface
{
    /**
     * The pipes callable
     *
     * @var callable
     */
    private $callable;

    /**
     * {@inheritdoc}
     */
    public function process(array $pipes, array $payload, callable $outlet = null)
    {
        $callable = $this->getCallable($pipes, $outlet);

        return $callable(...$payload);
    }

    /**
     * Build the chain callable
     *
     * @param array $pipes
     * @param callable|null $outlet
     *
     * @return \Closure
     */
    private function getCallable($pipes, $outlet)
    {
        if ($this->callable !== null) {
            return $this->callable;
        }

        if ($outlet !== null) {
            $callable = $outlet;
        } else {
            $callable = function ($payload) {
                return $payload;
            };
        };

        while ($pipe = array_pop($pipes)) {
            $callable = function (...$payload) use ($pipe, $callable) {
                return $pipe($callable, ...$payload);
            };
        }

        return $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function clearCache()
    {
        $this->callable = null;
    }
}
