<?php

use App\Tablas\Articulo;

session_start();

require '../vendor/autoload.php';

try {
    $id = obtener_get('id');
    $articulo = Articulo::obtener($id);
    $stock = $articulo->getStock();
    $carrito = unserialize(carrito());
    $lineas = $carrito->getLineas();

    if ($id === null) {
        return volver();
    }

    if ($articulo === null) {
        return volver();
    }

    // Impide insertar en el carrito más artículos del número de stock
    $cantidad = empty($lineas) || !isset($lineas[$id]) ? 0 : $lineas[$id]->getCantidad();

    if ($stock > $cantidad) {
        $carrito->insertar($id);
    } else {
        $_SESSION['error'] = 'No hay existencias suficientes.';
    }

    $_SESSION['carrito'] = serialize($carrito);

} catch (ValueError $e) {
    // TODO: mostrar mensaje de error en un Alert

    /* Catch (\Throwable $th) captura cualquier tipo de excepción, 
    incluyendo ValueError, mientras que catch (ValueError $e) captura solo 
    excepciones específicas de tipo ValueError y sus subclases. */
}

volver();
