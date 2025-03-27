<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "config.php";

if (!isset($_SESSION['unique_id'])) {
    echo "User not logged in.";
    exit();
}

$outgoing_id = $_SESSION['unique_id']; // Logged-in user ID
$output = "";

// Fetch all users except the logged-in user
$sql = $conn->query("SELECT * FROM users WHERE unique_id != {$outgoing_id} AND fname IS NOT NULL AND lname IS NOT NULL AND img IS NOT NULL");

while ($row = $sql->fetch_assoc()) {
    // Query to get the latest message
    $sql2 = "SELECT * FROM messages WHERE 
                (incoming_msg_id = {$row['unique_id']} OR outgoing_msg_id = {$row['unique_id']}) 
                AND (outgoing_msg_id = {$outgoing_id} OR incoming_msg_id = {$outgoing_id}) 
                ORDER BY msg_id DESC LIMIT 1";

    $query2 = $conn->query($sql2);
    $row2 = ($query2 && $query2->num_rows > 0) ? $query2->fetch_assoc() : null;

    // Get message or default text
    $result = ($row2 && isset($row2['msg'])) ? $row2['msg'] : "No message available";

    // Trim message if it's too long
    $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;

    // Determine if the message was sent by the logged-in user
    $you = ($row2 && isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";

    // Construct the output HTML for each user
    $output .= '<a href="chat.php?user_id=' . htmlspecialchars($row['unique_id']) . '">
                    <div class="content">
                        <img src="php/images/' . htmlspecialchars($row['img']) . '" alt="">
                        <div class="details">
                            <span>' . htmlspecialchars($row['fname'] . " " . $row['lname']) . '</span>
                            <p>' . htmlspecialchars($you . $msg) . '</p>
                        </div>
                    </div>
                    <div class="status-dot"><i class="fas fa-circle"></i></div>
                </a>';
}

echo $output;
?>
