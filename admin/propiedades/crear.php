<?php
    require '../../includes/app.php';
    use App\Propiedad;
    use App\Vendedor;

    use Intervention\Image\ImageManagerStatic as Image;

    estaAutenticado();

    $propiedad = new Propiedad;

    $vendedores = Vendedor::all();

    //validacion de campos
    $errores = Propiedad::getErrores();

    //ejecuta codigo despues de envio de formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST' ) {
        /**Crear nueva instancia**/
        $propiedad = new Propiedad($_POST['propiedad']);
        
        //**carga de archivos al servidor**//
        //generar nombre unico
        $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

        //setear imagen
        //realiza resize a imagen con intervention
        if($_FILES['propiedad']['tmp_name']['imagen']) {
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }

        //validar
        $errores = $propiedad->validar();

        //revisar que array de errores esté vacio
        if(empty($errores)) {

            //Crear la carpeta para subir imagenes
            if(!is_dir(CARPETA_IMAGENES)) {
                mkdir(CARPETA_IMAGENES);
            }

            //guarda imagen en servidor
            $image->save(CARPETA_IMAGENES . $nombreImagen);

            //guarda en la base de datos
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

        <form action="/admin/propiedades/crear.php" method="POST" class="formulario" enctype="multipart/form-data">
            <?php include '../../includes/templates/formulario_propiedades.php'; ?>
            <input type="submit" value="Crear nueva propiedad" class="boton boton-verde">
        </form>

    </main>

<?php
    incluirTemplate('footer');
?>