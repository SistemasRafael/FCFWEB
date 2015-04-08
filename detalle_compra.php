<?php
include_once("./foto.class.php");
include_once("./config.php");
include_once("./funciones.php");
fcflog(__FILE__, "");

$impresiones = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <script type="text/javascript" src="./jquery-1.8.1.js"></script>
    <link type="text/css" rel="stylesheet" href="./base.css"/>
</head>
<body id="detacomp">
<div id="instrucciones">
    <div id="detalleVenta">
        <span id="errorPedidoVacio" style="visibility: hidden; display: none;">
            Debe seleccionar al menos una imagen para poder imprimir
        </span>
        <table id="tablaDetalleVenta">
            <tr>
                <th>Imagenes Seleccionadas</th>
                <th>Total de copias</th>
            </tr>
            <?php
            foreach($_SESSION['fotos'] as $Objdetalle) {
                if ($Objdetalle->counter > 0) {
                    $impresiones += $Objdetalle->counter; // ir sumando total de fotos
                    ?>
                    <tr>
                        <td><?php echo $Objdetalle->nombre ?></td>
                        <td><?php echo $Objdetalle->counter ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </div>



    <div>
        <a id='btnAtras' href="./fotos.php"><img src="./images/btn_atras_rojo.png"/></a>
        <?php
        // si no trae ninguna foto en el pedido, no se muestra el boton de imprimir.
        if ($impresiones > 0) {
            echo '<div>';
            echo '<a id="btnTransferencia" href="./venta.php"><img src="./images/btn_procesa.png"/></a>';
            echo '</div>';
        }
        ?>
    </div>
</div>
<div id="detalleTxtTotal">
    Total de fotos:<br/>
    <span><?php echo $impresiones; ?></span>
</div>

<script type="text/javascript">
    $(document).ready(function(){
    <?php if ($impresiones == 0) { ?>
        document.getElementById('detalleVenta').style.visibility = 'hidden';
        document.getElementById('errorPedidoVacio').style.visibility = 'visible';
        document.getElementById('errorPedidoVacio').style.display = 'block';
        <?php } ?>
    });

</script>
</body>
</html>