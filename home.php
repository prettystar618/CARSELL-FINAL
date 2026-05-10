<!DOCTYPE html>
<html lang="en">
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
        }
        .logo{
            display:flex;
            align-items:center;
            gap:10px;
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

        /* 火爆车型 我用的是三菱翼神*/
        .banner{
            width:100%;
            height:480px;
            background:url("banner.jpg") center center / cover no-repeat;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            text-align:center;
            position:relative;
        }
        .banner::before{
            content:"";
            position:absolute;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.3);
        }
        .banner-text{
            position:relative;
            z-index:2;
        }
        .banner h1{
            font-size:40px;
            margin-bottom:15px;
        }
        .banner p{
            font-size:18px;
        }

        /* 推荐 四列两排 */
        .container{
            max-width:1300px;
            margin:50px auto;
            padding:0 20px;
        }
        .title{
            text-align:center;
            font-size:28px;
            margin-bottom:35px;
        }

       
        .car-list{
            display:grid;
            grid-template-columns:1fr 1fr 1fr 1fr;
            gap:20px;
        }

        .car-item{
            background:#f9f9f9;
            border-radius:8px;
            overflow:hidden;
            box-shadow:0 2px 5px rgba(0,0,0,0.1);
        }
        /* 车辆图片 */
        .car-item img{
            width:100%;
            height:200px;
            object-fit:cover;
        }
        .car-info{
            padding:20px;
        }
        .car-name{
            font-size:20px;
            margin-bottom:8px;
        }
        .car-price{
            font-size:17px;
            color:#1a73e8;
            font-weight:bold;
            margin-bottom:15px;
        }
        .buy-btn{
            display:inline-block;
            background:#1a73e8;
            color:#fff;
            padding:10px 20px;
            border-radius:5px;
            text-decoration:none;
        }
        .buy-btn:hover{
            background:#0d5bbc;
        }
        .footer{
            background:#f5f5f5;
            text-align:center;
            padding:25px;
            border-top:1px solid #eee;
            margin-top:40px;
            color:#666;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href=" " class="logo">
        Cabbage
    </a>
    <div class="nav-links">
        <a href="search.php">Search</a>
        <a href="home.php">Home</a>
        <a href="seller.php">Seller</a>
        <a href="login.php">Login</a>
    </div>
</div>

<!-- 火爆车图片 -->
<div class="banner">
    <div class="banner-text">
        <h1>Hot Selling Cars</h1>
        <p>Find your ideal car with Cabbage Car Sale</p>
    </div>
</div>

<!-- 推荐4列2排 -->
<div class="container">
    <h2 class="title">Recommended Cars</h2>
    <div class="car-list">

        <!-- car1 -->
        <div class="car-item">
            <img src="car1.jpg" alt="Car 1">
            <div class="car-info">
                <div class="car-name">Toyota Camry</div>
                <div class="car-price">¥195,000</div>
                <a href="detail.php" class="buy-btn">
                    Buy
                </a>
            </div>
        </div>

        <!-- car2 -->
        <div class="car-item">
            <img src="car2.jpg" alt="Car 2">
            <div class="car-info">
                <div class="car-name">Honda CR-V</div>
                <div class="car-price">¥225,000</div>
                <a href="detail.php" class="buy-btn">
                    Buy
                </a>
            </div>
        </div>

        <!-- car3 -->
        <div class="car-item">
            <img src="car3.jpg" alt="Car 3">
            <div class="car-info">
                <div class="car-name">BMW X5</div>
                <div class="car-price">¥850,000</div>
                <a href="detail.php" class="buy-btn">
                    Buy
                </a>
            </div>
        </div>

        <!-- car4 -->
        <div class="car-item">
            <img src="car4.jpg" alt="Car 4">
            <div class="car-info">
                <div class="car-name">Audi A6L</div>
                <div class="car-price">¥460,000</div>
                <a href="detail.php" class="buy-btn">
                    Buy
                </a>
            </div>
        </div>

        <!-- car5 -->
        <div class="car-item">
            <img src="car5.jpg" alt="Car 5">
            <div class="car-info">
                <div class="car-name">Mercedes C-Class</div>
                <div class="car-price">¥380,000</div>
                <a href="detail.php" class="buy-btn">
                    Buy
                </a>
            </div>
        </div>

        <!-- car6 -->
        <div class="car-item">
            <img src="car6.jpg" alt="Car 6">
            <div class="car-info">
                <div class="car-name">Volkswagen Magotan</div>
                <div class="car-price">¥190,000</div>
                <a href="detail.php" class="buy-btn">
                    Buy
                </a>
            </div>
        </div>

        <!-- car7 -->
        <div class="car-item">
            <img src="car7.jpg" alt="Car 7">
            <div class="car-info">
                <div class="car-name">Nissan Sylphy</div>
                <div class="car-price">¥120,000</div>
                <a href="detail.php" class="buy-btn">
                    Buy
                </a>
            </div>
        </div>

        <!-- car8 -->
        <div class="car-item">
            <img src="car8.jpg" alt="Car 8">
            <div class="car-info">
                <div class="car-name">Hyundai Tucson</div>
                <div class="car-price">¥175,000</div>
                <a href="detail.php" class="buy-btn">
                    Buy
                </a>
            </div>
        </div>

    </div>
</div>

<div class="footer">
    © 2026 Cabbage Car Sale. All rights reserved.
</div>

</body>
</html>