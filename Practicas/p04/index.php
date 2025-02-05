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

    <h2>Ejercicio 2</h2>
    <p>Proporcionando valores a las variables y mostrando sus contenidos:</p>

    <?php
        $a = "ManejadorSQL";
        $b = 'MySQL';
        $c = &$a;

        echo "<h4>Antes del segundo bloque de asignaciones:</h4>";
        echo "<ul>";
        echo "<li>\$a = $a</li>";
        echo "<li>\$b = $b</li>";
        echo "<li>\$c = $c</li>";
        echo "</ul>";

        $a = "PHP server";
        $b = &$a;

        echo "<h4>Después del segundo bloque de asignaciones:</h4>";
        echo "<ul>";
        echo "<li>\$a = $a</li>";
        echo "<li>\$b = $b</li>";
        echo "<li>\$c = $c</li>";
        echo "</ul>";

        echo "<h3>Explicación:</h3>";
        echo "<p>Cuando se usa el operador de referencia (&), cualquier cambio en la variable original afecta a todas las referencias.</p>";
        echo "<p>Inicialmente, \$c era una referencia de \$a, por lo que cuando se cambió \$a a 'PHP server', \$c también cambió.</p>";
        echo "<p>Luego, al hacer \$b = &\$a, ahora \$b también apunta a la misma memoria que \$a, por lo que su valor también se actualiza automáticamente.</p>";
    ?>

<h2>Ejercicio 3</h2>
    <p>Seguimiento del contenido y tipo de variables después de cada asignación:</p>

    <?php
        $a = "PHP5";
        echo "<h4>Después de \$a = \"PHP5\";</h4>";
        echo "<ul>";
        echo "<li>\$a = $a (Tipo: " . gettype($a) . ")</li>";
        echo "</ul>";

        $z[] = &$a;
        echo "<h4>Después de \$z[] = &\$a;</h4>";
        echo "<ul>";
        echo "<li>\$z[0] = {$z[0]} (Tipo: " . gettype($z[0]) . ")</li>";
        echo "</ul>";

        $b = "5a version de PHP";
        echo "<h4>Después de \$b = \"5a version de PHP\";</h4>";
        echo "<ul>";
        echo "<li>\$b = $b (Tipo: " . gettype($b) . ")</li>";
        echo "</ul>";

        $c = $b * 10;
        echo "<h4>Después de \$c = \$b * 10;</h4>";
        echo "<ul>";
        echo "<li>\$c = $c (Tipo: " . gettype($c) . ")</li>";
        echo "<li>PHP intentó convertir el string '\$b' en número, pero como comienza con '5', lo interpreta como 5 y multiplica 5 * 10 = 50.</li>";
        echo "</ul>";

        $a .= $b;
        echo "<h4>Después de \$a .= \$b;</h4>";
        echo "<ul>";
        echo "<li>\$a = $a (Tipo: " . gettype($a) . ")</li>";
        echo "<li>Como \$a era 'PHP5' y \$b era '5a version de PHP', la concatenación resulta en 'PHP55a version de PHP'.</li>";
        echo "</ul>";

        $b *= $c;
        echo "<h4>Después de \$b *= \$c;</h4>";
        echo "<ul>";
        echo "<li>\$b = $b (Tipo: " . gettype($b) . ")</li>";
        echo "<li>PHP convierte \$b en un número (5) y hace 5 * 50 = 250.</li>";
        echo "</ul>";

        $z[0] = "MySQL";
        echo "<h4>Después de \$z[0] = \"MySQL\";</h4>";
        echo "<ul>";
        echo "<li>\$z[0] = {$z[0]} (Tipo: " . gettype($z[0]) . ")</li>";
        echo "<li>\$a también cambia a 'MySQL' porque \$z[0] era una referencia a \$a.</li>";
        echo "<li>\$a = $a</li>";
        echo "</ul>";
    ?>

</body>
</html>
