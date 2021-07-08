<?php

namespace MonorepoBuilder20210708\Psr\Log;

/**
 * Basic Implementation of LoggerAwareInterface.
 */
trait LoggerAwareTrait
{
    /**
     * The logger instance.
     *
     * @var LoggerInterface|null
     */
    protected $logger;
    /**
     * Sets a logger.
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(\MonorepoBuilder20210708\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}