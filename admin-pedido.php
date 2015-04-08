<?php
include_once("./config.php");
include_once("./funciones.php");
include_once('./pedido.class.php');

// cargar pedido
$idpedido = $_GET['id'];
$pedido = cargarPedido($idpedido);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administración de Kioscos - Foto Color Fácil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="./css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
    </style>
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">Kioscos</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="container">
<?php
    $pedido = cargarPedido($idpedido);
?>
    <h1>Pedido #<?php echo $idpedido; ?></h1>
<?php
    echo "<ul>";
    echo "<li>Cliente: " . $pedido->getCliente() . "</li>";
    echo "<li> Cant. Fotos: " . $pedido->getCantidadFotos() . "</li>";
    echo "<li>Tamano Fotos: " . $pedido->getTamanoFotos() . "</li>";
    echo "<li>Kiosco: " . $pedido->getMaquina() . "</li>";
    echo "<li>Fecha: " . $pedido->getFecha() . "</li>";
    echo "<li>Sucursal: " . $pedido->getSucursal() . "</li>";
    echo "</ul>";
?>





</div>
<script src="./jquery-1.8.1.js"></script>
<script src="./js/bootstrap.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        // funcionalidad en click de boton "imprimir ticket"


    });
</script>

</body>
</html>
