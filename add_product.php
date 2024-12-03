<?php

require_once "classes/Database.php";
require_once "classes/ProductFactory.php";

// Функция валидации данных
function validateInput($data, $conn) {
    $errors = [];

    // Проверка поля SKU
    if (empty($data['sku'])) {
        $errors['sku'] = "SKU is required.";
    } else {
        // Проверка на уникальность SKU
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE sku = :sku");
        $stmt->execute(['sku' => $data['sku']]);
        if ($stmt->fetchColumn() > 0) {
            $errors['sku'] = "SKU must be unique.";
        }
    }

    // Проверка поля Name
    if (empty($data['name'])) {
        $errors['name'] = "Name is required.";
    }

    // Проверка поля Price
    if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
        $errors['price'] = "Price must be a positive number.";
    }

    // Проверка для DVD
    if ($data['productType'] === 'DVD' && 
        (!isset($data['size']) || !is_numeric($data['size']) || $data['size'] <= 0)) {
        $errors['size'] = "Size must be a positive number.";
    }

    // Проверка для Book
    if ($data['productType'] === 'Book' && 
        (!isset($data['weight']) || !is_numeric($data['weight']) || $data['weight'] <= 0)) {
        $errors['weight'] = "Weight must be a positive number.";
    }

    // Проверка для Furniture
    if ($data['productType'] === 'Furniture') {
        $dimensions = ['height', 'width', 'length'];
        foreach ($dimensions as $dimension) {
            if (!isset($data[$dimension]) || !is_numeric($data[$dimension]) || $data[$dimension] <= 0) {
                $errors[$dimension] = ucfirst($dimension) . " must be a positive number.";
            }
        }
    }

    return $errors;
}

$errors = [];
$formData = $_POST;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $conn = $database->getConnection();

    // Валидация данных формы
    $errors = validateInput($_POST, $conn);

    if (empty($errors)) {
        // Создание продукта с помощью фабрики
        $product = ProductFactory::createProduct($_POST);

        if ($product->save($conn)) {
            // Редирект на главную страницу после успешного добавления
            header("Location: index.php");
            exit; // Завершаем выполнение скрипта после редиректа
        } else {
            header("Location: index.php");
            // Логирование ошибки при сохранении
           
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
    <link rel="stylesheet" href="assets/style3.css">
    <script src="assets/script.js" defer></script>
</head>

<body>
    <header>
        <h1>Add Product</h1>
    </header>

    <main>
        <form id="product_form" method="POST" action="add_product.php">
            <label for="sku">SKU</label>
            <input type="text" id="sku" name="sku" value="<?= htmlspecialchars($formData['sku'] ?? '') ?>" required>
            <div class="error"><?= $errors['sku'] ?? '' ?></div>

            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($formData['name'] ?? '') ?>" required>
            <div class="error"><?= $errors['name'] ?? '' ?></div>

            <label for="price">Price ($)</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($formData['price'] ?? '') ?>" required>
            <div class="error"><?= $errors['price'] ?? '' ?></div>

            <label for="productType">Type Switcher</label>
            <select id="productType" name="productType">
                <option value="" <?= empty($formData['productType']) ? 'selected' : '' ?>>Select type</option>
                <option value="DVD" <?= $formData['productType'] === 'DVD' ? 'selected' : '' ?>>DVD</option>
                <option value="Book" <?= $formData['productType'] === 'Book' ? 'selected' : '' ?>>Book</option>
                <option value="Furniture" <?= $formData['productType'] === 'Furniture' ? 'selected' : '' ?>>Furniture</option>
            </select>
            <div class="error"><?= $errors['productType'] ?? '' ?></div>

            <!-- Поля для разных типов продуктов -->
            <div id="type-dvd" class="type-specific-fields <?= $formData['productType'] === 'DVD' ? '' : 'hidden' ?>">
                <label for="size">Size (MB)</label>
                <input type="number" id="size" name="size" value="<?= htmlspecialchars($formData['size'] ?? '') ?>">
                <div class="error"><?= $errors['size'] ?? '' ?></div>
            </div>

            <div id="type-book" class="type-specific-fields <?= $formData['productType'] === 'Book' ? '' : 'hidden' ?>">
                <label for="weight">Weight (KG)</label>
                <input type="number" id="weight" name="weight" value="<?= htmlspecialchars($formData['weight'] ?? '') ?>">
                <div class="error"><?= $errors['weight'] ?? '' ?></div>
            </div>

            <div id="type-furniture" class="type-specific-fields <?= $formData['productType'] === 'Furniture' ? '' : 'hidden' ?>">
                <label for="height">Height (CM)</label>
                <input type="number" id="height" name="height" value="<?= htmlspecialchars($formData['height'] ?? '') ?>">
                <div class="error"><?= $errors['height'] ?? '' ?></div>

                <label for="width">Width (CM)</label>
                <input type="number" id="width" name="width" value="<?= htmlspecialchars($formData['width'] ?? '') ?>">
                <div class="error"><?= $errors['width'] ?? '' ?></div>

                <label for="length">Length (CM)</label>
                <input type="number" id="length" name="length" value="<?= htmlspecialchars($formData['length'] ?? '') ?>">
                <div class="error"><?= $errors['length'] ?? '' ?></div>
            </div>

            <button type="submit">Save</button>
            <a href="index.php" class="btn">Cancel</a>
        </form>
    </main>

    <footer>
        <p>Scandiweb Test Assignment</p>
    </footer>

</body>
</html>
