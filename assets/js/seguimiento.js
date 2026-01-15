/**
 * Seguimiento de Pedidos - FastFood
 */

// ================== OBTENER SEGUIMIENTO ==================
function obtenerSeguimiento(numeroPedido) {
    if (!numeroPedido) {
        mostrarErrorSeguimiento('Ingresa un número de pedido');
        return;
    }

    mostrarCargandoSeguimiento();

    fetch('../php/api_seguimiento.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            numero_pedido: numeroPedido
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.exito) {
                mostrarSeguimientoPedido(data.pedido);
            } else {
                mostrarErrorSeguimiento(data.mensaje || 'Pedido no encontrado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarErrorSeguimiento('Error al buscar el pedido');
        });
}

// ================== MOSTRAR SEGUIMIENTO ==================
function mostrarSeguimientoPedido(pedido) {
    const contenedor = document.querySelector('.seguimiento-resultado');

    let estadoHTML = '';
    const estados = ['pendiente', 'confirmado', 'preparando', 'listo', 'entregado'];
    const estadoIndex = estados.indexOf(pedido.estado.toLowerCase());

    estados.forEach((estado, index) => {
        const activo = index <= estadoIndex;
        estadoHTML += `
            <div class="seguimiento-paso ${activo ? 'activo' : ''}">
                <div class="paso-numero">${index + 1}</div>
                <div class="paso-nombre">${estado.charAt(0).toUpperCase() + estado.slice(1)}</div>
            </div>
        `;
    });

    const html = `
        <div class="pedido-info">
            <h3>Pedido #${pedido.numero_pedido}</h3>
            <p class="fecha">Hecho el: ${new Date(pedido.fecha).toLocaleDateString('es-ES')}</p>
            
            <div class="estado-actual">
                <strong>Estado actual:</strong>
                <span class="badge" style="background: ${getColorEstado(pedido.estado)}">
                    ${pedido.estado.toUpperCase()}
                </span>
            </div>
            
            <div class="seguimiento-pasos">
                ${estadoHTML}
            </div>
            
            <div class="detalle-productos">
                <h4>Productos:</h4>
                <ul>
                    ${JSON.parse(pedido.items || '[]').map(item => `
                        <li>${item.nombre} x${item.cantidad} - $${(item.precio * item.cantidad).toFixed(2)}</li>
                    `).join('')}
                </ul>
            </div>
            
            <div class="total-pedido">
                <strong>Total: $${pedido.total.toFixed(2)}</strong>
            </div>
            
            ${pedido.observaciones ? `
                <div class="observaciones">
                    <p><strong>Observaciones:</strong> ${pedido.observaciones}</p>
                </div>
            ` : ''}
        </div>
    `;

    contenedor.innerHTML = html;
    contenedor.style.display = 'block';
}

// ================== OBTENER COLOR ESTADO ==================
function getColorEstado(estado) {
    const colores = {
        'pendiente': '#f97316',
        'confirmado': '#3b82f6',
        'preparando': '#8b5cf6',
        'listo': '#06b6d4',
        'entregado': '#16a34a'
    };
    return colores[estado.toLowerCase()] || '#64748b';
}

// ================== MOSTRAR ERROR SEGUIMIENTO ==================
function mostrarErrorSeguimiento(mensaje) {
    const contenedor = document.querySelector('.seguimiento-resultado');
    contenedor.innerHTML = `
        <div class="error-mensaje">
            ⚠️ ${mensaje}
        </div>
    `;
    contenedor.style.display = 'block';
}

// ================== MOSTRAR CARGANDO ==================
function mostrarCargandoSeguimiento() {
    const contenedor = document.querySelector('.seguimiento-resultado');
    contenedor.innerHTML = `
        <div class="cargando">
            <div class="spinner"></div>
            <p>Buscando pedido...</p>
        </div>
    `;
    contenedor.style.display = 'block';
}

// ================== AGREGAR ESTILOS ==================
const estilos = document.createElement('style');
estilos.textContent = `
    .seguimiento-pasos {
        display: flex;
        justify-content: space-between;
        margin: 2rem 0;
        position: relative;
    }
    
    .seguimiento-pasos::before {
        content: '';
        position: absolute;
        top: 30px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e2e8f0;
        z-index: 0;
    }
    
    .paso-numero {
        position: relative;
        z-index: 1;
        width: 60px;
        height: 60px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        border: 3px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .seguimiento-paso.activo .paso-numero {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .paso-nombre {
        position: absolute;
        top: 80px;
        white-space: nowrap;
        font-size: 0.875rem;
        color: #64748b;
    }
    
    .seguimiento-paso.activo .paso-nombre {
        color: var(--dark);
        font-weight: 600;
    }
    
    .pedido-info {
        background: #f8fafc;
        padding: 2rem;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
    }
    
    .estado-actual {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 1rem 0;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .detalle-productos {
        margin: 1.5rem 0;
    }
    
    .detalle-productos ul {
        list-style: none;
        padding: 0;
    }
    
    .detalle-productos li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .total-pedido {
        background: white;
        padding: 1rem;
        border-radius: 0.5rem;
        margin: 1rem 0;
        font-size: 1.25rem;
    }
    
    .observaciones {
        background: #fef3c7;
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 4px solid #f59e0b;
    }
    
    .error-mensaje {
        background: #fee2e2;
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 4px solid #ef4444;
        color: #dc2626;
    }
    
    .cargando {
        text-align: center;
        padding: 2rem;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #e2e8f0;
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 1rem;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(estilos);

// ================== INICIALIZAR SEGUIMIENTO ==================
document.addEventListener('DOMContentLoaded', function () {
    // Buscar por número de pedido si existe en el formulario
    const btnBuscar = document.querySelector('button[onclick*="obtenerSeguimiento"]')
        || document.querySelector('.btn-buscar-pedido');

    if (btnBuscar) {
        btnBuscar.addEventListener('click', function () {
            const input = document.querySelector('input[name="numero_pedido"]');
            if (input) {
                obtenerSeguimiento(input.value);
            }
        });
    }

    // Permitir buscar con Enter
    const input = document.querySelector('input[name="numero_pedido"]');
    if (input) {
        input.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                obtenerSeguimiento(this.value);
            }
        });
    }
});
