<?php
include_once './config.php';
include_once './funciones.php';
fcflog(__FILE__, "");
/**
 * Cuando se quieran hacer pruebas con datos locales, cambiar los valores "$simularMemoria" y "$pathMemoria"
 *      en el archivo config.php
 */

$simMemGlobal = $simularMemoria;

$medio = $_SESSION['medio'];
if($medio == 'BLUETOOTH'){
    $simularMemoria = TRUE;
} else {
    $simularMemoria = FALSE;
}

if ($simMemGlobal) $simularMemoria = true;

if ($simularMemoria) {
    error_log("*** SIMULANDO MEMORIA EN DISCO (PRUEBAS)::" + $pathMemoria);
    $_SESSION['drive'] = $pathMemoria;
    fcflog(__FILE__, "set [drive=" . $pathMemoria . "]");
    echo json_encode($pathMemoria);
} else {
    $disco = checarDiscos();

    if ($disco != null && sizeof($disco) > 0) {
        $_SESSION['drive'] = $disco[sizeof($disco) - 1];
        $drive = json_encode($disco[sizeof($disco) - 1]);
        fcflog(__FILE__, "set [drive=" . $drive . "]");
        echo $drive;
    }
}

//Recibe el path del archivo .bat e imprme el arreglo ($retval).
/*$execBat = exec($_batFile, $retval);
if($execBat){
    if(in_array($execBat, $retval)){
        $_SESSION['drive'] = $execBat;
    }
    $drive = json_encode($retval);
    echo $drive;
}else{
    $drive = json_encode($retval);
    echo $drive;
}*/
?>
