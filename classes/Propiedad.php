<?php
namespace App;

class Propiedad extends ActiveRecord {
   
    protected static $tabla = 'propiedades';
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedorId;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedorId = $args['vendedorId'] ?? '';
    }
  
    public function validar() {
        if(!$this->titulo) {
            self::$errores[]  = "Debes rellenar este campo";
        }

        if(!$this->precio) {
            self::$errores[]  = "Debes rellenar este campo";
        }

        if(strlen($this->descripcion) < 50) {
            self::$errores[]  = "La descripción debe tener mínimo 50 caracteres";
        }

        if(!$this->habitaciones) {
            self::$errores[]  = "Debes rellenar este campo";
        }

        if(!$this->wc) {
            self::$errores[]  = "Debes rellenar este campo";
        }

        if(!$this->estacionamiento) {
            self::$errores[]  = "Debes rellenar este campo";
        }

        if(!$this->vendedorId) {
            self::$errores[]  = "Debes rellenar este campo";
        }

        if(!$this->imagen) {
            self::$errores[]  = "Debes cargar una imagen";
        }

        return self::$errores;
    }

}