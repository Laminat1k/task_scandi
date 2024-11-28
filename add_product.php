<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "Database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $type = $_POST['productType'];

    $size = $_POST['size'] ?? null;
    $weight = $_POST['weight'] ?? null;
    $height = $_POST['height'] ?? null;
    $width = $_POST['width'] ?? null;
    $length = $_POST['length'] ?? null;

    $dimensions = $type === 'Furniture' ? $height . 'x' . $width . 'x' . $length : null;

    $database = new Database();
    $conn = $database->getConnection();

    $query = "INSERT INTO products (sku, name, price, type, size_mb, weight_kg, dimensions) 
              VALUES (:sku, :name, :price, :type, :size, :weight, :dimensions)";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(':sku', $sku);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':size', $size);
    $stmt->bindParam(':weight', $weight);
    $stmt->bindParam(':dimensions', $dimensions);

    // Проверка выполнения запроса
    if ($stmt->execute()) {
        echo "Product added successfully!";
        header("Location: index.php");
        exit;
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Error: Unable to save product. " . $errorInfo[2];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css"> <!-- Подключите стили -->
</head>

<script src="script.js" defer></script>

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
    <style>
        /* Основной стиль */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 1rem;
        }

        main {
            margin: 2rem auto;
            max-width: 600px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        form {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        label {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

label, input, select {
    margin-left: 1rem; 
}

        input, select, button {
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #4CAF50;
        }

        button {
            background-color: #4CAF50;
            color: white;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .btn {
            display: inline-block;
            text-align: center;
            background-color: #d9534f;
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #c9302c;
        }

        #type-specific-fields {
            margin-top: 1rem;
        }

        Ы

        footer {
            text-align: center;
            padding: 1rem;
            background-color: #333;
            color: white;
            margin-top: 2rem;
        }
        /* Выравнивание динамических полей */
#type-specific-fields {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem; /* Расстояние между полями */
}

#type-specific-fields label {
    flex: 1 0 100%; /* Метки занимают всю ширину строки */
}

#type-specific-fields input {
    flex: 1; /* Поля равной ширины */
    min-width: 120px; /* Минимальная ширина для маленьких экранов */
}

    </style>
    
</body>
<footer>
        <p>Scandiweb Test Assignment</p>
    </footer>
</html>

