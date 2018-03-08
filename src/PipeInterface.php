<?php

namespace Bdf\Pipeline;

/**
 * PipeInterface
 *
 * @author Sébastien Tanneux
 */
interface PipeInterface
{
    /**
     * Set the next pipe
     *
     * @param callable $pipe
     */
    public function setNext(callable $pipe);

    /**
     * Invoke pipe
     *
     * @param array $payload
     *
     * @return mixed
     */
    public function __invoke(...$payload);
}