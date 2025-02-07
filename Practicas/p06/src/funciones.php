<?php

// Funcion 1
function es_multiplo($num)
{
    if ($num % 5 == 0 && $num % 7 == 0) {
        echo '<h3>R= El número ' . $num . ' SÍ es múltiplo de 5 y 7.</h3>';
    } else {
        echo '<h3>R= El número ' . $num . ' NO es múltiplo de 5 y 7.</h3>';
    }
}
// Funcion 1

// Funcion 2
function generar_secuencia() {
    $secuencia = [];
    $filas = 0;

    // Generar números hasta obtener una secuencia impar, par, impar
    while (true) {
        $numeros = [rand(100, 999), rand(100, 999), rand(100, 999)];
        $filas++;

        // Verifica si la secuencia es impar, par, impar
        if ($numeros[0] % 2 != 0 && $numeros[1] % 2 == 0 && $numeros[2] % 2 != 0) {
            $secuencia[] = $numeros;
            break;
        }
        $secuencia[] = $numeros;
    }
    return [
        'secuencia' => $secuencia,
        'total_numeros' => count($secuencia) * 3,
        'iteraciones' => $filas
    ];
}
// Funcion 2

//Funcion 3
function buscar_multiplo($numero_dado) {
    $contador = 1;
    while (true) {
        $numero_aleatorio = rand(1, 100);
        if ($numero_aleatorio % $numero_dado == 0) {
            return "El primer múltiplo de $numero_dado es $numero_aleatorio encontrado en $contador iteraciones.";
        }
        $contador++;
    }
}
//Funcion 3

//Funcion 4 
function crear_arreglo_letras() {
    $arreglo = [];
    for ($i = 97; $i <= 122; $i++) {
        $arreglo[$i] = chr($i);
    }
    return $arreglo;
}
//Funcion 4 

//Funcion 5
function validarEdadSexo($edad, $sexo) {
    if ($sexo == 'femenino' && $edad >= 18 && $edad <= 35) {
        return "Bienvenida, usted está en el rango de edad permitido.";
    } else {
        return "Error: No cumple con los requisitos de edad o sexo.";
    }
}
//Funcion 5

?>