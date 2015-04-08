<?php
include_once("./foto.class.php");
session_start();

?>
<html>
<head></head>
<body>
<?php
    if (isset($_SESSION['fotos'])) {
        $fotos = $_SESSION['fotos'];
        echo "<br/>Se encontraron " . sizeof($fotos) . " fotos en la sesion";
        echo "<table border=1>";
        echo "<tr>";
        echo "<th>Llave</th>";
        echo "<th>Hash (obj)</th>";
        echo "<th>Path</th>";
        echo "<th>Counter</th>";
        echo "<th>Type</th>";
//        echo "<th>Tamano</th>";
        echo "</tr>";
        foreach ($fotos as $key=>$obj) {
            echo "<tr>";
            echo "<td>" . $key . "</td>";
            echo "<td>" . $obj->hash . "</td>";
            echo "<td>" . $obj->path . "</td>";
            echo "<td>" . $obj->counter . "</td>";
            echo "<td>" . $obj->mimeType . "</td>";
//            echo "<td>" . $obj->tamanoFotos . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay info guardada.</p>";
    }
?>
</body>
</html>