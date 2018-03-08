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
        if ($this->callable === null) {
            $this->callable = $this->buildCallable($pipes, $outlet);
        }

        $callable = $this->callable;

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
    private function buildCallable($pipes, $outlet)
    {
        $callable = function (...$payload) use($outlet) {
            if ($outlet !== null) {
                return $outlet(...$payload);
            }

            return $payload[0] ?? null;
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
