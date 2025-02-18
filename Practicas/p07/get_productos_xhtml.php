<?php
    header("Content-Type: application/xhtml+xml; charset=utf-8"); 
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Lista de Productos</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #333; color: white; }
        img { width: 100px; }
    </style>
</head>
<body>
    <h1>PRODUCTO</h1>
    <table>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Precio</th>
            <th>Unidades</th>
            <th>Detalles</th>
            <th>Imagen</th>
        </tr>
        
        <?php
        if(isset($_GET['tope'])) {
            $tope = intval($_GET['tope']);
        } else {
            echo "<tr><td colspan='8'>Parámetro 'tope' no detectado...</td></tr>";
            echo "</table></body></html>";
            exit();
        }

        @$link = new mysqli('localhost', 'root', 'distrito123n', 'marketzone');
        if ($link->connect_errno) {
            echo "<tr><td colspan='8'>Falló la conexión: " . htmlspecialchars($link->connect_error) . "</td></tr>";
            echo "</table></body></html>";
            exit();
        }

        $query = "SELECT * FROM productos WHERE unidades <= ?";
        if ($stmt = $link->prepare($query)) {
            $stmt->bind_param("i", $tope);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $num = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $num++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['marca']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['modelo']) . "</td>";
                    echo "<td>$" . number_format($row['precio'], 2) . "</td>";
                    echo "<td>" . intval($row['unidades']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['detalles']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($row['imagen']) . "' alt='Imagen de producto' /></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No hay productos con unidades menores o iguales a " . $tope . "</td></tr>";
            }
            
            $stmt->close();
        }
        $link->close();
        ?>
    </table>
</body>
</html>
