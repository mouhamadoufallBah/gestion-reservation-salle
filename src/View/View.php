<?php

declare(strict_types=1);

namespace App\View;

use App\Service\SessionManager;

class View
{
    public function __construct(
        private SessionManager $session
    ) {}

    public function renderView(
        string $view,
        array $data = [],
        string $layout = 'base'
    ): void {
        $viewPath = BASE_PATH . '/templates/' . $view . '.html.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);

            require BASE_PATH . '/templates/errors/404.html.php';

            return;
        }

        $flashMessages = $this->session->get('flash', []);

        $this->session->remove('flash');

        $data['flashMessages'] = $data['flashMessages'] ?? $flashMessages;

        extract($data);

        ob_start();

        require $viewPath;

        $contenu = ob_get_clean();

        require BASE_PATH . '/templates/layout/' . $layout . '.html.php';
    }
}