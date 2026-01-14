# 💻 Ejemplos de Código - FastFood Frontend

## Tabla de Contenidos
1. [Componentes HTML](#componentes-html)
2. [JavaScript Útil](#javascript-útil)
3. [CSS Personalizado](#css-personalizado)
4. [Patrones PHP](#patrones-php)

---

## Componentes HTML

### Navbar
```html
<nav class="navbar">
    <div class="navbar-content">
        <a href="index.html" class="navbar-logo">
            <i class="fas fa-burger"></i> FastFood
        </a>
        <ul class="navbar-menu">
            <li><a href="index.html">Inicio</a></li>
            <li><a href="comida.html">Menú</a></li>
            <li><a href="contacto.html">Contacto</a></li>
        </ul>
        <div class="navbar-right">
            <a href="carrito.php" class="btn btn-secondary btn-small">
                <i class="fas fa-shopping-cart"></i> Carrito
            </a>
            <a href="login.html" class="btn btn-primary btn-small">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        </div>
    </div>
</nav>
```

### Card Simple
```html
<div class="card">
    <h3 class="card-header">Título</h3>
    <div class="card-body">
        <p>Contenido principal</p>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary btn-small">Acción</button>
    </div>
</div>
```

### Product Card
```html
<div class="product-card">
    <div class="product-image">🍔</div>
    <div class="product-info">
        <div class="product-name">Hamburguesa Clásica</div>
        <div class="product-description">Carne, lechuga, tomate, queso</div>
        <div class="product-footer">
            <div class="product-price">$8.99</div>
            <button class="btn btn-primary btn-small" data-id="1" data-nombre="Hamburguesa" data-precio="8.99">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>
```

### Grid de 3 Columnas
```html
<div class="grid grid-3">
    <div class="card">Columna 1</div>
    <div class="card">Columna 2</div>
    <div class="card">Columna 3</div>
</div>
```

### Formulario
```html
<form action="procesar.php" method="POST" class="card" style="max-width: 400px;">
    <h3 class="card-header">Formulario</h3>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" required>
            <span class="form-error">Campo requerido</span>
        </div>
        
        <div class="form-group">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-input" required>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary" style="width: 100%;">Enviar</button>
    </div>
</form>
```

### Tabla
```html
<div style="background: white; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); color: white;">
                <th style="padding: 1rem; text-align: left;">Producto</th>
                <th style="padding: 1rem; text-align: left;">Precio</th>
                <th style="padding: 1rem; text-align: left;">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 1rem;">Pizza</td>
                <td style="padding: 1rem;">$12.99</td>
                <td style="padding: 1rem;">2</td>
            </tr>
        </tbody>
    </table>
</div>
```

### Hero Section
```html
<section class="hero">
    <div class="container">
        <h1>Bienvenido a FastFood</h1>
        <p>El mejor servicio de comida rápida</p>
        <a href="#menu" class="btn btn-primary">Explorar Menú</a>
    </div>
</section>
```

---

## JavaScript Útil

### Agregar al Carrito
```javascript
function agregarAlCarrito(id, nombre, precio) {
    const producto = {
        id: id,
        nombre: nombre,
        precio: parseFloat(precio),
        cantidad: 1
    };
    
    fetch('../php/agregar_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(producto)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Producto agregado al carrito');
            actualizarContadorCarrito();
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Uso:
// <button onclick="agregarAlCarrito(1, 'Pizza', 12.99)">Agregar</button>
```

### Actualizar Contador Carrito
```javascript
function actualizarContadorCarrito() {
    fetch('../php/contar_carrito.php')
        .then(res => res.json())
        .then(data => {
            const contador = document.getElementById('contador-carrito');
            if (contador) {
                contador.textContent = data.total || 0;
            }
        })
        .catch(err => console.error('Error:', err));
}

// Llamar al cargar la página
document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);
```

### Modal Personalizado
```javascript
function abrirModal(titulo, contenido, botones = []) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    `;
    
    const card = document.createElement('div');
    card.className = 'card';
    card.style.maxWidth = '500px';
    card.innerHTML = `
        <h3 class="card-header">${titulo}</h3>
        <div class="card-body">${contenido}</div>
        <div class="card-footer" style="display: flex; gap: 1rem;">
            ${botones.map(btn => `<button class="btn ${btn.clase}">${btn.texto}</button>`).join('')}
            <button class="btn btn-secondary" onclick="this.closest('div').parentElement.remove()">Cerrar</button>
        </div>
    `;
    
    modal.appendChild(card);
    document.body.appendChild(modal);
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.remove();
    });
}

// Uso:
// abrirModal('Confirmar', '¿Deseas continuar?', [
//     { texto: 'Sí', clase: 'btn-primary' },
//     { texto: 'No', clase: 'btn-secondary' }
// ]);
```

### Validar Formulario
```javascript
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('[required]');
    let valido = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            const error = input.nextElementSibling;
            if (error && error.classList.contains('form-error')) {
                error.textContent = 'Este campo es requerido';
            }
            valido = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return valido;
}

