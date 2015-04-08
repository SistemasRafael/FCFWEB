<?php
include_once("./foto.class.php");
include_once("./config.php");
include_once("./funciones.php");

$debug = false;

$drive = getDrive();
cargarListado($drive);
cargarFotos($drive);

if ($debug)
    echo "<br/>drive: " . $drive;

if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
    if ($pagina == null || $pagina == "") {
        $pagina = 1;
    }
} else {
    $pagina = 1;
}
$totalPaginas = getNumeroDePaginas();
$totalFotos = contarFotos();

$paginaAnterior = $pagina - 1;
$paginaSiguiente = $pagina + 1;

if ($debug)
    $pagina = 1;
if ($debug)
    echo "<br/>pagina: " . $pagina;

$fotos = getFotosDePagina($pagina);
if ($debug)
    echo "<pre>" . var_dump($fotos) . "</pre>";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Kiosco Foto Color Facil</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./css/bootstrap.css" rel="stylesheet">
        <link href="./css/bootstrap-responsive.css" rel="stylesheet">
        <link href="./css/docs.css" rel="stylesheet">
        <link href="./jquery.alerts.css" rel="stylesheet">
        <script type="text/javascript" src="./js/bootstrap.js"></script>
        <script type="text/javascript" src="./jquery-1.8.1.js"></script>
        <script type="text/javascript" src="./js/html5shiv.js"></script>
        <!--<script type="text/javascript" src="./js/jquery.numeric.js"></script>-->
        <script type="text/javascript" src="./jquery.alerts.js"></script>
    </head>
    <body>
        <div class="encabezado">
            <span class="der">
                <a href="./fotos.php"><img width="81" height="47" src="images/botonTopStart.png" alt="Pag Anterior"/></a>
                <?php if ($pagina == 1) { ?>
                    <a href="#"><img width="81" height="47" src="images/botonAtras1.png" alt="Pag Anterior"/></a>
                <?php } else { ?>
                    <a href="./fotos.php?pagina=<?php echo $paginaAnterior; ?>"><img src="images/botonAtras1.png" alt="Pag Anterior"/></a>
                <?php } ?>
                <input class="txtPaginacion" id="txtPaginacion" type="text" value="<?php echo $pagina; ?>" data-max-page="<?php echo $totalPaginas; ?>"/>
                <?php if ($pagina >= $totalPaginas) { ?>
                    <a href="#"><img width="81" height="47" src="./images/botonEnfrente1.png" alt="Pag Siguiente"/></a>
                <?php } else { ?>
                    <a href="./fotos.php?pagina=<?php echo $paginaSiguiente; ?>"><img width="81" height="47" src="./images/botonEnfrente1.png" alt="Pag Siguiente"/></a>
                <?php } ?>
                <a id="pagFin" href="./fotos.php?pagina=<?php echo $totalPaginas ?>"><img width="81" height="47" src="./images/botonTopEnd.png" /></a>
                <a id="finSele" href="./detalle_compra.php"><img src="./images/botonFinalizarSeleccion.png" alt="Finalizar Seleccion"/></a>
        </div>
        <div id="botonesNuevos" >
            <a id="todasPlus" href="#"><img width="81" height="47" src="./images/botonTodas+1.png" /></a>
            <a id="todasMinus" href="#"><img width="81" height="47" src="./images/botonTodas-1.png" /></a>
            <a id="btnCancel" href="#"><img width="47" height="47" src="./images/salir.png" /></a>
            <!--<a id="fppPlus" href="#"><b>FPPplus</b></a>-->
        </div>
    </span>
</div>
<div class="container" style="position: fixed; top: 120px; left: 0;">
    <!-- desplegar las fotos del disco -->
    <div class="span12">
        <form name="photoForm" method="post">
            <input type="hidden" name="newdir" value=""/>
            <ul class="thumbnails">
                <?php
                $llaves = array_keys($fotos);

                for ($fotoActual = 0; $fotoActual < sizeof($fotos); $fotoActual++) {
                    $foto = $fotos[$llaves[$fotoActual]];
                    $exif = @exif_read_data($foto->path);
                    $fn = $foto->nombre;
                    $fw = $exif['COMPUTED']['Width'];
                    $fh = $exif['COMPUTED']['Height'];

                    echo "\n<li style='width: 180px;' >";
                    echo "\n<div class=thumbnail style=\"text-align: center;\">";
                    echo "\n<div class=imgchica style=\"width:160px; height: 120px;\">\n";
                    echo '<img src="./thumb.php?f=' . $foto->hash . '"/>';
                    echo "\n</div>";
                    echo "</div>";
                    echo "<div class=caption>";
                    echo "<div style=\"font-size: 9pt; width: 450px; height: 20px; overflow: hidden;\">$fn</div >";
                    echo "<p style=\"font-size:24px; text-align: center;\">";
                    echo "<button id='btnMinus" . $foto->hash . "' class=\"btnminus btn btn-large btn-danger\" type=\"button\"><i class=\"icon-minus icon-white\"></i></button>";
                    echo '&nbsp;<span class="datanumfotos" id="datanumfotos' . $foto->hash . '">' . $foto->counter . '</span>&nbsp;';
                    echo "<button id='btnPlus" . $foto->hash . "' class=\"btnplus btn btn-large btn-success\" type=\"button\"><i class=\"icon-plus icon-white\"></i></button>";
                    echo "</p>";
                    echo "</div>";
                    echo "</li>";
                }   
                echo "<div class=imggrande></div>";
                   
                ?>
            </ul>
        </form>
    </div>

</div> <?php /* Cerrar div del container del header */ ?>

