<?php

namespace Bdf\Pipeline\Processor;

use Bdf\Pipeline\ProcessorInterface;

/**
 * PipeProcessor
 *
 * @author Sébastien Tanneux
 */
class PipeProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(array $pipes, array $payload, callable $outlet = null)
    {
        $payload = $payload[0] ?? null;

        foreach ($pipes as $pipe) {
            $payload = $pipe($payload);
        }

        if ($outlet !== null) {
            return $outlet($payload);
        }

        return $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function clearCache()
    {

    }
}
