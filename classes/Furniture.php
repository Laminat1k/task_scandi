<?php
require_once "classes/AbstractProduct.php";

class Furniture extends AbstractProduct
{
    private $dimensions;

    public function __construct($sku, $name, $price, $dimensions)
{
    parent::__construct($sku, $name, $price, 'Furniture');

    $parts = explode('x', $dimensions);
    if (count($parts) !== 3 || !array_reduce($parts, fn($carry, $value) => $carry && is_numeric($value) && $value > 0, true)) {
        throw new Exception("Dimensions must be positive numbers in the format HeightxWidthxLength.");
    }

    $this->dimensions = $dimensions;
}


    public function save($conn)
    {
        $query = "INSERT INTO products (sku, name, price, type, dimensions) 
                  VALUES (:sku, :name, :price, :type, :dimensions)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':sku', $this->sku);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':dimensions', $this->dimensions);

        if (!$stmt->execute()) {
            throw new Exception("Error saving Furniture: " . implode(", ", $stmt->errorInfo()));
        }
    }
}

?>
