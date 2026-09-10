<?php

namespace App\Exception;

use RuntimeException;

class MethodNotAllowedException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly array $allowedMethods = []
    ) {
        parent::__construct($message);
    }
}