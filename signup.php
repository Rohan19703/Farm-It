<?php
session_start();
include_once "config.php";

// Escape user inputs
$fname = mysqli_real_escape_string($conn, $_POST['fname']);
$lname = mysqli_real_escape_string($conn, $_POST['lname']); // Assuming this is the role
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email already exists
        $sql = $conn->query("SELECT email FROM users WHERE email = '{$email}'");
        if (mysqli_num_rows($sql) > 0) {
            echo "$email - This email already exists!";
        } else {
            // Check for profile image upload
            if (isset($_FILES['image'])) {
                $img_name = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $img_explode = explode('.', $img_name);
                $img_ext = end($img_explode);

                $extensions = ['png', 'jpg', 'jpeg'];
                if (in_array($img_ext, $extensions) === true) {
                    $time = time();
                    $new_img_name = $time . $img_name;

                    // Move the uploaded image
                    if (move_uploaded_file($tmp_name, "images/" . $new_img_name)) {
                        // Handle QR code upload
                        if (isset($_FILES['qr_code'])) {
                            $qr_name = $_FILES['qr_code']['name'];
                            $qr_tmp_name = $_FILES['qr_code']['tmp_name'];
                            $qr_explode = explode('.', $qr_name);
                            $qr_ext = end($qr_explode);

                            if (in_array($qr_ext, $extensions) === true) {
                                $new_qr_name = $time . $qr_name;

                                // Move the uploaded QR code
                                if (move_uploaded_file($qr_tmp_name, "images/" . $new_qr_name)) {
                                    $status = "Active now";
                                    $random_id = rand(time(), 10000000);
                                    $formattedlname = "($lname)"; // Format the role

                                    // Insert user data into the database
                                    $sql2 = $conn->query("INSERT INTO users (unique_id, fname, lname, email, password, img, qr_code, status) 
                                                           VALUES ({$random_id}, '{$fname}', '{$formattedlname}', '{$email}', '{$password}', '{$new_img_name}', '{$new_qr_name}', '{$status}')");

                                    if ($sql2) {
                                        // Fetch user data
                                        $sql3 = $conn->query("SELECT * FROM users WHERE email = '{$email}'");
                                        if (mysqli_num_rows($sql3) > 0) {
                                            $row = $sql3->fetch_assoc();
                                            $_SESSION['unique_id'] = $row['unique_id'];
                                            echo "success";
                                        }
                                    } else {
                                        echo "Something went wrong!";
                                    }
                                }
                            } else {
                                echo "Please select a valid QR code image file - jpeg, jpg, png!";
                            }
                        } else {
                            echo "Please select a QR code image file!";
                        }
                    }
                } else {
                    echo "Please select a valid profile image file - jpeg, jpg, png!";
                }
            } else {
                echo "Please select a profile image file!";
            }
        }
    } else {
        echo "$email - This is not a valid email!";
    }
} else {
    echo "All input fields are required!";
}
