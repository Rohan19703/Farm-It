<?php include_once "layout/header.php"; ?>

<style>
    body {
        background-image: url('farmer.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
    .wrapper {
        font-family: Arial, sans-serif;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        margin-left: 350px; 
        padding: 40px;
        box-shadow: 0 0 10px rgba(0.5, 0.5, 0.5, 1.0);
        border-radius: 8px;
        background-color: rgba(255, 255, 255, 0.5);
    }
    .form.signup header {
        font-size: 24px;
        margin-bottom: 20px;
    }
    .field.input label {
        font-size: 16px;
    }
    .field.input input {
        font-size: 16px;
    }
    .field.button input {
        font-size: 18px;
    }
    .error-txt {
        color: red;
        margin-bottom: 15px;
        display: none;
    }
</style>

<body>
    <div class="wrapper">
        <section class="form signup">
            <header>FARM-IT</header>
            <form action="#" enctype="multipart/form-data">
                <div class="error-txt"></div>
                <div class="name-details">
                    <div class="field input">
                        <label>Your Name</label>
                        <input type="text" name="fname" placeholder="Your Name" required>
                    </div>
                    <div class="field input">
                        <label>Role</label>
                        <input type="text" name="lname" placeholder="Farmer or Vendor" required>
                    </div>
                </div>
                <div class="field input">
                    <label>Email Address</label>
                    <input type="text" name="email" placeholder="Enter your email" required>
                </div>
                <div class="field input">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter new password" required>
                    <i class="fas fa-eye"></i>
                </div>
                <div class="field image">
                    <label>Select Profile Image</label>
                    <input type="file" name="image" required>
                </div>
                <div class="field image">
                    <label>Select QR Code</label>
                    <input type="file" name="qr_code" required>
                </div>
                <div class="field button">
                    <input type="submit" value="Register">
                </div>
                <div class="link">Already signed up? <a href="login.php">Login Now</a></div>
            </form>
        </section>
    </div>

    <script>
        const form = document.querySelector(".signup form"),
            continueBtn = form.querySelector(".button input"),
            errorText = form.querySelector(".error-txt"),
            passwordInput = form.querySelector("input[name='password']");

        form.onsubmit = (e) => {
            e.preventDefault();
            validatePassword();
        };

        function validatePassword() {
            const password = passwordInput.value;
            const passwordPattern = /^(?=.*[@#*]).{7,}$/;

            if (!passwordPattern.test(password)) {
                errorText.textContent = "Password must be at least 7 characters long and include @, #, or *.";
                errorText.style.display = "block";
                return false;
            }

            errorText.style.display = "none";
            sendFormData();
        }

        function sendFormData() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "php/signup.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    let data = xhr.response;
                    if (data == "success") {
                        location.href = "users.php";
                    } else {
                        errorText.textContent = data;
                        errorText.style.display = "block";
                    }
                }
            };
            let formData = new FormData(form);
            xhr.send(formData);
        }
    </script>
</body>
</html>
