<?php
    require 'includes/app.php';
    $db = conectarDB();

$errores = [];

    //autenticar usuario
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // echo '<pre>';
        // var_dump($_POST);
        // echo '</pre>';

        $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if(!$email) {
            $errores[] = "El email es obligatorio o no es válido";
        }

        if(!$password) {
            $errores[] = "El password es obligatorio o no es correcto";
        }

        if(empty($errores)) {
            //revisar si el usuario existe
            $query = "SELECT * FROM usuarios WHERE  email = '{$email}' ";
            $resultado = mysqli_query($db, $query);
            // var_dump($resultado);

            if($resultado->num_rows) {
                //revisar si el password es correcto
                $usuario = mysqli_fetch_assoc($resultado);
                
                //verificar password
                $auth = password_verify($password, $usuario['password']);

                if($auth) {
                    //el usuario está autenticado
                    session_start();

                    //Llenar el array de la sesión
                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;

                    header('Location: /admin');

                } else {
                    $errores[] = "El password no es correcto";
                }

            } else {
                $errores[] = "El usuario no existe";
            }
        }
    }

    incluirTemplate('header');

?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar sesión</h1>

    <?php foreach($errores as $error): ?> 
        <div class="alerta error">
                 <?php echo $error; ?>
            </div>
    <?php endforeach ?>
    <form method="POST" action="" class="formulario">
        <fieldset>
            <legend>Introduce tu email y contraseña</legend>

            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" placeholder="Tu email">

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Tu password">
        </fieldset>

        <input type="submit" value="Iniciar sesión" class="boton boton-verde">

    </form>
</main>

<?php
incluirTemplate('footer');

?>