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

<!--Ejercicio 2-->
<h2>Ejercicio 2: Generación de secuencia impar, par, impar</h2>
    <form method="get">
        <input type="hidden" name="ejercicio2" value="1">
        <input type="submit" value="Generar Secuencia">
    </form>
    <?php
    if (isset($_GET['ejercicio2'])) {
        require_once 'src/funciones.php';
        $resultado = generar_secuencia();
        echo "<h3>Secuencia generada:</h3><pre>";
        foreach ($resultado['secuencia'] as $fila) {
            echo implode(", ", $fila) . "<br>";
        }
        echo "</pre>";
        echo "<p>Total de números generados: {$resultado['total_numeros']}</p>";
        echo "<p>Número de iteraciones: {$resultado['iteraciones']}</p>";
    }
    ?>
    <hr>
<!--Ejercicio 2-->

</body>
</html>