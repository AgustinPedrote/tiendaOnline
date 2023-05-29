<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Comentarios usuario</title>
</head>

<body>
    <?php
    require '../vendor/autoload.php';

    if (!(\App\Tablas\Usuario::esta_logueado())) {
        return redirigir_login();
    }

    $usuario = (\App\Tablas\Usuario::logueado()->usuario);

    $pdo = conectar();

    $sent = $pdo->prepare("SELECT art.*, usuarios.usuario, com.comentario, com.created_at 
                           FROM articulos art 
                           LEFT JOIN comentarios com ON (art.id = com.articulo_id) 
                           JOIN usuarios ON (usuarios.id = com.usuario_id)
                           WHERE usuarios.usuario = :usuario 
                           ORDER BY com.created_at DESC");

    $sent->execute([':usuario' => $usuario]);
    ?>

    <div class="container mx-auto">
        <?php
        require '../src/_menu.php';
        require '../src/_alerts.php';
        ?>

        <div class="overflow-x-auto relative mt-4">
            <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th scope="col" class="py-3 px-6">Artículo</th>
                    <th scope="col" class="py-3 px-6">Fecha</th>
                    <th scope="col" class="py-3 px-6">Comentario</th>
                </thead>
                <tbody>
                    <?php foreach ($sent as $fila) : ?>                   
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6"><?= $fila['descripcion'] ?></td>
                            <td class="py-4 px-6"><?= $fila['created_at']  ?></td>
                            <td class="py-4 px-6"><?= $fila['comentario'] ? $fila['comentario'] : '' ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>