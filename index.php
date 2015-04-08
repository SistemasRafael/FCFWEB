<?php
session_start();

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Finally, destroy the session.
session_destroy();
include_once('./funciones.php');
fcflog(__FILE__, "");
?>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-cache,no-store,must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link type="text/css" rel="stylesheet" href="./base.css"/>
    <title>Bienvenida</title>
</head>
<body id="index">
<form action="nombre_cliente.php" method="POST" id="formInicial">
    <div id="btnInicio">
        <input type="submit" value=" " id="botonIn"/>
    </div>
</form>
</body>
</html>
