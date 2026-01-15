-- Crear tabla usuarios si no existe
CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    contrasena TEXT NOT NULL,
    rol TEXT DEFAULT 'cliente',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla productos si no existe
CREATE TABLE IF NOT EXISTS productos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    descripcion TEXT,
    precio REAL NOT NULL,
    categoria TEXT,
    imagen TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla pedidos si no existe
CREATE TABLE IF NOT EXISTS pedidos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER,
    estado TEXT DEFAULT 'pendiente',
    total REAL NOT NULL,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Insertar usuarios de prueba
DELETE FROM usuarios WHERE email IN ('admin@fastfood.mx', 'cliente@fastfood.mx');

INSERT INTO usuarios (nombre, email, contrasena, rol) VALUES (
    'Administrador',
    'admin@fastfood.mx',
    '1234567',
    'admin'
);

INSERT INTO usuarios (nombre, email, contrasena, rol) VALUES (
    'Cliente Test',
    'cliente@fastfood.mx',
    '1234567',
    'cliente'
);

-- Insertar productos de ejemplo
DELETE FROM productos;

INSERT INTO productos (nombre, descripcion, precio, categoria, imagen) VALUES
('Hamburguesa Doble', 'Dos carnes, queso y vegetales', 140.00, 'comida', '../img/comida/comida1.jpg'),
('Tacos al Pastor', '3 tacos con carne al pastor', 90.00, 'comida', '../img/comida/comida2.jpg'),
('Burrito Especial', 'Con queso, frijoles y carne', 120.00, 'comida', '../img/comida/comida3.jpg'),
('Hot Dog', 'Hot dog con salsas', 70.00, 'comida', '../img/comida/comida4.jpg'),
('Papas a la Francesa', 'Papas recién hechas', 60.00, 'comida', '../img/comida/comida5.jpg'),
('Refresco Mediano', 'Bebida refrescante', 30.00, 'bebida', '../img/bebidas/bebida1.jpg'),
('Bebida Energética', 'Energía y sabor', 40.00, 'bebida', '../img/bebidas/bebida2.jpg'),
('Pastel de Chocolate', 'Delicioso pastel casero', 80.00, 'postre', '../img/postres/postre1.jpg'),
('Flan Tradicional', 'Postre clásico', 60.00, 'postre', '../img/postres/postre2.jpg');
