
$(function() {

    let edit = false;
    console.log('jQuery is Working');
    $('#product-result').hide();
    listarProductos();
    $('#search').keyup( function(e) {
        if($('#search').val()){
            let search = $('#search').val();
            $.ajax({
                url:'backend/product-search.php',
                type: 'GET',
                data: { search },
                success: function(response){
                    let products = JSON.parse(response);
                    let template = '';
                    products.forEach(product => {
                        let descripcion = '';
                            descripcion += '<li>precio: '+product.precio+'</li>';
                            descripcion += '<li>unidades: '+product.unidades+'</li>';
                            descripcion += '<li>modelo: '+product.modelo+'</li>';
                            descripcion += '<li>marca: '+product.marca+'</li>';
                            descripcion += '<li>detalles: '+product.detalles+'</li>';
                        template += `
                            <tr productId = "${product.id}">
                                <td>${product.id}</td>
                                <td>${product.nombre}</td>
                                <td>${descripcion}</td>
                                <td>
                                <button class="product-delete btn btn-danger">
                                    Eliminar
                                </button>
                                </td>
                            </tr>
                        `
                    });
                    $('#products').html(template);
                }
            })
        }
    });
// Validar campos al perder el foco
$('#name').blur(validateName);
$('#marca').blur(validateMarca);
$('#modelo').blur(validateModelo);
$('#precio').blur(validatePrecio);
$('#unidades').blur(validateUnidades);

function validateName() {
    let name = $('#name').val();
    if (name === '' || name.length > 100) {
        $('#container').text('Nombre es requerido y debe tener 100 caracteres o menos.');
        $('#product-result').show();
        return false;
    }
    // Validación asincrónica de nombre único
    $.get('backend/product-name-check.php', { name }, function(response) {
        const data = JSON.parse(response);
        if (data.exists) {
            $('#container').text('Nombre ya existe. Elige otro nombre.');
            $('#product-result').show();
            return false;
        }
    });
    return true;
}

function validateMarca() {
    if ($('#marca').val() === '') {
        $('#container').text('Marca es requerida.');
        $('#product-result').show();
        return false;
    }
    return true;
}

function validateModelo() {
    let modelo = $('#modelo').val();
    if (modelo === '' || modelo.length > 25) {
        $('#container').text('Modelo es requerido y debe tener 25 caracteres o menos.');
        $('#product-result').show();
        return false;
    }
    return true;
}

function validatePrecio() {
    let precio = parseFloat($('#precio').val());
    if (isNaN(precio) || precio <= 99.99) {
        $('#container').text('Precio es requerido y debe ser mayor a 99.99.');
        $('#product-result').show();
        return false;
    }
    return true;
}

function validateUnidades() {
    let unidades = parseInt($('#unidades').val());
    if (isNaN(unidades) || unidades < 0) {
        $('#container').text('Unidades son requeridas y deben ser mayores o iguales a 0.');
        $('#product-result').show();
        return false;
    }
    return true;
}
// * AGREGAR PRODUCTO (envío de formulario) *
$('#product-form').submit(function (e) {
    e.preventDefault();
    if (!validateName() || !validateMarca() || !validateModelo() || !validatePrecio() || !validateUnidades()) {
        $('#container').text('Por favor, corrige los errores antes de enviar.');
        $('#product-result').show();
        return;
    }

    const postData = {
        nombre: $('#name').val(),
        marca: $('#marca').val(),
        modelo: $('#modelo').val(),
        precio: parseFloat($('#precio').val()),
        detalles: $('#detalles').val(),
        unidades: parseInt($('#unidades').val()),
        imagen: $('#imagen').val(),
        id: $('#product-id').val()
    };
    
    $.ajax({
        url: edit ? 'backend/product-edit.php' : 'backend/product-add.php',
        type: 'POST',
        data: JSON.stringify(postData),
        contentType: 'application/json',
        success: function (response) {
            const res = JSON.parse(response);
            $('#container').text(res.message);
            $('#product-result').show();
            if (res.status === "success") {
                listarProductos();
                $('#product-form').trigger('reset');
            }
        },
        error: function () {
            $('#container').text('Error al agregar o editar el producto.');
            $('#product-result').show();
        }
    });
});



    function listarProductos() {
        $.ajax({
            url: 'backend/product-list.php',
            type: 'GET',
            success: function (response){
                let products = JSON.parse(response);
                let template = '';
                products.forEach(product => {
                    let descripcion = '';
                        descripcion += '<li>precio: '+product.precio+'</li>';
                        descripcion += '<li>unidades: '+product.unidades+'</li>';
                        descripcion += '<li>modelo: '+product.modelo+'</li>';
                        descripcion += '<li>marca: '+product.marca+'</li>';
                        descripcion += '<li>detalles: '+product.detalles+'</li>';
                    template += `
                        <tr productId = "${product.id}">
                            <td>${product.id}</td>
                            <td>
                                <a href"#" class="product-item"> ${product.nombre} </a>
                            </td>
                            <td>${descripcion}</td>
                            <td>
                                <button class="product-delete btn btn-danger">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `
                });
                $('#products').html(template);
            }
        })
    }
    
    // Eliminar un producto
    $(document).on('click', '.product-delete', (e) => {
        if(confirm('¿Estás seguro de que deseas eliminarlo?')) {
            // Usar e.currentTarget para obtener el botón correcto
            const element = e.currentTarget.closest('tr'); // Cambiar a closest('tr')
            const id = $(element).attr('productId'); // Obtener el ID del producto
            $.get('backend/product-delete.php', {id}, (response) => {
                // Procesar la respuesta del servidor
                const res = JSON.parse(response);
                if (res.status === "success") {
                    $('#container').html(res.message); // Mostrar mensaje de éxito
                    $('#product-result').show();
                } else {
                    $('#container').html(res.message); // Mostrar mensaje de error
                    $('#product-result').show();
                }
                listarProductos(); // Actualizar la lista de productos después de eliminar
            });
        }
    });

    $(document).on('click', '.product-item', function() { 
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('productId');
        
        // Hacemos la petición GET para obtener el producto por su ID
        $.get('backend/product-single.php', {id}, function(response) {   
            const product = JSON.parse(response);
    
            // Verificamos si el estado de la respuesta es "success"
            if (product.status === 'success') {
                console.log(product);
    
                // Rellenar el campo del nombre con el nombre del producto
                $('#name').val(product.producto.nombre);
                $('#marca').val(product.producto.marca);
                $('#modelo').val(product.producto.modelo);
                $('#precio').val(product.producto.precio);
                $('#detalles').val(product.producto.detalles);
                $('#unidades').val(product.producto.unidades);
                $('#imagen').val(product.producto.imagen);

                // Convertir los detalles del producto a JSON y mostrarlos en el campo #description
                const description = {
                    precio: product.producto.precio,
                    unidades: product.producto.unidades,
                    modelo: product.producto.modelo,
                    marca: product.producto.marca,
                    detalles: product.producto.detalles,
                    imagen: product.producto.imagen
                };
    
                // Rellenar el campo de descripción en formato JSON
                $('#description').val(JSON.stringify(description, null, 2));  // Formateado para que sea más legible
                $('#product-id').val(product.producto.id);
                
                edit = true;
            } else {
                $('#container').html(product.message);  // En caso de error, muestra el mensaje
                $('#product-result').show();
            }
        });
    });
        

});

// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
  };



function init() {
    /**
     * Convierte el JSON a string para poder mostrarlo
     * ver: https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Global_Objects/JSON
     */


  
}


