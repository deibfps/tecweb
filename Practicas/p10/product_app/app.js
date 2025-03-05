// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// FUNCIÓN CALLBACK DE BOTÓN "Buscar por ID"
function buscarID(e) {
    e.preventDefault();
    var id = document.getElementById('search').value;
    var client = getXMLHttpRequest();

    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n' + client.responseText);
            let productos = JSON.parse(client.responseText);

            if (Object.keys(productos).length > 0) {
                let descripcion = `
                    <li>precio: ${productos.precio}</li>
                    <li>unidades: ${productos.unidades}</li>
                    <li>modelo: ${productos.modelo}</li>
                    <li>marca: ${productos.marca}</li>
                    <li>detalles: ${productos.detalles}</li>`;

                let template = `
                    <tr>
                        <td>${productos.id}</td>
                        <td>${productos.nombre}</td>
                        <td><ul>${descripcion}</ul></td>
                    </tr>`;

                document.getElementById("productos").innerHTML = template;
            }
        }
    };
    client.send("id=" + id);
}

// NUEVA FUNCIÓN: BÚSQUEDA FLEXIBLE POR NOMBRE, MARCA O DETALLES
function buscarProducto(e) {
    e.preventDefault();
    var query = document.getElementById('search').value;
    var client = getXMLHttpRequest();

    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n' + client.responseText);
            let productos = JSON.parse(client.responseText);
            let resultado = document.getElementById("productos");
            resultado.innerHTML = "";

            if (productos.length > 0) {
                productos.forEach(producto => {
                    let descripcion = `
                        <li>precio: ${producto.precio}</li>
                        <li>unidades: ${producto.unidades}</li>
                        <li>modelo: ${producto.modelo}</li>
                        <li>marca: ${producto.marca}</li>
                        <li>detalles: ${producto.detalles}</li>`;

                    let template = `
                        <tr>
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>`;

                    resultado.innerHTML += template;
                });
            } else {
                resultado.innerHTML = "<tr><td colspan='3'>No se encontraron productos.</td></tr>";
            }
        }
    };
    client.send("query=" + encodeURIComponent(query));
}

// SE CREA EL OBJETO DE CONEXIÓN COMPATIBLE CON EL NAVEGADOR
function getXMLHttpRequest() {
    var objetoAjax;
    try {
        objetoAjax = new XMLHttpRequest();
    } catch (err1) {
        try {
            objetoAjax = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (err2) {
            try {
                objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (err3) {
                objetoAjax = false;
            }
        }
    }
    return objetoAjax;
}

// FUNCIÓN PARA INICIALIZAR EL FORMULARIO CON EL JSON BASE
function init() {
    var JsonString = JSON.stringify(baseJSON, null, 2);
    document.getElementById("description").value = JsonString;
}
