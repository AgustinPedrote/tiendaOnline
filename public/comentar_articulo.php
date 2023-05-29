<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/output.css" rel="stylesheet">
    <title>Comentar artículo</title>
</head>

<body>

    <?php
    require '../vendor/autoload.php';
    require '../src/_alerts.php';

    if (!(\App\Tablas\Usuario::esta_logueado())) {
        return redirigir_login();
    }

    //Desde index
    $articulo_id = obtener_get('id');
    $usuario_id = obtener_get('usuario');

    $comentario = obtener_post('comentario');

    $pdo = conectar();

    if (isset($comentario) && $comentario != null or $comentario != '') {
        // Verificar si el usuario ya ha comentado en la tabla de comentarios
        $sent = $pdo->prepare("SELECT * FROM comentarios WHERE usuario_id = :usuario_id AND articulo_id = :articulo_id");
        $sent->execute(['usuario_id' => $usuario_id, 'articulo_id' => $articulo_id]);

        $sent = $pdo->prepare("INSERT INTO comentarios (comentario, usuario_id, articulo_id) VALUES (:comentario, :usuario_id, :articulo_id)");
        $sent->execute(['comentario' => $comentario, 'usuario_id' => $usuario_id, 'articulo_id' => $articulo_id]);

        $_SESSION['exito'] = 'El comentario se ha creado correctamente.';
        volver();
    }
    ?>

    <div class="container mx-auto">
        <?php
        require '../src/_menu.php';
        ?>

        <form class="mt-10 mr-96 ml-96" action="" method="POST">
            <div class="mb-6">
                <label for="comentario" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">
                    Comentar artículo:
                </label>

                <textarea rows="4" cols="50" name="comentario" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </textarea>
            </div>

            <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Enviar
            </button>
        </form>
    </div>

    <script src="../js/flowbite/flowbite.js"></script>

</body>

</html>