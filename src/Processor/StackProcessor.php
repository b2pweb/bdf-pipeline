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
     * {@inheritdoc}
     */
    public function process($callback, $payload, $next)
    {
        return $callback($next, ...$payload);
    }
}
