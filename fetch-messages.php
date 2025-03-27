<?php
session_start();
include_once "config.php";

if (isset($_POST['incoming_id'])) {
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $query = $conn->query("SELECT * FROM messages WHERE incoming_msg_id = {$incoming_id} OR outgoing_msg_id = {$incoming_id} ORDER BY msg_time ASC");

    $messages = [];
    while ($row = $query->fetch_assoc()) {
        $messages[] = [
            'message' => $row['msg']
        ];
    }
    echo json_encode($messages);
} else {
    echo json_encode([]);
}
?>
