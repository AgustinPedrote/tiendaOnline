<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Perfil de usuario</title>
</head>

<body>
    <?php
    require '../vendor/autoload.php';

    if (!(\App\Tablas\Usuario::esta_logueado())) {
        return redirigir_login();
    }

    $pdo = conectar();

    $id = \App\Tablas\Usuario::logueado()->id;

    $sent = $pdo->query("SELECT * FROM usuarios WHERE  id = $id");
    ?>

    <div class="container mx-auto">

        <?php
        require '../src/_menu.php';
        require '../src/_alerts.php';
        ?>

        <div class="overflow-x-auto relative mt-10">
            <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                <thead>
                    <td>
                        <a href="dashboard.php">
                            <button class="mb-2 focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900">
                                Facturas
                            </button>
                        </a>
                    </td>
                    <td>
                        <a href="comentarios.php">
                            <button class="mb-2 focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900">
                                Comentarios
                            </button>
                        </a>
                    </td>
                    <td colspan="4"></td>
                </thead>
                <tbody class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th scope="col" class="py-3 px-6">Usuario</th>
                    <th scope="col" class="py-3 px-6">Nombre</th>
                    <th scope="col" class="py-3 px-6">Apellidos</th>
                    <th scope="col" class="py-3 px-6">Email</th>
                    <th scope="col" class="py-3 px-6">Teléfono</th>
                    <th scope="col" class="py-3 px-6 text-center">Acciones</th>
                </tbody>
                <tfoot>
                    <?php foreach ($sent as $fila) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6"><?= hh($fila['usuario']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['nombre']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['apellidos']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['email']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['telefono']) ?></td>
                            <td class="px-6 text-center">
                                <a href="datos_perfil.php">
                                    <button class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">
                                        Datos perfil
                                    </button>
                                </a>
                                <a href="editar_password.php">
                                    <button class="mb-2 focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">
                                        Cambiar contraseña
                                    </button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tfoot>
            </table>
        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>