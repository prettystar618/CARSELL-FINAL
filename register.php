<?php
include 'config.php';

// Backend validation + handle POST request
$errorMsg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){ // Precisely check for POST request
    // 1. Receive and filter parameters
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    // 2. Backend validation (consistent with frontend rules to prevent bypass)
    $isValid = true;
    // Validate name: only letters and spaces
    if(!preg_match('/^[A-Za-z\s]+$/', $name)){
        $errorMsg = 'Name can only contain alphabetical letters and spaces';
        $isValid = false;
    }
    // Validate address: alphanumeric and spaces
    elseif(!preg_match('/^[A-Za-z0-9\s]+$/', $address)){
        $errorMsg = 'Address can only contain alphanumeric characters and spaces';
        $isValid = false;
    }
    // Validate phone: 11-digit China mobile number starting with 1
    elseif(!preg_match('/^1[3-9]\d{9}$/', $phone)){
        $errorMsg = 'Please enter a valid China phone number (11 digits starting with 1)';
        $isValid = false;
    }
    // Validate email: contains exactly one @ and ends with .cn or .com
    elseif(!preg_match('/^[^\s@]+@[^\s@]+\.(cn|com)$/', $email) || substr_count($email, '@') !== 1){
        $errorMsg = 'Email must contain @ exactly once and end with .cn or .com';
        $isValid = false;
    }
    // Validate username: at least 6 alphanumeric characters
    elseif(!preg_match('/^[A-Za-z0-9]{6,}$/', $username)){
        $errorMsg = 'Username must be at least 6 alphanumeric characters';
        $isValid = false;
    }
    // Validate password: at least 6 alphanumeric characters
    elseif(!preg_match('/^[A-Za-z0-9]{6,}$/', $password)){
        $errorMsg = 'Password must be at least 6 alphanumeric characters';
        $isValid = false;
    }
    // Validate password confirmation
    elseif($password !== $confirmPassword){
        $errorMsg = 'Passwords do not match';
        $isValid = false;
    }

    // 3. If validation passes, insert into database (prepared statement to prevent injection)
    if($isValid){
        // Hash password
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

        // First get max seller_id, auto generate new ID (avoid duplication)
        $maxIdSql = "SELECT MAX(seller_id) AS max_id FROM sellers";
        $maxIdResult = $conn->query($maxIdSql);
        $maxId = $maxIdResult->fetch_assoc()['max_id'] ?? 0;
        $newSellerId = $maxId + 1;

        // Prepared SQL (fields fully match sellers table: seller_id, name, username, password, email, address, phone, create_time)
        $sql = "INSERT INTO sellers (seller_id, name, username, password, email, address, phone, create_time) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if(!$stmt){ // Check if prepare succeeded
            $errorMsg = 'SQL prepare error: ' . $conn->error;
        } else {
            // Bind parameters (i=integer, s=string)
            $stmt->bind_param("issssss", $newSellerId, $name, $username, $hashedPwd, $email, $address, $phone);

            if($stmt->execute()){
                // Registration successful, force redirect (ensure execution)
                echo "<script>
                        alert('Registration successful!');
                        window.location.href = 'login.php';
                        </script>";
                exit; // Terminate subsequent code execution
            }else{
                // Precisely determine: username duplicate (assuming username is unique key)
                if($conn->errno === 1062){
                    $errorMsg = 'Username already exists!';
                }else{
                    $errorMsg = 'Registration failed: ' . $conn->error;
                }
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cabbage Car Sale - Register</title>
<style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:Arial,sans-serif;
    }
    body{
        background:#fff;
        color:#333;
    }
    .navbar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        background:#f5f5f5;
        padding:18px 40px;
        border-bottom:1px solid #eee;
        position: relative;
        width: 100%;
    }
    .logo{
        display:flex;
        align-items:center;
        gap:10px;
        font-size:24px;
        font-weight:bold;
        color:#1a73e8;
        text-decoration:none;
        position: relative;
    }
    .nav-links{
        display:flex;
        gap:30px;
        position: relative;
    }
    .nav-links a{
        text-decoration:none;
        color:#555;
        font-size:16px;
    }
    .nav-links a:hover{
        color:#1a73e8;
    }

    .register-container{
        max-width:500px;
        margin:50px auto;
        padding:0 20px;
    }
    .title{
        text-align:center;
        font-size:28px;
        margin-bottom:35px;
    }
    .register-form{
        background:#f9f9f9;
        padding:30px;
        border-radius:8px;
        box-shadow:0 2px 5px rgba(0,0,0,0.1);
    }
    .form-group{
        margin-bottom:20px;
    }
    .form-group label{
        display:block;
        margin-bottom:8px;
        font-size:16px;
        color:#555;
    }
    .form-group input{
        width:100%;
        padding:12px 15px;
        border:1px solid #ddd;
        border-radius:5px;
        font-size:16px;
        outline:none;
    }
    .form-group input:focus{
        border-color:#1a73e8;
    }
    .form-group .icon-input{
        position:relative;
    }
    .form-group .icon-input input{
        padding-right:15px;
    }
    .register-btn{
        width:100%;
        background:#1a73e8;
        color:#fff;
        padding:12px;
        border:none;
        border-radius:5px;
        font-size:17px;
        cursor:pointer;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        text-decoration:none;
    }
    .register-btn:hover{
        background:#0d5bbc;
    }
    .login-link{
        text-align:center;
        margin-top:20px;
        font-size:16px;
        color:#666;
    }
    .login-link a{
        color:#1a73e8;
        text-decoration:none;
    }
    .login-link a:hover{
        text-decoration:underline;
    }
    .footer{
        background:#f5f5f5;
        text-align:center;
        padding:25px;
        border-top:1px solid #eee;
        margin-top:40px;
        color:#666;
        position: relative;
        top: 120px;
        width: 100%;
    }
    .error-message {
        color: #dc3545;
        font-size:14px;
        margin-top:5px;
        display:none;
    }
    .global-error {
        color: #dc3545;
        font-size:16px;
        text-align:center;
        margin-bottom:20px;
        padding:10px;
        background:#f8d7da;
        border-radius:5px;
    }
</style>
</head>
<body>

<div class="navbar">
    <a href="home.php" class="logo">
        Cabbage
    </a>
    <div class="nav-links">
        <a href="search.php">Search</a>
        <a href="home.php">Home</a>
        <a href="seller.php">Seller</a>
        <a href="login.php">Login</a>
    </div>
</div>

<div class="register-container">
    <h2 class="title">Create Your Account</h2>
    <form class="register-form" id="registerForm" method="post">
        <!-- 全局错误提示 -->
        <?php if(!empty($errorMsg)): ?>
            <div class="global-error"><?php echo $errorMsg; ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Name</label>
            <div class="icon-input">
                <input type="text" id="name" name="name" placeholder="Enter your full name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>
            <div class="error-message" id="nameError">Name can only contain alphabetical letters and spaces</div>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <div class="icon-input">
                <input type="text" id="address" name="address" placeholder="Enter your address" required value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
            </div>
            <div class="error-message" id="addressError">Address can only contain alphanumeric characters and spaces</div>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <div class="icon-input">
                <input type="tel" id="phone" name="phone" placeholder="Enter your China phone number" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            </div>
            <div class="error-message" id="phoneError">Please enter a valid China phone number (11 digits starting with 1)</div>
        </div>
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="icon-input">
                <input type="email" id="email" name="email" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="error-message" id="emailError">Email must contain @ exactly once and end with .cn or .com</div>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <div class="icon-input">
                <input type="text" id="username" name="username" placeholder="Enter your username (at least 6 chars)" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="error-message" id="usernameError">Username must be at least 6 alphanumeric characters</div>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <div class="icon-input">
                <input type="password" id="password" name="password" placeholder="Create a password (at least 6 chars)" required>
            </div>
            <div class="error-message" id="passwordError">Password must be at least 6 alphanumeric characters</div>
        </div>
        <div class="form-group">
            <label for="confirm-password">Confirm Password</label>
            <div class="icon-input">
                <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <div class="error-message" id="confirmPasswordError">Passwords do not match</div>
        </div>
        <button type="submit" class="register-btn">
            Register Now
        </button>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </form>
</div>

<div class="footer">
    © 2026 Cabbage Car Sale. All rights reserved.
</div>

<script>
    const form = document.getElementById('registerForm');
    const nameInput = document.getElementById('name');
    const addressInput = document.getElementById('address');
    const phoneInput = document.getElementById('phone');
    const emailInput = document.getElementById('email');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');

    const nameError = document.getElementById('nameError');
    const addressError = document.getElementById('addressError');
    const phoneError = document.getElementById('phoneError');
    const emailError = document.getElementById('emailError');
    const usernameError = document.getElementById('usernameError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    function validateName() {
        const regex = /^[A-Za-z\s]+$/;
        if (!regex.test(nameInput.value.trim())) {
            nameError.style.display = 'block';
            return false;
        }
        nameError.style.display = 'none';
        return true;
    }
    function validateAddress() {
        const regex = /^[A-Za-z0-9\s]+$/;
        if (!regex.test(addressInput.value.trim())) {
            addressError.style.display = 'block';
            return false;
        }
        addressError.style.display = 'none';
        return true;
    }
    function validatePhone() {
        const regex = /^1[3-9]\d{9}$/;
        if (!regex.test(phoneInput.value.trim())) {
            phoneError.style.display = 'block';
            return false;
        }
        phoneError.style.display = 'none';
        return true;
    }
    function validateEmail() {
        const regex = /^[^\s@]+@[^\s@]+\.(cn|com)$/;
        const atCount = (emailInput.value.match(/@/g) || []).length;
        if (atCount !== 1 || !regex.test(emailInput.value.trim())) {
            emailError.style.display = 'block';
            return false;
        }
        emailError.style.display = 'none';
        return true;
    }
    function validateUsername() {
        const regex = /^[A-Za-z0-9]{6,}$/;
        if (!regex.test(usernameInput.value.trim())) {
            usernameError.style.display = 'block';
            return false;
        }
        usernameError.style.display = 'none';
        return true;
    }
    function validatePassword() {
        const regex = /^[A-Za-z0-9]{6,}$/;
        if (!regex.test(passwordInput.value.trim())) {
            passwordError.style.display = 'block';
            return false;
        }
        passwordError.style.display = 'none';
        return true;
    }
    function validateConfirmPassword() {
        if (confirmPasswordInput.value.trim() !== passwordInput.value.trim()) {
            confirmPasswordError.style.display = 'block';
            return false;
        }
        confirmPasswordError.style.display = 'none';
        return true;
    }

    nameInput.addEventListener('blur', validateName);
    addressInput.addEventListener('blur', validateAddress);
    phoneInput.addEventListener('blur', validatePhone);
    emailInput.addEventListener('blur', validateEmail);
    usernameInput.addEventListener('blur', validateUsername);
    passwordInput.addEventListener('blur', validatePassword);
    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const isNameValid = validateName();
        const isAddressValid = validateAddress();
        const isPhoneValid = validatePhone();
        const isEmailValid = validateEmail();
        const isUsernameValid = validateUsername();
        const isPasswordValid = validatePassword();
        const isConfirmPasswordValid = validateConfirmPassword();

        // 前端验证通过则提交表单（不再手动跳转，交给后端处理）
        if (isNameValid && isAddressValid && isPhoneValid && isEmailValid && isUsernameValid && isPasswordValid && isConfirmPasswordValid) {
            form.submit();
        }
    });
</script>
</body>
</html>