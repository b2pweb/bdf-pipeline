<?php

namespace Bdf\Pipeline\Processor;

use Bdf\Pipeline\ProcessorInterface;

/**
 * StackProcessor
 *
 * @author Johnmeurt
 */
class StackProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($callback, $payload, $next)
    {
        return $callback($next, ...$payload);
    }
}
