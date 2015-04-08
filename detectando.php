<?php
include_once 'config.php';
include_once 'funciones.php';
fcflog(__FILE__, "");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="./base.css" rel="stylesheet">
    <link href="./css/bootstrap.css" rel="stylesheet">
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">
    <link href="./css/docs.css" rel="stylesheet">
    <script type="text/javascript" src="./js/bootstrap.js"></script>
    <script type="text/javascript" src="./jquery-1.8.1.js"></script>
    <script type="text/javascript" src="./blockUI.js"></script>
    <link type="text/css" rel="stylesheet" href="./base.css"/>
    <style>
        article, aside, figure, footer, header, hgroup,
        menu, nav, section {
            display: block;
        }

        #reloj {
            display: none;
            position: absolute;
            top: 300px;
            left: 440px;
        }

        body {
            text-align: center;
        }

    </style>
    <title>Cargando Dispositivo</title>
</head>
<body id="detectando">
<img src="./images/waiting.png" alt="Espere por favor" id="reloj"/>
<?php
if (!isset($_SESSION['tamano'])) {
    $tamanoFotos = $_POST['tamano'];
    $_SESSION['tamano'] = $tamanoFotos;
} else {
    $tamanoFotos = $_SESSION['tamano'];
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            type:"POST",
            url:"./driverTest.php"
        }).done(function (data) {
                    data = jQuery.parseJSON(data);
                    console.log(data);
                    if (data == "" || data == "C:") {
                        //TODO: contemplar que el cliente decida siempre no hacer el pedido durante la deteccion del dispositivo
                        //regresar a inicio y destruir la sesion.
                        setInterval(function () {
                            top.location.href = "./detectando.php";
                        }, 4000);

                    } else {
                        $(function () {
                            $('#reloj').fadeIn(3000, function () {
                                $(this).delay(1000).fadeOut(2000, function () {
                                    window.location.replace("./fotos.php");
                                });
                            });
                        });
                    }
                });
    });
</script>
</body>
</html>
