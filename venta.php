<?php
include_once('foto.class.php');
include_once('config.php');
include_once('funciones.php');
fcflog(__FILE__, "");

?>
<html>
<head>
    <title>Kiosco</title>
    <script type="text/javascript" src="./jquery-1.8.1.js"></script>
    <link type="text/css" rel="stylesheet" href="./css/bootstrap.css" >
    <link type="text/css" rel="stylesheet" href="./base.css"/>
</head>
<body id="venta">
<?php
$pedido = getNumPedido();

echo '<a href="index.php"><img id="btnFinalizar" src="./images/boton-ini.png"/></a>';
?>
<div class="procesandoPedido">
    <span class="imagenProcesoPedido"><img src="./images/ajax-loader.gif" /></span>
    <span class="mensageProcesoPedido">Espere un momento,<br/>copiando imagenes del pedido.</span>
</div>
<?php
echo "<div id='ticket'>";
echo "<h2>No. Pedido: " . $pedido . "</h2><br/>";
echo "<a id='reimprimirTicket' href='#'><img src='./images/btn_imprimir.png' /></a>";
echo "</div>";
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#reimprimirTicket").click(function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "./reimprimeticket.php",
                success: function(){
                    alert('Ticket impreso, de click en Finalizar porfavor');
                },error: function(){
                    alert('Error al reimprimir Ticket');
                }
            });
        });
        $.ajax({
            type: "POST",
            url: './copiandoimgs.php',
            success: function(data){
                data = jQuery.parseJSON(data);
                console.log(data);
                if(data == "true"){
                    $(function(){
                        $('.procesandoPedido').fadeIn(800, function() {
                            $(this).delay(1000).fadeOut(1200, function() {
                                $('div').remove('.procesandoPedido');
                                $('div').remove('.mensageProcesoPedido');
                            });
                        });
                    });
                }
            },
            error: function(){
                alert('ha habido un error');
            }
        });

    });
</script>
</body>
</html>
