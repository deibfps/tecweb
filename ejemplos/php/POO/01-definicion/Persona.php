<?php
class Persona {
    private $nombre;

    public function inicializar($name) {
        $this->nombre = $name;
    }

    public funtion mostrar() {
        echo '<p>'.$this->nombre.'</p>';
    }
}
?>