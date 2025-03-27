<?php
include_once "config.php";

if (isset($_POST['searchTerm'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
    $output = "";

    // Query to search for users
    $sql = $conn->query("SELECT * FROM users WHERE fname LIKE '%{$searchTerm}%' OR lname LIKE '%{$searchTerm}%'");

    if (mysqli_num_rows($sql) > 0) {
        while ($row = $sql->fetch_assoc()) {
            // Assuming data.php handles how each user is displayed
            // Here you can customize how you want to display each user
            $output .= "<div class='user'>
                            <img src='php/images/{$row['img']}' alt=''>
                            <div class='details'>
                                <span>{$row['fname']} {$row['lname']}</span>
                                <p>{$row['status']}</p>
                            </div>
                        </div>";
        }
    } else {
        $output .= "No user found related to your search term";
    }

    echo $output; // Output the result
} else {
    echo "Search term not provided"; // Error handling
}

