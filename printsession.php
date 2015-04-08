<?php
include_once('./foto.class.php');
session_start();
?>
<html>
<body>
<a href="./dev.php">Regresar</a>
<?php
    print_r('<pre>');
    print_r($_SESSION);
    print_r('</pre>');
?>
<a href="./dev.php">Regresar</a>
</body>
</html>



