<?php

namespace Bdf\Pipeline;

/**
 * ProcessorInterface
 *
 * @author Sébastien Tanneux
 */
interface ProcessorInterface
{
    /**
     * Process a callback from pipeline
     *
     * @param callable $callback
     * @param array $payload
     * @param callable $next
     *
     * @return mixed
     */
    public function process($callback, $payload, $next);
}