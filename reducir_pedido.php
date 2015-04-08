<?php
include_once("./foto.class.php");
include_once("./config.php");
include_once("./funciones.php");

// tomamos el valor del # de pedido de los parametros. Es el 1 ya que el comando es el parametro 0.
$nPedido = $argv[1];
if ($nPedido > 0) {
    reducirPedido($nPedido);
}

?>