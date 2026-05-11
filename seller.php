<?php 
session_start(); 
include 'config.php';
if(!isset($_SESSION['seller_id'])) header("Location:login.php");

// Handle form submission
if($_POST){
    //Receive and filter form data
    $color = mysqli_real_escape_string($conn, trim($_POST['color']));
    $model = mysqli_real_escape_string($conn, trim($_POST['model']));
    $year = (int)$_POST['year'];
    $location = mysqli_real_escape_string($conn, trim($_POST['location']));
    $price = (float)$_POST['price'];
    $seller_id = $_SESSION['seller_id'];

    // Receive and filter form data
    $uploadOk = 1;
    $car_image = '';
    if(isset($_FILES['carImage']) && $_FILES['carImage']['error'] == 0){
        $targetDir = "uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        
        $fileInfo = pathinfo($_FILES['carImage']['name']);
        $fileName = uniqid('car_') . '.' . $fileInfo['extension'];
        $targetFile = $targetDir . $fileName;
        
        // Verify file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['carImage']['tmp_name']);
        
        // Verify file size
        if($_FILES['carImage']['size'] > 5 * 1024 * 1024){
            $uploadOk = 0;
            $errorMsg = "File size cannot exceed 5MB";
        }
        if(!in_array($fileType, $allowedTypes)){
            $uploadOk = 0;
            $errorMsg = "only support jpg. png.";
        }
        
        // Submit the form if verification passes
        if($uploadOk && move_uploaded_file($_FILES['carImage']['tmp_name'], $targetFile)){
            $car_image = $targetFile;
        }else{
            $uploadOk = 0;
            $errorMsg = $errorMsg ?: "Image upload failed";
        }
    }else{
        $uploadOk = 0;
        $errorMsg = "please uplode photo";
    }

    // Submit the form if verification passes
    if($uploadOk && !empty($color) && !empty($model) && $year >=1900 && $year <=2025 && !empty($location) && $price >0){
        $sql = "INSERT INTO cars (seller_id,model,year,color,location,price,car_image) VALUES (?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isissds", $seller_id, $model, $year, $color, $location, $price, $car_image);
        
        if($stmt->execute()){
            echo "<script>alert('Vehicle added successfully ');</script>";
            echo "<script>window.location.href='home.php';</script>";
        }else{
            echo "<script>alert('Addition failed：" . $stmt->error . "');</script>";
        }
        $stmt->close();
    }else{
        $errorMsg = $errorMsg ?: "pealse cheack：The year must be between 1900 and 2025, the price must be greater than 0, and all required fields cannot be empty.";
        echo "<script>alert('Addition failed：" . $errorMsg . "');</script>";
    }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabbage Car Sale</title>
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

        .container{
            position: relative;
            left: 50%;
            top: 80px;
            transform: translateX(-50%);
            width: 600px;
            margin-bottom: 120px;
        }
        .page-title{
            text-align:center;
            font-size:28px;
            margin-bottom:35px;
            color:#333;
        }
        .form-group{
            margin-bottom: 25px;
        }
        label{
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color:#333;
            font-size: 16px;
        }
        input{
            width: 100%;
            padding:12px;
            border:1px solid #eee;
            border-radius:5px;
            font-size:16px;
            outline:none;
        }
        input:focus {
            border-color:#1a73e8;
        }

        .submit-btn {
            display:inline-flex;
            align-items:center;
            gap:8px;
            width:100%;
            background:#1a73e8;
            color:#fff;
            padding:12px 25px;
            border-radius:5px;
            border:none;
            font-size:16px;
            cursor:pointer;
            justify-content:center;
        }
        .submit-btn:hover {
            background:#0d5bbc;
        }

        .error {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
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

        <div class="navbar">
    <a href="home.php" class="logo">Cabbage</a>
    <div class="nav-links">
        <a href="search.php">Search</a>
        <a href="home.php">Home</a>
        <a href="seller.php">Seller</a>
        <a href="login.php">Login</a>
    </div>
</div>

    <div class="container">
        <h2 class="page-title">Add New Car</h2>
        <form id="addCarForm" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Car Color</label>
                <input type="text" id="color" name="color">
                <div class="error" id="colorError">Please enter car color</div>
            </div>

            <div class="form-group">
                <label>Car Model</label>
                <input type="text" id="model" name="model">
                <div class="error" id="modelError">Please enter car model</div>
            </div>

            <div class="form-group">
                <label>Manufacture Year</label>
                <input type="number" id="year" name="year" min="1900" max="2025">
                <div class="error" id="yearError">Please enter valid year (1900-2025)</div>
            </div>

            <div class="form-group">
                <label>Car Location</label>
                <input type="text" id="location" name="location">
                <div class="error" id="locationError">Please enter car location</div>
            </div>

            <div class="form-group">
                <label>Price (¥)</label>
                <input type="number" id="price" name="price" min="0.01" step="0.01">
                <div class="error" id="priceError">Please enter valid price (>0)</div>
            </div>

            <div class="form-group">
                <label>Car Image</label>
                <input type="file" id="carImage" name="carImage" accept="image/*">
                <div class="error" id="imageError">Please upload car image</div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fa-solid fa-plus"></i> Submit Car Information
            </button>
        </form>
    </div>

    <div class="footer">
        © 2026 Cabbage Car Sale
    </div>

<script>
    var form = document.getElementById("addCarForm");
    form.onsubmit = function(e){
        e.preventDefault();

        // Submit the form if verification passes：color/model/year/location/price/carImage
        var color = document.getElementById("color").value.trim();
        var model = document.getElementById("model").value.trim();
        var year = document.getElementById("year").value.trim();
        var location = document.getElementById("location").value.trim();
        var price = document.getElementById("price").value.trim();
        var img = document.getElementById("carImage").files[0];

        // Submit the form if verification passes
        document.getElementById("colorError").style.display = "none";
        document.getElementById("modelError").style.display = "none";
        document.getElementById("yearError").style.display = "none";
        document.getElementById("locationError").style.display = "none";
        document.getElementById("priceError").style.display = "none";
        document.getElementById("imageError").style.display = "none";

        var isValid = true;

        if(color === ""){
            document.getElementById("colorError").style.display = "block";
            isValid = false;
        }
        if(model === ""){
            document.getElementById("modelError").style.display = "block";
            isValid = false;
        }
        if(isNaN(year) || year < 1900 || year > 2025){
            document.getElementById("yearError").style.display = "block";
            isValid = false;
        }
        if(location === ""){
            document.getElementById("locationError").style.display = "block";
            isValid = false;
        }
        if(isNaN(price) || parseFloat(price) <= 0){
            document.getElementById("priceError").style.display = "block";
            isValid = false;
        }
        if(!img){
            document.getElementById("imageError").style.display = "block";
            isValid = false;
        }

        // Submit the form if verification passes
        if(isValid){
            form.submit(); // Submit the form to the backend for processing
        }
    }
</script>
</body>
</html>