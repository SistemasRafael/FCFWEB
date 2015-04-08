<?php
include_once ('config.php');
include_once ('funciones.php');
$_SESSION['medio'] = "BLUETOOTH";
fcflog(__FILE__, "");
fcflog(__FILE__, "set[medio=BLUETOOTH]");
?>

<html>   
<head>
    <link type="text/css" rel="stylesheet" href="./base.css"/>
    <script type="text/javascript" src="./jquery-1.8.1.js"></script>
    <title>Kiosco Foto Color Facil</title>
</head>
<body id="bodyMedio">
 <div id="titulo">Instrucciones</div>
 <div id="instrucciones">
     <div class="txtInstrucciones">
         <?php
            echo "<div>Envie su fotografia al dispositivo " . $numMaq . "</div>";
            echo "<div>Imagenes Transferidas: </div>"; 
            echo "<div id='results'>" . contarImagenes($pathMemoria). "</div>";
      ?>   
    </div>
     </div>
 
        <a id='btnAtras' href="./seleccionar_medio.php"><img src="./images/btn_atras_rojo.png"/></a>
        <a id='btnTransferencia' href="./detectando.php"><img src="./images/btn_transferencia.png"/></a>
     
 
<script type="text/javascript">
function actualiza(){
    $("#results").load("medio.php");
  }
    setInterval( "actualiza()", 10);
 </script>
</body>
</html>