// CSS para is-invalid
// .is-invalid { border-color: #dc2626 !important; }
```

### Dark Mode Toggle
```javascript
function toggleDarkMode() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark-mode');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

// Cargar tema guardado
if (localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark-mode');
}

// HTML
// <button onclick="toggleDarkMode()" class="btn btn-secondary btn-small">
//     <i class="fas fa-moon"></i>
// </button>
```

---

## CSS Personalizado

### Color Gradiente Personalizado
```css
.custom-gradient {
    background: linear-gradient(135deg, #tu_color1 0%, #tu_color2 100%);
}
```

### Animación de Carga
```css
@keyframes loading {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #ef4444;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: loading 1s linear infinite;
}
```

### Efecto Hover Deslizante
```css
.hover-slide {
    position: relative;
    overflow: hidden;
}

.hover-slide::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.1);
    transition: left 0.3s ease;
}

.hover-slide:hover::before {
    left: 100%;
}
```

### Sombra Personalizada
```css
.shadow-custom {
    box-shadow: 
        0 10px 40px rgba(239, 68, 68, 0.1),
        0 0 20px rgba(239, 68, 68, 0.05);
}
```

---

## Patrones PHP

### Proteger Página
```php
<?php
session_start();

// Verificar si está logueado
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Debes iniciar sesión'); window.location.href='login.html';</script>";
    exit();
}

$usuario = htmlspecialchars($_SESSION['usuario_nombre']);
?>
```

### Conexión PDO
```php
<?php
require_once '../php/conexion.php';

try {
    $db = DatabaseConnection::getInstance();
    
    // Usar prepared statements
    $stmt = $db->query("SELECT * FROM usuarios WHERE id = ?", [$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
```

### Validar POST
```php
<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Método no permitido']));
}

$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos || empty($datos['email'])) {
    http_response_code(400);
    die(json_encode(['error' => 'Email requerido']));
}

// Procesar...
echo json_encode(['success' => true]);
?>
```

### Respuesta JSON
```php
<?php
header('Content-Type: application/json');

function responder($exito, $mensaje, $datos = []) {
    echo json_encode([
        'success' => $exito,
        'message' => $mensaje,
        'data' => $datos
    ]);
    exit();
}

// Uso:
responder(true, '✅ Operación exitosa', ['id' => 123]);
responder(false, '❌ Error en operación');
?>
```

---

## Snippets Rápidos

### Badge
```html
<span style="display: inline-block; background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: bold;">NUEVO</span>
```

### Skeleton Loading
```html
<div style="background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite; height: 200px; border-radius: 0.5rem;"></div>
```

### Tooltip
```html
<div style="position: relative; display: inline-block;">
    <button class="btn btn-primary">Hover me</button>
    <div style="position: absolute; bottom: -40px; background: #1e293b; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity 0.3s;">
        Texto del tooltip
    </div>
</div>
```

### Breadcrumb
```html
<nav style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
    <a href="/" style="color: #ef4444;">Inicio</a>
    <span>/</span>
    <a href="/menu" style="color: #ef4444;">Menú</a>
    <span>/</span>
    <span style="color: #64748b;">Detalle</span>
</nav>
```

---

## 🎓 Mejores Prácticas

### HTML
```html
<!-- ✅ Bien -->
<button class="btn btn-primary" onclick="agregarAlCarrito(1)">Agregar</button>

<!-- ❌ Evitar -->
<button style="background: red; padding: 10px;" onclick="alert('agregado')">Agregar</button>
```

### CSS
```css
/* ✅ Bien */
.producto-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(239, 68, 68, 0.2);
}

/* ❌ Evitar */
.producto-card { transition: all 0.001s; }
```

### JavaScript
```javascript
// ✅ Bien
fetch('/api/productos')
    .then(r => r.json())
    .then(data => console.log(data))
    .catch(e => console.error(e));

// ❌ Evitar
var x = new XMLHttpRequest();
x.open('GET', '/api/productos');
```

---

**Última actualización**: 2024
**Versión**: 1.0
