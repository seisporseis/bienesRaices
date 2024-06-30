<?php

function conectarDB() : mysqli {

    $db = mysqli_connect('localhost', 'root', '', 'bienesraices_crud');

    if(!$db) {
        echo "no se ha podido conectar a la base de datos";
    exit;
    }

    return $db;
}