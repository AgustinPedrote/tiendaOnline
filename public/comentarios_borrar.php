<?php
session_start();

require '../vendor/autoload.php';

$id_art = obtener_post('id_art');
$id_us = obtener_post('id_us');
$hora = obtener_post('hora');

// if (!comprobar_csrf()) {
//     return volver_admin();
// }

if (!isset($id_art, $id_us, $hora)) {
    return volver();
}

// TODO: Validar id
// Comprobar si el departamento tiene empleados

$pdo = conectar();
$sent = $pdo->prepare("DELETE FROM comentarios WHERE articulo_id = :articulo_id AND usuario_id = :usuario_id AND created_at = :created_at");
$sent->execute([':articulo_id' => $id_art, ':usuario_id' => $id_us, ':created_at' => $hora]);

$_SESSION['exito'] = 'El comentario se ha borrado correctamente.';

volver_comentarios();
