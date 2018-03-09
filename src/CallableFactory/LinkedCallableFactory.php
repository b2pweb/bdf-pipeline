<?php

namespace Bdf\Pipeline\CallableFactory;

use Bdf\Pipeline\CallableFactoryInterface;

/**
 * LinkedCallableFactory
 *
 * @author Sébastien Tanneux
 */
class LinkedCallableFactory implements CallableFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createCallable(array $pipes, callable $outlet = null)
    {
        if ($outlet !== null) {
            $pipes[] = $outlet;
        }

        return function($payload) use($pipes) {
            foreach ($pipes as $pipe) {
                $payload = $pipe($payload);
            }

            return $payload;
        };
    }
}
