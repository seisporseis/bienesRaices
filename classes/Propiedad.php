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
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedorId = $args['vendedorId'] ?? 1;
    }

    //definir conexion a base de datos
    public static function setDB($database) {
        self::$db = $database;
    }

    public function guardar() {
        if(!is_null($this->id)) {
            //actualizar
            $this->actualizar();
        } else {
            //si no hay registro, crea uno nuevo.
            $this->crear();
        }
    }

    public function crear() {
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

       //mensaje de exito o error
       if($resultado) {
        //redireccionar usuario: para no meter datos duplicados
        header("Location: /admin?resultado=1");
        }
    }

    public function actualizar() {
        //sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key} = '{$value}'";
        }

        //insertar en base de datos
        $query = "UPDATE propiedades SET ";
        $query .= join(', ', $valores);  
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        $resultado = self::$db->query($query);

        if($resultado) {
            //redireccionar usuario: para no meter datos duplicados
            header("Location: /admin?resultado=2");
        }
    }

    public function eliminar() {
        $query = "DELETE FROM propiedades WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1 ";

        $resultado = self::$db->query($query);

        if($resultado) {
            $this->borrarImagen();
            header("Location: /admin?resultado=3");
        }
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
        //Elimina imagen previa
        if(!is_null($this->id)) {
           $this->borrarImagen();
        }

        //asignar al atributo de imagen el nombre de la imagen
        if($imagen) {
            $this->imagen = $imagen;
        }
    }

    public function borrarImagen() {
        //Comprueba si existe un archivo y lo elimina
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
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

    //Lista todas las registros
    public static function all() {
        $query = "SELECT * FROM propiedades";

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    //Buscar un registro por su ID
    public static function find($id)  {
        $query = "SELECT * FROM propiedades WHERE id = {$id}";

        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }

    public static function consultarSQL($query) {
        //Consultar DB
        $resultado = self::$db->query($query);

        //Iterar resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }

        //Liberar memoria
        $resultado->free();

        //Retornar resultados
        return $array;
    }

    public static function crearObjeto($registro) {
        $objeto = new self;
        
        foreach($registro as $key => $value) {
            if(property_exists( $objeto, $key )) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = []) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key ) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

}