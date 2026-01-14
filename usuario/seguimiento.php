<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Pedido - FastFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .contador {
            font-variant-numeric: tabular-nums;
        }
        .circulo-estado {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 28px;
            font-weight: bold;
        }
        .linea-progreso {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }
        .progreso-relleno {
            height: 100%;
            background: linear-gradient(to right, #ef4444, #f97316);
            transition: width 0.3s ease;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-50">
    
    <nav class="bg-white shadow-md">
        <div class="max-w-4xl mx-auto px-4 py-4">
            <h1 class="text-2xl font-bold text-red-600">🍔 FastFood</h1>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        
        <!-- TARJETA PRINCIPAL -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden mb-8" id="contenedor-pedido">
            
            <!-- ENCABEZADO -->
            <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white p-6">
                <h2 class="text-3xl font-bold mb-2">Tu Pedido Está en Camino 🚚</h2>
                <p class="text-red-100">Pedido <span id="numero-pedido" class="font-mono font-bold">#--</span></p>
            </div>

            <!-- CONTENIDO -->
            <div class="p-8">
                
                <!-- CONTADOR REGRESIVO -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg p-8 mb-8 text-center border-2 border-orange-200">
                    <p class="text-gray-600 text-lg font-semibold mb-2">Tiempo Estimado de Entrega</p>
                    <div class="contador text-6xl font-black text-orange-600" id="contador">
                        <span id="minutos">--</span>:<span id="segundos">--</span>
                    </div>
                    <p class="text-gray-500 mt-2">minutos y segundos</p>
                </div>

                <!-- BARRA DE PROGRESO -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-center flex-1">
                            <div class="circulo-estado bg-green-100 text-green-600 mx-auto mb-2">✓</div>
                            <p class="font-bold text-gray-800">Recibido</p>
                            <p class="text-sm text-gray-500">Hace poco</p>
                        </div>
                        <div class="flex-1 mx-2 mb-8">
                            <div class="linea-progreso">
                                <div class="progreso-relleno" id="linea-1" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="text-center flex-1">
                            <div class="circulo-estado bg-yellow-100 text-yellow-600 mx-auto mb-2" id="icono-2">🍳</div>
                            <p class="font-bold text-gray-800">Cocinando</p>
                            <p class="text-sm text-gray-500" id="tiempo-2">--</p>
                        </div>
                        <div class="flex-1 mx-2 mb-8">
                            <div class="linea-progreso">
                                <div class="progreso-relleno" id="linea-2" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="text-center flex-1">
                            <div class="circulo-estado bg-purple-100 text-purple-600 mx-auto mb-2" id="icono-3">🚗</div>
                            <p class="font-bold text-gray-800">En Camino</p>
                            <p class="text-sm text-gray-500" id="tiempo-3">--</p>
                        </div>
                        <div class="flex-1 mx-2 mb-8">
                            <div class="linea-progreso">
                                <div class="progreso-relleno" id="linea-3" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="text-center flex-1">
                            <div class="circulo-estado bg-gray-100 text-gray-600 mx-auto mb-2" id="icono-4">📦</div>
                            <p class="font-bold text-gray-800">Entregado</p>
                            <p class="text-sm text-gray-500" id="tiempo-4">--</p>
                        </div>
                    </div>
                </div>

                <!-- DETALLES DEL PEDIDO -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Detalles del Pedido</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600 text-sm">Total a Pagar</p>
                            <p class="text-2xl font-bold text-red-600">$<span id="monto-total">0.00</span></p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Estado Actual</p>
                            <p class="text-xl font-bold text-gray-800" id="estado-actual">--</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Fecha del Pedido</p>
                            <p class="text-sm text-gray-800" id="fecha-pedido">--</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Hora Estimada</p>
                            <p class="text-sm text-gray-800" id="hora-estimada">--</p>
                        </div>
                    </div>
                </div>

                <!-- MENSAJE MOTIVACIONAL -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="text-blue-700">✨ ¡Tu deliciosa comida viene en camino! Ten paciencia, pronto la tendrás en la puerta.</p>
                </div>
            </div>
        </div>

        <!-- BOTONES -->
        <div class="flex gap-4">
            <a href="/" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                Volver al Inicio
            </a>
            <button onclick="location.reload()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition">
                Actualizar
            </button>
        </div>

    </div>

    <script>
        // Obtener ID del pedido desde URL
        const urlParams = new URLSearchParams(window.location.search);
        const pedidoId = urlParams.get('pedido_id');

        if (!pedidoId) {
            document.getElementById('contenedor-pedido').innerHTML = `
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <p class="text-red-700">❌ No se especificó un pedido. <a href="/" class="underline font-bold">Volver al inicio</a></p>
                </div>
            `;
        } else {
            // Cargar y actualizar pedido cada 3 segundos
            function actualizarPedido() {
                fetch(`api/consultar_pedido.php?pedido_id=${pedidoId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            renderizarPedido(data.pedido);
                        }
                    })
                    .catch(err => console.error('Error:', err));
            }

            function renderizarPedido(pedido) {
                // Actualizar números
                document.getElementById('numero-pedido').textContent = `#${pedido.id}`;
                document.getElementById('monto-total').textContent = parseFloat(pedido.total).toFixed(2);
                document.getElementById('estado-actual').textContent = pedido.estado_nombre;
                
                // Contador regresivo
                const minutos = Math.floor(pedido.tiempo_restante / 60);
                const segundos = pedido.tiempo_restante % 60;
                document.getElementById('minutos').textContent = String(minutos).padStart(2, '0');
                document.getElementById('segundos').textContent = String(segundos).padStart(2, '0');

                // Progreso visual
                const progreso = pedido.progreso;
                document.getElementById('linea-1').style.width = Math.min(100, progreso) + '%';
                document.getElementById('linea-2').style.width = Math.max(0, progreso - 25) * (100 / 25) + '%';
                document.getElementById('linea-3').style.width = Math.max(0, progreso - 50) * (100 / 25) + '%';

                // Actualizar iconos según estado
                const iconos = { 2: '🍳', 3: '🚗', 4: '✓' };
                for (let i = 2; i <= 4; i++) {
                    const icono = document.getElementById(`icono-${i}`);
                    if (pedido.estado >= i) {
                        icono.textContent = '✓';
                        icono.className = 'circulo-estado bg-green-100 text-green-600 mx-auto mb-2';
                    } else if (pedido.estado === i - 1) {
                        icono.textContent = iconos[i];
                        icono.className = `circulo-estado bg-${i === 2 ? 'yellow' : i === 3 ? 'purple' : 'gray'}-100 text-${i === 2 ? 'yellow' : i === 3 ? 'purple' : 'gray'}-600 mx-auto mb-2`;
                    }
                }

                // Fechas y horas
                const fecha = new Date(pedido.fecha_pedido);
                document.getElementById('fecha-pedido').textContent = fecha.toLocaleString('es-ES');
                
                const horaEstimada = new Date(fecha.getTime() + pedido.tiempo_estimado * 60000);
                document.getElementById('hora-estimada').textContent = horaEstimada.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            }

            actualizarPedido();
            setInterval(actualizarPedido, 3000);
        }
    </script>
</body>
</html>
