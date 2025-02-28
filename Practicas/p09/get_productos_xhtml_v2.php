<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"/>
</head>
<body>
    <h3>Lista de Productos con Unidades Menores o Iguales a <?= htmlspecialchars($_GET['tope'] ?? 'N/A') ?></h3>
    <br/>

    <?php
    if (isset($_GET['tope']) && is_numeric($_GET['tope'])) {
        $tope = intval($_GET['tope']);

        /** SE CREA EL OBJETO DE CONEXIÓN */
        @$link = new mysqli('localhost', 'root', 'distrito123', 'marketzone');

        /** Comprobar la conexión */
        if ($link->connect_errno) {
            die('<div class="alert alert-danger">Falló la conexión: ' . $link->connect_error . '</div>');
        }

        /** Ejecutar la consulta */
        if ($result = $link->query("SELECT * FROM productos WHERE unidades <= $tope")) {
            if ($result->num_rows > 0) {
                echo '<table class="table">';
                echo '<thead class="thead-dark">';
                echo '<tr><th>#</th><th>Nombre</th><th>Marca</th><th>Modelo</th><th>Precio</th><th>Unidades</th><th>Detalles</th><th>Imagen</th></tr>';
                echo '</thead><tbody>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<th scope="row">' . htmlspecialchars($row['id']) . '</th>';
                    echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['marca']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['modelo']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['precio']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['unidades']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['detalles']) . '</td>';
                    echo '<td><img src="img/' . htmlspecialchars(basename($row['imagen'])) . '" width="100" height="100"/></td>';
                    echo '</tr>';
                }

                echo '</tbody></table>';
            } else {
                echo '<div class="alert alert-warning">No hay productos con unidades menores o iguales a ' . $tope . '.</div>';
            }

            /** Liberar resultados */
            $result->free();
        }

        /** Cerrar conexión a base de datos */
        $link->close();
    } else {
        echo '<div class="alert alert-danger">Parámetro "tope" no válido o no proporcionado.</div>';
    }
    ?>
</body>
</html>
</html>