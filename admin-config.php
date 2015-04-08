<?php
include_once("./config.php");
include_once("./funciones.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administración de Kioscos - Foto Color Fácil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="./css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
    </style>
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="./js/html5shiv.js"></script>
    <![endif]-->
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
            <!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">
    <h1>Configuración</h1>
    <?php
        $lines = file("./config.php");
        foreach($lines as $linenum=>$line) {
            echo "<br/>" . $linenum . " - " . $line;
        }

    $disks = checarDiscos();
    foreach ($disks as $disk) {
        echo "<br/>";
        echo "Disco: [" . $disk . "]";
        $free = getEspacioLegible(@disk_free_space($disk));
        if ($free != " bytes") {
            echo "<br/>Espacio Libre: " . $free;
        } else {
            echo "<br/>No existe media insertada en el Dispositivo.";
        }
    }

    ?>
</div>
<!-- /container -->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="./jquery-1.8.1.js"></script>
<script src="./js/bootstrap.js"></script>

</body>
</html>
