<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - FastFood</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet JS para mapas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <style>
        #mapa-repartidores {
            width: 100%;
            height: 400px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .pedido-card {
            transition: all 0.3s ease;
        }
        .pedido-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .estado-1 { @apply bg-blue-100 text-blue-800; }
        .estado-2 { @apply bg-yellow-100 text-yellow-800; }
        .estado-3 { @apply bg-purple-100 text-purple-800; }
        .estado-4 { @apply bg-green-100 text-green-800; }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-2xl font-bold text-red-600">🍔 FastFood - Panel Admin</h1>
                <a href="logout.php" class="text-red-600 hover:text-red-800 font-semibold">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- MAPA DE MONITOREO -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">📍 Mapa de Monitoreo de Repartidores</h2>
            <div id="mapa-repartidores"></div>
            <p class="text-sm text-gray-500 mt-4">Los repartidores se simulan moviéndose aleatoriamente en el mapa.</p>
        </div>

        <!-- ESTADÍSTICAS RÁPIDAS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg">
                <p class="text-sm opacity-90">Pedidos Nuevos</p>
                <p class="text-3xl font-bold" id="count-estado-1">0</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg p-6 shadow-lg">
                <p class="text-sm opacity-90">En Cocina</p>
                <p class="text-3xl font-bold" id="count-estado-2">0</p>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg p-6 shadow-lg">
                <p class="text-sm opacity-90">En Camino</p>
                <p class="text-3xl font-bold" id="count-estado-3">0</p>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg">
                <p class="text-sm opacity-90">Entregados</p>
                <p class="text-3xl font-bold" id="count-estado-4">0</p>
            </div>
        </div>

        <!-- LISTA DE PEDIDOS -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">📋 Gestión de Pedidos</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">ID Pedido</th>
                            <th class="px-4 py-3 text-left">Cliente</th>
                            <th class="px-4 py-3 text-center">Total</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-center">Tiempo (min)</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-pedidos">
                        <!-- Cargado dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL PARA EDITAR PEDIDO -->
        <div id="modal-editar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 max-w-md w-full shadow-2xl">
                <h3 class="text-xl font-bold mb-4">Actualizar Pedido</h3>
                
                <form id="form-actualizar" class="space-y-4">
                    <input type="hidden" id="pedido-id" name="pedido_id">
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2">Estado del Pedido</label>
                        <select id="estado-select" name="estado" class="w-full border-2 border-gray-300 rounded-lg p-2 focus:outline-none focus:border-red-500">
                            <option value="1">Recibido</option>
                            <option value="2">Cocinando</option>
                            <option value="3">En Camino</option>
                            <option value="4">Entregado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Tiempo de Entrega (minutos)</label>
                        <input type="number" id="tiempo-input" name="tiempo_estimado" min="5" max="120" class="w-full border-2 border-gray-300 rounded-lg p-2 focus:outline-none focus:border-red-500">
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">Guardar</button>
                        <button type="button" onclick="cerrarModal()" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        // ==================== MAPA CON LEAFLET ====================
        const mapa = L.map('mapa-repartidores').setView([4.6097, -74.0817], 12); // Centro de Bogotá

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapa);

        // Simular repartidores
        const repartidores = [];
        for (let i = 0; i < 5; i++) {
            const lat = 4.6097 + (Math.random() - 0.5) * 0.1;
            const lng = -74.0817 + (Math.random() - 0.5) * 0.1;
            
            const marker = L.circleMarker([lat, lng], {
                radius: 8,
                fillColor: '#ef4444',
                color: '#dc2626',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(mapa);

            marker.bindPopup(`🚚 Repartidor ${i + 1}`);

            repartidores.push({
                marker: marker,
                lat: lat,
                lng: lng
            });
        }

        // Animar movimiento de repartidores
        setInterval(() => {
            repartidores.forEach(rep => {
                rep.lat += (Math.random() - 0.5) * 0.002;
                rep.lng += (Math.random() - 0.5) * 0.002;
                rep.marker.setLatLng([rep.lat, rep.lng]);
            });
        }, 2000);

        // ==================== CARGAR PEDIDOS ====================
        function cargarPedidos() {
            fetch('api/obtener_pedidos.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        renderizarPedidos(data.pedidos);
                        actualizarContadores(data.pedidos);
                    }
                })
                .catch(err => console.error('Error:', err));
        }

        function renderizarPedidos(pedidos) {
            const tabla = document.getElementById('tabla-pedidos');
            tabla.innerHTML = '';

            const estadosNombres = { 1: 'Recibido', 2: 'Cocinando', 3: 'En Camino', 4: 'Entregado' };

            pedidos.forEach(pedido => {
                const fila = `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-bold text-red-600">#${pedido.id}</td>
                        <td class="px-4 py-3">${pedido.nombre_cliente}</td>
                        <td class="px-4 py-3 text-center font-semibold">$${parseFloat(pedido.total).toFixed(2)}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="estado-${pedido.estado} px-3 py-1 rounded-full text-xs font-bold">
                                ${estadosNombres[pedido.estado]}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">${pedido.tiempo_estimado} min</td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="abrirModal(${pedido.id}, ${pedido.estado}, ${pedido.tiempo_estimado})" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-semibold transition">
                                Editar
                            </button>
                        </td>
                    </tr>
                `;
                tabla.innerHTML += fila;
            });
        }

        function actualizarContadores(pedidos) {
            const contadores = { 1: 0, 2: 0, 3: 0, 4: 0 };
            pedidos.forEach(p => contadores[p.estado]++);
            
            for (let i = 1; i <= 4; i++) {
                document.getElementById(`count-estado-${i}`).textContent = contadores[i];
            }
        }

        // ==================== MODAL ====================
        function abrirModal(pedidoId, estado, tiempo) {
            document.getElementById('pedido-id').value = pedidoId;
            document.getElementById('estado-select').value = estado;
            document.getElementById('tiempo-input').value = tiempo;
            document.getElementById('modal-editar').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modal-editar').classList.add('hidden');
        }

        // ==================== ENVIAR ACTUALIZACIÓN ====================
        document.getElementById('form-actualizar').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('api/actualizar_pedido.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Pedido actualizado correctamente');
                    cerrarModal();
                    cargarPedidos();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => console.error('Error:', err));
        });

        // ==================== INICIALIZAR ====================
        cargarPedidos();
        setInterval(cargarPedidos, 5000); // Actualizar cada 5 segundos
    </script>
</body>
</html>
