<?php

spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});


require_once "classes/DVD.php";
require_once "classes/Book.php";
require_once "classes/Furniture.php";

class ProductFactory {
    public static function createProduct($data) {
        // Здесь создаем объект продукта в зависимости от типа
        if ($data['productType'] === 'DVD') {
            return new DVD($data['sku'], $data['name'], $data['price'], $data['size']);
        } elseif ($data['productType'] === 'Book') {
            return new Book($data['sku'], $data['name'], $data['price'], $data['weight']);
        } elseif ($data['productType'] === 'Furniture') {
            return new Furniture($data['sku'], $data['name'], $data['price'], "{$data['height']}x{$data['width']}x{$data['length']}");
        }

        return null; // Если тип неизвестен
    }
}
?>

