<?php
include_once 'config.php';
include_once 'funciones.php';
fcflog(__FILE__, "");

$tamFoto = $_SESSION['tamano'];
$pedido = $_SESSION['nPedido'];

$file = $destino . '\\' . $numMaq ."-". $pedido . '-' . $tamFoto . "\\ticket.txt";
//$file = $destino . '\\' . $numMaq ."-". $pedido . '-' . $tamFoto . "\\numero.txt";
imprimirTicket($file);

?>
