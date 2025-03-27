<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    
    $outgoing_id = mysqli_real_escape_string($conn, $_POST['outgoing_id']);
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Handle the image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_type = $_FILES['image']['type'];

        // Check if the file is an image
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image_type, $allowed_types)) {
            $upload_dir = 'uploads/images/';
            $image_new_name = time() . '_' . $image_name; // Give unique name to avoid conflicts
            $image_path = $upload_dir . $image_new_name;

            // Move the uploaded file to the target folder
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                // Insert image path into the database
                $message = $image_path; // Set the image path as message content
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Image upload failed.']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid image type.']);
            exit();
        }
    }

    // Insert the message (either text or image path)
    if (!empty($message)) {
        $sql = $conn->query("INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) 
                              VALUES ({$incoming_id}, {$outgoing_id}, '{$message}')");

        if ($sql) {
            echo json_encode(['status' => 'success', 'message' => $message]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Unable to send message. Please try again.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Message cannot be empty.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated. Please log in.']);
}
