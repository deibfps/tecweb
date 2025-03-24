//EN ESTE ESTOY TRABAJANDO

// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
  };

  let editar = false;

function init() {
    /**
     * Convierte el JSON a string para poder mostrarlo
     * ver: https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Global_Objects/JSON
     */
    $('#product-result').hide(); //Ocultamos el cuadro de product-result siempre
    //var JsonString = JSON.stringify(baseJSON,null,2);
    //document.getElementById("description").value = JsonString;
    
    // SE LISTAN TODOS LOS PRODUCTOS
    listarProductos();
    agregarProducto();
    buscarProducto();
    eliminarProducto();
    editarProducto();
}

function listarProductos(){
    $.ajax({
        url: 'backend/product-list.php',
        type: 'GET',
        success: function(response){
            //alert(response);
            let products = JSON.parse(response);
            let template = '';
            products.forEach(product => {

                let descripcion = '';
                descripcion += '<li>precio: '+product.precio+'</li>';
                descripcion += '<li>unidades: '+product.unidades+'</li>';
                descripcion += '<li>modelo: '+product.modelo+'</li>';
                descripcion += '<li>marca: '+product.marca+'</li>';
                descripcion += '<li>detalles: '+product.detalles+'</li>';

                template += `<tr productId = "${product.id}">
                    <td>${product.id}</td>
                    <td>
                        <a href = "#" class = "product-item">${product.nombre}</a>
                    </td>
                    <td><ul>${descripcion}</ul></td>
                        <td>
                            <button class="product-delete btn btn-danger">
                                Eliminar
                            </button>
                        </td>
            </tr>`
            });
            $('#products').html(template);
        }
    });
}

function buscarProducto(){
    //Obtenemos la informacion del input de la barra de tareas
    $('#product-result').hide;
    $('#search').keyup(function(e){
        if ($('#search').val()){
            let search = $('#search').val();
            $.ajax({
                url: 'backend/product-search.php',
                type: 'GET',
                data: {search},
                success: function(response){
                    //console.log(response);
                    let products = JSON.parse(response);
                    let template = '';
                    products.forEach(product => {
                        template += `<li>
                            ${product.nombre}
                        </li>`
                    });
                    $('#container').html(template);
                    //$('#product-result').show;
                    //Solo mostrar el contenedor si hay resultados
                    if (template) {
                        $('#container').html(template);
                        $('#product-result').show();
                        //listarProductos();
                    } else {
                        $('#product-result').hide();
                    }

                    let productTableTemplate = '';
                    products.forEach(product => { 
                        let descripcion = '';
                        descripcion += '<li>precio: '+product.precio+'</li>';
                        descripcion += '<li>unidades: '+product.unidades+'</li>';
                        descripcion += '<li>modelo: '+product.modelo+'</li>';
                        descripcion += '<li>marca: '+product.marca+'</li>';
                        descripcion += '<li>detalles: '+product.detalles+'</li>';

                        productTableTemplate += `<tr productId="${product.id}">
                            <td>${product.id}</td>
                            <td><a href="#" class = product-item>${product.nombre}</a></td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-delete btn btn-danger">
                                    Eliminar
                                </button>
                            </td>
                        </tr>`;
                    });
                    $('#products').html(productTableTemplate);
                }
            });
        }
    });
}

function agregarProducto() {
    $('#product-form').submit(function(e) {
        e.preventDefault();
        //const jsonData = $('#description').val();
        //let productData;
        /*try {
            productData = JSON.parse(jsonData);
        } catch (error) {
            alert("Error: El JSON no está bien definido.");
            return;
        }*/

        const postData = {
            id: $('#product-Id').val(),
            nombre: $('#name').val().trim(),
            marca: $('#form-marca').val(),
            modelo: $('#form-modelo').val().trim(),
            precio: $('#form-precio').val(),
            unidades: $('#form-unidades').val(),
            detalles: $('#form-detalles').val(),
            imagen: $('#form-imagen').val()
        };
        //console.log(postData);
        const jsonPostData = JSON.stringify(postData);

        let url = editar === false ? 'backend/product-add.php' : 'backend/product-edit.php';
        let template = '';

        $.post(url, jsonPostData, function(response) {
            //console.log(response);
            //alert(response);
            let result = typeof response === 'string' ? JSON.parse(response) : response;
            template += `Status: `;
            template += typeof result.status === 'string' ? result.status : JSON.stringify(result.status);
            template += `<br>Message: `;
            //let result = JSON.parse(response);
            template += typeof result.message === 'string' ? result.message : JSON.stringify(result.message);
            console.log(template);
            $('#container').html(template);
            $('#product-result').show();
            if (result.status === "success") {
                alert("Registro exitoso");
                listarProductos();
                $('#product-form').trigger('reset');
                $('#description').val(JSON.stringify(baseJSON, null, 2));
                editar = false;
            } else {
                alert(result.message);
            }
        });
    });
}


function eliminarProducto() {
    $(document).on('click', '.product-delete', function() {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('productId');
        let templete = ' ';
        if (confirm(`¿Estás seguro de eliminar el producto con ID: ${id}?`)) {
            $.get('backend/product-delete.php', { id }, function(response) {
                //alert(response);
                let result = JSON.parse(response);
                template = `Status: ${result.status}<br>Message: ${result.message}`;
                console.log(template);
                $('#container').html(template);
                $('#product-result').show();
                
                if (result.status === "success") {
                    alert("Eliminado exitosamente");
                } else {
                    alert("Error al eliminar el producto: " + result.message);
                }
                listarProductos();
            });
        }
    });
}

function editarProducto(){
    $(document).on('click', '.product-item', function(){
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('productId');
        //console.log(element);
        //console.log(id);
        $.post('backend/product-single.php', { id }, function(response) {
            console.log(response);
            const producto = JSON.parse(response);
            //alert(producto);
            $('#name').val(producto.nombre);
            const descripcionJSON = JSON.stringify({
                precio: producto.precio,
                unidades: producto.unidades,
                modelo: producto.modelo,
                marca: producto.marca,
                detalles: producto.detalles,
                imagen: producto.imagen
            }, null, 2);
            $('#product-Id').val(producto.id);
            $('#name').val(producto.nombre);
            $('#form-marca').val(producto.marca);
            $('#form-modelo').val(producto.modelo);
            $('#form-precio').val(producto.precio);
            $('#form-unidades').val(producto.unidades);
            $('#form-detalles').val(producto.detalles);
            //$('#form-imagen').val(producto.imagen);
            //$('#description').val(descripcionJSON);
            editar = true;
        });
    });

}

