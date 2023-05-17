<?php

namespace App\Tablas;

use PDO;

class Articulo extends Modelo
{
    protected static string $tabla = 'articulos';

    public $id;
    private $codigo;
    private $descripcion;
    private $precio;
    private $stock;
    private $descuento;

    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->codigo = $campos['codigo'];
        $this->descripcion = $campos['descripcion'];
        $this->precio = $campos['precio'];
        $this->stock = $campos['stock'];
        $this->descuento = $campos['descuento'];
    }

    public static function existe(int $id, ?PDO $pdo = null): bool
    {
        return static::obtener($id, $pdo) !== null;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function getDescuento()
    {
        return $this->descuento;
    }

     //Insertar artículo.
     public static function insertar($codigo, $descripcion, $precio, $descuento, $stock, ?PDO $pdo = null)
     {
         $pdo = $pdo ?? conectar();
 
         $sent = $pdo->prepare('INSERT INTO articulos (codigo, descripcion, precio, descuento, stock)
                                     VALUES (:codigo, :descripcion, :precio, :descuento, :stock)');
         $sent->execute([':codigo' => $codigo, ':descripcion' => $descripcion, ':precio' => $precio, ':descuento' => $descuento, ':stock' => $stock]);
     }

     //Modificar artículo.
    public static function modificar($id, $codigo, $descripcion, $precio, $descuento, $stock, ?PDO $pdo = null)
    {
        $pdo = $pdo ?? conectar();

        $sent = $pdo->prepare("UPDATE articulos
                                  SET codigo = :codigo, descripcion = :descripcion, precio = :precio, descuento = :descuento, stock = :stock
                                WHERE id = :id");
        $sent->execute([':id' => $id, ':codigo' => $codigo, ':descripcion' => $descripcion, ':precio' => $precio, ':descuento' => $descuento, ':stock' => $stock]);
    }
}
