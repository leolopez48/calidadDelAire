<?php
/**
 * Función sencilla para repetir el comportamiento de PHP 5
 */
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


// Dormir por un momento
$i;
//$time_start = microtime_float();
//exec("jupyter nbconvert --to notebook --execute /var/www/aire/public/machine/prediccion_pm25itca.ipynb", $datos, $ar);
$time_pre = microtime(true);
//exec(...);
$time_post = microtime(true);
$exec_time = $time_post - $time_pre;
//$time_end = microtime_float();
//$time = $time_end - $time_start;

//echo "No se hizo nada en $time segundos\n";
$i++;
echo $i;
usleep(3e+7);
exec("jupyter nbconvert --to notebook --execute /var/www/aire/public/machine/prediccion_pm25itca.ipynb", $datos, $ar);
?>
