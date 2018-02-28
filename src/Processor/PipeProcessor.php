<?php

namespace Bdf\Pipeline\Processor;

use Bdf\Pipeline\ProcessorInterface;

/**
 * PipeProcessor
 *
 * @author Johnmeurt
 */
class PipeProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($callback, $payload, $next)
    {
        return $next($callback(...$payload));
    }
}
