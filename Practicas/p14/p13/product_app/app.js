// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "http://localhost/tecweb/practicas/p09/img/img.png"
};

function init() {
    // Muestra el JSON en el campo description al inicializar (opcional)
    // document.getElementById("description").value = JSON.stringify(baseJSON, null, 2);
}

$(document).ready(function() {
    let edit = false;

    console.log('jQuery is Working');
    $('#product-result').hide();
    listarProductos();

    $('#search').keyup(function(e) {
        if ($('#search').val()) {
            let search = $('#search').val();
            console.log(`Buscando productos con el término: ${search}`);
            $.ajax({
                url: 'backend/product-search.php',
                type: 'GET',
                data: { search },
                success: function(response) {
                    console.log(`Respuesta de búsqueda: ${response}`);
                    let products = JSON.parse(response);
                    let template = '';

                    if (products.length > 0) {
                        products.forEach(product => {
                            template += `
                                <li>
                                    <strong>${product.nombre}</strong><br>
                                    Precio: ${product.precio}<br>
                                    Unidades: ${product.unidades}<br>
                                    Modelo: ${product.modelo}<br>
                                    Marca: ${product.marca}<br>
                                    Detalles: ${product.detalles}<br>
                                    <hr>
                                </li>
                            `;
                        });
                        $('#product-result').show(); // Muestra los resultados
                        $('#container').html(template); // Muestra los productos en el contenedor
                        $('#products').hide(); // Oculta la tabla de productos
                    } else {
                        $('#product-result').hide(); // Oculta si no hay resultados
                        $('#products').show(); // Muestra la tabla de productos si no hay resultados de búsqueda
                    }
                }
            });
        } else {
            $('#container').html(''); // Limpia el contenedor si no hay búsqueda
            $('#products').show(); // Muestra la tabla de productos nuevamente
        }
    });

    // Validaciones de los campos (foco)
    $('#name').blur(validateName);
    $('#marca').blur(validateMarca);
    $('#modelo').blur(validateModelo);
    $('#precio').blur(validatePrecio);
    $('#unidades').blur(validateUnidades);
    
    function validateName() {
        let name = $('#name').val();
        
        // Si estamos en modo edición, no verificamos si el nombre ya existe
        if (edit) {
            console.log('Modo edición: se ignora la validación de nombre.');
            return true; // Permitir el nombre actual
        }
        
        if (name === '' || name.length > 100) {
            $('#container').text('Nombre es requerido y debe tener 100 caracteres o menos.');
            $('#product-result').show();
            return false;
        }
    
        console.log('Validando nombre:', name);
        // Validación asincrónica de nombre único
        $.get('backend/product-name.php', { name }, function(response) {
            const data = JSON.parse(response);
            console.log('Respuesta de validación de nombre:', data);
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
    $('#product-form').submit(function(e) {
        e.preventDefault();
        
        // Verificar las validaciones antes de proceder
        Promise.all([
            validateName(),
            validateMarca(),
            validateModelo(),
            validatePrecio(),
            validateUnidades()
        ]).then(results => {
            console.log(`Resultados de validación: ${results}`);
            if (results.includes(false)) {
                $('#container').text('Por favor, corrige los errores antes de enviar.');
                $('#product-result').show();
                return;
            }

            // Verifica si la URL de la imagen está vacía
            let imagen = $('#imagen').val();
            if (!imagen) {
                imagen = "http://localhost/tecweb/practicas/p09/img/img.png"; // URL por defecto
                console.log('Se usará la URL de imagen por defecto:', imagen);
            }

            const postData = {
                nombre: $('#name').val(),
                marca: $('#marca').val(),
                modelo: $('#modelo').val(),
                precio: parseFloat($('#precio').val()),
                detalles: $('#detalles').val(),
                unidades: parseInt($('#unidades').val()),
                imagen: imagen,
                id: $('#product-id').val()
            };
            console.log('Datos a enviar:', postData);
            
            $.ajax({
                url: edit ? 'backend/product-edit.php' : 'backend/product-add.php',
                type: 'POST',
                data: JSON.stringify(postData),
                contentType: 'application/json',
                success: function(response) {
                    console.log(`Respuesta de agregar/editar producto: ${response}`);
                    const res = JSON.parse(response);
                    $('#container').text(res.message);
                    $('#product-result').show();
                    if (res.status === "success") {
                        listarProductos();
                        $('#product-form').trigger('reset');
                        edit = false; // Resetea el estado de edición
                    }
                },
                error: function() {
                    $('#container').text('Error al agregar o editar el producto.');
                    $('#product-result').show();
                }
            });
        });
    });

    // Obtener todos los productos
    function listarProductos() {
        console.log('Obteniendo la lista de productos...');
        $.ajax({
            url: 'backend/product-list.php',
            type: 'GET',
            success: function(response) {
                console.log(`Respuesta de listar productos: ${response}`);
                try {
                    let products = JSON.parse(response);
                    let template = '';
                    products.forEach(product => {
                        let descripcion = '';
                        descripcion += '<li>precio: ' + product.precio + '</li>';
                        descripcion += '<li>unidades: ' + product.unidades + '</li>';
                        descripcion += '<li>modelo: ' + product.modelo + '</li>';
                        descripcion += '<li>marca: ' + product.marca + '</li>';
                        descripcion += '<li>detalles: ' + product.detalles + '</li>';
                        template += `
                            <tr productId="${product.id}">
                                <td>${product.id}</td>
                                <td><a href="javascript:void(0);" class="product-item">${product.nombre}</a></td>
                                <td>${descripcion}</td>
                                <td>
                                    <button class="product-delete btn btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#products').html(template);
                } catch (error) {
                    console.error('Error al parsear JSON:', error);
                    console.log('Respuesta recibida:', response);
                }
            }
        });
    }

    // Obtener un Producto por ID
    $(document).on('click', '.product-item', function() { 
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('productId');
        console.log(`Obteniendo el producto con ID: ${id}`);
        
        // Hacemos la petición GET para obtener el producto por su ID
        $.get('backend/product-single.php', { id }, function(response) {   
            console.log(`Respuesta de obtener producto: ${response}`);
            const product = JSON.parse(response);

            // Verificamos si el estado de la respuesta es "success"
            if (product.status === 'success') {
                console.log('Producto obtenido:', product);

                // Rellenar los campos del producto
                $('#name').val(product.producto.nombre);
                $('#marca').val(product.producto.marca);
                $('#modelo').val(product.producto.modelo);
                $('#precio').val(product.producto.precio);
                $('#detalles').val(product.producto.detalles);
                $('#unidades').val(product.producto.unidades);
                $('#imagen').val(product.producto.imagen);
                $('#product-id').val(product.producto.id);

                edit = true;
            } else {
                $('#container').html(product.message);  // En caso de error, muestra el mensaje
                $('#product-result').show();
            }
        });
    });

    // Eliminar un producto
    $(document).on('click', '.product-delete', (e) => {
        if(confirm('¿Estás seguro de que deseas eliminarlo?')) {
            const element = e.currentTarget.closest('tr'); // Cambiar a closest('tr')
            const id = $(element).attr('productId'); // Obtener el ID del producto
            console.log(`Eliminando producto con ID: ${id}`);
            $.post('backend/product-delete.php', { id }, (response) => {
                console.log(`Respuesta de eliminar producto: ${response}`);
                const res = JSON.parse(response);
                if (res.status === "success") {
                    alert(res.message); // Mostrar mensaje de éxito
                } else {
                    alert(res.message); // Mostrar mensaje de error
                }
                listarProductos(); // Actualizar la lista de productos después de eliminar
            });
        }
    });
});
