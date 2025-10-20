<?php
header('Content-Type: application/json');

$estados = ['Preparando pedido', 'Empacado', 'En camino', 'Entregado'];
$random = rand(0, count($estados) - 1);

echo json_encode([
    'estado' => $estados[$random],
    'tiempo_estimado' => rand(5, 30) . ' min'
]);
