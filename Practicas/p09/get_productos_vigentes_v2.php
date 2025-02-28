<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Lista de Productos Vigentes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"/>
    
    <script>
        function show(event) {
            var rowId = event.target.parentNode.parentNode.id;
            var data = document.getElementById(rowId).querySelectorAll(".row-data");

            var id = data[0].innerHTML;
            var nombre = data[1].innerHTML;
            var marca = data[2].innerHTML;
            var modelo = data[3].innerHTML;
            var precio = data[4].innerHTML;
            var unidades = data[5].innerHTML;
            var detalles = data[6].innerHTML;
            var imagen = data[7].querySelector("img").src;

            alert("Producto seleccionado:\n" +
                  "ID: " + id + "\n" +
                  "Nombre: " + nombre + "\n" +
                  "Marca: " + marca + "\n" +
                  "Modelo: " + modelo + "\n" +
                  "Precio: " + precio + "\n" +
                  "Unidades: " + unidades + "\n" +
                  "Detalles: " + detalles);

            send2form(id, nombre, marca, modelo, precio, unidades, detalles, imagen);
        }

        function send2form(id, nombre, marca, modelo, precio, unidades, detalles, imagen) {
            var url = "formulario_productos_v2.html" +
                      "?id=" + encodeURIComponent(id) +
                      "&nombre=" + encodeURIComponent(nombre) +
                      "&marca=" + encodeURIComponent(marca) +
                      "&modelo=" + encodeURIComponent(modelo) +
                      "&precio=" + encodeURIComponent(precio) +
                      "&unidades=" + encodeURIComponent(unidades) +
                      "&detalles=" + encodeURIComponent(detalles) +
                      "&imagen=" + encodeURIComponent(imagen);

            window.location.href = url;
        }
    </script>
</head>
<body>
    <h3>Lista de Productos con Unidades Menores o Iguales a <?= htmlspecialchars($_GET['tope'] ?? 'N/A') ?></h3>
    <br/>

    <?php 
    if (isset($_GET['tope']) && is_numeric($_GET['tope'])) {
        $tope = intval($_GET['tope']);

        @$link = new mysqli('localhost', 'root', 'distrito123', 'marketzone');

        if ($link->connect_errno) {
            die('<div class="alert alert-danger">Falló la conexión: ' . $link->connect_error . '</div>');
        }

        if ($result = $link->query("SELECT * FROM productos WHERE unidades <= $tope AND eliminado = 0")) {
            if ($result->num_rows > 0) {
                echo '<table class="table">';
                echo '<thead class="thead-dark">';
                echo '<tr><th>#</th><th>Nombre</th><th>Marca</th><th>Modelo</th><th>Precio</th><th>Unidades</th><th>Detalles</th><th>Imagen</th></tr>';
                echo '<tr><th>#</th><th>Nombre</th><th>Marca</th><th>Modelo</th><th>Precio</th><th>Unidades</th><th>Detalles</th><th>Imagen</th><th>Acción</th></tr>';
                echo '</thead><tbody>';

                while ($row = $result->fetch_assoc()) {
                    $id = htmlspecialchars($row['id']);
                    echo '<tr id="row-' . $id . '">';
                    echo '<th scope="row" class="row-data">' . $id . '</th>';
                    echo '<td class="row-data">' . htmlspecialchars($row['nombre']) . '</td>';
                    echo '<td class="row-data">' . htmlspecialchars($row['marca']) . '</td>';
                    echo '<td class="row-data">' . htmlspecialchars($row['modelo']) . '</td>';
                    echo '<td class="row-data">' . htmlspecialchars($row['precio']) . '</td>';
                    echo '<td class="row-data">' . htmlspecialchars($row['unidades']) . '</td>';
                    echo '<td class="row-data">' . htmlspecialchars($row['detalles']) . '</td>';
                    echo '<td class="row-data"><img src="img/' . htmlspecialchars(basename($row['imagen'])) . '" width="100" height="100"/></td>';
                    echo '<td><button class="btn btn-primary" onclick="show(event)">Seleccionar</button></td>';
                    echo '</tr>';
                }
                

                echo '</tbody></table>';
            } else {
                echo '<div class="alert alert-warning">No hay productos vigentes con unidades menores o iguales a ' . $tope . '.</div>';
            }

            /** Liberar resultados */
            $result->free();
        }

        $link->close();
    } else {
        echo '<div class="alert alert-danger">Parámetro "tope" no válido o no proporcionado.</div>';
    }
    ?>
</body>
</html>