<?php
include_once("./foto.class.php");
include_once('./config.php');
include_once("./pedido.class.php");


/**
 * Funcion general para loguear informacion de uso del kiosco
 * @param $mensaje
 */
function fcflog($srcFile, $mensaje) {

    global $logFile;
    global $suc;
    global $numMaq;
    date_default_timezone_set("America/Hermosillo");
    $date = date('Y-m-d H:i:s');
    if ($fh = @fopen($logFile, "a+")) {
        $logtxt = $date . "||" . $suc . "||" . $numMaq . "||" . $srcFile . "||" . $mensaje . "\n";
        fputs($fh, $logtxt, strlen($logtxt));
        fclose($fh);
    }
    else{
        echo "no entro";
    }
}

/**
 * @param $hash
 * @param $path
 * @param $mime
 * @return bool
 */
function registrarFotoEnSesion($hash, $path, $mime) {
    if (!isset($_SESSION['fotos'][$hash])) {
        $foto = new Foto();
        $foto->hash = $hash;
        $foto->path = $path;
        $foto->mimeType = $mime;
        $_SESSION['fotos'][$hash] = $foto;
        return true;
    }
    return false;
}

/**
 * @return mixed
 */
function getDrive() {
    return $_SESSION['drive'];
}

/**
 * @param $dir
 */
function cargarListado($dir) {
    error_log("cargarListado:: " + $dir);
    // dir inic hay que modificarlo para que pueda
    // ser utiliizado por la subfuncion procesa
    if (!isset($_SESSION['archivos'])) {
        //$dir = $dir . "/";
        $dir = $dir . DIRECTORY_SEPARATOR;
        $_SESSION['archivos'] = procesaDir($dir);
    }
}

/**
 * @param $dir
 * @return array
 */
function procesaDir($dir) {
    $result = array();
    error_log("[" . $dir . "]");
    $root = scandir($dir);
    if ($root) {
        foreach ($root as $value) {
            if (strpos($value, ".") === 0) {
                continue;
            }
            $val = $dir . $value;
            if (is_file($val)) {
                if (isImage($val))
                    $result[] = $val;
                continue;
            } else {
                foreach (procesaDir($val . DIRECTORY_SEPARATOR) as $img) {
                    $result[] = $img;
                }
            }
        }
    }
    return $result;
}

/**
 * @param $filepath
 * @return bool
 */
function isImage($filepath) {
    global $supportedExts;
    $pi = pathinfo($filepath);
    if (isset($pi["extension"])) {
        return in_array(strtolower($pi["extension"]), $supportedExts);
    }
    return false;
}

/**
 * Regresa el listado de archivos guardado en la sesion
 * @return mixed
 */
function leerListado() {
    return $_SESSION['archivos'];
}

/*
 * Regresa los objetos de tipo foto de la pagina solicitada
 * @param $pagina
 * @return array
 */
function getFotosDePagina($pagina) {
    global $fpp; // fotos por pagina
    $fotos = leerFotos();
    $inicial = ($pagina - 1) * $fpp;
    return array_slice($fotos, $inicial, $fpp);
}

/**
 * @return float
 */
function getNumeroDePaginas() {
    global $fpp;
    $pags = floor(sizeof(leerListado()) / $fpp);
    if (sizeof(leerListado()) % $fpp > 0) {
        return $pags + 1;
    }
    return $pags;
}

/**
 * Carga las fotos del listado de archivos del directorio en objetos tipo foto
 * Si esta variable ya existe en sesion, no sobreescribir!
 */
function cargarFotos() {
    $listado = leerListado();
    $fotos = array();
    if (isset($_SESSION['fotos']))
        return;
    foreach ($listado as $a) {
        $tmp = new Foto($a);
        $fotos[$tmp->hash] = $tmp;
    }
    $_SESSION['fotos'] = $fotos;
}

/**
 * Lee el arreglo de fotos de la sesion
 * @return mixed
 */
function leerFotos() {
    return $_SESSION['fotos'];
}

/**
 * @return int
 */
function contarFotos() {
    $contador = 0;
    foreach (leerFotos() as $foto) {
        $contador += $foto->counter;
    }
    return $contador;
}

/**
 * @param $hash
 * @return mixed
 */
function getFoto($hash) {
    return $_SESSION['fotos'][$hash];
}
/**
 * Utiliza el comando para imprimir el archivo
 * @param File $archivo el archivo a imprimir (ticket.txt)
 * ANEXO PARA IMPRESION EN WEB SERVICE 11/13/12
 *
 */
