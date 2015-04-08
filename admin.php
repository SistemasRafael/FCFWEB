<?php
    include_once("./config.php");
    include_once("./funciones.php");
    include_once('./pedido.class.php');
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
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <h1>Pedidos</h1>

    <?php

    $pedidos = leerPedidosPorDir();
    $pedidos = leerPedidos();


    echo "<h3>Se encontraron " . sizeof($pedidos) . " pedidos.</h3>";
    ?>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Numero</th>
            <th>Sucursal</th>
            <th>Cliente</th>
            <th>Tamano</th>
            <th>Fotos</th>
            <th>Fecha y Hora</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php      
            foreach($pedidos as $pedido) {
                echo "<tr>";
                echo "<td>" . $pedido->getNumero() . "</td>";
                echo "<td>" . $pedido->getSucursal() . "</td>";
                echo "<td>" . $pedido->getCliente() . "</td>";
                echo "<td>" . $pedido->getTamanoFotos() . "</td>";
                echo "<td>" . $pedido->getCantidadFotos() . "</td>";
                echo "<td>" . $pedido->getFecha() . "</td>";
                echo "<td>";
                echo "<a class=\"btn btnVer btn-inverse\" href=\"./admin-pedido.php?id=" . $pedido->getNumero() . "\"><i class=\"icon-white icon-eye-open\"></i> Ver</a>";
                echo "&nbsp;<a class=\"btn btnChecar btn-inverse\" href=\"./admin-checar.php?id=" . $pedido->getNumero(). "\"><i class=\"icon-white icon-cog\"></i> Checar</a>";
                 echo "&nbsp;<a class=\"btn btnImprimir btn-inverse\" id=\"imprimir" . $pedido->getNumero() . "\"><i class=\"icon-white icon-print\"></i> Imprimir ticket</a>";
                 echo "&nbsp;<a class=\"btn btnBorrar btn-danger\" id=\"imprimir" . $pedido->getNumero() . "\"><i class=\"icon-white icon-remove\"></i> Borrar</a>";
            //   echo "&nbsp;<a id='imprimirticket' href='#' class=\"btn\">Imprimir ticket</a>";

                echo "<a href=\"./imprimirticket.php?id=" . $pedido->getNumero(). "&sucursal=". $pedido->getSucursal().
                     "&cliente=" . $pedido->getCliente(). "&tamFotos=". $pedido->getTamanoFotos(). "&fecha=". $pedido->getFecha().
                     "&counter=" . $pedido->getGlobalCounter(). "&archivo=". $pedido->getDirectorio().
                     "&numeroMaquina=" . $pedido->getMaquina(). "\" class=\"btn\" >Imprimir Ticket</a>";       
                echo "</td>";
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
</div> 
<script src="./jquery-1.8.1.js"></script>
<script src="./js/bootstrap.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        // funcionalidad en click de boton "imprimir ticket"
        $('.btnImprimir').each(function(i){
            $(this).click(function(){
                var btnId = $(this).attr('id');
                alert("Implementar llamada ajax de impresion: " + btnId);


            });
        });

    });
</script>
        
</body>
</html>
