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

// Funciones 6
$parqueVehicular = [
    "ABC1234" => [
        "Auto" => ["marca" => "Toyota", "modelo" => 2021, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Juan Pérez", "ciudad" => "Puebla, Pue.", "direccion" => "Av. Reforma 100"]
    ],
    "XYZ5678" => [
        "Auto" => ["marca" => "Honda", "modelo" => 2019, "tipo" => "hatchback"],
        "Propietario" => ["nombre" => "María Gómez", "ciudad" => "Ciudad de México, CDMX", "direccion" => "Col. Roma 45"]
    ],
    "LMN8765" => [
        "Auto" => ["marca" => "Ford", "modelo" => 2020, "tipo" => "camioneta"],
        "Propietario" => ["nombre" => "Carlos Méndez", "ciudad" => "Guadalajara, Jal.", "direccion" => "Calle Juárez 200"]
    ],
    "DEF4321" => [
        "Auto" => ["marca" => "Chevrolet", "modelo" => 2018, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Ana López", "ciudad" => "Monterrey, NL", "direccion" => "Blvd. Constitución 321"]
    ],
    "GHI2468" => [
        "Auto" => ["marca" => "Mazda", "modelo" => 2022, "tipo" => "hatchback"],
        "Propietario" => ["nombre" => "Luis Fernández", "ciudad" => "Querétaro, Qro.", "direccion" => "Zona Centro"]
    ],
    "JKL9753" => [
        "Auto" => ["marca" => "Nissan", "modelo" => 2017, "tipo" => "camioneta"],
        "Propietario" => ["nombre" => "Rosa Ortega", "ciudad" => "Toluca, Méx.", "direccion" => "Col. Industrial"]
    ],
    "MNO6321" => [
        "Auto" => ["marca" => "Volkswagen", "modelo" => 2023, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Pedro Castillo", "ciudad" => "León, Gto.", "direccion" => "Av. Insurgentes 90"]
    ],
    "PQR9876" => [
        "Auto" => ["marca" => "BMW", "modelo" => 2019, "tipo" => "hatchback"],
        "Propietario" => ["nombre" => "Andrea Torres", "ciudad" => "San Luis Potosí, SLP", "direccion" => "Calle Hidalgo 456"]
    ],
    "STU5432" => [
        "Auto" => ["marca" => "Mercedes-Benz", "modelo" => 2021, "tipo" => "camioneta"],
        "Propietario" => ["nombre" => "José Ramírez", "ciudad" => "Mérida, Yuc.", "direccion" => "Centro Histórico"]
    ],
    "VWX6543" => [
        "Auto" => ["marca" => "Audi", "modelo" => 2020, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Daniela Hernández", "ciudad" => "Cancún, QR", "direccion" => "Zona Hotelera"]
    ],
    "YZA3456" => [
        "Auto" => ["marca" => "Jeep", "modelo" => 2018, "tipo" => "camioneta"],
        "Propietario" => ["nombre" => "Miguel Estrada", "ciudad" => "Culiacán, Sin.", "direccion" => "Blvd. Pedro Infante"]
    ],
    "BCD1239" => [
        "Auto" => ["marca" => "Tesla", "modelo" => 2022, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Sofía Méndez", "ciudad" => "Hermosillo, Son.", "direccion" => "Col. Centro"]
    ],
    "EFG9872" => [
        "Auto" => ["marca" => "Kia", "modelo" => 2023, "tipo" => "hatchback"],
        "Propietario" => ["nombre" => "Ricardo Velázquez", "ciudad" => "Chihuahua, Chih.", "direccion" => "Calle Morelos 87"]
    ],
    "HIJ6547" => [
        "Auto" => ["marca" => "Hyundai", "modelo" => 2017, "tipo" => "camioneta"],
        "Propietario" => ["nombre" => "Natalia Romero", "ciudad" => "Aguascalientes, Ags.", "direccion" => "Col. Universidad"]
    ],
    "KLM4328" => [
        "Auto" => ["marca" => "Subaru", "modelo" => 2019, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Eduardo Sánchez", "ciudad" => "Tijuana, BC", "direccion" => "Zona Río"]
    ],
];

// Función para buscar un vehículo por matrícula
function buscarVehiculo($matricula) {
    global $parqueVehicular;
    return isset($parqueVehicular[$matricula]) ? $parqueVehicular[$matricula] : null;
}

// Función para obtener todos los vehículos
function obtenerTodosLosVehiculos() {
    global $parqueVehicular;
    return $parqueVehicular;
}
// Funciones 6

?>