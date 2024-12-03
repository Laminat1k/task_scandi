<?php
// Подключение к базе данных
require_once "Database.php";

// Создание объекта Database и получение соединения
$database = new Database();
$conn = $database->getConnection();

// Проверка, были ли отправлены данные формы
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete']) && !empty($_POST['delete'])) {
    // Получаем IDs товаров для удаления
    $product_ids = $_POST['delete'];

    try {
        // Начало транзакции, чтобы все удаления произошли корректно
        $conn->beginTransaction();

        // Подготовка запроса на удаление
        $query = "DELETE FROM products WHERE id IN (" . implode(',', array_map('intval', $product_ids)) . ")";
        $stmt = $conn->prepare($query);

        // Выполнение запроса
        $stmt->execute();

        // Подтверждение транзакции
        $conn->commit();

        // Перенаправляем обратно на страницу продуктов
        header("Location: ../index.php");
        exit;
    } catch (PDOException $e) {
        // Если ошибка, откатываем транзакцию
        $conn->rollBack();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
} else {
    // Если не выбраны товары, отображаем сообщение
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        echo "<p style='color: red;'>Продукты не выбраны.</p>";
    }
}

?>
