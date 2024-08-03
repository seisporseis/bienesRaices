<?php
    require '../../includes/app.php';
    use App\Propiedad;
    use Intervention\Image\ImageManagerStatic as Image;

    estaAutenticado();

    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        header('Location: /admin');
    }

    // Obtener los datos de la propiedad
    $propiedad = Propiedad::find($id);

    //consulta sql para obtener vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    //validacion de campos
    $errores = Propiedad::getErrores();

    //ejecuta codigo despues de envio de formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST' ) {
        //Asignar atributos
        $args = $_POST['propiedad'];
        
        $propiedad->sincronizar($args);

        //Validación
        $errores = $propiedad->validar();

        //Subida de archivos
        //generar nombre unico
        $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

        if($_FILES['propiedad']['tmp_name']['imagen']) {
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }

        //revisar que array de errores esté vacio
        if(empty($errores)) {
             // Almacenar la imagen
             if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image->save(CARPETA_IMAGENES . $nombreImagen);
            }
            
            $propiedad->guardar();
        }
    }
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Create</h1>
        <a href="/admin" class="boton boton-verde">Regresar</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                 <?php echo $error; ?>
            </div>
           
        <?php endforeach; ?>
        <form method="POST" class="formulario" enctype="multipart/form-data">
            <?php include '../../includes/templates/formulario_propiedades.php'; ?>
            <input type="submit" value="Actualizar datos" class="boton boton-verde">
        </form>

    </main>

<?php
    incluirTemplate('footer');
?>