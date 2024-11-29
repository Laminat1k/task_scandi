<?php
require_once "classes/AbstractProduct.php";

class Book extends AbstractProduct
{
    private $weight;

    public function __construct($sku, $name, $price, $weight)
{
    parent::__construct($sku, $name, $price, 'Book');

    if (!is_numeric($weight) || $weight <= 0) {
        throw new Exception("Weight must be a positive number.");
    }

    $this->weight = $weight;
}

    public function save($conn)
    {
        $query = "INSERT INTO products (sku, name, price, type, weight_kg) 
                  VALUES (:sku, :name, :price, :type, :weight)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':sku', $this->sku);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':weight', $this->weight);

        if (!$stmt->execute()) {
            throw new Exception("Error saving Book: " . implode(", ", $stmt->errorInfo()));
        }
    }
}

?>
