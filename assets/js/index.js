/**
 * FastFood - Inicializador Principal
 * Carga y configura todos los módulos
 */

console.log('🍔 FastFood v2.0 - Sistema inicializando...');

// =====================================================
// CONFIGURACIÓN GLOBAL
// =====================================================

const CONFIG = {
    API_URL: '/FastFood/php/',
    TIMEOUT: 5000,
    DEBUG: true
};

// =====================================================
// VERIFICACIÓN DE FUNCIONES
// =====================================================

const funcionesRequeridas = [
    'agregarAlCarrito',
    'validarFormularioLogin',
    'cargarProductos',
    'obtenerSeguimiento'
];

function verificarFunciones() {
    let funcionesFaltantes = [];

    funcionesRequeridas.forEach(func => {
        if (typeof window[func] !== 'function') {
            funcionesFaltantes.push(func);
        }
    });

    if (funcionesFaltantes.length > 0) {
        console.warn('⚠️ Funciones faltantes:', funcionesFaltantes);
    } else {
        console.log('✅ Todas las funciones disponibles');
    }
}

// =====================================================
// INICIALIZACIÓN DE MÓDULOS
// =====================================================

document.addEventListener('DOMContentLoaded', function () {
    console.log('📦 DOM cargado - Inicializando módulos...');

    // Verificar funciones disponibles
    verificarFunciones();

    // Inicializar carrito
    if (localStorage.getItem('carrito')) {
        console.log('🛒 Carrito restaurado del almacenamiento local');
        actualizarContadorCarrito();
    }

    // Inicializar búsqueda en catálogos
    const inputBusqueda = document.querySelector('input[name="busqueda"]');
    if (inputBusqueda && typeof buscarProductos === 'function') {
        console.log('🔍 Búsqueda de productos activada');
    }

    // Inicializar validaciones
    const formLogin = document.querySelector('form[onsubmit*="validarFormularioLogin"]');
    const formRegistro = document.querySelector('form[onsubmit*="validarFormularioRegistro"]');

    if (formLogin) {
        console.log('📝 Validación de login activada');
    }
    if (formRegistro) {
        console.log('📝 Validación de registro activada');
    }

    // Inicializar seguimiento
    const inputSeguimiento = document.querySelector('input[name="numero_pedido"]');
    if (inputSeguimiento) {
        console.log('📍 Seguimiento de pedidos activado');
    }

    console.log('✨ FastFood v2.0 listo para usar');
});

// =====================================================
// UTILIDADES GLOBALES
// =====================================================

/**
 * Mostrar alerta personalizada
 */
window.mostrarAlerta = function (mensaje, tipo = 'info') {
    const colores = {
        info: '#3b82f6',
        éxito: '#16a34a',
        error: '#ef4444',
        advertencia: '#f97316'
    };

    const alerta = document.createElement('div');
    alerta.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colores[tipo] || colores.info};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-weight: 500;
        max-width: 90vw;
    `;
    alerta.textContent = mensaje;

    document.body.appendChild(alerta);

    setTimeout(() => {
        alerta.style.opacity = '0';
        alerta.style.transition = 'opacity 0.3s';
        setTimeout(() => alerta.remove(), 300);
    }, 3000);
};

/**
 * Log condicional para debug
 */
window.debugLog = function (mensaje, dato = '') {
    if (CONFIG.DEBUG) {
        console.log(`[DEBUG] ${mensaje}`, dato);
    }
};

/**
 * Obtener parámetro URL
 */
window.obtenerParametroURL = function (nombre) {
    const params = new URLSearchParams(window.location.search);
    return params.get(nombre);
};

/**
 * Formatear moneda
 */
window.formatearMoneda = function (cantidad) {
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'USD'
    }).format(cantidad);
};

/**
 * Formatear fecha
 */
window.formatearFecha = function (fecha) {
    return new Date(fecha).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// =====================================================
// EXPORTAR CONFIG
// =====================================================

window.FASTFOOD_CONFIG = CONFIG;

console.log('✅ Inicializador completado');
