<?php

namespace App\View;

class View
{
    private static ?View $instance = null;

    private function __construct() {}

    public static function getInstance(): View
    {
        if (self::$instance === null) {
            self::$instance = new View();
        }

        return self::$instance;
    }

    public function renderView(string $view, array $data = [], string $layout = 'base'): void
    {
        $viewPath = BASE_PATH . '/templates/' . $view . '.html.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);


            require BASE_PATH . '/templates/errors/404.html.php';

            return;
        }

        extract($data);

        ob_start();

        require $viewPath;


        $contenu = ob_get_clean();

        require BASE_PATH . '/templates/layout/' . $layout . '.html.php';
    }
}
