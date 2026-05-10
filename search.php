<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Cars</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f5f5f5;
            padding: 18px 40px;
            border-bottom: 1px solid #ddd;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: bold;
            color: #1a73e8;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: #666;
            font-size: 16px;
        }

        .nav-links a:hover {
            color: #1a73e8;
        }

        .search-box {
            background-color: #f8f9fa;
            padding: 40px 5%;
            text-align: center;
        }

        .search-box h2 {
            margin-bottom: 20px;
            font-size: 26px;
        }

        .search-form {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .search-form input,
        .search-form select {
            padding: 12px 16px;
            min-width: 140px;
            flex: 1;
            max-width: 200px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
        }

        .search-btn {
            padding: 12px 28px;
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        .section-title {
            padding: 30px 5% 10px;
            font-size: 22px;
        }
        .show-all-container {
            text-align: center;
            padding: 20px 0;
        }
        .show-all-btn {
            display: inline-block;
            padding: 12px 32px;
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            text-decoration: none;
            font-weight: 500;
        }

        .car-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
            padding: 0 5% 20px;
        }

        .car-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .car-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background-color: #eee;
        }

        .car-info {
            padding: 16px;
        }

        .car-model {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .car-year, .car-color {
            font-size: 14px;
            color: #666;
            margin-bottom: 4px;
        }

        .car-price {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #1a73e8;
        }

        .footer {
            background: #f5f5f5;
            text-align: center;
            padding: 25px;
            border-top: 1px solid #ddd;
            margin-top: 40px;
            color: #666;
        }

        .no-result {
            text-align: center;
            padding: 40px 20px;
            font-size: 16px;
            color: #666;
            grid-column: 1 / -1;
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

<div class="search-box">
    <h2>Find Your Ideal Car</h2>
    <form method="GET" class="search-form">
        <input type="text" name="model" placeholder="Car Model">
        <input type="number" name="year" placeholder="Year">
        <button type="submit" class="search-btn">Search</button>
    </form>
</div>

<div class="section-title">Available Cars</div>
<div class="car-grid">
    <?php
    $model = $_GET['model'] ?? '';
    $year = $_GET['year'] ?? '';
    $show_all = isset($_GET['show_all']) && $_GET['show_all'] == '1';

    $sql = "SELECT * FROM cars WHERE model LIKE '%$model%' AND year LIKE '%$year%'";
    $result = $conn->query($sql);

    $i = 0;
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $i++;
            if(!$show_all && $i > 8) break;

            echo '
            <div class="car-card">
                <img src="'.$row['car_image'].'" class="car-img">
                <div class="car-info">
                    <div class="car-model">'.$row['model'].'</div>
                    <div class="car-year">Year: '.$row['year'].'</div>
                    <div class="car-color">Color: '.$row['color'].'</div>
                    <div class="car-price">¥'.$row['price'].'</div>
                </div>
            </div>
            ';
        }
    } else {
        echo '<div class="no-result">No cars found</div>';
    }
    ?>
</div>
<div class="show-all-container">
    <a href="?<?php echo http_build_query(array_merge($_GET, ['show_all' => $show_all ? '0' : '1'])); ?>" class="show-all-btn">
        <?php echo $show_all ? 'Show Only 8 Cars' : 'Show All Cars'; ?>
    </a>
</div>

<div class="footer">
    © 2026 Cabbage Car Sale
</div>

</body>
</html>