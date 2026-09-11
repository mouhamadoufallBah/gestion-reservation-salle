<?php

namespace App\Exception;

use App\View\View;
use Throwable;

class ExceptionHandler
{
    public function __construct(
        private string $viewFormat, private View $view
    ) {}

    public function handle(Throwable $exception): void
    {
        $status = $this->getStatusCode($exception);

        $this->respond($exception, $status);
    }

    private function getStatusCode(Throwable $exception): int
    {
        return match (true) {
            $exception instanceof SalleIntrouvableException,
            $exception instanceof ReservationIntrouvableException,
            $exception instanceof RouteNotFoundException
                => 404,

            $exception instanceof SalleIndisponibleException
                => 409,

            $exception instanceof MethodNotAllowedException
                => 405,

            default
                => 500,
        };
    }

    private function respond(Throwable $exception, int $status): void
    {
        if ($status === 500) {
            error_log((string) $exception);
        }

        if ($this->viewFormat === 'json') {
            $this->jsonResponse(
                $status === 500
                    ? 'Une erreur interne est survenue sur le serveur.'
                    : $exception->getMessage(),
                $status
            );

            return;
        }

        $this->htmlResponse($exception, $status);
    }

    private function htmlResponse(Throwable $exception, int $status): void
    {
        http_response_code($status);

        match ($status) {
            404 => $this->view->renderView('errors/404', [
                'message' => $exception->getMessage(),
            ]),

            405 => $this->view->renderView('errors/405', [
                'allowedMethods' => $exception instanceof MethodNotAllowedException
                    ? $exception->allowedMethods
                    : [],
            ]),

            409 => $this->view->renderView('errors/409', [
                'message' => $exception->getMessage(),
            ]),

            default => $this->view->renderView('errors/500', [
                'message' => 'Une erreur interne est survenue sur le serveur.',
            ]),
        };
    }

    private function jsonResponse(string $message, int $status): void
    {
        header_remove();
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);

        echo json_encode([
            'status' => 'error',
            'message' => $message,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}