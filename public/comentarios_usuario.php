<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Comentarios usuario</title>
    <script>
        function cambiar(el, id) {
            el.preventDefault();
            const oculto = document.getElementById('oculto');
            oculto.setAttribute('value', id);
        }
    </script>
</head>

<body>
    <?php
    require '../vendor/autoload.php';

    if (!(\App\Tablas\Usuario::esta_logueado())) {
        return redirigir_login();
    }

    $usuario = (\App\Tablas\Usuario::logueado()->usuario);

    $pdo = conectar();

    $sent = $pdo->prepare("SELECT art.*, usuarios.usuario, com.id AS id_com, com.comentario, com.created_at 
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
                                <?php $fila_id = hh($fila['id']) ?>
                                <a href="/admin/editar.php?id=<?= $fila_id ?>&codigo=<?= hh($fila['codigo']) ?>&descripcion=<?= hh($fila['descripcion']) ?>&precio=<?= hh($fila['precio']) ?>&descuento=<?= hh($fila['descuento']) ?>&stock=<?= hh($fila['stock']) ?>" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">
                                    Editar
                                </a>
                                <form action="/comentarios_borrar.php" method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?= $fila['id_com'] ?>">
                                    <button type="submit" onclick="cambiar(event, <?= $fila['id_com'] ?>)" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" data-modal-toggle="popup-modal">
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

    <!-- Ventana emergente para borrar. -->
    <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="popup-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Cerrar ventana</span>
                </button>
                <div class="p-6 text-center">
                    <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">¿Seguro que desea borrar este comentario?</h3>
                    <form action="/comentarios_borrar.php" method="POST">
                        <input id="oculto" type="hidden" name="id">
                        <button data-modal-toggle="popup-modal" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Sí, seguro
                        </button>
                        <button data-modal-toggle="popup-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>