<?php 
session_start();
include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    echo "User not logged in.";
    exit();
}

$outgoing_id = $_SESSION['unique_id'];
$sql = $conn->query("SELECT * FROM users WHERE unique_id != {$outgoing_id}");
$output = "";

if (mysqli_num_rows($sql) == 0) {
    $output .= "No users are available to chat";
} else {
    include "data.php"; // Include the file that generates the user list
}

echo $output;
?>
