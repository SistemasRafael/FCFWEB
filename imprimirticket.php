<?php
   include_once("./config.php");
   include_once('./pedido.class.php');
   include_once ('admin.php');

function imprimeTicketLista() {

     $data = array("id" => $_GET['id'],
         "nombreCliente"=> $_GET['cliente'],
         "cantidadFotos"=> $_GET['counter'],
         "sucursal"     => $_GET['sucursal'],
         "idKiosco"     => $_GET['numeroMaquina'],
         "tamanoFotos"  => $_GET['tamFotos'],
         "fecha"        => $_GET['fecha']
         );

    fcflog(__FILE__, "ticket" . $data);

    $json = json_encode($data);
	error_log("***" . $json);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://localhost:9990/imprime");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($json))
	);
	$ce = curl_exec($ch);
	curl_close($ch);
	error_log("-- curl: " . $ce);

}
  $archivo = $destino . '\\' . $numMaq ."-". $_GET['id'] . '-' . $_GET['tamFotos'] . "\\ticket.txt";
  imprimeTicketLista($archivo);
?>
