<?php
include_once('foto.class.php');
include_once ('config.php');
include_once ('funciones.php');
$nombreCli = $_POST['txtNombre'];
$_SESSION['txtNombre'] = $nombreCli;
fcflog(__FILE__, "set[nombre=" . $nombreCli. "]");
fcflog(__FILE__, "");
?>
<html>
<head>
    <script type="text/javascript" src="./jquery-1.8.1.js"></script>
    <link type="text/css" rel="stylesheet" href="./base.css"/>
    <title>Tama&ntilde;o de las fotos</title>
</head>
<body id="tF">

<form name="formaTamano" action="./seleccionar_medio.php" method="POST" id="tamFoto">
    <input type="hidden" value="" name="tamano"/>
    <div id="radioBtns">
        <?php echo "Hola " . $nombreCli . ", <br/>por favor seleccione el tama&ntilde;o de fotos que desea";?>
        <div id="btnTam">
            <?php
            foreach ($tamanoFotos as $tam) {
                echo "<div><input type=\"submit\" value=\"$tam\" id=\"boton\" class=\"btnTam\"/></div>";
            }
            ?>
        </div>
    </div>
</form>

<script type="text/javascript">
    /* Hacer que los botones hagan submit con su valor */
    $(document).ready(function () {
        var btnTam = $('.btnTam');
        $(btnTam).each(function (i) {
            $(this).click(function () {
                var btnVal = $(this).attr('value');
                document.formaTamano.tamano.value = btnVal;
                document.formaTamano.submit();
            });
        });
    });
</script>

</body>
</html>