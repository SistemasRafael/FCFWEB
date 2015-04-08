<?php
session_start();
/**
 * Datos de configuracion del kiosco
 */
$carddrive = @$_SESSION['drive'] . DIRECTORY_SEPARATOR;
//$cachedir = "/var/kiosco/cache"; path linux
$cachedir = "\\fcf\\cache";
set_time_limit(3000);

//numero de la maquina:
$numMaq = "FCF122";
//$numeroMaquina= "FCF122"

//Sucursal
$suc = "HI";
//$idSucursal = "HI";
$ubicacion = "Hermosillo, Sonora";


//Carpeta donde se guardan los pedidos del cliente
//$destino = "/var/kiosco/pedidos"; path linux
//$destino = "c:". DIRECTORY_SEPARATOR . "default";
$destino = "c:\\default"; //path de los pedidos en windows
$archivoNumPedido = "numero.txt";

// lado "grande" de la foto
$fotox = 160;
$fotoy = 120;

// pagina actual, default 1
$pagina = 1;

// paginas totales, default 1
$totalPaginas = 1;

//Extensiones y Tamanos soportados
$supportedExts = array('gif', 'jpg','jpeg','png');
$tamanoFotos = array('4x6','5x7','6x8','8x10','11x14');

// imagenes
$folderimg = "./images/folder.png";
$photoimg = "./images/pic.png";

// paginacion
$fpp = 15;  // fotos por pagina
//$ignorarDiscos = array("C:","D:");

// usar un directorio local como memoria (para pruebas)
//$simularMemoria = true;
$pathMemoria = "C:\\default\\bluetooth";
//$pathMemoria = "c:\\memorias\\d";

// Para registrar los pedidos de los diferentes kioscos
//$remoteReportingUrl = "http://fcf.mx/ping.php";
$remoteReportingUrl = "http://localhost/kiosco/ping.php";

// Se implemento la reduccion de imagenes para fotos de 4x6 hasta 8x10
// Definir si se quiere reducir las imagenes para fines de impresion
$reducirParaImpresion = true;
// Cual sera el tamano en pixeles por cada pulgada de impresion
$pppImpresion = 200;
// directorio a donde respaldar las fotos originales
// 12-abril-2013: se cambio a utilizar el directorio aqui especificado + el id del pedido
$dirOriginales = "c:\\default\\originales";
$pathPhpExe = "c:\\UniServer\\usr\\local\\php\\php.exe";


// archivo de log
$logFile = "c:\\default\\log.txt";
?>
