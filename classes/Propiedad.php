<?php
namespace App;

class Propiedad {

    //base de datos
    protected static $db;
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

    //Errores
    protected static $errores = [];


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
        $this->id = $args['id'] ?? '';
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

    //definir conexion a base de datos
    public static function setDB($database) {
        self::$db = $database;
    }

    public function guardar() {

        //sanitizar los datos
        $atributos = $this->sanitizarAtributos();


        //Insertar en la base de datos:
        $query = " INSERT INTO propiedades (";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";  
        $query .= join(" ', ' ", array_values($atributos));
        $query .= " ') ";
        // debuguear($query);

        $resultado = self::$db->query($query);

       return $resultado;
    }

    public function atributos() {
        $atributos = [];
        foreach(self::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    //Carga de archivos
    public function setImagen($imagen) {
        if($imagen) {
            $this->imagen = $imagen;
        }
    }

    //Validacion
    public static function getErrores() {
        return self::$errores;
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