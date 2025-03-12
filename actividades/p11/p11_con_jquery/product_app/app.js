// JSON BASE PARA MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

function init() {
    var JsonString = JSON.stringify(baseJSON, null, 2);
    $("#description").val(JsonString);

    listarProductos();
}

// FUNCIÓN PARA LISTAR PRODUCTOS
function listarProductos() {
    $.ajax({
        url: './backend/product-list.php',
        method: 'GET',
        dataType: 'json',
        success: function (productos) {
            if (productos.length > 0) {
                let template = '';

                productos.forEach(producto => {
                    let descripcion = `
                        <li>precio: ${producto.precio}</li>
                        <li>unidades: ${producto.unidades}</li>
                        <li>modelo: ${producto.modelo}</li>
                        <li>marca: ${producto.marca}</li>
                        <li>detalles: ${producto.detalles}</li>
                    `;

                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>
                                <a href="#" class="product-name" onclick="cargarProducto(${producto.id})">${producto.nombre}</a>
                            </td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-delete btn btn-danger" onclick="eliminarProducto(${producto.id})">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });

                $("#products").html(template);
            }
        }
    });
}

// FUNCIÓN PARA BUSCAR UN PRODUCTO
function buscarProducto(e) {
    e.preventDefault();
    var search = $('#search').val().trim();

    if (search === "") {
        listarProductos();
        return;
    }

    $.ajax({
        url: './backend/product-search.php',
        method: 'GET',
        data: { search: search },
        dataType: 'json',
        success: function (productos) {
            if (productos.length > 0) {
                let template = '';

                productos.forEach(producto => {
                    let descripcion = `
                        <li>precio: ${producto.precio}</li>
                        <li>unidades: ${producto.unidades}</li>
                        <li>modelo: ${producto.modelo}</li>
                        <li>marca: ${producto.marca}</li>
                        <li>detalles: ${producto.detalles}</li>
                    `;

                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>
                                <a href="#" class="product-name" onclick="cargarProducto(${producto.id})">${producto.nombre}</a>
                            </td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-delete btn btn-danger" onclick="eliminarProducto(${producto.id})">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });

                $("#products").html(template);
            } else {
                $("#products").html("<tr><td colspan='4'>No se encontraron productos.</td></tr>");
            }
        }
    });
}

// FUNCIÓN PARA CARGAR UN PRODUCTO AL FORMULARIO AL HACER CLIC EN SU NOMBRE
function cargarProducto(id) {
    $.ajax({
        url: './backend/product-change.php',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function (producto) {
            $('#name').val(producto.nombre);
            $('#description').val(JSON.stringify(producto, null, 2));
            $('#submit-btn').text('Actualizar Producto').attr('data-id', id);
        }
    });
}

// FUNCIÓN PARA AGREGAR O ACTUALIZAR UN PRODUCTO
function agregarProducto(e) {
    e.preventDefault();
    var productoJsonString = $('#description').val();
    var finalJSON = JSON.parse(productoJsonString);
    finalJSON['nombre'] = $('#name').val();

    var id = $('#submit-btn').attr('data-id');
    var url = id ? './backend/product-change.php' : './backend/product-add.php';

    if (id) finalJSON['id'] = id;

    // Validaciones
    if (!finalJSON['nombre'] || finalJSON['nombre'].trim() === '') {
        alert("El nombre del producto es obligatorio.");
        return;
    }

    if (finalJSON['precio'] <= 100) {
        alert("El precio debe ser mayor a 100.");
        return;
    }

    if (id) {
        // Comprobamos si el ID ya existe
        $.ajax({
            url: './backend/product-change.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (producto) {
                if (producto) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(finalJSON, null, 2),
                        dataType: 'json',
                        success: function (respuesta) {
                            if (respuesta.status === "success") {
                                alert("Producto modificado con éxito.");
                            } else {
                                alert("Error al modificar producto: " + respuesta.message);
                            }
                            listarProductos();
                            $('#submit-btn').text('Agregar Producto').removeAttr('data-id');
                            $('#name').val('');
                            $('#description').val(JSON.stringify(baseJSON, null, 2));
                        }
                    });
                }
            }
        });
    } else {
        $.ajax({
            url: url,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(finalJSON, null, 2),
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.status === "success") {
                    alert("Producto agregado con éxito.");
                } else {
                    alert("Error al agregar producto: " + respuesta.message);
                }
                listarProductos();
                $('#submit-btn').text('Agregar Producto').removeAttr('data-id');
                $('#name').val('');
                $('#description').val(JSON.stringify(baseJSON, null, 2));
            }
        });
    }
}

// FUNCIÓN PARA ELIMINAR UN PRODUCTO
function eliminarProducto(id) {
    if (confirm("¿De verdad deseas eliminar el producto?")) {
        $.ajax({
            url: './backend/product-delete.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (respuesta) {
                listarProductos();
            }
        });
    }
}

// EVENTOS
$('#search').on('input', function (e) {
    buscarProducto(e);
});

$('#submit-btn').on('click', function (e) {
    agregarProducto(e);
});

// INICIALIZACIÓN
init();
