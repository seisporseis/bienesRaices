<?php
    //importar base de datos
    require '../includes/config/database.php';
    $db = conectarDB();

    //escribir el query
    $query = "SELECT * FROM propiedades";

    //consultar DB
    $resultadoConsulta = mysqli_query($db, $query);

    //muestra mensaje condicional
    $resultado = $_GET['resultado'] ?? null;

    var_dump($resultado);

    //incluye un template
    require '../includes/funciones.php';
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Admin de proyecto</h1>
        <?php if(intval($resultado) === 1): ?>
            <p class="alerta exito">Anuncio creado correctamente</p>
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
                <?php while($propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
                <tr>
                    <td><?php echo $propiedad['id']; ?></td>
                    <td><?php echo $propiedad['titulo']; ?></td>
                    <td> <img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla"> </td>
                    <td>$ <?php echo $propiedad['precio']; ?>></td>
                    <td>                      
                       <a class="boton-rojo-block" href="">Eliminar</a>
                       <a class="boton-amarillo-block" href="">Actualizar</a>
                    </td>
                </tr>
                <?php endwhile; ?>

            </tbody>
        </table>
    </main>

<?php

    //cerrar conexion (opcional)
    mysqli_close($db);

    incluirTemplate('footer');
?>