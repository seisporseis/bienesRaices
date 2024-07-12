<?php
    //DB
    require '../../includes/config/database.php';
    $db = conectarDB();

    //consulta sql para obtener vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    //validacion de campos
    $errores = [];

    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones = '';
    $wc = '';
    $estacionamiento = '';
    $vendedorId = '';

    //ejecuta codigo despues de envio de formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST' ) {

        // echo '<pre>';
        // var_dump($_POST);
        // echo '</pre>';

        echo '<pre>';
        var_dump($_FILES);
        echo '</pre>';

        $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
        $precio = mysqli_real_escape_string( $db, $_POST['precio'] );
        $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion'] );
        $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones'] );
        $wc = mysqli_real_escape_string( $db, $_POST['wc'] );
        $estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento'] );
        $vendedorId = mysqli_real_escape_string( $db, $_POST['vendedor'] );
        $creado = date('Y/m/d');

        $imagen = $_FILES['imagen'];
    
        if(!$titulo) {
            $errores[]  = "Debes rellenar este campo";
        }

        if(!$precio) {
            $errores[]  = "Debes rellenar este campo";
        }

        if(strlen($descripcion) < 50) {
            $errores[]  = "La descripción debe tener mínimo 50 caracteres";
        }

        if(!$habitaciones) {
            $errores[]  = "Debes rellenar este campo";
        }

        if(!$wc) {
            $errores[]  = "Debes rellenar este campo";
        }

        if(!$estacionamiento) {
            $errores[]  = "Debes rellenar este campo";
        }

        if(!$vendedorId) {
            $errores[]  = "Debes rellenar este campo";
        }

        if(!$imagen['name']) {
            $errores[]  = "Debes cargar una imagen";
        }

        //validar tamaño de imagen (100kb max)
        $medida = 1000 * 1000;

        if($imagen['size'] > $medida) {
            $errores[]  = "Debes cargar una imagen";
        }

        //revisar que array de errores esté vacio
        if(empty($errores)) {
            //*carga de archivos al servidor*//

            //crear carpeta para imagenes
            $carpetaImagenes = '../../imagenes/';

            if(!is_dir($carpetaImagenes)) {
                mkdir($carpetaImagenes);
            }

            //generar nombre unico
            $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

            //subir imagen
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen );


            //Insertar en la base de datos:
            $query = " INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId) VALUES ( '$titulo', '$precio', '$nombreImagen', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedorId' ) ";

            $resultado = mysqli_query($db, $query);

            if($resultado) {
                //redireccionar usuario: para no meter datos duplicados
                header("Location: /admin?resultado=1");

                // echo "Correctamente insertado";
            }
        }
        
        // echo $query;
    }
   
    require '../../includes/funciones.php';
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

                <select name="vendedor" id="vendedor">
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