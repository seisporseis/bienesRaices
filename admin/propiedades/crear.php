<?php
    require '../../includes/funciones.php';
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Create</h1>
        <a href="/admin" class="boton boton-verde">Regresar</a>

        <form action="" class="formulario">
            <fieldset>
                <legend>Info general</legend>

                <label for="titulo">Título:</label>
                <input type="text" id="titulo" placeholder="Titulo para la propiedad">

                <label for="precio">Precio:</label>
                <input type="number" id="precio" placeholder="Precio de la propiedad">

                <label for="imagen">Foto:</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png">

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" placeholder="Describe la propiedad"></textarea>

            </fieldset>
            <fieldset>
                <legend>Info de la propiedad</legend>

                <label for="habitaciones">Número de habitaciones:</label>
                <input type="number" id="habitaciones" placeholder="Ejemplo: 3" min="1" max="9">

                <label for="wc">Número de baños:</label>
                <input type="number" id="wc" placeholder="Ejemplo: 1" min="1" max="9">

                <label for="estacionamiento">Plazas de estacionamiento:</label>
                <input type="number" id="estacionamiento" placeholder="Ejemplo: 2" min="1" max="9">
            </fieldset>
            <fieldset>
                <legend>Vendedor de la propiedad</legend>

                <select name="" id="">
                    <option value="1">Juan</option>
                    <option value="2">Karen</option>
                </select>
            </fieldset>
            <input type="submit" value="Crear nueva propiedad" class="boton boton-verde">
        </form>

    </main>

<?php
    incluirTemplate('footer');
?>