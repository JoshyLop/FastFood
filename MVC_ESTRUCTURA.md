# 📚 Estructura MVC - FastFood

## Arquitectura del Proyecto

```
FastFood/
├── app/
│   ├── models/          # Modelos (Acceso a datos)
│   │   ├── Model.php    # Clase base
│   │   ├── Usuario.php
│   │   ├── Producto.php
│   │   └── Pedido.php
│   │
│   ├── controllers/     # Controladores (Lógica)
│   │   ├── Controller.php    # Clase base
│   │   ├── AuthController.php
│   │   ├── PedidoController.php
│   │   └── AdminController.php
│   │
│   └── views/           # Vistas (Presentación)
│       ├── home.php
│       ├── admin_dashboard.php
│       └── seguimiento.php
│
├── config/
│   ├── Router.php       # Enrutador
│   └── routes.php       # Definición de rutas
│
├── api/                 # Endpoints API
│
├── db/
│   ├── schema.sql       # Esquema BD
│   └── fastfood.db      # Base de datos SQLite
│
└── php/
    └── conexion.php     # Conexión PDO
```

## 🎯 Flujo MVC

```
Usuario hace Request HTTP
         ↓
    routes.php (define ruta)
         ↓
    Router.php (enruta a controlador)
         ↓
    Controller (procesa lógica)
         ↓
    Model (accede a BD)
         ↓
    Response JSON/View
```

## 📋 Rutas Disponibles

### Autenticación
```
POST /api/auth/register        → AuthController->register()
POST /api/auth/login           → AuthController->login()
GET  /api/auth/logout          → AuthController->logout()
GET  /api/auth/perfil          → AuthController->perfil()
```

### Pedidos (Cliente)
```
GET  /api/pedidos/mis-pedidos  → PedidoController->misPedidos()
GET  /api/pedidos/detalles     → PedidoController->detalles()
POST /api/pedidos/crear        → PedidoController->crear()
GET  /api/pedidos/estado       → PedidoController->estado()
```

### Admin
```
GET  /api/admin/pedidos                   → AdminController->obtenerPedidos()
POST /api/admin/pedidos/actualizar        → AdminController->actualizarPedido()
GET  /api/admin/estadisticas              → AdminController->estadisticas()
```

## 💡 Cómo Usar

### Crear un Nuevo Modelo
```php
<?php
require_once __DIR__ . '/Model.php';

class MiModelo extends Model {
    protected $table = 'mi_tabla';
    
    // Métodos personalizados
}
?>
```

### Crear un Nuevo Controlador
```php
<?php
require_once __DIR__ . '/Controller.php';

class MiControlador extends Controller {
    public function __construct() {
        $this->loadModel('MiModelo');
    }
    
    public function miAccion() {
        $this->requireLogin();  // Verificar sesión
        $datos = $this->model->getAll();
        $this->json(['success' => true, 'datos' => $datos]);
    }
}
?>
```

### Registrar una Nueva Ruta
```php
$router->post('/api/mi-ruta', 'MiControlador', 'miAccion');
```

## 🔒 Métodos de Seguridad

### En Controller
```php
$this->requireLogin();   // Requiere estar autenticado
$this->requireAdmin();   // Requiere ser administrador
$this->validate();       // Valida datos POST
```

## 📊 Métodos Base del Modelo

```php
$this->getAll();              // Obtiene todos
$this->getById($id);          // Obtiene por ID
$this->where($conditions);    // Obtiene con condiciones
$this->create($data);         // Crea registro
$this->update($id, $data);    // Actualiza
$this->delete($id);           // Elimina
$this->count();               // Cuenta registros
```

## ✅ Ejemplo Completo: Crear Producto

### 1. Modelo (Producto.php)
```php
class Producto extends Model {
    protected $table = 'productos';
}
```

### 2. Controlador
```php
class ProductoController extends Controller {
    public function __construct() {
        $this->loadModel('Producto');
    }
    
    public function crear() {
        $producto = $this->model->create([
            'nombre' => 'Pizza',
            'precio' => 25000
        ]);
        $this->json(['success' => true, 'id' => $producto]);
    }
}
```

### 3. Ruta
```php
$router->post('/api/productos/crear', 'ProductoController', 'crear');
```

### 4. Request
```bash
curl -X POST http://localhost/FastFood/api/productos/crear \
  -d "nombre=Pizza&precio=25000"
```

## 🎓 Ventajas de esta Estructura

✅ **Separación de responsabilidades** - Cada capa con su función
✅ **Reutilizable** - Métodos base en Model y Controller
✅ **Escalable** - Fácil agregar nuevos modelos/controladores
✅ **Mantenible** - Código organizado y claro
✅ **Seguro** - Validaciones y verificaciones de sesión
✅ **Flexible** - Soporta vistas y APIs JSON
