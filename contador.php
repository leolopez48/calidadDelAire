<?php
/**
 * Función sencilla para repetir el comportamiento de PHP 5
 */
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();

// Dormir por un momento
usleep(1);

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "No se hizo nada en $time segundos\n";
?> 