function imprimirTicket() {
    global $suc, $numMaq, $remoteReportingUrl;
    date_default_timezone_set('America/Phoenix');
    $data = array("id" => getNumPedido(),
        "nombreCliente" => $_SESSION['txtNombre'],
        "cantidadFotos" => $_SESSION['counter'],
        "sucursal" => $suc,
        "idKiosco" => $numMaq,
        "tamanoFotos" => $_SESSION['tamano'],
        "fecha" => date("d-m-Y H:i:s")
    );
    $json = json_encode($data);
    error_log("***" . $json);
    $ch = curl_init();
    global $ipImpresora;
    curl_setopt($ch, CURLOPT_URL, "http://" . $ipImpresora .":9990/imprime");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json))
    );
    $ce = curl_exec($ch);

    $data = array("id" => getNumPedido(),
        "nombreCliente" => $_SESSION['txtNombre'],
        "cantidadFotos" => $_SESSION['counter'],
        "sucursal" => $suc,
        "idKiosco" => $numMaq,
        "tamanoFotos" => $_SESSION['tamano'],
        "fecha" => date("d-m-Y H:i:s"),
        "fechab" => date("Y-m-d H:i:s") // formato para query mysql
    );

    // intentar enviar datos al servidor fcf.mx
    // curl_setopt($ch, CURLOPT_URL, "http://localhost/kiosco/ping.php");
    curl_setopt($ch, CURLOPT_URL, $remoteReportingUrl);
    $ce = curl_exec($ch);

    curl_close($ch);
}


/**
 * @return int|string
 */
function getNumPedido() {
    global $destino;
    global $archivoNumPedido;
    if (isset($_SESSION['nPedido'])) {
        return $_SESSION['nPedido'];
    } else {
        $fileName = $destino . DIRECTORY_SEPARATOR . $archivoNumPedido;
//        echo $fileName;
        error_log($fileName);
        $nPedido = file_get_contents($fileName);
        if (!$nPedido) {
            $nPedido = 0;
        }
        file_put_contents($fileName, $nPedido + 1);
        $_SESSION['nPedido'] = $nPedido + 1;
        fcflog(__FILE__, "set [pedido=" . ($nPedido + 1) . "]");
        return $nPedido + 1;
    }
}

/**
 * @return int|string
 */
function procesaPedido() {
    global $pathPhpExe;
    $nPedido = getNumPedido();
    copiarFotosDePedido();
    generaTicket();
    /*
    Ejecutar la reduccion de las imagenes en el background
        Nota:
        1. El directorio donde esta el php.exe debe estar en el path de windows
        2. Asegurar que el php-cli.ini tenga las siguientes extensiones habilitadas:
            extension=php_mbstring.dll
            extension=php_exif.dll      ; Must be after mbstring as it depends on it
        3. Si por alguna razon no ejecuta, checar que el .ini utilizado sea el correcto con php.exe -i
        4. En XP (y otros?) se tiene que activa el checkbox de "permitir que el servicio interactue con el escritorio"
            si el apache esta corriendo como servicio (en propiedades del servicio).
        5. La variable pathPhpExe del config debe de tener la ubicacion del php.exe.
    */
    $cmd = "start /B " . $pathPhpExe . " ./reducir_pedido.php " . $nPedido . " > NUL 2> NUL";
    popen($cmd, "r");
    // reducirPedido($nPedido);
    return $nPedido;
}

/**
 * @return string
 */
function getDirectorioPedido() {
    global $destino;
    global $numMaq;
    $nPedido = getNumPedido();
    $tamFoto = $_SESSION['tamano'];
//    return $destino . '\\\\' . $numMaq . "-" . $nPedido . '-' . $tamFoto;
    return $destino . DIRECTORY_SEPARATOR . $numMaq . "-" . $nPedido . '-' . $tamFoto;
}

/**
 *
 */
