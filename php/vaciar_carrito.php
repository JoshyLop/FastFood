<?php
header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_GET['action'] === 'clear') {
    unset($_SESSION['carrito']);
    echo json_encode(['success' => true, 'message' => 'Carrito vaciado']);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
