<?php

namespace App\Middleware;

use App\Exception\ExceptionHandler;
use Throwable;

class ExceptionMiddleware
{
    public function __construct(
        private ExceptionHandler $exceptionHandler
    ) {
    }

    public function handle(callable $next): void
    {
        try {
            $next();
        } catch (Throwable $exception) {
            $this->exceptionHandler->handle($exception);
        }
    }
}