<?php

namespace App\Services\RiotAPI;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseHandler
{
    /**
     * Error Logging service
     *
     * @var LoggerInterface
     */
    private $log;

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function isSuccessful(ResponseInterface $response): bool
    {
        return 200 === $response->getStatusCode();
    }
}
