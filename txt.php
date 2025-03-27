<?php
session_start();
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    exit();
}

include_once "layout/header.php";
include_once "php/config.php";

// Sample crop data
$crops = [
    'vegetables' => [
        ['id' => 1, 'name' => 'Potato', 'description' => 'Fresh and starchy potatoes', 'image' => 'img/potato.jpeg', 'available' => true],
        ['id' => 2, 'name' => 'Onion', 'description' => 'Strong and flavorful onions', 'image' => 'img/onion.jpg', 'available' => true],
        ['id' => 3, 'name' => 'Tomato', 'description' => 'Fresh red tomatoes', 'image' => 'img/tomato.jpg', 'available' => true],
        ['id' => 4, 'name' => 'Cauliflower', 'description' => 'Crisp white cauliflower', 'image' => 'img/cauli.jpg', 'available' => true],
        ['id' => 5, 'name' => 'Spinach', 'description' => 'Fresh green spinach leaves', 'image' => 'img/spinach.jpg', 'available' => true],
        ['id' => 6, 'name' => 'Carrot', 'description' => 'Crunchy orange carrots', 'image' => 'img/carrot.jpg', 'available' => true],
        ['id' => 7, 'name' => 'Brinjal', 'description' => 'Fresh purple brinjals', 'image' => 'img/brinjal.jpg', 'available' => true],
        ['id' => 8, 'name' => 'Cabbage', 'description' => 'Crisp green cabbage', 'image' => 'img/cabbage.jpg', 'available' => true],
        ['id' => 9, 'name' => 'Bhindi', 'description' => 'Fresh ladyfinger (okra)', 'image' => 'img/bhindi.jpg', 'available' => true],
        ['id' => 10, 'name' => 'Capsicum', 'description' => 'Colorful bell peppers', 'image' => 'img/capsicum.jpeg', 'available' => true],
        ['id' => 11, 'name' => 'Pumpkin', 'description' => 'Fresh and sweet pumpkin', 'image' => 'img/pum.jpg', 'available' => true],
        ['id' => 12, 'name' => 'Lauki', 'description' => 'Fresh bottle gourd', 'image' => 'img/lauki.jpg', 'available' => true],
        ['id' => 13, 'name' => 'Mooli', 'description' => 'Crunchy white radish', 'image' => 'img/mooli.jpg', 'available' => true],
        ['id' => 14, 'name' => 'Matar', 'description' => 'Fresh green peas', 'image' => 'img/matar.jpg', 'available' => true],
        ['id' => 15, 'name' => 'Methi', 'description' => 'Fresh fenugreek leaves', 'image' => 'img/methi.jpg', 'available' => true],
    ],
    'fruits' => [
        ['id' => 16, 'name' => 'Mango', 'description' => 'Sweet and juicy mangoes', 'image' => 'img/mango.jpg', 'available' => true],
        ['id' => 17, 'name' => 'Banana', 'description' => 'Ripe and sweet bananas', 'image' => 'img/banana.jpg', 'available' => true],
        ['id' => 18, 'name' => 'Guava', 'description' => 'Fresh and flavorful guavas', 'image' => 'img/guava.jpg', 'available' => true],
        ['id' => 19, 'name' => 'Papaya', 'description' => 'Sweet and soft papayas', 'image' => 'img/papaya.jpg', 'available' => true],
        ['id' => 20, 'name' => 'Pomegranate', 'description' => 'Juicy and tangy pomegranates', 'image' => 'img/pomo.jpg', 'available' => true],
        ['id' => 21, 'name' => 'Apple', 'description' => 'Crisp and sweet apples', 'image' => 'img/apple.jpg', 'available' => true],
        ['id' => 22, 'name' => 'Orange', 'description' => 'Juicy and tangy oranges', 'image' => 'img/orange.jpg', 'available' => true],
        ['id' => 23, 'name' => 'Lemon', 'description' => 'Sour and zesty lemons', 'image' => 'img/lemon.jpg', 'available' => true],
        ['id' => 24, 'name' => 'Watermelon', 'description' => 'Refreshing and sweet watermelon', 'image' => 'img/watermelon.jpg', 'available' => true],
        ['id' => 25, 'name' => 'Pineapple', 'description' => 'Sweet and tangy pineapples', 'image' => 'img/pine.jpg', 'available' => true],
        ['id' => 26, 'name' => 'Coconut', 'description' => 'Refreshing coconut water', 'image' => 'img/coco.jpg', 'available' => true],
        ['id' => 27, 'name' => 'Chikoo', 'description' => 'Sweet chikoo fruit', 'image' => 'img/chiku.png', 'available' => true],
        ['id' => 28, 'name' => 'Custard Apple', 'description' => 'Creamy custard apples', 'image' => 'img/capple.jpg', 'available' => true],
        ['id' => 29, 'name' => 'Lychee', 'description' => 'Sweet and juicy lychee', 'image' => 'img/lychee.jpg', 'available' => true],
    ],
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_crops'])) {
    $selectedCrops = json_decode($_POST['selected_crops'], true);
    if (!empty($selectedCrops)) {
        echo "<div class='selected-crops-summary'><h2>Selected Crops Summary</h2><ul>";
        foreach ($selectedCrops as $cropId => $quantity) {
            if ($quantity > 0) {
                foreach (['vegetables', 'fruits'] as $category) {
                    foreach ($crops[$category] as $crop) {
                        if ($crop['id'] == $cropId) {
                            echo "<li>{$crop['name']} - Quantity: $quantity</li>";
                        }
                    }
                }
            }
        }
        echo "</ul></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Crops</title>
    <style>
        body {
            background-image: url('crop.png');
            background-size: cover;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
        }
        .wrapper {
            max-width: 1000px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .crop-selection {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .crop-item {
            flex: 1 1 calc(33.333% - 15px);
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .crop-item img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
        .quantity-control {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }
        .quantity-control button {
            padding: 5px 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .quantity-control button:hover {
            background: #0056b3;
        }
        .quantity {
            width: 50px;
            text-align: center;
            margin: 0 10px;
        }
        button.submit {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button.submit:hover {
            background: #218838;
        }
        .selected-crops-summary {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <a href="edit_profile.php" class="back-button">Back</a>
    <h1>Select Crops</h1>
    <form method="POST">
        <h2>Vegetables</h2>
        <div class="crop-selection">
            <?php foreach ($crops['vegetables'] as $crop): ?>
                <div class="crop-item">
                    <img src="<?= $crop['image'] ?>" alt="<?= $crop['name'] ?>">
                    <h3><?= $crop['name'] ?></h3>
                    <p><?= $crop['description'] ?></p>
                    <div class="quantity-control">
                        <button type="button" onclick="changeQuantity(<?= $crop['id'] ?>, -1)">-</button>
                        <input type="number" id="quantity_<?= $crop['id'] ?>" name="selected_crops[<?= $crop['id'] ?>]" value="0" min="0" class="quantity">
                        <button type="button" onclick="changeQuantity(<?= $crop['id'] ?>, 1)">+</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <h2>Fruits</h2>
        <div class="crop-selection">
            <?php foreach ($crops['fruits'] as $crop): ?>
                <div class="crop-item">
                    <img src="<?= $crop['image'] ?>" alt="<?= $crop['name'] ?>">
                    <h3><?= $crop['name'] ?></h3>
                    <p><?= $crop['description'] ?></p>
                    <div class="quantity-control">
                        <button type="button" onclick="changeQuantity(<?= $crop['id'] ?>, -1)">-</button>
                        <input type="number" id="quantity_<?= $crop['id'] ?>" name="selected_crops[<?= $crop['id'] ?>]" value="0" min="0" class="quantity">
                        <button type="button" onclick="changeQuantity(<?= $crop['id'] ?>, 1)">+</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="submit">Submit Selected Crops</button>
    </form>
</div>
<script>
    function changeQuantity(cropId, delta) {
        const quantityInput = document.getElementById('quantity_' + cropId);
        let quantity = parseInt(quantityInput.value) || 0;
        quantity += delta;
        if (quantity < 0) quantity = 0;
        quantityInput.value = quantity;
    }
</script>
</body>
</html>
