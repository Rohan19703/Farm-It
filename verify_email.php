<?php
include_once "config.php";

if (isset($_GET['email']) && isset($_GET['token']) && isset($_GET['verify'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];
    $verify = $_GET['verify'];

    $check_token = $conn->query("SELECT * FROM email_verification WHERE email = '$email' AND token = '$token'");

    if ($check_token->num_rows > 0) {
        if ($verify == "yes") {
            // Email confirmed, allow registration
            $conn->query("UPDATE email_verification SET status='verified' WHERE email = '$email'");
            echo "Your email is verified. You can now complete registration.";
            header("refresh:2;url=register.php");
        } else {
            // Email verification denied
            $conn->query("DELETE FROM email_verification WHERE email = '$email'");
            echo "Email verification denied.";
        }
    } else {
        echo "Invalid verification link!";
    }
}
?>
