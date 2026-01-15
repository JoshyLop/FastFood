<?php
/**
 * Script de setup - Crea BD y usuarios de prueba
 */

try {
    $db = new PDO('sqlite:' . __DIR__ . '/db/fastfood.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Leer y ejecutar SQL
    $sql = file_get_contents(__DIR__ . '/db/seed_new.sql');
    $db->exec($sql);
    
    echo "✅ Base de datos configurada correctamente\n\n";
    echo "👤 Usuarios de prueba creados:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "ADMIN:\n";
    echo "  Email: admin@fastfood.mx\n";
    echo "  Contraseña: 1234567\n";
    echo "  Rol: admin\n\n";
    echo "CLIENTE:\n";
    echo "  Email: cliente@fastfood.mx\n";
    echo "  Contraseña: 1234567\n";
    echo "  Rol: cliente\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
