<?php
    require '../../includes/app.php';
    use App\Vendedor;

    estaAutenticado();

    $vendedor = new Vendedor;

    $errores = Vendedor::getErrores();

    if($_SERVER['REQUEST_METHOD'] === 'POST' ) {

    }

    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Registrar nuevo vendedor</h1>
        <a href="/admin" class="boton boton-verde">Regresar</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                    <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form action="/admin/vendedor/crear.php" method="POST" class="formulario" enctype="multipart/form-data">
            <?php include '../../includes/templates/formulario_vendedores.php'; ?>
            <input type="submit" value="Registrar vendedor" class="boton boton-verde">
        </form>
    </main>