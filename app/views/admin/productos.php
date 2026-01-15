<?php
session_start();
$usuario = $_SESSION['usuario_nombre'] ?? null;
$rol = $_SESSION['usuario_rol'] ?? 'cliente';

if (!$usuario || $rol !== 'admin') {
    echo "<script>alert('Acceso denegado'); window.location.href='login.html';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍕 Gestionar Productos - FastFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="inicio.php" class="flex items-center gap-2 text-2xl font-bold text-red-600">
                    <span>🍔</span> FastFood Admin
                </a>
                <div class="flex gap-4">
                    <a href="inicio.php" class="px-4 py-2 text-gray-700 hover:text-red-600 font-medium">← Volver</a>
                    <a href="cerrar_sesion.php" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">Salir</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">🍕 Gestionar Productos</h1>
            <p class="text-red-100 mt-2">Agrrega, edita o elimina productos del menú</p>
        </div>
    </div>

    <!-- CONTENIDO -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Botón Agregar -->
        <button onclick="mostrarFormulario()" class="mb-6 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg">
            ➕ Agregar Nuevo Producto
        </button>

        <!-- Formulario Modal -->
        <div id="formModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
                <h2 id="formTitle" class="text-2xl font-bold text-gray-800 mb-6">Agregar Producto</h2>
                <form id="productoForm" onsubmit="guardarProducto(event)">
                    <input type="hidden" id="productoId">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre</label>
                        <input type="text" id="nombre" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Categoría</label>
                        <select id="categoria" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                            <option value="">Seleccionar</option>
                            <option value="comida">Comida</option>
                            <option value="bebida">Bebida</option>
                            <option value="postre">Postre</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Precio (MXN)</label>
                        <input type="number" id="precio" step="0.01" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                        <textarea id="descripcion" rows="3" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none"></textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-lg">Guardar</button>
                        <button type="button" onclick="cerrarFormulario()" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 rounded-lg">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de Productos -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b-2 border-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold text-gray-800">Producto</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-800">Categoría</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-800">Precio</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-800">Descripción</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-800">Acciones</th>
                    </tr>
                </thead>
                <tbody id="productosTable">
                    <!-- Cargado con JS -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let productos = [];

        // Cargar productos al inicio
        async function cargarProductos() {
            try {
                const response = await fetch('../../../php/productos_crud.php?action=listar');
                const data = await response.json();
                if (data.success) {
                    productos = data.data;
                    mostrarTabla();
                }
            } catch (error) {
                alert('Error al cargar productos: ' + error.message);
            }
        }

        function mostrarTabla() {
            const table = document.getElementById('productosTable');
            table.innerHTML = productos.map(p => `
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold text-gray-800">${p.nombre}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            ${p.categoria}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-red-600">$${parseFloat(p.precio).toFixed(0)} MXN</td>
                    <td class="px-6 py-4 text-gray-600 text-sm">${p.descripcion || '-'}</td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="editarProducto(${p.id})" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm mr-2">✏️ Editar</button>
                        <button onclick="eliminarProducto(${p.id})" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm">🗑️ Eliminar</button>
                    </td>
                </tr>
            `).join('');
        }

        function mostrarFormulario() {
            document.getElementById('formModal').classList.remove('hidden');
            document.getElementById('formTitle').textContent = 'Agregar Producto';
            document.getElementById('productoId').value = '';
            document.getElementById('productoForm').reset();
        }

        function cerrarFormulario() {
            document.getElementById('formModal').classList.add('hidden');
        }

        function editarProducto(id) {
            const producto = productos.find(p => p.id == id);
            if (producto) {
                document.getElementById('formTitle').textContent = 'Editar Producto';
                document.getElementById('productoId').value = producto.id;
                document.getElementById('nombre').value = producto.nombre;
                document.getElementById('categoria').value = producto.categoria;
                document.getElementById('precio').value = producto.precio;
                document.getElementById('descripcion').value = producto.descripcion || '';
                document.getElementById('formModal').classList.remove('hidden');
            }
        }

        async function guardarProducto(event) {
            event.preventDefault();
            
            const id = document.getElementById('productoId').value;
            const data = {
                id: id || null,
                nombre: document.getElementById('nombre').value,
                categoria: document.getElementById('categoria').value,
                precio: document.getElementById('precio').value,
                descripcion: document.getElementById('descripcion').value
            };

            try {
                const action = id ? 'actualizar' : 'crear';
                const response = await fetch(`../../../php/productos_crud.php?action=${action}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    cerrarFormulario();
                    cargarProductos();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function eliminarProducto(id) {
            if (confirm('¿Está seguro de que desea eliminar este producto?')) {
                try {
                    const response = await fetch('../../../php/productos_crud.php?action=eliminar', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id })
                    });

                    const result = await response.json();
                    if (result.success) {
                        alert(result.message);
                        cargarProductos();
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch (error) {
                    alert('Error: ' + error.message);
                }
            }
        }

        // Cargar al abrir la página
        cargarProductos();
    </script>

</body>
</html>
