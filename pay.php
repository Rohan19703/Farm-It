<?php
session_start();
include_once "php/config.php"; // Database connection

if (!isset($_GET['sender_id'])) {
    die("Invalid request"); // If sender ID is not provided
}

$sender_id = mysqli_real_escape_string($conn, $_GET['sender_id']);

// Fetch sender details (fname and qr_code)
$sql = "SELECT fname, qr_code FROM users WHERE unique_id = '$sender_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fname = htmlspecialchars($row['fname']);
    $qr_code = htmlspecialchars($row['qr_code']); // Path to the QR image
} else {
    die("User details not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        body {
            background-image: url("pay2.png"); /* Replace with your image path */
            background-size: cover; /* Cover the entire viewport */
            background-repeat: no-repeat; /* Do not repeat the image */
            background-position: center; /* Center the image */
            font-size: large;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        .container {
            background-color: white;
            height: 630px;
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .qr-image {
            width: 400px;
            height: 500px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Payment Details</h2>
    <p><strong> Name:</strong> <?php echo $fname; ?></p>
    <img src="php/images/<?php echo $qr_code; ?>" alt="QR Code" class="qr-image">
</div>

</body>
</html>

