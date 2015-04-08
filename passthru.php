<?php
include_once("./foto.class.php");
include_once("./config.php");
include_once("./funciones.php");

// Si es jpg usar el thumbnail embedido (solo jpgs)
$usarThumbnailEmbedido = false;

// foto a mostrar, recibimos el hash
$hash = $_GET['f'];
$exif = null;

$foto = getFoto($hash);
$src = $foto->path;
header('Content-Type: image/jpeg');
header('Content-Length: ' . filesize($src));
ob_clean();
flush();
readfile($src);
exit;


$cachefile = $cachedir . DIRECTORY_SEPARATOR . $foto->hash . ".png";


if (!file_exists($cachefile)) {
// exif_read_data solo soporta jpgs y tiffs
    try {
        $imgtype = exif_imagetype($src);
    } catch (Exception $e) {
        throw new Exception('Could not get imagetype from exif', 0, $e);
    }

    switch ($imgtype) {
        case 1: // gif
            $src_img = imagecreatefromgif($src);
            break;
        case 2: // jpeg
            $src_img = imagecreatefromjpeg($src);
            break;
        case 3: // png
            $src_img = imagecreatefrompng($src);
            break;
        default:
            $src_img = imagecreatefromjpeg($src);
    }

// rotacion
    if ($exif == null || $exif['Orientation'] == null || !isset($exif['Orientation'])) {
        $rotated = $src_img;
    } else {
        switch ($exif['Orientation']) {
            case 8:
                $rotated = imagerotate($src_img, 90, 0);
                break;
            case 3:
                $rotated = imagerotate($src_img, 180, 0);
                break;
            case 6:
                $rotated = imagerotate($src_img, -90, 0);
                break;
            case 1:
                $rotated = $src_img;
        }
    }

    $width = imagesx($rotated);
    $height = imagesy($rotated);
    // limitar dentro del recuadro donde estara el thumbnail
    // este esta definido por las variables fotox y fotoy en el config.php
    if ($width > $height) {
        error_log("w>h");
        $neww = $fotox;
//        $neww = $width * $fotoy / $height;
        $newh = round($fotox * $height / $width);
        if ($newh > $fotoy) {
            $newh = $fotoy;
            $neww = round($fotoy * $width / $height);
        }
    } else {
        error_log("h>w");
        $newh = $fotoy;
        $neww = round($fotoy * $width / $height);
    }

    error_log("preview size: [" . $neww . "," . $newh . "]");
    error_log("orig size: [" . $width . "," . $height . "]");
    error_log("path: [" . $src . "]");


    header("Content-type: image/png\nContent-Disposition: inline;");
    $tn = imagecreatetruecolor($neww, $newh);
    imagecopyresized($tn, $rotated, 0, 0, 0, 0, $neww, $newh, $width, $height);
    $thumb = imagepng($tn, $cachefile, 9);
    imagedestroy($tn);
}

$fp = fopen($cachefile, 'rb'); # stream the image directly from the cachefile
header("Content-type: image/png\nContent-Disposition: inline;\nContent-length: " . (string)(filesize($fp)));
//@fpassthru($fp);
@fpassthru($fp);
@fclose($fp);
exit;
?>