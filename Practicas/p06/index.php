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

<!--Ejercicio3-->
<h2>Ejercicio 3: Buscar múltiplo de un número dado (usando while)</h2>
    <form method="get">
        <label for="multiplo">Número para encontrar múltiplo:</label>
        <input type="number" name="multiplo" id="multiplo">
        <input type="submit" value="Buscar Múltiplo">
    </form>
    <?php
    if (isset($_GET['multiplo']) && is_numeric($_GET['multiplo'])) {
        require_once 'src/funciones.php';
        echo buscar_multiplo($_GET['multiplo']);
    }
    ?>
    <hr>
<!--Ejercicio3-->

<!--Ejercicio 4-->
<h2>Ejercicio 4: Crear y mostrar arreglo con letras de a => z</h2>
    <?php
    require_once 'src/funciones.php';
    $arreglo_letras = crear_arreglo_letras();
    echo "<h3>Arreglo de letras:</h3><table border='1'>
            <tr><th>Índice</th><th>Letra</th></tr>";
    foreach ($arreglo_letras as $key => $value) {
        echo "<tr><td>$key</td><td>$value</td></tr>";
    }
    echo "</table>";
    ?>
    <hr>
<!--Ejercicio 4-->

<!--Ejercicio 5-->
<h2>Ejercicio 5: Validar Edad y Sexo</h2>
    <form method="post">
        <label for="edad">Edad:</label>
        <input type="number" name="edad" id="edad" required><br><br>

        <label for="sexo">Sexo:</label>
        <select name="sexo" id="sexo">
            <option value="femenino">Femenino</option>
            <option value="masculino">Masculino</option>
        </select><br><br>
        <input type="submit" value="Enviar">
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edad']) && isset($_POST['sexo'])) {
        require_once 'src/funciones.php';
        echo validarEdadSexo($_POST['edad'], $_POST['sexo']);
    }
    ?>
    <hr>
<!--Ejercicio 5-->

<!--Ejercicio 6-->
<h2>Ejercicio 6: Consulta de Parque Vehicular</h2>
<form method="get">
    <label for="matricula">Ingrese la matrícula del vehículo:</label>
    <input type="text" name="matricula" id="matricula" required>
    <input type="submit" value="Buscar">
</form>

<form method="get">
    <input type="hidden" name="mostrar_todos" value="1">
    <input type="submit" value="Mostrar Todos">
</form>

<?php
require_once __DIR__ . '/src/funciones.php';

if (isset($_GET['matricula'])) {
    $matricula = strtoupper($_GET['matricula']);
    $resultado = buscarVehiculo($matricula);

    if ($resultado) {
        echo "<h3>Información del Vehículo</h3><pre>";
        print_r($resultado);
        echo "</pre>";
    } else {
        echo "<p>No se encontró un vehículo con la matrícula $matricula.</p>";
    }
}

if (isset($_GET['mostrar_todos'])) {
    echo "<h3>Todos los Vehículos Registrados</h3><pre>";
    print_r(obtenerTodosLosVehiculos());
    echo "</pre>";
}
?>
<!--Ejercicio 6-->

</body>
</html>