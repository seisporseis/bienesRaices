<?php

require  'funciones.php';

require 'config/database.php';

require __DIR__ . '/../vendor/autoload.php';

//conectarnos a db
$db = conectar DB();

use App\Propiedad;

Propiedad::setDB();

