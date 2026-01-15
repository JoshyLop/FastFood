<?php
/**
 * Configuración Central - FastFood
 */

// Rutas base
define('APP_URL', '/FastFood/');
define('APP_PATH', dirname(__DIR__));
define('VIEWS_PATH', APP_PATH . '/app/views/');
define('CONTROLLERS_PATH', APP_PATH . '/app/controllers/');
define('MODELS_PATH', APP_PATH . '/app/models/');

// URLs relativas para vistas
define('JS_URL', APP_URL . 'js/');
define('CSS_URL', APP_URL . 'css/');
define('IMG_URL', APP_URL . 'img/');
define('PHP_URL', APP_URL . 'php/');

// Base de datos
define('DB_PATH', APP_PATH . '/db/fastfood.db');

// Sesión
session_start();

// Cargar modelos y controladores si existe autoloader
if (file_exists(MODELS_PATH . 'Model.php')) {
    require_once MODELS_PATH . 'Model.php';
}
