<?php

$src = $_GET['f'];

$pi = pathinfo($src);

// determinar el tipo de archivo segun la extension
$ext = strtolower($pi['extension']);

$exif = exif_read_data($src);
echo printArray($exif);


function printArray($arr) {
    $markup = "<table border=1>";
    foreach($arr as $key=>$value) {
        if (is_array($value)) {
            $markup .= "<tr><td>" . $key . "</td><td>" . printArray($value) . "</td></tr>";
        } else {
            $markup .= "<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
        }
    }
    $markup .= "</table>";
    return $markup;
}



?>