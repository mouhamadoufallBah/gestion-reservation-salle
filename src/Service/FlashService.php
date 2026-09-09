<?php

declare(strict_types=1);

namespace App\Service;

class FlashService
{
    public function __construct()
    {
        $this->startSessionIfNeeded();
    }

    public function success(string $message): void
    {
        $this->add('success', $message);
    }

    public function error(string $message): void
    {
        $this->add('danger', $message);
    }

    public function warning(string $message): void
    {
        $this->add('warning', $message);
    }

    public function info(string $message): void
    {
        $this->add('info', $message);
    }

    public function add(string $type, string $message): void
    {
        $this->startSessionIfNeeded();
        $_SESSION['flash'][$type][] = $message;
    }

    public function get(string $type): array
    {
        $this->startSessionIfNeeded();
        $messages = $_SESSION['flash'][$type] ?? [];
        unset($_SESSION['flash'][$type]);

        return $messages;
    }

    public function all(): array
    {
        $this->startSessionIfNeeded();
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $messages;
    }

    public function has(string $type): bool
    {
        $this->startSessionIfNeeded();

        return !empty($_SESSION['flash'][$type]);
    }

    public function hasAny(): bool
    {
        $this->startSessionIfNeeded();

        return !empty($_SESSION['flash']);
    }

    private function startSessionIfNeeded(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }
}
