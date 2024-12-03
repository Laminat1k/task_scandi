<?php
require_once "classes/AbstractProduct.php";

class Furniture extends AbstractProduct {
    private $dimensions;

    public function __construct($sku, $name, $price, $dimensions) {
        parent::__construct($sku, $name, $price, 'Furniture');
        $this->dimensions = $dimensions;
    }

    public function save($conn) {
        $query = "INSERT INTO products (sku, name, price, type, dimensions) 
                  VALUES (:sku, :name, :price, :type, :dimensions)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':sku', $this->sku);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':dimensions', $this->dimensions);
        $stmt->execute();
    }
}
?>
