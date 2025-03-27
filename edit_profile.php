<?php 
session_start();
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    exit();
}

include_once "layout/header.php";
include_once "php/config.php";

// Fetch user data
$sql = $conn->query("SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
$row = mysqli_num_rows($sql) > 0 ? $sql->fetch_assoc() : null;

// Fetch selected crops
$selectedCrops = [];
$cropsWithPrices = [];

if ($row && !empty($row['selected_crops'])) {
    $selectedCrops = explode(',', $row['selected_crops']); // Convert selected crops into an array
    
    // Fetch crop prices from the database
    foreach ($selectedCrops as $cropName) {
        $cropName = trim($cropName);
        $cropQuery = $conn->query("SELECT price_per_kg FROM crops WHERE name = '$cropName'");
        if ($cropQuery->num_rows > 0) {
            $cropData = $cropQuery->fetch_assoc();
            $cropsWithPrices[] = $cropName . " - ₹" . $cropData['price_per_kg'] . " per kg";
        } else {
            $cropsWithPrices[] = $cropName . " - Price not available";
        }
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $status = $_POST['status'];

    // Prepare an update query for user information
    $updateQuery = "UPDATE users SET fname='$fname', status='$status'";

    // Handle profile picture upload
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $img = $_FILES['img'];
        $imgName = time() . '_' . basename($img['name']);
        $imgPath = "php/images/" . $imgName;

        if (move_uploaded_file($img['tmp_name'], $imgPath)) {
            $updateQuery .= ", img='$imgName'";
        }
    }

    // Handle QR code upload
    if (isset($_FILES['qr_code']) && $_FILES['qr_code']['error'] == 0) {
        $qrCode = $_FILES['qr_code'];
        $qrCodeName = time() . '_qr_' . basename($qrCode['name']);
        $qrCodePath = "php/images/" . $qrCodeName;

        if (move_uploaded_file($qrCode['tmp_name'], $qrCodePath)) {
            $updateQuery .= ", qr_code='$qrCodeName'";
        }
    }

    // Finalize query and execute
    $updateQuery .= " WHERE unique_id='{$_SESSION['unique_id']}'";
    if ($conn->query($updateQuery)) {
        header("location: users.php");
        exit();
    } else {
        echo "Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            background-image: url('farmer.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .wrapper {
            font-family: Arial, sans-serif;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .edit-profile-container {
            padding: 25px 30px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .edit-profile-container header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .edit-profile-container img {
            border-radius: 50%;
            height: 100px;
            width: 100px;
            object-fit: cover;
        }

        .edit-profile-container .form-group {
            margin-bottom: 15px;
        }

        .edit-profile-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .edit-profile-container input[type="text"],
        .edit-profile-container input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .edit-profile-container button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .edit-profile-container button:hover {
            background: #0056b3;
        }

        .logout, .select-crops {
            display: inline-block;
            background: black;
            color: #fff;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            transition: background 0.3s;
        }

        .logout:hover {
            background: gray;
        }

        .select-crops {
            background: #007bff;
            margin-left: 10px;
        }

        .select-crops:hover {
            background: #0056b3;
        }

        .crop-list {
            list-style-type: none;
            padding: 0;
        }

        .crop-list li {
            background: #f1f1f1;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <section class="edit-profile-container">
            <header>
                <h2>Edit Profile</h2>
                <div>
                    <a href="users.php" class="logout">Back</a>
                    <a href="select_crops.php" class="select-crops">Select Crops</a>
                </div>
            </header>
            <?php if ($row): ?>
            <div class="profile-pic">
                <img src="php/images/<?= $row['img'] ?>" alt="Profile Picture">
                <img src="php/images/<?= $row['qr_code'] ?>" alt="QR Code" style="margin-left: 20px; height: 100px; width: 100px; border-radius: 8px;">
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fname">Username</label>
                    <input type="text" name="fname" id="fname" value="<?= htmlspecialchars($row['fname']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="lname">Role</label>
                    <p id="lname"><?= htmlspecialchars($row['lname']) ?></p>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" name="status" id="status" value="<?= htmlspecialchars($row['status']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="crops">Crops Selected</label>
                    <ul class="crop-list">
                        <?php foreach ($cropsWithPrices as $crop): ?>
                            <li><?= htmlspecialchars($crop) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="form-group">
                    <label for="img">Profile Picture (optional)</label>
                    <input type="file" name="img" id="img">
                </div>
                <div class="form-group">
                    <label for="qr_code">QR Code (optional)</label>
                    <input type="file" name="qr_code" id="qr_code">
                </div>
                <button type="submit">Update Profile</button>
            </form>
            <?php else: ?>
                <p>User not found.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
