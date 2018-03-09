<?php

namespace Bdf\Pipeline\CallableFactory;

use Bdf\Pipeline\CallableFactoryInterface;

/**
 * StackCallableFactory
 *
 * @author Sébastien Tanneux
 */
class StackCallableFactory implements CallableFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createCallable(array $pipes, callable $outlet = null)
    {
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
}
