<?php 
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";

    // Validate POST data
    if (!isset($_POST['outgoing_id']) || !isset($_POST['incoming_id'])) {
        echo "Error: Missing required parameters.";
        exit();
    }

    $outgoing_id = mysqli_real_escape_string($conn, $_POST['outgoing_id']);
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";

    // Fetch chat messages
    $sql = $conn->query("SELECT messages.*, users.img FROM messages
                          LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
                          WHERE (outgoing_msg_id = $outgoing_id AND incoming_msg_id = $incoming_id) 
                          OR (outgoing_msg_id = $incoming_id AND incoming_msg_id = $outgoing_id) 
                          ORDER BY msg_id");

    if ($sql->num_rows > 0) {
        while ($row = $sql->fetch_assoc()) {
            $message = htmlspecialchars_decode($row['msg']); // ✅ Decode HTML entities
            $message = nl2br($message); // ✅ Convert new lines to <br> tags
            $sender_id = $row['outgoing_msg_id'];

            // Check if message is an image URL
            $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            $is_image = false;
            foreach ($image_extensions as $ext) {
                if (stripos($message, $ext) !== false) {
                    $is_image = true;
                    break;
                }
            }

            if (filter_var($message, FILTER_VALIDATE_URL)) {
                if ($is_image) {
                    $message = "<img src='$message' alt='Image' style='max-width: 100%; max-height: 200px; border-radius: 8px; margin-top: 5px;'>";
                } else {
                    $message = "<a href='$message' target='_blank' style='color: blue;'>View Link</a>";
                }
            }

            // If message is "PAY", make it a clickable link
            if (trim(strtoupper($row['msg'])) === "PAY") {
                $message = "<a href='pay.php?sender_id=$sender_id' class='pay-link' style='color: blue; text-decoration: underline;'>PAY</a>";
            }

            // Check if the message is from the sender (outgoing)
            if ($sender_id == $outgoing_id) {
                $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. $message .'</p>
                                </div>
                            </div>';
            } else { // Message is from the receiver (incoming)
                $output .= '<div class="chat incoming">
                                <img src="php/images/'. htmlspecialchars($row['img']) .'" alt="User Image" 
                                     style="width: 35px; height: 35px; border-radius: 50%;">
                                <div class="details">
                                    <p>'. $message .'</p>
                                </div>
                            </div>';
            }
        }
    } else {
        $output = "<p style='text-align:center; color:gray;'>No messages found</p>";
    }

    echo $output;
} else {
    header("location: ../login.php");
    exit();
}
?>
