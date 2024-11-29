<?php

abstract class AbstractProduct
{
    protected $sku;
    protected $name;
    protected $price;
    protected $type;

    public function __construct($sku, $name, $price, $type)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
    }

    abstract public function save($conn);

    public static function create($data)
    {
        switch ($data['productType']) {
            case 'DVD':
                return new DVD($data['sku'], $data['name'], $data['price'], $data['size']);
            case 'Book':
                return new Book($data['sku'], $data['name'], $data['price'], $data['weight']);
            case 'Furniture':
                return new Furniture($data['sku'], $data['name'], $data['price'], $data['dimensions']);
            default:
                throw new Exception("Invalid product type");
        }
    }
}

?>
