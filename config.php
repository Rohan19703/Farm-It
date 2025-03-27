<?php
$conn = new mysqli("localhost", "root", "", "chatapp1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

