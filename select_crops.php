<?php
session_start();
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    exit();
}

include_once "layout/header.php";
include_once "php/config.php";

// Sample crop data (replace with your database query as needed)
$crops = [
    'vegetables' => [
        ['id' => 1, 'name' => 'Potato', 'description' => 'Fresh and starchy potatoes', 'image' => 'img/potato.jpeg', 'price' => '50₹/kg', 'available' => true],
        ['id' => 2, 'name' => 'Onion', 'description' => 'Strong and flavorful onions', 'image' => 'img/onion.jpg', 'price' => '30₹/kg', 'available' => true],
        ['id' => 3, 'name' => 'Tomato', 'description' => 'Fresh red tomatoes', 'image' => 'img/tomato.jpg', 'price' => '40₹/kg', 'available' => true],
        ['id' => 4, 'name' => 'Cauliflower', 'description' => 'Crisp white cauliflower', 'image' => 'img/cauli.jpg', 'price' => '60₹/kg', 'available' => true],
        ['id' => 5, 'name' => 'Spinach', 'description' => 'Fresh green spinach leaves', 'image' => 'img/spinach.jpg', 'price' => '20₹/kg', 'available' => true],
        ['id' => 6, 'name' => 'Carrot', 'description' => 'Crunchy orange carrots', 'image' => 'img/carrot.jpg', 'price' => '45₹/kg', 'available' => true],
        ['id' => 7, 'name' => 'Brinjal', 'description' => 'Fresh purple brinjals', 'image' => 'img/brinjal.jpg', 'price' => '55₹/kg', 'available' => true],
        ['id' => 8, 'name' => 'Cabbage', 'description' => 'Crisp green cabbage', 'image' => 'img/cabbage.jpg', 'price' => '35₹/kg', 'available' => true],
        ['id' => 9, 'name' => 'Bhindi', 'description' => 'Fresh ladyfinger (okra)', 'image' => 'img/bhindi.jpg', 'price' => '50₹/kg', 'available' => true],
        ['id' => 10, 'name' => 'Capsicum', 'description' => 'Colorful bell peppers', 'image' => 'img/capsicum.jpeg', 'price' => '70₹/kg', 'available' => true],
        ['id' => 11, 'name' => 'Pumpkin', 'description' => 'Fresh and sweet pumpkin', 'image' => 'img/pum.jpg', 'price' => '25₹/kg', 'available' => true],
        ['id' => 12, 'name' => 'Lauki', 'description' => 'Fresh bottle gourd', 'image' => 'img/lauki.jpg', 'price' => '30₹/kg', 'available' => true],
        ['id' => 13, 'name' => 'Mooli', 'description' => 'Crunchy white radish', 'image' => 'img/mooli.jpg', 'price' => '15₹/kg', 'available' => true],
        ['id' => 14, 'name' => 'Matar', 'description' => 'Fresh green peas', 'image' => 'img/matar.jpg', 'price' => '50₹/kg', 'available' => true],
        ['id' => 15, 'name' => 'Methi', 'description' => 'Fresh fenugreek leaves', 'image' => 'img/methi.jpg', 'price' => '30₹/kg', 'available' => true],
    ],
    'fruits' => [
        ['id' => 16, 'name' => 'Mango', 'description' => 'Sweet and juicy mangoes', 'image' => 'img/mango.jpg', 'price' => '100₹/kg', 'available' => true],
        ['id' => 17, 'name' => 'Banana', 'description' => 'Ripe and sweet bananas', 'image' => 'img/banana.jpg', 'price' => '40₹/kg', 'available' => true],
        ['id' => 18, 'name' => 'Guava', 'description' => 'Fresh and flavorful guavas', 'image' => 'img/guava.jpg', 'price' => '50₹/kg', 'available' => true],
        ['id' => 19, 'name' => 'Papaya', 'description' => 'Sweet and soft papayas', 'image' => 'img/papaya.jpg', 'price' => '30₹/kg', 'available' => true],
        ['id' => 20, 'name' => 'Pomegranate', 'description' => 'Juicy and tangy pomegranates', 'image' => 'img/pomo.jpg', 'price' => '80₹/kg', 'available' => true],
        ['id' => 21, 'name' => 'Apple', 'description' => 'Crisp and sweet apples', 'image' => 'img/apple.jpg', 'price' => '120₹/kg', 'available' => true],
        ['id' => 22, 'name' => 'Orange', 'description' => 'Juicy and tangy oranges', 'image' => 'img/orange.jpg', 'price' => '60₹/kg', 'available' => true],
        ['id' => 23, 'name' => 'Lemon', 'description' => 'Sour and zesty lemons', 'image' => 'img/lemon.jpg', 'price' => '20₹/kg', 'available' => true],
        ['id' => 24, 'name' => 'Watermelon', 'description' => 'Refreshing and sweet watermelon', 'image' => 'img/watermelon.jpg', 'price' => '25₹/kg', 'available' => true],
        ['id' => 25, 'name' => 'Pineapple', 'description' => 'Sweet and tangy pineapples', 'image' => 'img/pine.jpg', 'price' => '50₹/kg', 'available' => true],
        ['id' => 26, 'name' => 'Coconut', 'description' => 'Refreshing coconut water', 'image' => 'img/coco.jpg', 'price' => '30₹/kg', 'available' => true],
        ['id' => 27, 'name' => 'Chikoo', 'description' => 'Sweet chikoo fruit', 'image' => 'img/chiku.png', 'price' => '40₹/kg', 'available' => true],
        ['id' => 28, 'name' => 'Custard Apple', 'description' => 'Creamy custard apples', 'image' => 'img/capple.jpg', 'price' => '70₹/kg', 'available' => true],
        ['id' => 29, 'name' => 'Lychee', 'description' => 'Sweet and juicy lychee', 'image' => 'img/lychee.jpg', 'price' => '90₹/kg', 'available' => true],
    ],
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_crops']) && is_array($_POST['selected_crops'])) {
        // Use crop names directly instead of IDs
        $selectedCrops = implode(',', array_map('htmlspecialchars', $_POST['selected_crops'])); // Sanitize crop names
        $uniqueId = $_SESSION['unique_id']; // Get the logged-in user's unique ID

        // Update the selected_crops column in the database
        $query = "UPDATE users SET selected_crops = ? WHERE unique_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("si", $selectedCrops, $uniqueId);
            if ($stmt->execute()) {
            } else {
                echo "<p style='color: red; text-align: center;'>Error saving selected crops. Please try again.</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: red; text-align: center;'>Database error: Unable to prepare the statement.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>No crops selected. Please select crops before submitting.</p>";
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
        /* Main page styles */
        * {
            box-sizing: border-box;
        }

        body {
            background-image: url('crop.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            font-family: Arial, sans-serif;
        }

        .wrapper {
            width: 100%;
            max-width: 1000px; /* Ensure it doesn't stretch beyond 1000px */
            margin: 0 auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }


        /* Back button styles */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            display: inline-block;
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-button:hover {
            background: #0056b3;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        .crop-selection {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            gap: 10px; /* Adds space between the crop items */
            max-height: 500px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }


        .crop-item {
            background: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px;
            padding: 15px;
            text-align: center;
            width: 30%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .crop-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .not-available {
            color: red;
            font-weight: bold;
        }

        button.submit {
            width: 100%;
            max-width: 300px; /* Limit the width of the submit button */
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

    </style>
</head>
<body>

    <div class="wrapper">
        <!-- Back Button inside the wrapper -->
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
                        <p><strong>Price: </strong><?= $crop['price'] ?></p> <!-- Add this line to display the price -->
                        <?php if ($crop['available']): ?>
                            <label>
                                <input type="checkbox" name="selected_crops[]" value="<?= htmlspecialchars($crop['name']) ?>"> Select
                            </label>
                        <?php else: ?>
                            <p style="color: red;">Not Available</p>
                        <?php endif; ?>
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
                        <p><strong>Price: </strong><?= $crop['price'] ?></p> <!-- Add this line to display the price -->
                        <?php if ($crop['available']): ?>
                            <label>
                                <input type="checkbox" name="selected_crops[]" value="<?= htmlspecialchars($crop['name']) ?>"> Select
                            </label>
                        <?php else: ?>
                            <p style="color: red;">Not Available</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="submit">Submit</button>
        </form>
    </div>
</body>
</html>
