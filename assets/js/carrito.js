/**
 * Sistema de Carrito de Compras - FastFood
 * Gestiona agregar, actualizar y eliminar productos del carrito
 */

// ================== AGREGAR AL CARRITO ==================
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
                mostrarNotificacion('✅ Producto agregado al carrito', 'success');
                actualizarContadorCarrito();
            } else {
                mostrarNotificacion('❌ Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('❌ Error al agregar producto', 'error');
        });
}

// ================== ACTUALIZAR CARRITO ==================
function actualizarCantidad(id, cantidad) {
    if (cantidad < 1) {
        eliminarDelCarrito(id);
        return;
    }

    const datos = {
        id: id,
        cantidad: cantidad
    };

    fetch('../php/actualizar_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizarContadorCarrito();
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
}

// ================== ELIMINAR DEL CARRITO ==================
function eliminarDelCarrito(id) {
    if (confirm('¿Deseas eliminar este producto del carrito?')) {
        fetch('../php/eliminar_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion('✅ Producto eliminado', 'success');
                    actualizarContadorCarrito();
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

// ================== ACTUALIZAR CONTADOR ==================
function actualizarContadorCarrito() {
    fetch('../php/contar_carrito.php')
        .then(res => res.json())
        .then(data => {
            const contadores = document.querySelectorAll('#contador-carrito');
            contadores.forEach(contador => {
                contador.textContent = data.total || 0;
            });
        })
        .catch(err => console.error('Error:', err));
}

// ================== VACIAR CARRITO ==================
function vaciarCarrito() {
    if (confirm('¿Estás seguro de que deseas vaciar el carrito?')) {
        fetch('../php/vaciar_carrito.php', {
            method: 'POST'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion('✅ Carrito vaciado', 'success');
                    actualizarContadorCarrito();
                    setTimeout(() => location.reload(), 500);
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

// ================== NOTIFICACIÓN ==================
function mostrarNotificacion(mensaje, tipo = 'info') {
    const notif = document.createElement('div');
    notif.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        color: white;
        z-index: 9999;
        animation: slideIn 0.3s ease;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    `;

    const colores = {
        success: 'background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);',
        error: 'background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);',
        info: 'background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);'
    };

    notif.style.cssText += colores[tipo] || colores.info;
    notif.textContent = mensaje;

    document.body.appendChild(notif);

    setTimeout(() => {
        notif.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notif.remove(), 300);
    }, 3000);
}

// ================== AGREGAR ESTILOS ==================
if (!document.querySelector('style[data-carrito-animations]')) {
    const style = document.createElement('style');
    style.setAttribute('data-carrito-animations', 'true');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

// ================== INICIALIZAR ==================
document.addEventListener('DOMContentLoaded', function () {
    actualizarContadorCarrito();
});
