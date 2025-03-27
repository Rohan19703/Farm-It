<?php
session_start();
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
}

include_once "layout/header.php";
include_once "php/config.php";

$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
$sql = $conn->query("SELECT * FROM users WHERE unique_id = {$user_id}");
$row = mysqli_num_rows($sql) > 0 ? $sql->fetch_assoc() : null;
?>

<body>
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <?php if ($row): ?>
                <img src="php/images/<?= $row['img']; ?>" alt="">
                <div class="details">
                    <span><?= $row['fname'] . " " . $row['lname'] ?></span>
                    <p><?= $row['status'] ?></p>
                </div>
                <?php endif; ?>
            </header>
            <div class="chat-box"></div>
            <form action="#" class="typing-area" autocomplete="off">
                <input type="text" name="outgoing_id" value="<?= $_SESSION['unique_id']; ?>" hidden>
                <input type="text" name="incoming_id" value="<?= $user_id; ?>" hidden>
                <input type="text" name="message" class="input-field" placeholder="Type a message here...">
                <button><i class="fab fa-telegram-plane"></i></button>
            </form>
        </section>
    </div>

    <script>
        const form = document.querySelector(".typing-area"),
            inputField = form.querySelector(".input-field"),
            sendBtn = form.querySelector("button"),
            chatBox = document.querySelector(".chat-box");

        form.onsubmit = (e) => {
            e.preventDefault(); // prevent the form from submitting
        }

        sendBtn.onclick = () => {
            // Start Ajax
            let xhr = new XMLHttpRequest(); // creating XML object
            xhr.open("POST", "php/insert-chat.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        inputField.value = ""; // clear input field after sending message
                        scrollToBottom(); // scroll to bottom after sending
                    }
                }
            }
            // Send form data through Ajax to PHP
            let formData = new FormData(form); // creating new FormData object
            xhr.send(formData); // sending the form data to PHP
        }

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight; // scroll to the bottom of the chat box
        }

        setInterval(() => {
            // Start Ajax
            let xhr = new XMLHttpRequest(); // creating XML object
            xhr.open("POST", "php/get-chat.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;
                        chatBox.innerHTML = data; // update chat box with new messages
                        scrollToBottom(); // scroll to bottom after updating
                    }
                }
            }
            // Send form data through Ajax to PHP
            let formData = new FormData(form); // creating new FormData object
            xhr.send(formData); // sending the form data to PHP
        }, 500); // this function will run frequently after 500ms
    </script>
</body>

</html>
