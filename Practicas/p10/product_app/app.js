document.addEventListener("DOMContentLoaded", function() {
    let form = document.getElementById("task-form");
    
    // Eliminamos cualquier event listener previo para evitar duplicados
    form.removeEventListener("submit", agregarProducto);
    form.addEventListener("submit", agregarProducto);
});

// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "nombre": "Producto X",
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// FUNCIÓN PARA INICIALIZAR EL FORMULARIO
function init() {
    document.getElementById("description").value = JSON.stringify(baseJSON, null, 2);
}

// FUNCIÓN PARA AGREGAR UN PRODUCTO A LA BASE DE DATOS
function agregarProducto(event) {
    event.preventDefault(); // Evita la doble ejecución

    let jsonText = document.getElementById("description").value.trim();

    if (!jsonText) {
        alert("Por favor, ingresa los datos del producto en formato JSON.");
        return;
    }

    try {
        let producto = JSON.parse(jsonText);

        fetch("./backend/create.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(producto),
        })
        .then(response => response.json())
        .then(data => {
            console.log("[SERVIDOR]", data);
            alert(data.message);
            
            // Limpia el campo después de agregar un producto
            document.getElementById("description").value = "";
        })
        .catch(error => console.error("Error:", error));

    } catch (error) {
        alert("Error en el formato del JSON. Verifica la sintaxis.");
    }
}

// FUNCIÓN PARA BUSCAR UN PRODUCTO POR ID
function buscarID(event) {
    event.preventDefault();
    let id = document.getElementById("search").value.trim();
    if (!id) return alert("Ingresa un ID válido.");

    fetch("./backend/read.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + encodeURIComponent(id)
    })
    .then(response => response.json())
    .then(producto => {
        let resultado = document.getElementById("productos");
        resultado.innerHTML = producto ? `
            <tr>
                <td>${producto.id}</td>
                <td>${producto.nombre}</td>
                <td>
                    <ul>
                        <li>Precio: ${producto.precio}</li>
                        <li>Unidades: ${producto.unidades}</li>
                        <li>Modelo: ${producto.modelo}</li>
                        <li>Marca: ${producto.marca}</li>
                        <li>Detalles: ${producto.detalles}</li>
                    </ul>
                </td>
            </tr>` : `<tr><td colspan='3'>No se encontraron productos.</td></tr>`;
    });
}

// FUNCIÓN PARA BÚSQUEDA FLEXIBLE POR NOMBRE/MARCA/DETALLES
function buscarProducto(event) {
    event.preventDefault();
    let query = document.getElementById("search").value.trim();
    if (!query) return alert("Ingresa un criterio de búsqueda.");

    fetch("./backend/read.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "query=" + encodeURIComponent(query)
    })
    .then(response => response.json())
    .then(productos => {
        let resultado = document.getElementById("productos");
        resultado.innerHTML = productos.length ? productos.map(p => `
            <tr>
                <td>${p.id}</td>
                <td>${p.nombre}</td>
                <td>
                    <ul>
                        <li>Precio: ${p.precio}</li>
                        <li>Unidades: ${p.unidades}</li>
                        <li>Modelo: ${p.modelo}</li>
                        <li>Marca: ${p.marca}</li>
                        <li>Detalles: ${p.detalles}</li>
                    </ul>
                </td>
            </tr>`).join("") : `<tr><td colspan='3'>No se encontraron productos.</td></tr>`;
    });
}
