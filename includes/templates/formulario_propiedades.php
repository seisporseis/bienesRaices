<fieldset>
    <legend>Info general</legend>

    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="propiedad[titulo]" value="<?php echo sane($propiedad->titulo); ?>" placeholder="Titulo para la propiedad">

    <label for="precio">Precio:</label>
    <input type="number" id="precio" name="propiedad[precio]" value="<?php echo sane($propiedad->precio); ?>" placeholder="Precio de la propiedad">

    <label for="imagen">Foto:</label>
    <input type="file" id="imagen" name="propiedad[imagen]" accept="image/jpeg, image/png">

    <?php if($propiedad->imagen) { ?>
        <img src="/imagenes/<?php echo $propiedad->imagen ?>" alt="small photo" class="imagen-small">
    <?php } ?>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion"  name="propiedad[descripcion]" placeholder="Describe la propiedad"><?php echo sane($propiedad->descripcion); ?></textarea>
</fieldset>
<fieldset>
    <legend>Info de la propiedad</legend>

    <label for="habitaciones">Número de habitaciones:</label>
    <input type="number" id="habitaciones" name="propiedad[habitaciones]" value="<?php echo sane($propiedad->habitaciones); ?>" placeholder="Ejemplo: 3" min="1" max="9">

    <label for="wc">Número de baños:</label>
    <input type="number" id="wc" name="propiedad[wc]" value="<?php echo sane($propiedad->wc); ?>" placeholder="Ejemplo: 1" min="1" max="9">

    <label for="estacionamiento">Plazas de estacionamiento:</label>
    <input type="number" id="estacionamiento" name="propiedad[estacionamiento]" value="<?php echo sane($propiedad->estacionamiento); ?>" placeholder="Ejemplo: 2" min="1" max="9">
</fieldset>
<fieldset>
    <legend>Vendedor de la propiedad</legend>

    <!-- <select name="vendedorId" id="vendedor">
        <option value="" >--Seleccione--</option>
        <?php while ($vendedor = mysqli_fetch_assoc($resultado) ): ?>
            <option 
            <?php echo $vendedorId === sane($propiedad->vendedor['id']) ? 'selected' : ''; ?> 
            value="<?php echo $vendedor['id']; ?>"> <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?>
            </option>
        <?php endwhile; ?>
    </select> -->
</fieldset>