<?php
include_once('./config.php');
include_once('./funciones.php');
// echo file_get_contents(getDirectorioPedido() . "\\progreso.txt");
echo count(glob(getDirectorioPedido() . "/*.*")) - 1;

?>
