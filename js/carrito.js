// Función para actualizar el contador del carrito desde el servidor
function actualizarContadorCarrito() {
    fetch('../php/contar_carrito.php')
        .then(res => res.json())
        .then(data => {
            const contador = document.getElementById('contador-carrito');
            if (contador) {
                contador.textContent = data.total || 0;
            }
        })
        .catch(err => console.error('Error al contar carrito:', err));
}

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    actualizarContadorCarrito(); // Al cargar la página

    // Manejo de botones + y -
    document.querySelectorAll('.btn-mas').forEach(boton => {
        boton.addEventListener('click', () => {
            const input = boton.parentElement.querySelector('.cantidad');
            input.value = parseInt(input.value) + 1;
        });
    });

    document.querySelectorAll('.btn-menos').forEach(boton => {
        boton.addEventListener('click', () => {
            const input = boton.parentElement.querySelector('.cantidad');
            const actual = parseInt(input.value);
            if (actual > 1) input.value = actual - 1;
        });
    });

    // Manejo del botón Agregar
    const botones = document.querySelectorAll('.agregar-carrito');

    botones.forEach(boton => {
        boton.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const precio = this.getAttribute('data-precio');

            // Buscar input cantidad desde el contenedor padre
            const cantidadInput = this.parentElement.querySelector('.cantidad');
            const cantidad = parseInt(cantidadInput?.value) || 1;

            const datos = new URLSearchParams();
            datos.append('id', id);
            datos.append('nombre', nombre);
            datos.append('precio', precio);
            datos.append('cantidad', cantidad);

            fetch('../php/agregar_carrito.php', {
                method: 'POST',
                body: datos,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Mostrar mensaje
                actualizarContadorCarrito(); // Refrescar contador
            })
            .catch(error => {
                console.error('Error al agregar al carrito:', error);
                alert('Hubo un error al agregar el producto.');
            });
        });
    });
});