function copiarFotosDePedido() {
    $_SESSION['progreso'] = 0;
    $data = getDirectorioPedido();
    if(!is_dir($data)) {
        @mkdir($data);
    }
    $count = 0;
    $arrFotos = $_SESSION['fotos'];
    foreach ($arrFotos as $objFoto) {
        if ($objFoto->counter > 0) {
            try {
                for ($numF = 0; $numF < $objFoto->counter; $numF++) {
                    // Pidieron saliera primero el nombre original de la foto para facil identificacion en la maquina de
                    // impresion cuando solicitan mas copias de alguna foto.
                    @copy($objFoto->path, $data . DIRECTORY_SEPARATOR . $objFoto->nombre . "-" . $count . "-" . $numF . "." . $objFoto->imgExtension);
                    $_SESSION['progreso'] = $_SESSION['progreso'] + 1;
                    file_put_contents($data . DIRECTORY_SEPARATOR . "progreso.txt", $_SESSION['progreso']);
                }
                $count++;
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
}

/**
 *
 */
function generaTicket() {
    $data = getDirectorioPedido();
    $directorio = "file:\\".getDirectorioPedido()."\\ticket.txt";
//    echo "<br/>".$data."<br/>";
    date_default_timezone_set('America/Phoenix');
    $pedido = getNumPedido();
    $tamFoto = $_SESSION['tamano'];
    $nombreCli = $_SESSION['txtNombre'];
    $fotos = $_SESSION['fotos'];
    $counter = $_SESSION['counter'];
    $sucursal = $_SESSION['sucursal'];
    $idSucursal = $_SESSION['idSuc'];
    $maquina = $_SESSION['numMaquina'];

    // copiar a este arreglo toda la información que ocupemos en el archivo json de respaldo
    $respaldo["numero"]= $pedido;
    $respaldo["fotos"] = $fotos;
    $respaldo["cliente"] = $nombreCli;
    $respaldo["tamano"] = $tamFoto;
    $respaldo["sucursal"] = $sucursal;
    $respaldo["idSucursal"] = $idSucursal;
    $respaldo["numeroMaquina"] = $maquina;
    $respaldo["fecha"] = date("d-m-Y H:i:s"); // = 1351189042
    $respaldo["cantidadFotos"] = $counter;
    $respaldo["directorio"]=$directorio;

    fcflog(__FILE__, "ticket[" . $pedido . "][" . $counter . "-" . $tamFoto . "]");

    $strCliente =
        "          FOTO COLOR FACIL SA DE CV" . PHP_EOL .
            "               FCF-841011-764" . PHP_EOL .
            "          ".$ubicacion.", Mexico" . PHP_EOL .
            "Sucursal: ". $sucursal . PHP_EOL .
            "Fecha: " . date("d-m-Y H:i:s") . PHP_EOL .
            "Pedido No. " . $pedido . PHP_EOL .
            "Cliente: " . $nombreCli . PHP_EOL .
            "Kiosco: " . $maquina . PHP_EOL . PHP_EOL .
            "=============================================" . PHP_EOL .
            "IMP. FOTOS " . $tamFoto . "                  " . $counter . PHP_EOL .
            "=============================================" . PHP_EOL . PHP_EOL .
            "Gracias por su preferencia!" . PHP_EOL;

    // si no existe el directorio, crearlo
    if(!is_dir($data)) {
        @mkdir($data);
    }
    // Guardar el archivo temporal del ticket a imprimir
    $file = $data . DIRECTORY_SEPARATOR . 'ticket.txt';
    $fileFh = fopen($file, 'w') or die("no se pueden guardar los datos del cliente");
    fwrite($fileFh, $strCliente);
    fclose($fileFh);
    // de una vez genera tambien el *respaldo*
    file_put_contents($data . DIRECTORY_SEPARATOR . "respaldo.json", json_encode($respaldo));
}

/**
 * Leer de disco los pedidos almacenados
 * @return array Arreglo con la lista de pedidos
 */
function leerPedidos() {
    // checamos cual es el directorio donde se guardan los pedidos
    global $destino;
    $result = array();
    $root = @scandir($destino);
    foreach ($root as $value) {
        if (strpos($value, ".") === 0) {
            continue;
        }
        if (is_dir("$destino" . DIRECTORY_SEPARATOR  . "$value")) {
            $path = $destino . DIRECTORY_SEPARATOR . $value;
            $pedido = new Pedido($path);
            $result[] = $pedido;
        }
    }
    return $result;
}

/**
 * Funcion para leer rapidamente los pedidos que se encuentran en un kiosco, limitandose a dar un escaneo rapido de
 * los directorios existentes
 * @return array
 */
function leerPedidosPorDir() {
    // checamos cual es el directorio donde se guardan los pedidos
    global $destino;
    $result = array();
    $root = @scandir($destino);
    foreach ($root as $value) {
        if (strpos($value, ".") === 0) {
            continue;
        }
        if (is_dir("$destino" . DIRECTORY_SEPARATOR  . "$value")) {
            $path = $destino . DIRECTORY_SEPARATOR . $value;
            $pedido = new Pedido($path);
            $result[] = $pedido;
        }
    }
    return $result;
}




/**
 * @param $id
 */
function cargarPedido($id) {
    global $destino;
    global $numMaq;
    $pedido = new Pedido();
    // leer el listado de directorios
    $root = @scandir($destino);
    $foundDir = "";
    foreach($root as $dir) {
        if (stristr($dir, "-" . $id . "-")) {
            $foundDir = $dir;
            break;
        }
    }
    $ubicacion = $destino . DIRECTORY_SEPARATOR . $foundDir;
    if (is_dir($ubicacion)) {
        // echo "<h2>Ubicaci&oacute;n: " . $ubicacion;
        // leer el .json del pedido
        $arch = file_get_contents($ubicacion . DIRECTORY_SEPARATOR . "respaldo.json");
        $json = json_decode($arch);
        $pedido->setNumero($json->numero);
        $pedido->setPath($ubicacion);
        $pedido->setCliente($json->cliente);
        $pedido->setCantidadFotos($json->cantidadFotos);
        $pedido->setTamanoFotos($json->tamano);
        $pedido->setFecha($json->fecha);
        $pedido->setMaquina($json->numeroMaquina);
        $pedido->setDirectorio($json->directorio);
        $pedido->setFotos($json->fotos);
        $pedido->setSucursal($json->sucursal);

        return $pedido;
    }

}


/**
 * @return array
 */
function checarDiscos() {
    $disks = array();
    if (php_uname('s') == 'Windows NT') {
        $fso = new COM('Scripting.FileSystemObject');
        $D = $fso->Drives;
        $type = array("Unknown","Removable","Fixed","Network","CD-ROM","RAM Disk");
        foreach($D as $d ){
            $dO = $fso->GetDrive($d);
            $s = "";
            if($dO->DriveType == 3){
                $n = $dO->Sharename;
            }else if($dO->IsReady){
                $n = $dO->VolumeName;
                $disks[] = $dO->DriveLetter . ":";
            }else{
                $n = "[Drive not ready]";
            }
        }
        return $disks;
    } else {
        // unix
        $data = `mount`;
        $data = explode(' ', $data);
        $isDisk = 0;
        $diskToken = "";
        foreach ($data as $token) {
            // echo "<br/>* $token ";
            if ($isDisk == 1) {
                if ($token == 'type') {
                    $isDisk = 0;
                    $disks[] = $diskToken;
                    $diskToken = "";
                } else {
                    // si no es "type", entonces sigue el nombre todavia
                    $diskToken = $diskToken . " " . $token;
                }
            }
            if (substr($token, 0, 7) == '/media/') {
                $isDisk = 1;
                $diskToken = $token;
            }
        }
        return $disks;
    }
}

/**
 * @param $Bytes
 * @return string
 */
function getEspacioLegible($Bytes) {
    $Type = array("", "kilo", "mega", "giga", "tera", "peta", "exa", "zetta", "yotta");
    $Index = 0;
    while ($Bytes >= 1024) {
        $Bytes /= 1024;
        $Index++;
    }
    return ("" . $Bytes . " " . $Type[$Index] . "bytes");
}

/**
 * @param $cantidad
 */
function aumentarFotos($cantidad) {
    $fotos = leerFotos();
    foreach($fotos as $foto) {
        $foto->counter = $foto->counter + $cantidad;
    }
}

/**
 * @param $cantidad
 */
function restarFotos($cantidad) {
    $fotos = leerFotos();
    foreach($fotos as $foto) {
        $foto->counter = $foto->counter - $cantidad;
        if ($foto->counter < 0) $foto->counter = 0;
    }
}

function reducirPedido($id) {
    $pedido = cargarPedido($id);
    if (is_null($pedido->numero)) return 0;

    $dir = $pedido->path . DIRECTORY_SEPARATOR;

    $root = scandir($dir);
    $result = array();
    if ($root) {
        foreach ($root as $value) {
            if (strpos($value, ".") === 0) {
                continue;
            }
            $val = $dir . $value;
            if (is_file($val)) {
                if (isImage($val))
                    $result[] = $val;
                continue;
            } else {
                foreach (procesaDir($val . DIRECTORY_SEPARATOR) as $img) {
                    $result[] = $img;
                }
            }
        }
    }

    if ($result) {
        foreach($result as $foto) {
            $p = $pedido->path;
            $f = str_replace($p . DIRECTORY_SEPARATOR, "", $foto);
            reducirFoto($p, $f, $pedido->tamanoFotos, $id);
        }
    }
}

function contarImagenes ($directorio){
    $totalImagenes = count(glob($directorio."/{*.jpg,*.gif,*.png, *.jpeg}", GLOB_BRACE));
    return $totalImagenes;
}

function borrarImagenes($directorio){
    $leerDirectorio = opendir($directorio);
    while ($file = readdir($leerDirectorio)){
        if (is_file($directorio.DIRECTORY_SEPARATOR.$file))   {
            unlink($directorio.DIRECTORY_SEPARATOR.$file);
        }
    }

}

/**
 * @param $path
 * @param $nombreFoto
 * @param $tamano
 * @param $idPedido
 * @return int
 * @throws Exception
 */
function reducirFoto($path, $nombreFoto, $tamano, $idPedido) {
    global $pppImpresion;
    global $dirOriginales;

    $src = $path . DIRECTORY_SEPARATOR . $nombreFoto;
    $originales = $dirOriginales . DIRECTORY_SEPARATOR . $idPedido;
    mkdir($originales);

    // Si no es un archivo el path enviado, no hacer nada
    if (!is_file($src)) return -1;

    try {
        $imgtype = exif_imagetype($src);
    } catch (Exception $e) {
        throw new Exception('Could not get imagetype from exif', 0, $e);
    }

    switch ($imgtype) {
        case 1: // gif
            $fotoSource = imagecreatefromgif($src);
            break;
        case 2: // jpeg
            $fotoSource = imagecreatefromjpeg($src);
            break;
        case 3: // png
            $fotoSource = imagecreatefrompng($src);
            break;
        default:
            $fotoSource = imagecreatefromjpeg($src);
    }

    $size = getimagesize($src);
    $fotoWidth = $size[0];
    $fotoHeight = $size[1];

    // Calcular el tamano deseado
    $aa = 4; $bb = 6;
    if ($tamano == "4x6") {
        $aa = 4; $bb = 6;
    } else if ($tamano == "5x7") {
        $aa = 5; $bb = 7;
    } else if ($tamano == "6x8") {
        $aa = 6; $bb = 8;
    } else if ($tamano == "8x10") {
        $aa = 8; $bb = 10;
    }

    // aqui es donde nos preocupamos si es horizontal o vertical
    if ($fotoWidth > $fotoHeight) {
        $minWidth = $bb * $pppImpresion; // 1200
        $minHeight= $aa * $pppImpresion; // 800
    } else {
        $minWidth = $aa * $pppImpresion; // 800
        $minHeight= $bb * $pppImpresion; // 1200
    }

    // calcular el tamano deseado en pixeles
    // modo simple, forzar los tamanos
    $wDeseado = $minWidth;
    $hDeseado = $fotoHeight * $minWidth / $fotoWidth;
    if ($hDeseado < $minHeight) {
        // recalcular
        $hDeseado = $minHeight;
        $wDeseado = $fotoWidth * $minHeight / $fotoHeight;
    }

    // en ningun caso se debe de expander la imagen, asi que si hace eso, no procesar
    if (($wDeseado <= $fotoWidth) && ($hDeseado <= $fotoHeight)) {
        $fotoDestino = imagecreatetruecolor($wDeseado, $hDeseado);
        imagecopyresized($fotoDestino, $fotoSource, 0, 0, 0, 0, $wDeseado, $hDeseado, $fotoWidth, $fotoHeight);
        imagejpeg($fotoDestino, $src . "-R.jpg");
        rename($src, $originales . DIRECTORY_SEPARATOR . $nombreFoto);
        return 1;
    } else {
        // prueba a ver si vale la pena resampling hacia arriba para impresion
        //      no, no vamos a hacer resampling, las imagenes salen mas pixeleadas
        /*$fotoDestino = imagecreatetruecolor($wDeseado, $hDeseado);
        imagecopyresampled($fotoDestino, $fotoSource, 0, 0, 0, 0, $wDeseado, $hDeseado, $fotoWidth, $fotoHeight);
        imagejpeg($fotoDestino, $src . "-Resample.jpg");
        imagecopyresized($fotoDestino, $fotoSource, 0, 0, 0, 0, $wDeseado, $hDeseado, $fotoWidth, $fotoHeight);
        imagejpeg($fotoDestino, $src . "-Resized.jpg");*/
        return 0;
    }

}

?>
