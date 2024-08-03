<?php
    require '../includes/app.php';
    estaAutenticado();

    use App\Propiedad;

    //escribir el query
    $query = "SELECT * FROM propiedades";

    //consultar DB
    $resultadoConsulta = mysqli_query($db, $query);

    //implementar método para obtener todas las propiedades
    $propiedades = Propiedad::all();

    //muestra mensaje condicional
    $resultado = $_GET['resultado'] ?? null;

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $id = filter_var($id,FILTER_VALIDATE_INT);

        // if($id) {
        //     //eliminar el archivo
        //     $query = "SELECT imagen FROM propiedades WHERE id = {$id}";

        //     $resultado = mysqli_query($db, $query);
        //     $propiedad = mysqli_fetch_assoc($resultado);

        //     unlink('../imagenes/' . $propiedad['imagen']);

        // }
    }

    //incluye un template
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Admin de proyecto</h1>
        <?php if(intval($resultado) === 1): ?>
            <p class="alerta exito">Anuncio creado correctamente</p>
        <?php elseif(intval($resultado) === 2): ?>
            <p class="alerta exito">Anuncio actualizado correctamente</p>
        <?php elseif(intval($resultado) === 3): ?>
            <p class="alerta exito">Anuncio eliminado correctamente</p>
        <?php endif; ?>

        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Agregar nueva propiedad</a>

        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody> <!-- Mostrar los Resultados -->
                <?php foreach( $propiedades as $propiedad ): ?>
                <tr>
                    <td><?php echo $propiedad->id; ?></td>
                    <td><?php echo $propiedad->titulo; ?></td>
                    <td> <img src="/imagenes/<?php echo $propiedad->imagen; ?>" class="imagen-tabla"> </td>
                    <td>$ <?php echo $propiedad->precio; ?>></td>
                    <td>
                        <form method="POST" class="w-100" action="">
                            <input type="hidden" name="id" value="<?php echo $propiedad->id; ?>">    
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>

                    <a class="boton-amarillo-block" href="admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>">Actualizar</a> 
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

<?php

    //cerrar conexion (opcional)
    mysqli_close($db);

    incluirTemplate('footer');
?>