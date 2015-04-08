<?php
/**
 * Pagina para recibir un post del json de la informacion de pedidos de kioscos
 *
 */
$debug = true;

$json = $HTTP_RAW_POST_DATA;
if ($debug) $json = '{"id":7,"nombreCliente":"xcv","cantidadFotos":1,"sucursal":null,"idKiosco":null,"tamanoFotos":"8x10","fecha":"13-02-2013 17:06:20"}';

$data = json_decode($json);

$numPedido = $data->id;
$nombreCliente = $data->nombreCliente;
$cantidadFotos = $data->cantidadFotos;
$sucursal = $data->sucursal;
$idKiosco = $data->idKiosco;
$tamanoFotos = $data->tamanoFotos;
$fecha = $data->fechab;

if (!$numPedido || $numPedido == null) {
    $numPedido = 0;
}

$query = "insert into pedidos(numPedido, nombreCliente, cantidadFotos, sucursal, idKiosco, tamanoFotos, fecha)"
    . " values ("
    . $numPedido . ", "
    . "'" . $nombreCliente . "', "
    . $cantidadFotos. ", "
    . "'" . $sucursal . "', "
    . "'" . $idKiosco . "', "
    . "'" . $tamanoFotos . "', "
    . "'" . $fecha . "'); ";

if ($debug) {
    echo $query;
}

// lo podemos guardar a archivo...
// file_put_contents(".\ping.json", $json, FILE_APPEND);

$mysqli = new mysqli("localhost", "jsalido_kioscos", "0ouDDr=]zz8+", "jsalido_kioscos");

/* check connection */
if ($mysqli->connect_errno) {
    error_log("Connect failed: %s\n", $mysqli->connect_error);
    if ($debug) echo $mysqli->connect_error;
    exit();
}

/* Select queries return a resultset */
if ($result = $mysqli->query($query)) {
    error_log("Select returned %d rows.\n", $result->num_rows);
    if ($debug) echo $result->num_rows;
    /* free result set */
    $result->close();
}


$mysqli->close();


?>