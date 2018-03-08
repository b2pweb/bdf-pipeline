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
     * @param callable[] $pipes
     * @param array $payload
     * @param callable|null $outlet
     *
     * @return mixed
     */
    public function process(array $pipes, array $payload, callable $outlet = null);

    /**
     * Clear processor cache
     */
    public function clearCache();
}