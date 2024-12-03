<?php
// Подключение к базе данных
require_once "classes/Database.php";

// Создание объекта Database и получение соединения
$database = new Database();
$conn = $database->getConnection();

// Запрос для получения всех продуктов
$query = "SELECT * FROM products";
$stmt = $conn->prepare($query);
$stmt->execute();

// Извлечение всех записей
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Проверка наличия продуктов
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="assets/styles.css"> <!-- Укажите путь к CSS -->
</head>
<body>
<header>
<h1>Product List</h1>
<div class="action-buttons">
    <button id="add-product-btn" type="button" onclick="location.href='add_product.php'">ADD</button>
    <button id="delete-product-btn" type="button" onclick="checkSelection()">Mass Delete</button>
</div>
</header>

<main>
    <!-- Форма для массового удаления продуктов -->
    <form method="POST" action="classes/delete_products.php" id="product-list">
        <div class="product-container">
            <!-- Добавление чекбоксов для каждого продукта -->
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <input type="checkbox" class="delete-checkbox" name="delete[]" value="<?= htmlspecialchars($product['id']) ?>">
                        <p><strong>SKU:</strong> <?= htmlspecialchars($product['sku']) ?></p>
                        <p><strong>Name:</strong> <?= htmlspecialchars($product['name']) ?></p>
                        <p><strong>Price:</strong> $<?= htmlspecialchars($product['price']) ?></p>
                        <p><strong>Type:</strong> <?= htmlspecialchars($product['type']) ?></p>

                        <!-- Отображение дополнительной информации в зависимости от типа продукта -->
                        <?php if ($product['type'] == 'DVD'): ?>
                            <p><strong>Size (MB):</strong> <?= htmlspecialchars($product['size_mb']) ?> MB</p>
                        <?php elseif ($product['type'] == 'Book'): ?>
                            <p><strong>Weight (KG):</strong> <?= htmlspecialchars($product['weight_kg']) ?> KG</p>
                        <?php elseif ($product['type'] == 'Furniture'): ?>
                            <p><strong>Dimensions (HxWxL):</strong> <?= htmlspecialchars($product['dimensions']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </form>
</main>

    <script>
function checkSelection() {
    // Получаем все чекбоксы
    const checkboxes = document.querySelectorAll('input[name="delete[]"]:checked');
    
    // Если чекбоксы не выбраны, показываем сообщение и не отправляем форму
    if (checkboxes.length === 0) {
        alert('Продукты не выбраны.');
    } else {
        // Если выбраны, отправляем форму
        document.getElementById('product-list').submit();
    }
}
</script>

</body>

<footer>
        <p>Scandiweb Test Assignment</p>
    </footer>
</html>

