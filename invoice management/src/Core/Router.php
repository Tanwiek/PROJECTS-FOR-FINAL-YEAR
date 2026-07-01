<?php
// src/Core/Router.php

namespace Core;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Décoder le chemin (pour gérer les espaces %20)
        $path = urldecode($path);
        
        // Supprimer le sous-répertoire si présent
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptName !== '/' && strpos($path, $scriptName) === 0) {
            $path = substr($path, strlen($scriptName));
        }

        // Si le chemin est vide ou /index.php, le traiter comme /
        if ($path === '' || $path === '/index.php') {
            $path = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                list($controllerName, $methodName) = explode('@', $route['handler']);
                $controllerClass = "Controllers\\$controllerName";
                
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $methodName)) {
                        return $controller->$methodName();
                    }
                }
                
                die("Method $methodName not found in $controllerClass");
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo "404 - Page non trouvée";
    }
}
