<?php

namespace App;

class ActiveRecord {
    //base de datos
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    //Errores
    protected static $errores = [];
  
    //definir conexion a base de datos
    public static function setDB($database) {
        self::$db = $database;
    }

    //Validacion
    public static function getErrores() {
        return static::$errores;
    }

    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

    //CRUD 
    public function guardar() {
        if(!is_null($this->id)) {
            //actualizar
            $this->actualizar();
        } else {
            //si no hay registro, crea uno nuevo.
            $this->crear();
        }
    }

    //Lista todas las registros
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    public static function get($cantidad) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    //Buscar un registro por su ID
    public static function find($id)  {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";

        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }
  
    public function crear() {
        //sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        //Insertar en la base de datos:
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";  
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        $resultado = self::$db->query($query);

        //mensaje de exito o error
        if($resultado) {
            //redireccionar usuario: para no meter datos duplicados
            header('Location: /admin?resultado=1');
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
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);  
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        $resultado = self::$db->query($query);

        if($resultado) {
            //redireccionar usuario: para no meter datos duplicados
            header('Location: /admin?resultado=2');
        }
    }

    public function eliminar() {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado) {
            $this->borrarImagen();
            header('Location: /admin?resultado=3');
        }
    }

    public static function consultarSQL($query) {
        //Consultar DB
        $resultado = self::$db->query($query);

        //Iterar resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        //Liberar memoria
        $resultado->free();

        //Retornar resultados
        return $array;
    }

    public static function crearObjeto($registro) {
        $objeto = new static;
        
        foreach($registro as $key => $value) {
            if(property_exists( $objeto, $key )) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
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

     //Sincroniza el objeto en memoria con los cambios realizados por el usuario
     public function sincronizar($args = []) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key ) && !is_null($value)) {
                $this->$key = $value;
            }
        }
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
}