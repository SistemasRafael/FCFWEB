<?php
class Foto {
    public $hash;
    public $counter = 0;
    public $path;
    public $mimeType;
    public $thumb;
    public $nombre;
    public $imgExtension;
    public $width;
    public $height;
    
    public function __construct($path = "") {
        $this->path = $path;
        $this->counter = 0;
        $this->hash = hash("md5", $path);
        $pinfo = pathinfo($path);
        $this->imgExtension = $pinfo["extension"];
        $this->nombre = $pinfo["filename"];
    }

    public function agregar() {
        $this->counter++;
    }

    public function quitar() {
        if ($this->counter > 0) $this->counter--;
    }

    public function __toString() {
        return "hash:[" . $this->hash . "], path:[" . $this->path . "], counter:[" . $this->counter . "]";
    }

}

?>
