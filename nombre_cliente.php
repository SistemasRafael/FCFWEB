<?php
include_once ('config.php');
include_once ('funciones.php');
fcflog(__FILE__, "");
?>
<html>
<head>
    <title>Kiosco</title>
    <script type="text/javascript" src="./jquery-1.8.1.js"></script>
    <link type="text/css" rel="stylesheet" href="./base.css"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body id="nomcli">

<form action="./tamano_foto.php" method="POST" id="formInicial">
    <div id="nomC">
        <input type="text" name="txtNombre" id="txtNombre"/><br/><br/>
        <div><input type="submit" id="botonS" value=""/></div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {

        $("#formInicial").submit(function () {
            if ($("#txtNombre").val().length < 3) {
                alert("El nombre es obligatorio y debe de ser minimo 3 letras");
                return false;
            }
            return true;
        });

        $('#txtNombre').keyup(function () {
            if (this.value.match(/[^a-zA-Z ]/g)) {
                this.value = this.value.replace(/[^a-zA-Z ]/g, '');
            }
        });

    });
</script>
</body>
</html>
