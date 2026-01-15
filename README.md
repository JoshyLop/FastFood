# 🍔 FastFood

Aplicación de delivery de comida rápida con estructura MVC completa.

## 📁 Estructura MVC

```
app/
├── controllers/    AuthController, PedidoController, AdminController
├── models/         Usuario, Producto, Pedido
└── views/          Vistas HTML

js/                 Funcionalidad frontend
├── index.js        Inicializador
├── carrito.js      Carrito de compras
├── validacion.js   Validación de formularios
├── productos.js    Búsqueda y filtrado
└── seguimiento.js  Rastreo de pedidos

css/
└── Estilos.css     CSS global + componentes

db/
└── fastfood.db     SQLite database
```

## 🎯 Funciones JavaScript

- **Carrito**: agregar, actualizar, eliminar, vaciar
- **Validación**: email, contraseña, formularios login/registro
- **Productos**: cargar, buscar, filtrar, ordenar
- **Seguimiento**: buscar pedidos, ver estado, timeline

## 🎨 Colores

- Primario: #ef4444 (Rojo)
- Secundario: #f97316 (Naranja)
- Éxito: #16a34a (Verde)

## 📱 Responsivo

- Desktop: 1280px+
- Tablet: 768px-1279px
- Mobile: <768px

## 🔧 Requisitos

- PHP 7.4+
- SQLite3
- Font Awesome 6.4
