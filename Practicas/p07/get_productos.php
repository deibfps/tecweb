<?php
    header("Content-Type: application/xhtml+xml; charset=utf-8"); 

echo "<?xml version='1.0' encoding='UTF-8'?>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Lista de Productos</title>
</head>
<body>
    <h1>Lista de Productos</h1>
    
    <?php
    if(isset($_GET['tope'])) {
        $tope = intval($_GET['tope']);
    } else {
        die('<p>Parámetro "tope" no detectado...</p></body></html>');
    }

    if (!empty($tope)) {
        /** SE CREA EL OBJETO DE CONEXION */
        @$link = new mysqli('localhost', 'root', '12345678a', 'marketzone');
        
        /** comprobar la conexión */
        if ($link->connect_errno) {
            die('<p>Falló la conexión: '.$link->connect_error.'</p></body></html>');
        }

        /** Ejecutar la consulta */
        if ($result = $link->query("SELECT * FROM productos WHERE unidades <= $tope")) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Unidades</th><th>Precio</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($row['unidades']) . "</td>";
                echo "<td>" . htmlspecialchars($row['precio']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            $result->free();
        } else {
            echo '<p>No se encontraron productos.</p>';
        }

        $link->close();
    }
    ?>
</body>
</html>
