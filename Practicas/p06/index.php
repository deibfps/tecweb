<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 4</title>
</head>
<body>

<!--Ejercicio 1-->
    <h2>Ejercicio 1</h2>
    <p>Escribir programa para comprobar si un número es un múltiplo de 5 y 7</p>
    
    <?php
        require_once __DIR__ . '/src/funciones.php';
        if (isset($_GET['numero']) && is_numeric($_GET['numero'])) {
            $num = intval($_GET['numero']);
            es_multiplo($num);
        }
    ?>
    <form method="get">
        <label for="numero">Ingresa un número:</label>
        <input type="text" name="numero" id="numero">
        <input type="submit" value="Comprobar">
    </form>
    <hr>
<!--Ejercicio 1-->

</body>
</html>