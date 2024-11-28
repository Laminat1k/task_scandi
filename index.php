<?php
// Подключение к базе данных
require_once "Database.php";

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
    <link rel="stylesheet" href="styles.css"> <!-- Укажите путь к CSS -->
</head>
<body>
<header>
    <h1>Product List</h1>
    <div class="action-buttons">

        <a href="/other/add_product.php" class="btn primary-btn">Add</a>
        <button id="delete-product-btn" class="btn danger-btn" type="submit" form="product-list">Mass Delete</button>
        <input type="checkbox" id="select-all" /> Select All
    </div>
</header>


    <main>
        <!-- Форма для массового удаления продуктов -->
        <form method="POST" action="delete_products.php" id="product-list">
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


    <!-- Скрипт для обработки выбора всех чекбоксов и подтверждения удаления -->
    <script>
        // Проверка перед удалением
        document.getElementById('delete-product-btn').addEventListener('click', function (event) {
            const checkboxes = document.querySelectorAll('.delete-checkbox:checked');
            if (checkboxes.length === 0) {
                alert("Please select at least one product to delete.");
                event.preventDefault();
            } else if (!confirm("Are you sure you want to delete selected products?")) {
                event.preventDefault();
            }
        });

        // Скрипт для выбора всех чекбоксов
        document.getElementById('select-all').addEventListener('change', function () {
            const isChecked = this.checked;
            const checkboxes = document.querySelectorAll('.delete-checkbox');
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        });
    </script>
</body>

<footer>
        <p>Scandiweb Test Assignment</p>
    </footer>
    
</html>

