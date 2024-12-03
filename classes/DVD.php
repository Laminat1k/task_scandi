<?php
require_once "classes/AbstractProduct.php";

class DVD extends AbstractProduct {
    private $size;

    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price, 'DVD');
        $this->size = $size;
    }

    public function save($conn) {
        $query = "INSERT INTO products (sku, name, price, type, size_mb) 
                  VALUES (:sku, :name, :price, :type, :size)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':sku', $this->sku);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':size', $this->size);
        $stmt->execute();
    }
}
?>
