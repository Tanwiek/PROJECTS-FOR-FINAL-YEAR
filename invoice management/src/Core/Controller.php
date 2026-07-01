<?php
// src/Core/Controller.php

namespace Core;

class Controller {
    protected function render($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Vue $view non trouvée");
        }
    }

    protected function redirect($url) {
        $fullUrl = BASE_URL . $url;
        header("Location: $fullUrl");
        exit;
    }
}
