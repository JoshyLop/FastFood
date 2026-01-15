/**
 * Validación de Formularios - FastFood
 */

// ================== VALIDAR EMAIL ==================
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// ================== VALIDAR CONTRASEÑA ==================
function validarContrasena(contrasena) {
    return contrasena.length >= 6;
}

// ================== VALIDAR FORMULARIO LOGIN ==================
function validarFormularioLogin(event) {
    event.preventDefault();

    const email = document.querySelector('input[name="correo"]').value.trim();
    const contrasena = document.querySelector('input[name="contrasena"]').value;

    // Validaciones
    if (!email) {
        mostrarError('El email es requerido');
        return false;
    }

    if (!validarEmail(email)) {
        mostrarError('El email no es válido');
        return false;
    }

    if (!contrasena) {
        mostrarError('La contraseña es requerida');
        return false;
    }

    if (!validarContrasena(contrasena)) {
        mostrarError('La contraseña debe tener al menos 6 caracteres');
        return false;
    }

    // Si todo es válido, enviar formulario
    event.target.submit();
}

// ================== VALIDAR FORMULARIO REGISTRO ==================
function validarFormularioRegistro(event) {
    event.preventDefault();

    const nombre = document.querySelector('input[name="nombre"]').value.trim();
    const email = document.querySelector('input[name="correo"]').value.trim();
    const contrasena = document.querySelector('input[name="contrasena"]').value;
    const confirmar = document.querySelector('input[name="confirmar_contrasena"]').value;

    // Validaciones
    if (!nombre) {
        mostrarError('El nombre es requerido');
        return false;
    }

    if (nombre.length < 3) {
        mostrarError('El nombre debe tener al menos 3 caracteres');
        return false;
    }

    if (!email) {
        mostrarError('El email es requerido');
        return false;
    }

    if (!validarEmail(email)) {
        mostrarError('El email no es válido');
        return false;
    }

    if (!contrasena) {
        mostrarError('La contraseña es requerida');
        return false;
    }

    if (!validarContrasena(contrasena)) {
        mostrarError('La contraseña debe tener al menos 6 caracteres');
        return false;
    }

    if (contrasena !== confirmar) {
        mostrarError('Las contraseñas no coinciden');
        return false;
    }

    // Si todo es válido
    event.target.submit();
}

// ================== MOSTRAR ERROR ==================
function mostrarError(mensaje) {
    const error = document.createElement('div');
    error.style.cssText = `
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 0.5rem;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        font-weight: 600;
    `;
    error.textContent = '❌ ' + mensaje;

    document.body.appendChild(error);

    setTimeout(() => {
        error.style.opacity = '0';
        error.style.transition = 'opacity 0.3s ease';
        setTimeout(() => error.remove(), 300);
    }, 4000);
}

// ================== MOSTRAR ÉXITO ==================
function mostrarExito(mensaje) {
    const exito = document.createElement('div');
    exito.style.cssText = `
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 0.5rem;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        font-weight: 600;
    `;
    exito.textContent = '✅ ' + mensaje;

    document.body.appendChild(exito);

    setTimeout(() => {
        exito.style.opacity = '0';
        exito.style.transition = 'opacity 0.3s ease';
        setTimeout(() => exito.remove(), 300);
    }, 3000);
}

// ================== INICIALIZAR VALIDACIONES ==================
document.addEventListener('DOMContentLoaded', function () {
    // Agregar validación en tiempo real
    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');

    inputs.forEach(input => {
        input.addEventListener('blur', function () {
            if (this.name === 'email' || this.name === 'correo') {
                if (this.value && !validarEmail(this.value)) {
                    this.style.borderColor = '#dc2626';
                } else {
                    this.style.borderColor = '#e2e8f0';
                }
            }
        });
    });
});
