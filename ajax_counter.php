<?php

include_once("./foto.class.php");
include_once './funciones.php';
//session_start();
// sacamos el id del boton de los parametros
$btnId = $_POST['boton'];
//$btnallP = $_POST['botonallplus'];
// el id del boton puede ser btnPlus o btnMinus, checar cual de los 2 viene
$isBtnPlus = strstr($btnId, "btnPlus");
//$isBtnAllP = strstr($btnallP, "btnAllPlus");
// quitamos la parte de 'btnPlus' del id para que nos quede el puro hash
if ($isBtnPlus) {
    $fhash = substr($btnId, 7);
} else {
    // o si es btnMinus...
    $fhash = substr($btnId, 8);
}
// debug...
//error_log($btnId);
//error_log($fhash);
if (isset($_SESSION['fotos'])) {
    $foto = $_SESSION['fotos'][$fhash];
    if ($btnId == 'todasPlus') {
        //Incrementa todas las fotos de la sesion +1
        aumentarFotos(1);
        fcflog(__FILE__, "action [todas+]");
    } else if ($btnId == 'todasMinus') {
        //Resta todas las foto de la sesion -1
        restarFotos(1);
        fcflog(__FILE__, "action [todas-]");
    }
//    else if($btnId == 'fppPlus'){
//        plusFpp(1);
//    }
    else if ($isBtnPlus) {
        // incrementar el contador
        $foto->agregar();
        fcflog(__FILE__, "action [add] " . $foto);
    } else {
        // o restarle
        $foto->quitar();
        fcflog(__FILE__, "action [sub] " . $foto);
    }
    // contador global despues de la ","
    $totalFotos = totalFotos($_SESSION['fotos']);
    echo $foto->counter . ',' . $totalFotos;
} else {
    echo "Error";
}

/**
 * @param Array $arrFotos el arreglo de fotos para recorrer.
 */
function totalFotos($arrFotos) {
    $count = 0;
    foreach ($arrFotos as $objFoto) {
        $count += $objFoto->counter;
        $_SESSION['counter'] = $count;
    }
    return $count;
}

?>