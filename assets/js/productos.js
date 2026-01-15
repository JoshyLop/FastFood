/**
 * Funcionalidad de Productos - FastFood
 */

// ================== CARGAR PRODUCTOS ==================
function cargarProductos(categoría = 'todas') {
    fetch(`../php/obtener_productos.php?categoria=${categoría}`)
        .then(response => response.json())
        .then(data => {
            if (data.exito) {
                mostrarProductos(data.productos);
            } else {
                console.error('Error al cargar productos:', data.mensaje);
            }
        })
        .catch(error => console.error('Error:', error));
}

// ================== MOSTRAR PRODUCTOS ==================
function mostrarProductos(productos) {
    const contenedor = document.querySelector('.productos-grid')
        || document.querySelector('.grid');

    if (!contenedor) return;

    if (productos.length === 0) {
        contenedor.innerHTML = '<p class="sin-productos">No hay productos disponibles</p>';
        return;
    }

    let html = '';
    productos.forEach(producto => {
        html += `
            <div class="product-card">
                <div class="product-image">
                    <img src="${producto.imagen}" alt="${producto.nombre}" onerror="this.src='../img/default.png'">
                    ${producto.descuento ? `<span class="descuento-badge">-${producto.descuento}%</span>` : ''}
                </div>
                <div class="product-content">
                    <h3>${producto.nombre}</h3>
                    <p class="descripcion">${producto.descripcion}</p>
                    <div class="product-price">
                        ${producto.precio_original ? `
                            <span class="precio-original">$${producto.precio_original.toFixed(2)}</span>
                            <span class="precio-actual">$${producto.precio.toFixed(2)}</span>
                        ` : `
                            <span class="precio">$${producto.precio.toFixed(2)}</span>
                        `}
                    </div>
                    <div class="product-actions">
                        <div class="cantidad-selector">
                            <button class="btn-cantidad" onclick="decrementarCantidad(this)">−</button>
                            <input type="number" class="cantidad-input" value="1" min="1">
                            <button class="btn-cantidad" onclick="incrementarCantidad(this)">+</button>
                        </div>
                        <button class="btn btn-primary" onclick="agregarAlCarrito(${producto.id}, '${producto.nombre}', ${producto.precio})">
                            <i class="fas fa-shopping-cart"></i> Agregar
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    contenedor.innerHTML = html;
}

// ================== INCREMENTAR CANTIDAD ==================
function incrementarCantidad(btn) {
    const input = btn.previousElementSibling;
    input.value = parseInt(input.value) + 1;
}

// ================== DECREMENTAR CANTIDAD ==================
function decrementarCantidad(btn) {
    const input = btn.nextElementSibling;
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

// ================== FILTRAR PRODUCTOS ==================
function filtrarProductos(evento) {
    const categoria = evento.target.value || evento.target.getAttribute('data-categoria');
    cargarProductos(categoria);

    // Highlight botón activo
    document.querySelectorAll('.btn-filtro').forEach(btn => {
        btn.classList.remove('activo');
    });
    evento.target.classList.add('activo');
}

// ================== BUSCAR PRODUCTOS ==================
function buscarProductos(termino) {
    const contenedor = document.querySelector('.productos-grid')
        || document.querySelector('.grid');

    if (!contenedor || !termino) {
        cargarProductos();
        return;
    }

    fetch(`../php/buscar_productos.php?q=${encodeURIComponent(termino)}`)
        .then(response => response.json())
        .then(data => {
            if (data.exito) {
                mostrarProductos(data.productos);
            } else {
                contenedor.innerHTML = '<p class="sin-productos">No se encontraron productos</p>';
            }
        })
        .catch(error => console.error('Error:', error));
}

// ================== ORDENAR PRODUCTOS ==================
function ordenarProductos(orden) {
    const contenedor = document.querySelector('.productos-grid')
        || document.querySelector('.grid');

    if (!contenedor) return;

    const productos = Array.from(contenedor.querySelectorAll('.product-card'));

    productos.sort((a, b) => {
        const precioA = parseFloat(a.querySelector('.precio-actual')?.textContent || a.querySelector('.precio')?.textContent);
        const precioB = parseFloat(b.querySelector('.precio-actual')?.textContent || b.querySelector('.precio')?.textContent);
        const nombreA = a.querySelector('h3').textContent;
        const nombreB = b.querySelector('h3').textContent;

        switch (orden) {
            case 'precio-menor':
                return precioA - precioB;
            case 'precio-mayor':
                return precioB - precioA;
            case 'nombre-az':
                return nombreA.localeCompare(nombreB);
            case 'nombre-za':
                return nombreB.localeCompare(nombreA);
            default:
                return 0;
        }
    });

    contenedor.innerHTML = '';
    productos.forEach(producto => contenedor.appendChild(producto));
}

// ================== AGREGAR ESTILOS ==================
const estilos = document.createElement('style');
estilos.textContent = `
    .productos-grid, .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }
    
    .product-card {
        background: white;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .product-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: #f1f5f9;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .descuento-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        font-weight: bold;
        font-size: 0.875rem;
    }
    
    .product-content {
        padding: 1rem;
    }
    
    .product-content h3 {
        margin: 0 0 0.5rem 0;
        color: var(--dark);
        font-size: 1rem;
    }
    
    .descripcion {
        color: #64748b;
        font-size: 0.875rem;
        margin: 0.5rem 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-price {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin: 1rem 0;
        font-weight: bold;
    }
    
    .precio-original {
        color: #94a3b8;
        text-decoration: line-through;
        font-size: 0.875rem;
    }
    
    .precio-actual, .precio {
        color: var(--primary);
        font-size: 1.25rem;
    }
    
    .product-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .cantidad-selector {
        display: flex;
        border: 1px solid #e2e8f0;
        border-radius: 0.25rem;
        overflow: hidden;
    }
    
    .btn-cantidad {
        background: #f1f5f9;
        border: none;
        color: var(--dark);
        width: 2rem;
        cursor: pointer;
        font-weight: bold;
        transition: background 0.2s;
    }
    
    .btn-cantidad:hover {
        background: #e2e8f0;
    }
    
    .cantidad-input {
        border: none;
        text-align: center;
        width: 3rem;
        font-weight: 600;
    }
    
    .cantidad-input::-webkit-outer-spin-button,
    .cantidad-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    .sin-productos {
        text-align: center;
        padding: 3rem 1rem;
        color: #64748b;
    }
    
    .btn-filtro {
        padding: 0.5rem 1rem;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-filtro:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
    
    .btn-filtro.activo {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    @media (max-width: 768px) {
        .productos-grid, .grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .product-actions {
            flex-direction: column;
        }
    }
`;
document.head.appendChild(estilos);

// ================== INICIALIZAR PRODUCTOS ==================
document.addEventListener('DOMContentLoaded', function () {
    // Cargar productos en página de catálogo
    const contenedor = document.querySelector('.productos-grid')
        || document.querySelector('.grid');

    if (contenedor && !contenedor.querySelector('.product-card')) {
        cargarProductos();
    }

    // Agregar evento de búsqueda
    const inputBusqueda = document.querySelector('input[name="busqueda"]')
        || document.querySelector('.input-busqueda');

    if (inputBusqueda) {
        let timeout;
        inputBusqueda.addEventListener('keyup', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                buscarProductos(this.value);
            }, 300);
        });
    }

    // Agregar evento de ordenamiento
    const selectOrden = document.querySelector('select[name="orden"]');
    if (selectOrden) {
        selectOrden.addEventListener('change', function () {
            ordenarProductos(this.value);
        });
    }
});
