<?php
/**
 * DEFINICIÓN DE RUTAS DE LA APLICACIÓN
 */

session_start();

require_once __DIR__ . '/Router.php';

$router = new Router();

// ==================== RUTAS DE AUTENTICACIÓN ====================
$router->post('/api/auth/register', 'AuthController', 'register');
$router->post('/api/auth/login', 'AuthController', 'login');
$router->get('/api/auth/logout', 'AuthController', 'logout');
$router->get('/api/auth/perfil', 'AuthController', 'perfil');

// ==================== RUTAS DE PEDIDOS (CLIENTE) ====================
$router->get('/api/pedidos/mis-pedidos', 'PedidoController', 'misPedidos');
$router->get('/api/pedidos/detalles', 'PedidoController', 'detalles');
$router->post('/api/pedidos/crear', 'PedidoController', 'crear');
$router->get('/api/pedidos/estado', 'PedidoController', 'estado');

// ==================== RUTAS DE ADMIN ====================
$router->get('/api/admin/pedidos', 'AdminController', 'obtenerPedidos');
$router->post('/api/admin/pedidos/actualizar', 'AdminController', 'actualizarPedido');
$router->get('/api/admin/estadisticas', 'AdminController', 'estadisticas');

// Ejecutar
$router->dispatch();
?>
