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

    $sent = $pdo->prepare("SELECT art.*, com.*, usuarios.usuario 
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
                    <th scope="col" class="py-3 px-6 text-center">Acciones</th>
                </thead>
                <tbody>
                    <?php foreach ($sent as $fila) : ?>
                        <?php
                        //Separar fecha y hora.
                        $fecha = explode(' ', $fila['created_at']);
                        //Formateo a fecha europea.
                        $fecha_formateada = date("d-m-Y", strtotime($fecha[0]));
                        ?>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6"><?= $fila['descripcion'] ?></td>
                            <td class="py-4 px-6"><?= $fecha_formateada ?></td>
                            <td class="py-4 px-6"><?= $fila['comentario'] ? $fila['comentario'] : '' ?></td>
                            <!-- Acciones -->
                            <td class="px-6 text-center">
                                <!-- Editar -->
                                <?php $fila_id = hh($fila['id']) ?>
                                <a href="/admin/editar.php?id=<?= $fila_id ?>&codigo=<?= hh($fila['codigo']) ?>&descripcion=<?= hh($fila['descripcion']) ?>&precio=<?= hh($fila['precio']) ?>&descuento=<?= hh($fila['descuento']) ?>&stock=<?= hh($fila['stock']) ?>" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">
                                    Editar
                                </a>
                                <!-- Borrar -->
                                <form action="/comentarios_borrar.php" method="POST" class="inline">
                                    <input type="hidden" name="id_art" value="<?= $fila['articulo_id'] ?>">
                                    <input type="hidden" name="id_us" value="<?= $fila['usuario_id'] ?>">
                                    <input type="hidden" name="hora" value="<?= $fila['created_at'] ?>">

                                    <button type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>