<div class="piefotos">
    <?php
    echo "<span class=izq>Total de Fotos: <b id=\"totalF\">$totalFotos</b></span>";
    echo "<span class=der>P&aacute;gina <b>" . $pagina . "</b> de <b>" . $totalPaginas . "</b></span>";
    ?>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var btnPlus     = $('.btnplus');
        var btnMinus    = $('.btnminus');
        var btnPlusAll  = $('#todasPlus');
        var btnAllMinus = $('#todasMinus');
        var btnCancelar = $('#btnCancel');

// Sumar cantidad por imagen +
        $(btnPlus).each(function (i) {
            $(this).click(function () {
                var btnId = $(this).attr('id');
                var sHash = btnId.substring(7);
                var numId = "#datanumfotos" + sHash;
                $.ajax({
                    type:"POST",
                    url:"ajax_counter.php",
                    data:{ boton:btnId }
                }).done(function (newcounter) {
                    console.log(newcounter);
                    $(numId).html(newcounter.substring(0, newcounter.indexOf(',')));
                    $('#totalF').html(newcounter.substring(newcounter.indexOf(',') + 1));
                });
            });
        });
        // Restar cantidad por imagen -
        $(btnMinus).each(function (i) {
            $(this).click(function () {
                var btnId = $(this).attr('id');
                var sHash = btnId.substring(8);
                var numId = "#datanumfotos" + sHash;
                $.ajax({
                    type:"POST",
                    url:"ajax_counter.php",
                    data:{ boton:btnId }
                }).done(function (newcounter) {
                    $(numId).html(newcounter.substring(0, newcounter.indexOf(',')));
                    $('#totalF').html(newcounter.substring(newcounter.indexOf(',') + 1));
                });
            });
        });
        //Aumenta Todas las fotos de la sesion +1
        $(btnPlusAll).each(function(i){
            $(this).click(function(){
                var btnId = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    url: "ajax_counter.php",
                    data: { boton:btnId }
                }).done(function(newAllCounter){
                    $(btnId).html(newAllCounter.substring(0, newAllCounter+1));
                    $('#totalF').html(newAllCounter.substring(newAllCounter.indexOf(',') + 1));
                    window.location.href = './fotos.php';
                });
            });
        });
        //Resta Todas las fotos de la sesion -1
        $(btnAllMinus).each(function(i){
            $(this).click(function(){
                var btnId = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    url: "ajax_counter.php",
                    data: { boton:btnId }
                }).done(function(newAllCounter){
                    $(btnId).html(newAllCounter.substring(0, newAllCounter+1));
                    $('#totalF').html(newAllCounter.substring(newAllCounter.indexOf(',') + 1));
                    window.location.href = './fotos.php';
                });
            });
        });
        //Starts: Validacion del txtPaginacion
        $('#txtPaginacion').keyup(function() {
            if (this.value.match(/[^0-9]/g)) {
                this.value = this.value.replace(/[^0-9]/g, '');
            }

        });
        //Ends: Validacion del txtPaginacion
        //Ejecuta el evento "ENTER" para dirijirse a la pagina solicitada en el text box        
        $("#txtPaginacion").keypress(function(event){
            if (event.which == 13) {
                var pagina = parseInt(document.getElementById("txtPaginacion").value);
                var totalPaginas=parseInt('<?php echo $totalPaginas; ?>');
                    
                if(pagina<=totalPaginas && pagina>0){
                    window.location.href = './fotos.php?pagina='+ pagina;
                }
                else{
                    alert('La pagina ' + pagina + ' no se encuentra, su dispositivo tiene '
                        + totalPaginas + ' paginas');
                    window.location.href = './fotos.php?pagina';
                }
            }
        });
        //Salir del pedido confirmacion
        $(btnCancelar).click(function(){
            jConfirm('Seguro que desea salir?', 'Dialogo de confirmacion', function(r) {
                if(r == true){
                    window.location.href = "./index.php";
                }else{
                    return false;
                }
//                jAlert('Confirmed: ' + r, 'Confirmation Results');
            });
        });
    });
    
   var imagen=$(".imgchica img")
   var imagengrande=$("div.imggrande");
   imagen.hover(
      function(){
         ruta=($(this).attr('src'));
         $("div.imggrande").append("<img src="+ruta+" style='margin-top: -50%' width=450 height=240 hspace=20%/>");
         imagengrande.fadeIn();
}, 
      function(){
         $("div.imggrande").empty();
         imagengrande.hide()
} 
); 
</script>

<style type="text/css">
    body {
        background-image: url('images/fondo_fotos.png');
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    .piefotos {
        width: 100%;
        color: white;
        height: 35px;
        position: fixed;
        bottom: 0;
        font-size: 28pt;
    }

    .piefotos .izq {
        position: relative;
        top: 5px;
        left: 20px;
        float: left;
    }

    .piefotos .der {
        position: relative;
        top: 5px;
        right: 15px;
        float: right;
    }
    .encabezado .der {
        position: relative;
        top: -25px;
        right: 15px;
        float: right;
        height: 25px;
    }
    #txtPaginacion{
        width: 10px; 
        height: 27px;  
        margin-top: 10px; 
        width: 50px;
        text-align: center; 
        font-family: serif, arial; 
        font-size: 25px; 
        background-color: #CCCCCC;
        border-radius: 5px;
    }
    #irA{
        display: inline-block;
        float: left;
        margin-left: 69%;
        font-weight: bold;
    }
    #todasPlus{
        margin-left: 0.2%;
        margin-top: 2%;
    }

    #todasMinus{
        margin-left: 0%;
    }

    #botonesNuevos{
        /*border: 2px solid red;*/
        height: 50px;
        margin-left: 50%;
        margin-top: 2%;
    }
</style>
</body>
</html>
