<?php
session_start();
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    exit();
}

include_once "layout/header.php";
include_once "php/config.php";

$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
$sql = $conn->query("SELECT * FROM users WHERE unique_id = {$user_id}");
$row = mysqli_num_rows($sql) > 0 ? $sql->fetch_assoc() : null;

// Fetch selected crops of logged-in user
$logged_in_id = $_SESSION['unique_id'];
$crops_query = $conn->query("SELECT selected_crops FROM users WHERE unique_id = '$logged_in_id'");
$crops_row = mysqli_fetch_assoc($crops_query);
$selected_crops = $crops_row['selected_crops'] ?? ''; // Store selected crops (if available)
?>

<style>
    /* Chat Area CSS Code */
    body {
        background-image: url('farmer.png'); /* Replace with your image path */
        background-size: cover; /* Cover the entire viewport */
        background-repeat: no-repeat; /* Do not repeat the image */
        background-position: center; /* Center the image */
    }
    .wrapper {
        font-family: Arial, sans-serif;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 7px;
    }

    .chat-area header {
        display: flex;
        align-items: center;
        padding: 18px 30px;
    }

    .chat-area header .back-icon {
        font-size: 18px;
        color: #333;   
    }

    .chat-area header img {
        height: 45px;
        width: 45px;
        margin: 0 15px;
    }

    .chat-area header span {
        font-size: 17px;
        font-weight: 600;
    }

    .chat-box {
        height: 500px;
        overflow-y: auto;
        background-image: url('back.png');
        background-size: cover;
        padding: 10px 30px 20px 30px;
        box-shadow: inset 0 32px 32px -32px rgb(0 0 0 / 5%),
                    inset 0 -32px 32px -32px rgb(0 0 0 / 5%);
    }

    .chat-box .chat {
        margin: 15px 0;
    }

    .chat-box .chat p {
        word-wrap: break-word;
        padding: 8px 16px;
        box-shadow: 0 0 32px rgb(0 0 0 / 8%),
                    0 16px 16px -16px rgb(0 0 0 / 10%);
    }

    .chat-box .outgoing {
        display: flex;
    }

    .outgoing .details {
        margin-left: auto;
        max-width: calc(100% - 130px);
    }

    .outgoing .details p {
        background: gainsboro;
        color: black;
        border-radius: 18px 18px 0 18px;
    }

    .chat-box .incoming {
        display: flex;
        align-items: flex-end;
    }

    .chat-box .incoming img {
        width: 35px;
        height: 35px;
    }

    .incoming .details {
        margin-left: 10px;
        margin-right: auto;
        max-width: calc(100% - 130px);
    }

    .incoming .details p {
        color: #333;
        background: #fff;
        border-radius: 18px 18px 18px 0;
    }

    .chat-area .typing-area {
        padding: 18px 30px;
        display: flex;
        justify-content: space-between;
    }

    .typing-area input {
        height: 45px;
        width: calc(100% - 58px);
        font-size: 17px;
        border: 1px solid #ccc;
        padding: 0 13px;
        border-radius: 5px;
        outline: none;
    }

    .typing-area button {
        width: 55px;
        border: none;
        outline: none;
        background: #333;
        color: #fff;
        font-size: 19px;
        cursor: pointer;
        border-radius: 0 5px 5px 0;
        margin-left: 10px; /* Adjust the value as needed */
    }
    .file-input {
        display: none;
    }

    .custom-file-label {
        display: inline-block;
        padding: 8px 12px;
        background-color: #333;
        color: white;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
    }

    
</style>

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
        <form action="send_message.php" method="POST" class="typing-area" autocomplete="off" enctype="multipart/form-data">
            <input type="text" name="outgoing_id" value="<?= $_SESSION['unique_id']; ?>" hidden>
            <input type="text" name="incoming_id" value="<?= $user_id; ?>" hidden>
            
            <!-- Message Input Field -->
            <input type="text" name="message" class="input-field" placeholder="Type a message here...">
            

            <!-- File Upload Input for Images -->
            <input type="file" name="image" class="file-input" accept="image/*">
            
            <!-- Send Message Button -->
            <button type="submit"><i class="fab fa-telegram-plane"></i></button>
            
            <!-- Additional Buttons -->
            <button type="button" class="location-btn">📍</button>
            <button type="button" class="custom-btn">📋</button> <!-- Crops button -->
            <button type="button" class="payment-request-btn">💲</button>
        </form>
    </section>
</div>


<script>
   const form = document.querySelector(".typing-area"),
    inputField = form.querySelector(".input-field"),
    sendBtn = form.querySelector("button"),
    locationBtn = document.querySelector(".location-btn"),
    cropsBtn = document.querySelector(".custom-btn"), // Crops button
    payBtn = document.querySelector(".payment-request-btn"), // Pay button
    chatBox = document.querySelector(".chat-box"),
    imageInput = form.querySelector("input[type='file']"); // Image input field

form.onsubmit = (e) => {
    e.preventDefault(); // Prevent form submission
};

// Send message when send button is clicked
sendBtn.onclick = () => {
    sendMessage(inputField.value, imageInput.files[0]); // Pass message and selected file
};

// Send location when location button is clicked
locationBtn.onclick = () => {
    if (confirm("Do you want to share your location?")) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                const locationMessage = `https://www.google.com/maps?q=${latitude},${longitude}`;
                sendMessage(locationMessage); // Send location as message
            }, () => {
                alert("Unable to retrieve your location.");
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
};

// Fetch and send selected crops when crops button is clicked
cropsBtn.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "php/get-crops.php", true);

    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let crops = xhr.responseText.trim();

            if (crops.toLowerCase() === "error") {
                alert("Error retrieving crops. Please log in again.");
            } else if (crops === "" || crops.toLowerCase().includes("no crops")) {
                alert("No crops selected in your profile!");
            } else {
                // ✅ Fix newlines for chat display
                let formattedCrops = crops.replace(/\n/g, "<br>");

                sendMessage(formattedCrops); // Send as formatted HTML message
            }
        }
    };

    xhr.onerror = () => {
        alert("Failed to fetch crops. Please check your internet connection.");
    };

    xhr.send();
};

// Send "PAY" message when the payment button is clicked
payBtn.onclick = () => {
    sendMessage("PAY"); // Send "PAY" as a message
};

// Function to send a message via AJAX (including text and images)
function sendMessage(message, image = null) {
    if (message.trim() === "" && !image) return;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/insert-chat.php", true);
    let formData = new FormData(form);
    
    // ✅ Convert newlines to proper HTML format before sending
    message = message.replace(/\n/g, "<br>");
    
    formData.append("message", message);
    if (image) formData.append("image", image);
    xhr.send(formData);
}

// Function to scroll chat to the bottom
function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Auto-refresh chat messages every 500ms
setInterval(() => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/get-chat.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let data = xhr.response;
            let chatBox = document.querySelector(".chat-box");

            // ✅ Ensure chat messages are properly rendered as HTML
            chatBox.innerHTML = data;
            chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to bottom
        }
    };

    let formData = new FormData(document.querySelector(".typing-area"));
    xhr.send(formData);
}, 500);


// Event listener for dynamically generated "PAY" links
chatBox.addEventListener("click", (e) => {
    if (e.target.classList.contains("pay-link")) {
        e.preventDefault();
        window.location.href = e.target.href;
    }
});

    </script>
</body>
</html>
