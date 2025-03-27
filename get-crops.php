<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    echo "error";
    exit();
}

$logged_in_id = $_SESSION['unique_id'];

// Fetch selected crops for the logged-in user
$sql = $conn->prepare("SELECT selected_crops FROM users WHERE unique_id = ?");
$sql->bind_param("s", $logged_in_id);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $selected_crops = trim($row['selected_crops']);

    if (empty($selected_crops)) {
        echo "No crops selected";
        exit();
    }

    // Convert selected crops string into an array
    $cropList = array_map('trim', explode(",", $selected_crops));

    // Ensure we have crops to search for
    if (empty($cropList)) {
        echo "No crops selected";
        exit();
    }

    // Use prepared statements to prevent SQL injection
    $placeholders = implode(',', array_fill(0, count($cropList), '?'));
    $query = "SELECT name, price_per_kg FROM crops WHERE name IN ($placeholders)";
    $stmt = $conn->prepare($query);

    $stmt->bind_param(str_repeat('s', count($cropList)), ...$cropList);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response = ["🌱 Available Crops:\n"]; // Title

        while ($crop = $result->fetch_assoc()) {
            $response[] = "{$crop['name']} - ₹" . number_format($crop['price_per_kg'], 2) . " per kg";
        }

        // ✅ Fix newlines: use "\n" properly
        header("Content-Type: text/plain"); // Ensure text output format
        echo implode("\n", $response);
    } else {
        echo "No crops found in the database.";
    }
} else {
    echo "No crops selected";
}
?>
