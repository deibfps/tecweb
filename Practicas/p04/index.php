<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Práctica 3</title>
</head>
<body>
    <h2>Ejercicio 1</h2>
    <p>Determina cuáles de las siguientes variables son válidas y explica por qué:</p>
    
    <?php
        echo '<h4>Variables evaluadas:</h4>';
        echo '<ul>';
        echo '<li>$_myvar</li>';
        echo '<li>$_7var</li>';
        echo '<li>myvar (inválida, falta  $)</li>';
        echo '<li>$myvar</li>';
        echo '<li>$var7</li>';
        echo '<li>$_element1</li>';
        echo '<li>$house*5 (inválida, el  *  no está permitido en nombres de variables)</li>';
        echo '</ul>';

        echo '<h4>Respuesta:</h4>';   
        echo '<ul>';
        echo '<li>$_myvar es válida porque inicia con guión bajo y usa caracteres permitidos.</li>';
        echo '<li>$_7var es válida porque inicia con guión bajo y usa caracteres permitidos.</li>';
        echo '<li>myvar es inválida porque no tiene el signo de dólar ( $ ).</li>';
        echo '<li>$myvar es válida porque inicia con una letra.</li>';
        echo '<li>$var7 es válida porque inicia con una letra y contiene números.</li>';
        echo '<li>$_element1 es válida porque inicia con guión bajo y usa caracteres permitidos.</li>';
        echo '<li>$house*5 es inválida porque el símbolo  *  no está permitido en nombres de variables.</li>';
        echo '</ul>';
    ?>
</body>
</html>
