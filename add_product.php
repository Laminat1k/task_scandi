<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once "classes/Database.php";
require_once "classes/ProductFactory.php";

function validateInput($data) {
    $errors = [];

    if (empty($data['sku'])) {
        $errors[] = "SKU is required.";
    }

    if (empty($data['name'])) {
        $errors[] = "Name is required.";
    }

    if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
        $errors[] = "Price must be a positive number.";
    }

    if ($data['productType'] === 'DVD' && 
        (!isset($data['size']) || !is_numeric($data['size']) || $data['size'] <= 0)) {
        $errors[] = "Size must be a positive number.";
    }

    if ($data['productType'] === 'Book' && 
        (!isset($data['weight']) || !is_numeric($data['weight']) || $data['weight'] <= 0)) {
        $errors[] = "Weight must be a positive number.";
    }

    if ($data['productType'] === 'Furniture') {
        $dimensions = ['height', 'width', 'length'];
        foreach ($dimensions as $dimension) {
            if (!isset($data[$dimension]) || !is_numeric($data[$dimension]) || $data[$dimension] <= 0) {
                $errors[] = ucfirst($dimension) . " must be a positive number.";
            }
        }
    }

    return $errors;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Валидируем данные
    $errors = validateInput($_POST);

    if (!empty($errors)) {
        // Выводим ошибки
        echo "Validation errors:<br>";
        foreach ($errors as $error) {
            echo "- $error<br>";
        }
    } else {
        // Если ошибок нет, сохраняем данные
        $database = new Database();
        $conn = $database->getConnection();

        // Создаем продукт через фабрику
        $product = ProductFactory::createProduct($_POST);

        // Сохраняем продукт
        if ($product->save($conn)) {
            header("Location: index.php");
            exit;
        } else {
            header("Location: index.php");
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="assets/style3.css"> <!-- Подключите стили -->
</head>

<script src="assets/script.js" defer></script>

<body>
    <header>
        <h1>Add Product</h1>
    </header>
    <main>
        <form id="product_form" method="POST" action="add_product.php">
            <label for="sku">SKU</label>
            <input type="text" id="sku" name="sku" required>

            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="price">Price ($)</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="productType">Type Switcher</label>
            <select id="productType" name="productType" required>
                <option value="">Select type</option>
                <option value="DVD">DVD</option>
                <option value="Book">Book</option>
                <option value="Furniture">Furniture</option>
            </select>

            <!-- Поля, которые будут меняться динамически -->
            <div id="type-specific-fields">
                <!-- Динамические поля появятся здесь -->
            </div>

            <button type="submit">Save</button>
            <a href="index.php" class="btn">Cancel</a>
        </form>

    </main>
    
</body>
<footer>
        <p>Scandiweb Test Assignment</p>
    </footer>
</html>

