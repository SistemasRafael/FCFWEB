<?php
include_once('foto.class.php');
include_once ('config.php');
include_once ('funciones.php');
fcflog(__FILE__, "");
borrarImagenes($pathMemoria);

if (!isset($_SESSION['tamano'])) {
    $tamanoFotos = $_POST['tamano'];
    $_SESSION['tamano'] = $tamanoFotos;
    fcflog(__FILE__, "set[tamano=" . $tamanoFotos . "]");
} else {
    $tamanoFotos = $_SESSION['tamano'];
}

?>

<html>
<head> 
    <link type="text/css" rel="stylesheet" href="./base.css"/>
    <title>Kiosco Foto Color Facil</title>
</head>
<body id="bodySeleccion">
        <div id="titulo">Por favor seleccione el medio a utilizar</div>

    <div id="seleccion">
            <div id="btnMed">
            <input type="button" name="medio" value="MEMORIA" id="botonM" 
                   onclick="document.location='./detectando.php'"/>
            <input type="button" name="medio" value="BLUETOOTH" id="botonB"
                   onclick="document.location='./medio.php'">
            </div>
            </div>
    </div>

    
</body>
</html>
