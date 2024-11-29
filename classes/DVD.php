<?php
require_once "classes/AbstractProduct.php";

class DVD extends AbstractProduct
{
    private $size;

    public function __construct($sku, $name, $price, $size)
{
    parent::__construct($sku, $name, $price, 'DVD');

    if (!is_numeric($size) || $size <= 0) {
        throw new Exception("Size must be a positive number.");
    }

    $this->size = $size;
}


    public function save($conn)
    {
        $query = "INSERT INTO products (sku, name, price, type, size_mb) 
                  VALUES (:sku, :name, :price, :type, :size)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':sku', $this->sku);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':size', $this->size);

        if (!$stmt->execute()) {
            throw new Exception("Error saving DVD: " . implode(", ", $stmt->errorInfo()));
        }
    }
}

?>
