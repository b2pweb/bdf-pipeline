<?php

namespace Bdf\Pipeline;

/**
 * CallableFactoryInterface
 *
 * @author Sébastien Tanneux
 */
interface CallableFactoryInterface
{
    /**
     * Build the chain callable
     *
     * @param callable[] $pipes
     * @param callable|null $outlet
     *
     * @return callable
     */
    public function createCallable(array $pipes, callable $outlet = null);

    /**
     * Invoke a pipeline
     *
     * @param Pipeline $pipeline
     * @param array $payload
     *
     * @return mixed
     */
    public function callPipeline($pipeline, array $payload);
}