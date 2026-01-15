-- Crear tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    contrasena TEXT NOT NULL,
    rol TEXT DEFAULT 'cliente',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla productos
CREATE TABLE IF NOT EXISTS productos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    descripcion TEXT,
    precio REAL NOT NULL,
    categoria TEXT,
    disponible INTEGER DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla repartidores
CREATE TABLE IF NOT EXISTS repartidores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    telefono TEXT NOT NULL,
    estado TEXT DEFAULT 'disponible',
    latitud REAL,
    longitud REAL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL,
    repartidor_id INTEGER,
    estado TEXT DEFAULT 'pendiente',
    total REAL NOT NULL,
    direccion TEXT,
    telefonoCliente TEXT,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega DATETIME,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (repartidor_id) REFERENCES repartidores(id)
);

-- Crear tabla pedido_items
CREATE TABLE IF NOT EXISTS pedido_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pedido_id INTEGER NOT NULL,
    producto_id INTEGER NOT NULL,
    cantidad INTEGER NOT NULL,
    precio_unitario REAL NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Insertar usuarios de prueba
DELETE FROM usuarios WHERE email IN ('admin@fastfood.mx', 'cliente@fastfood.mx');

INSERT INTO usuarios (nombre, email, contrasena, rol) VALUES 
('Administrador', 'admin@fastfood.mx', '1234567', 'admin'),
('Cliente Test', 'cliente@fastfood.mx', '1234567', 'cliente');

-- Insertar productos
DELETE FROM productos;

INSERT INTO productos (nombre, categoria, precio, descripcion) VALUES
('Hamburguesa Premium', 'comida', 360, 'Con queso, lechuga y tomate'),
('Pizza Pepperoni', 'comida', 700, 'Grande - 8 porciones'),
('Sándwich de Pollo', 'comida', 300, 'Pollo asado crujiente'),
('Tacos', 'comida', 440, '5 unidades con salsa'),
('Papas Fritas', 'comida', 160, 'Crujientes y doradas'),
('Refresco Cola', 'bebida', 100, 'Frio y refrescante - 600ml'),
('Jugo Natural', 'bebida', 160, 'Naranja y limón - 500ml'),
('Batido de Fresa', 'bebida', 200, 'Delicioso y cremoso'),
('Café Americano', 'bebida', 120, 'Café puro y aromático'),
('Pastel de Chocolate', 'postre', 500, 'Delicioso y esponjoso'),
('Helado 3 Sabores', 'postre', 240, 'Vainilla, chocolate, fresa'),
('Cupcakes', 'postre', 360, '6 unidades variadas'),
('Galletas de Chocolate', 'postre', 200, 'Suave y crujiente');

-- Insertar repartidores de prueba
INSERT INTO repartidores (nombre, telefono, estado, latitud, longitud) VALUES
('Juan Pérez', '5551234567', 'disponible', 20.9671, -101.6864),
('María García', '5559876543', 'disponible', 20.9680, -101.6870),
('Carlos López', '5555555555', 'ocupado', 20.9660, -101.6850),
('Ana Rodríguez', '5554444444', 'disponible', 20.9700, -101.6880);
