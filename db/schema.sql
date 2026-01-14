-- ================================
-- SCHEMA SQLITE PARA FASTFOOD
-- ================================

-- Tabla de Usuarios (Clientes y Administradores)
CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    correo TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    rol TEXT CHECK(rol IN ('cliente', 'admin')) DEFAULT 'cliente',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT 1
);

-- Tabla de Productos
CREATE TABLE IF NOT EXISTS productos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    descripcion TEXT,
    categoria TEXT CHECK(categoria IN ('comida', 'bebida', 'postre')),
    precio REAL NOT NULL,
    imagen TEXT,
    disponible BOOLEAN DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_usuario INTEGER NOT NULL,
    total REAL NOT NULL,
    estado INTEGER CHECK(estado IN (1, 2, 3, 4)) DEFAULT 1,
    -- 1: Recibido, 2: Cocinando, 3: En camino, 4: Entregado
    tiempo_estimado INTEGER DEFAULT 20,  -- en minutos
    tiempo_restante INTEGER DEFAULT 20,  -- en minutos (actualizado por API)
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega DATETIME,
    notas TEXT,
    FOREIGN KEY(id_usuario) REFERENCES usuarios(id)
);

-- Tabla de Detalle de Pedidos
CREATE TABLE IF NOT EXISTS detalle_pedidos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_pedido INTEGER NOT NULL,
    id_producto INTEGER NOT NULL,
    cantidad INTEGER NOT NULL,
    precio_unitario REAL NOT NULL,
    subtotal REAL NOT NULL,
    FOREIGN KEY(id_pedido) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY(id_producto) REFERENCES productos(id)
);

-- Tabla de Repartidores (para simulación del mapa)
CREATE TABLE IF NOT EXISTS repartidores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    estado TEXT CHECK(estado IN ('disponible', 'en_ruta', 'entregado')) DEFAULT 'disponible',
    latitud REAL DEFAULT 0,
    longitud REAL DEFAULT 0,
    id_pedido INTEGER,
    fecha_ultima_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(id_pedido) REFERENCES pedidos(id)
);

-- Índices para optimización
CREATE INDEX IF NOT EXISTS idx_pedidos_usuario ON pedidos(id_usuario);
CREATE INDEX IF NOT EXISTS idx_pedidos_estado ON pedidos(estado);
CREATE INDEX IF NOT EXISTS idx_detalle_pedidos ON detalle_pedidos(id_pedido);
CREATE INDEX IF NOT EXISTS idx_usuarios_correo ON usuarios(correo);
