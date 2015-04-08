<?php
include_once("./foto.class.php");
include_once("./config.php");
include_once("./funciones.php");
$debug = false;
$drive = getDrive();
cargarListado($drive);
cargarFotos($drive);

if ($debug) echo "<br/>drive: " . $drive;

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

fcflog(__FILE__, "pag " . $pagina);

$paginaAnterior = $pagina - 1;
$paginaSiguiente = $pagina + 1;

if ($debug) $pagina = 1;
if ($debug) echo "<br/>pagina: " . $pagina;

$fotos = getFotosDePagina($pagina);
if ($debug) echo "<pre>" . var_dump($fotos) . "</pre>";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Kiosco Foto Color Facil</title>
    <meta name="viewport" content="width=1024, initial-scale=1.0">
    <link href="./base.css" rel="stylesheet">
    <link href="./css/docs.css" rel="stylesheet">
    <link href="./css/bootstrap.css" rel="stylesheet">
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">
    <link href="./jquery.alerts.css" rel="stylesheet">
    <script type="text/javascript" src="./js/bootstrap.js"></script>
    <script type="text/javascript" src="./jquery-1.8.1.js"></script>
    <script type="text/javascript" src="./js/html5shiv.js"></script>
    <script type="text/javascript" src="./jquery.alerts.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body id="bodyFotos">
<div id="botonesDerechaArriba">
    <?php if ($pagina == 1) { ?>
    <a href="#"><img width="81" height="47" src="images/botonTopStartD.png" alt="|&lt;-"/></a>
    <a href="#"><img width="81" height="47" src="./images/btn_anterior_flecha_d.png" alt="Pag Anterior"/></a>
    <?php } else { ?>
    <a href="./fotos.php"><img width="81" height="47" src="images/botonTopStart.png" alt="|&lt;-"/></a>
    <a href="./fotos.php?pagina=<?php echo $paginaAnterior; ?>"><img width="81" height="47" src="./images/btn_anterior_flecha.png" alt="Pag Anterior"/></a>
    <?php } ?>

    <input class="txtPaginacion" id="txtPaginacion" type="text" value="<?php echo $pagina; ?>" data-max-page="<?php echo $totalPaginas; ?>"/>

    <?php if ($pagina >= $totalPaginas) { ?>
    <a href="#"><img width="81" height="47" src="./images/btn_siguiente_flecha_d.png" alt="Pag Siguiente"/></a>
    <a href="#"><img width="81" height="47" src="./images/botonTopEndD.png" /></a>
    <?php } else { ?>
    <a href="./fotos.php?pagina=<?php echo $paginaSiguiente; ?>"><img width="81" height="47" src="./images/btn_siguiente_flecha.png" alt="Pag Siguiente"/></a>
    <a href="./fotos.php?pagina=<?php echo $totalPaginas ?>"><img width="81" height="47" src="./images/botonTopEnd.png" /></a>
    <?php } ?>

    <a id="finSele" href="./detalle_compra.php"><img src="./images/botonFinalizarSeleccion.png" alt="Finalizar Seleccion"/></a>
</div>

<div id="botonesDerechaAbajo" >
    <a id="todasMinus" href="#"><img width="81" height="47" src="./images/botonTodas-1.png" /></a>
    <a id="todasPlus" href="#"><img width="81" height="47" src="./images/botonTodas+1.png" /></a>
    <a id="btnCancel" href="#"><img width="47" height="47" src="./images/salir.png" /></a>
</div>

<div id="containerFotos">
    <form name="photoForm" method="post">
        <?php
        $llaves = array_keys($fotos);
        $fotosPorColumna = 5;
        $fotosDesplegadas = 0;

        for ($fotoActual = 0; $fotoActual < sizeof($fotos); $fotoActual++) {
            $foto = $fotos[$llaves[$fotoActual]];
            $exif = @exif_read_data($foto->path);
            $fn = $foto->nombre;
            $fw = $exif['COMPUTED']['Width'];
            $fh = $exif['COMPUTED']['Height'];
            /*if ($fotosDesplegadas % $fotosPorColumna == 0) {
                echo "<tr>";
            }*/
            ?>
            <div class="fotoTile">
                <div class="linkGrande fotoThumb">
                    <img class="imgFoto" onclick="mostrarEnGrande('<?php echo $foto->hash; ?>')" src="./thumb.php?f=<?php echo $foto->hash; ?>"/>
                </div>
                <div class="fotoInfo"><?php echo $fn; ?></div>
                <div class="botonesFoto">
                    <button id="btnMinus<?php echo $foto->hash; ?>" class="btnminus btn btn-large btn-danger" type="button"><i class="icon-minus icon-white"></i></button>
                    &nbsp;<span class="datanumfotos" id="datanumfotos<?php echo $foto->hash; ?>"><?php echo $foto->counter; ?></span>&nbsp;
                    <button id="btnPlus<?php echo $foto->hash; ?>" class="btnplus btn btn-large btn-success" type="button"><i class="icon-plus icon-white"></i></button>

                </div>
            </div>
            <?php
            $fotosDesplegadas++;
        }
        ?>
        </table>
    </form>
</div>

</div>

<div id="pieFotos">
    <span id="pieTotalFotos">Total de Fotos: <b id="totalF"><?php echo $totalFotos; ?></b></span>
    <span id="piePaginacion">P&aacute;gina <b><?php echo $pagina . "</b> de <b>" . $totalPaginas; ?></b></span>
</div>

<div id="fotoGrande" onclick="esconderEnGrande()" style="width: 1024px; height: 768px; background-color: black; z-index: 50; position: fixed; top:0; left:0; visibility: hidden"></div>
<div id="infoGrande" onclick="rotarImgCW()" style="width: 260px; height: 20px; border: solid white 1px; background-color: black; z-index: 60; position: fixed; top:0; left:0; visibility: hidden; color: white;">
    &nbsp;&nbsp;Haga click en la imagen para regresar
</div>

<style type="text/css">
    .rotar90 {
        -webkit-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
    }
    .rotar180 {
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
    }
    .rotar270 {
        -webkit-transform: rotate(270deg);
        -moz-transform: rotate(270deg);
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){

    });

    function rotarImg0() {
        document.getElementById('visualizacionFoto').className = "";
    }

    function rotarImg90() {
        document.getElementById('visualizacionFoto').className = "rotar90";
    }

    function rotarImg180() {
        document.getElementById('visualizacionFoto').className = "rotar180";
    }

    function rotarImg270() {
        document.getElementById('visualizacionFoto').className = "rotar270";
    }

    function mostrarEnGrande($hash, $pw, $ph) {
        document.getElementById('fotoGrande').innerHTML = '<img onclick="esconderEnGrande()" id="visualizacionFoto" style="max-width: 1024px; max-height: 768px; min-width: 576px; min-height: 384px; " src="./passthru.php?f=' + $hash + '"/>';
        document.getElementById('fotoGrande').style.visibility = 'visible';
        document.getElementById('infoGrande').style.visibility = 'visible';
    }

    function esconderEnGrande() {
        document.getElementById('fotoGrande').style.visibility = 'hidden';
        document.getElementById('infoGrande').style.visibility = 'hidden';
    }

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
        // Salir del pedido confirmacion
        $(btnCancelar).click(function(){
            jConfirm('Seguro que desea salir?', 'Dialogo de confirmacion', function(r) {
                if(r == true){
                    window.location.href = "./index.php";
                }else{
                    return false;
                }
            });
        });
    });

</script>
</body>
</html>
