<?php
// Activamos el manejo de errores (opcional en desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Cargamos clases base
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Controller.php';

// Iniciamos sesión
session_start();

// Ejecutamos el enrutador
use Core\Router;

$router = new Router();
$router->route();
