<?php
namespace Core;

class Router
{
    public function route()
    {
        $url = $_GET['url'] ?? 'usuarios/login'; // ruta por defecto
        $url = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));

        $controllerName = ucfirst($url[0]) . 'Controller';
        $method = $url[1] ?? 'index';
        $params = array_slice($url, 2);

        $controllerFile = __DIR__ . '/../Controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $fullControllerName = "\\App\\Controllers\\$controllerName";
            $controller = new $fullControllerName();

            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], $params);
                return;
            }
        }

        echo "🚫 Página no encontrada.";
    }
}
