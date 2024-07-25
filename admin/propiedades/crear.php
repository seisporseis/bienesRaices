<?php
    require '../../includes/app.php';
    use App\Propiedad;

    estaAutenticado();

    use Intervention\Image\ImageManagerStatic as Image;

    //DB
    $db = conectarDB();

    //consulta sql para obtener vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    //validacion de campos
    $errores = Propiedad::getErrores();

    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones = '';
    $wc = '';
    $estacionamiento = '';
    $vendedorId = '';

    //ejecuta codigo despues de envio de formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST' ) {

        $propiedad = new Propiedad($_POST);
        
        //**carga de archivos al servidor**//

        //generar nombre unico
        $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

        //setear imagen
        //realiza resize a imagen con intervention
        if($_FILES['imagen']['tmp_name']) {
            $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }

        //validar
        $errores = $propiedad->validar();

        //revisar que array de errores esté vacio
        if(empty($errores)) {
            //Crear la carpeta para subir imagenes
            if(!is_dir(CARPETA_IMAGENES)) {
                mkdir(CARPETAS_IMAGENES);
            }

            //guarda imagen en servidor
            $image->save(CARPETAS_IMAGENES . $nombreImagen);

            //guarda en la base de datos
            $resultado = $propiedad->guardar();

            //mensaje de exito o error
            if($resultado) {
                //redireccionar usuario: para no meter datos duplicados
                header("Location: /admin?resultado=1");

                // echo "Correctamente insertado";
            }
        }
        
        // echo $query;
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
            <fieldset>
                <legend>Info general</legend>

                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo $titulo; ?>" placeholder="Titulo para la propiedad">

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" value="<?php echo $precio; ?>" placeholder="Precio de la propiedad">

                <label for="imagen">Foto:</label>
                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">
                
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion"  name="descripcion" placeholder="Describe la propiedad"><?php echo $descripcion; ?></textarea>

            </fieldset>
            <fieldset>
                <legend>Info de la propiedad</legend>

                <label for="habitaciones">Número de habitaciones:</label>
                <input type="number" id="habitaciones" name="habitaciones" value="<?php echo $habitaciones; ?>" placeholder="Ejemplo: 3" min="1" max="9">

                <label for="wc">Número de baños:</label>
                <input type="number" id="wc" name="wc" value="<?php echo $wc; ?>" placeholder="Ejemplo: 1" min="1" max="9">

                <label for="estacionamiento">Plazas de estacionamiento:</label>
                <input type="number" id="estacionamiento" name="estacionamiento" value="<?php echo $estacionamiento; ?>" placeholder="Ejemplo: 2" min="1" max="9">
            </fieldset>
            <fieldset>
                <legend>Vendedor de la propiedad</legend>

                <select name="vendedorId" id="vendedor">
                    <option value="" >--Seleccione--</option>
                    <?php while ($vendedor = mysqli_fetch_assoc($resultado) ): ?>
                        <option 
                        <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> 
                        value="<?php echo $vendedor['id']; ?>"> <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?>
                        </option>
                    <?php endwhile; ?>

                   
                </select>
            </fieldset>
            <input type="submit" value="Crear nueva propiedad" class="boton boton-verde">
        </form>

    </main>

<?php
    incluirTemplate('footer');
?>