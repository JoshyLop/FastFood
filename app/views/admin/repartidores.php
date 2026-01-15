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
    <title>🚗 Gestionar Repartidores - FastFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
</head>
<body class="bg-gray-50">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-lg sticky top-0 z-40">
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
    <div class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">🚗 Gestionar Repartidores</h1>
            <p class="text-blue-100 mt-2">Administra tu equipo de delivery</p>
        </div>
    </div>

    <!-- CONTENIDO -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Panel Izquierdo: Lista -->
            <div class="lg:col-span-1">
                <!-- Botón Agregar -->
                <button onclick="mostrarFormulario()" class="w-full mb-6 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg">
                    ➕ Agregar Repartidor
                </button>

                <!-- Lista de repartidores -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-blue-600 text-white px-6 py-4 font-bold">
                        Repartidores
                    </div>
                    <div id="repartidoresList" class="divide-y">
                        <!-- Cargado con JS -->
                    </div>
                </div>
            </div>

            <!-- Panel Derecho: Mapa -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div id="mapa" style="height: 600px; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario Modal -->
    <div id="formModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Agregar Repartidor</h2>
            <form id="repartidorForm" onsubmit="guardarRepartidor(event)">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre</label>
                    <input type="text" id="nombre" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-600 focus:outline-none">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                    <input type="tel" id="telefono" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-600 focus:outline-none">
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg">Guardar</button>
                    <button type="button" onclick="cerrarFormulario()" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 rounded-lg">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let repartidores = [];
        let mapa = null;
        let marcadores = {};

        // Inicializar mapa
        function inicializarMapa() {
            mapa = L.map('mapa').setView([20.9671, -101.6864], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(mapa);
        }

        // Cargar repartidores
        async function cargarRepartidores() {
            try {
                const response = await fetch('../../../php/repartidores_crud.php?action=listar');
                const data = await response.json();
                if (data.success) {
                    repartidores = data.data;
                    mostrarLista();
                    actualizarMapa();
                }
            } catch (error) {
                alert('Error al cargar repartidores: ' + error.message);
            }
        }

        function mostrarLista() {
            const list = document.getElementById('repartidoresList');
            list.innerHTML = repartidores.map(r => `
                <div class="p-4 hover:bg-blue-50 cursor-pointer" onclick="seleccionarRepartidor(${r.id})">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-gray-800">${r.nombre}</span>
                        <span class="px-2 py-1 rounded text-xs font-bold 
                            ${r.estado === 'disponible' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800'}">
                            ${r.estado === 'disponible' ? '🟢 Disponible' : '🟠 Ocupado'}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">📞 ${r.telefono}</div>
                    <div class="text-xs text-gray-500 mt-1">
                        ${r.latitud.toFixed(4)}, ${r.longitud.toFixed(4)}
                    </div>
                    <div class="mt-2 flex gap-2">
                        <button onclick="cambiarEstado(event, ${r.id}, '${r.estado === 'disponible' ? 'ocupado' : 'disponible'}')" 
                                class="text-xs px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded">
                            ${r.estado === 'disponible' ? 'Marcar Ocupado' : 'Marcar Disponible'}
                        </button>
                        <button onclick="eliminarRepartidor(event, ${r.id})" class="text-xs px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded">Eliminar</button>
                    </div>
                </div>
            `).join('');
        }

        function actualizarMapa() {
            // Limpiar marcadores antiguos
            Object.values(marcadores).forEach(m => mapa.removeLayer(m));
            marcadores = {};

            // Agregar nuevos marcadores
            repartidores.forEach(r => {
                const icono = r.estado === 'disponible' ? '🟢' : '🟠';
                const marker = L.marker([r.latitud, r.longitud], {
                    title: r.nombre
                }).addTo(mapa)
                .bindPopup(`<b>${r.nombre}</b><br>${r.estado}<br>📞 ${r.telefono}`);
                
                marcadores[r.id] = marker;
            });
        }

        function seleccionarRepartidor(id) {
            const repartidor = repartidores.find(r => r.id == id);
            if (repartidor) {
                mapa.setView([repartidor.latitud, repartidor.longitud], 15);
                marcadores[id].openPopup();
            }
        }

        function mostrarFormulario() {
            document.getElementById('formModal').classList.remove('hidden');
            document.getElementById('repartidorForm').reset();
        }

        function cerrarFormulario() {
            document.getElementById('formModal').classList.add('hidden');
        }

        async function guardarRepartidor(event) {
            event.preventDefault();
            
            const data = {
                nombre: document.getElementById('nombre').value,
                telefono: document.getElementById('telefono').value
            };

            try {
                const response = await fetch('../../../php/repartidores_crud.php?action=crear', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    cerrarFormulario();
                    cargarRepartidores();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function cambiarEstado(event, id, nuevoEstado) {
            event.stopPropagation();
            
            try {
                const response = await fetch('../../../php/repartidores_crud.php?action=actualizar_estado', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, estado: nuevoEstado })
                });

                const result = await response.json();
                if (result.success) {
                    cargarRepartidores();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function eliminarRepartidor(event, id) {
            event.stopPropagation();
            
            if (confirm('¿Está seguro de que desea eliminar este repartidor?')) {
                try {
                    const response = await fetch('../../../php/repartidores_crud.php?action=eliminar', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id })
                    });

                    const result = await response.json();
                    if (result.success) {
                        alert(result.message);
                        cargarRepartidores();
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch (error) {
                    alert('Error: ' + error.message);
                }
            }
        }

        // Inicializar al cargar
        inicializarMapa();
        cargarRepartidores();
    </script>

</body>
</html>
