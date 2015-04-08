<?php
include_once("./foto.class.php");
include_once("./config.php");
include_once("./funciones.php");

fcflog(__FILE__, "");
procesaPedido();
echo json_encode("true");

?>
