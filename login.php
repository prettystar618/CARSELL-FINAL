<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

$errorMsg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if(empty($username) || empty($password)){
        $errorMsg = 'Username and password cannot be empty!';
    } else {
        $sql = "SELECT * FROM sellers WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows === 1){
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])){
                $_SESSION['seller_id'] = $user['seller_id'];
                $_SESSION['username'] = $user['username'];
                echo "<script>
                        alert('Login successful!');
                        window.location.href = 'home.php';
                      </script>";
                exit;
            } else {
                $errorMsg = 'Incorrect password!';
            }

            // if($password === $user['password']){
            //     $_SESSION['seller_id'] = $user['seller_id'];
            //     $_SESSION['username'] = $user['username'];
            //     echo "<script>
            //             alert('Login successful!');
            //             window.location.href = 'home.php';
            //           </script>";
            //     exit;
            // } else {
            //     $errorMsg = 'Incorrect password!';
            // }
        } else {
            $errorMsg = 'Username does not exist!';
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabbage Car Sale - Login</title>
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
            width: 100%;
        }
        .logo{
            font-size:24px;
            font-weight:bold;
            color:#1a73e8;
            text-decoration:none;
        }
        .nav-links{
            display:flex;
            gap:30px;
        }
        .nav-links a{
            text-decoration:none;
            color:#555;
            font-size:16px;
        }
        .nav-links a:hover{
            color:#1a73e8;
        }
        .login-container{
            max-width:400px;
            margin:80px auto;
            padding:0 20px;
        }
        .title{
            text-align:center;
            font-size:28px;
            margin-bottom:35px;
            color:#1a73e8;
        }
        .login-form{
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
        .login-btn{
            width:100%;
            background:#1a73e8;
            color:#fff;
            padding:12px;
            border:none;
            border-radius:5px;
            font-size:17px;
            cursor:pointer;
        }
        .login-btn:hover{
            background:#0d5bbc;
        }
        .register-link{
            text-align:center;
            margin-top:20px;
            font-size:16px;
            color:#666;
        }
        .register-link a{
            color:#1a73e8;
            text-decoration:none;
        }
        .register-link a:hover{
            text-decoration:underline;
        }
        .error-message{
            color:#dc3545;
            text-align:center;
            margin-bottom:15px;
            padding:10px;
            background:#f8d7da;
            border-radius:5px;
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
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.php" class="logo">Cabbage</a>
        <div class="nav-links">
            <a href="search.php">Search</a>
            <a href="home.php">Home</a>
            <a href="seller.php">Seller</a>
            <a href="login.php">Login</a>
        </div>
    </div>

    <div class="login-container">
        <h2 class="title">Login to Your Account</h2>
        <div class="login-form">
            <?php if(!empty($errorMsg)): ?>
                <div class="error-message"><?php echo $errorMsg; ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="login-btn">Login Now</button>
                
                <div class="register-link">
                    Don't have an account? <a href="register.php">Register here</a>
                </div>
            </form>
        </div>
    </div>

    <div class="footer">
        © 2026 Cabbage Car Sale. All rights reserved.
    </div>
</body>
</html>