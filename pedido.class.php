<?php

class Pedido {
    var $path;
    var $numero;
    var $cliente;
    var $sucursal;
    var $numeroMaquina;
    var $tamanoFotos;
    var $cantidadFotos;
    var $fecha;
    var $globalCounter;
    var $directorio;
    // no cargar fotos al menos que sea necesario (lazy loading)
    var $fotos = array();

    function __construct($path = "") {
        $this->setPath($path);
        $this->init();
    }

    private function init() {
        // Path es el directorio del pedido
        $exp = explode("-", $this->getPath());
        if (sizeof($exp) < 2) {
            error_log("No se pudo cargar el pedido en " . $this->getPath());
        }
        if (file_exists($this->getPath() . "/respaldo.json")) {
            $archjson = file_get_contents($this->getPath() . "/respaldo.json");
            $json = json_decode($archjson);

            $fotos = (array) $json->fotos;

            $this->setNumero($json->numero);
            $this->setSucursal($json->sucursal);
            $this->setMaquina($json->numeroMaquina);
            $this->setCliente($json->cliente);
            $this->setTamanoFotos($json->tamano);
            $this->setCantidadFotos($json->cantidadFotos);
            $this->setFecha($json->fecha);
            $this->setGlobalCounter($json->globalCounter);
            $this->setDirectorio($json->directorio);
            
        } else {
            error_log("No se encontro el archivo de datos del pedido " . $this->getPath());
        }
    }


    public function setPath($path) {
        $this->path = $path;
    }

    public function getPath() {
        return $this->path;
    }

    public function setMaquina($numeroMaquina) {
        $this->numeroMaquina = $numeroMaquina;
    }

    public function getMaquina() {
        return $this->numeroMaquina;
    }
     public function setDirectorio($directorio) {
        $this->directorio = $directorio;
    }

    public function getDirectorio() {
        return $this->directorio;
    }
    
    public function setCantidadFotos($cantidadFotos) {
        $this->cantidadFotos = $cantidadFotos;
    }

    public function getCantidadFotos() {
        return $this->cantidadFotos;
    }
    
     public function setGlobalCounter($globalCounter) {
        $this->globalCounter = $globalCounter;
    }

    public function getGlobalCounter() {
        return $this->globalCounter;
    }

    public function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function setFotos($fotos) {
        $this->fotos = $fotos;
    }

    public function getFotos() {
        return $this->fotos;
    }

    public function setNumero($numero) {
        $this->numero = $numero;

    }

    public function getNumero() {
        if (!isset($this->numero)) return "";
        return $this->numero;
    }
    
    public function setSucursal($sucursal) {
        $this->sucursal = $sucursal;
    }

    public function getSucursal() {
        return $this->sucursal;
    }

    public function setTamanoFotos($tamanoFotos) {
        $this->tamanoFotos = $tamanoFotos;
    }

    public function getTamanoFotos() {
        return $this->tamanoFotos;
    }
    
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function getFecha() {
        return $this->fecha;
    }
    
    function __toString() {
        return "numero:[" . $this->getNumero()
            . "], sucursal:[" . $this->getSucursal()
            . "], cliente:[" . $this->getCliente()
            . "], tamano:[" . $this->getTamanoFotos()
            . "], cant fotos:[" . $this->getCantidadFotos()
            . "]";
    }


}

?>