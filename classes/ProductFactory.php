<?php

require_once "DVD.php";
require_once "Book.php";
require_once "Furniture.php";

class ProductFactory
{
    public static function createProduct($data)
    {
        return AbstractProduct::create($data);
    }
}

?>
