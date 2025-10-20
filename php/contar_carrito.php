<?php
session_start();

$total = 0;

if (isset($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['cantidad'];
    }
}

echo json_encode(['total' => $total]);
