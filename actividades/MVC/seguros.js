function validarNombre() {
    const nombre = $('#name').val();
    $('#product-result').hide();
    
    if (nombre.length > 100) {
        $('#container').text('El nombre es demasiado largo');
        $('#product-result').show();
    } else if (nombre.trim() === "") {
        $('#container').text('Por favor inserte un nombre');
        $('#product-result').show();
    } else {
        $.get('backend/product-name.php', { nombre: nombre }, function(response) {
            console.log(response);
            if (response.exists) {
                $('#container').text('Nombre ya existe, elige otro nombre');
                $('#product-result').show();
            } else {
                $('#container').text('');
                $('#product-result').hide();
            }
        });
    }
}



function validarMarca(){
    var marca = $('#form-marca').val();
    $('#product-result').hide();
    if (marca === "") {
        $('#container').html("Selecciona una marca");
        $('#product-result').show();
    }
}

function validarModelo(){
    var modelo = $('#form-modelo').val().trim();
    $('#product-result').hide();
    var alfanumerico = /^[\w\s]+$/;
    if (modelo.length === 0) {
        $('#container').html("El modelo es requerido");
        $('#product-result').show();
    } else if (modelo.length > 25) {
        $('#container').html("El modelo debe tener 25 caracteres o menos");
        $('#product-result').show();
    } else if (!alfanumerico.test(modelo)) {
        $('#container').html("El modelo debe ser alfanumérico");
        $('#product-result').show();
    }
}

function validarPrecio() {
    var precio = $('#form-precio').val();
    $('#product-result').hide();
    if (precio.trim() === "") {
        $('#container').html("El precio es requerido");
        $('#product-result').show();
    } else {
        precio = parseFloat(precio);
        if (precio > 99.99) {
        } else {
            $('#container').html("El precio debe ser mayor de 99.99");
            $('#product-result').show();
        }
    }
}

function validarUnidades() {
    var unidades = $('#form-unidades').val();
    $('#product-result').hide();
    if (unidades.trim() === "") {
        $('#container').html("Las unidades son requeridas");
        $('#product-result').show();
    } else {
        unidades = parseInt(unidades, 10);
        if (unidades >= 0) {
        } else {
            $('#container').html("Las unidades deben ser mayor o igual a 0");
            $('#product-result').show();
        }
    }
}


function validarDetalles() {
    var detalles = $('#form-detalles').val();
    $('#product-result').hide();
    if (detalles.length > 250) {
        $('#container').html("Los detalles deben tener 250 caracteres o menos");
        $('#product-result').show();
    }
}

function validarImagen() {
    var inputImagen = $('#form-imagen')[0];
    var formData = new FormData(document.getElementById("product-form"));
    if (inputImagen.files.length === 0) {
        formData.append("imagen", "defecto.jpg");
    } else {
        var file = inputImagen.files[0];
        formData.append("imagen", file);
    }
    $('#container').html("");
}




