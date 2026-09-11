<?php

declare(strict_types=1);

namespace App\Service;

class FlashService
{
    public function __construct(
        private SessionManager $session
    ) {}

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
        $messages = $this->session->get('flash', []);

        $messages[$type][] = $message;

        $this->session->set('flash', $messages);
    }

    public function get(string $type): array
    {
        $flash = $this->session->get('flash', []);

        $messages = $flash[$type] ?? [];

        unset($flash[$type]);

        $this->session->set('flash', $flash);

        return $messages;
    }

    public function all(): array
    {
        $messages = $this->session->get('flash', []);

        $this->session->remove('flash');

        return $messages;
    }

    public function has(string $type): bool
    {
        $flash = $this->session->get('flash', []);

        return !empty($flash[$type]);
    }

    public function hasAny(): bool
    {
        return !empty(
            $this->session->get('flash', [])
        );
    }
}