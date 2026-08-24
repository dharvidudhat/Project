<?php
session_start();
include("../includes/db.php");

// Login Check
if(!isset($_SESSION['data']))
{
    header("Location: ../login.php");
    exit();
}

// Category Filter
$category = "";

if(isset($_GET['category']))
{
    $category = $_GET['category'];
}

// Cake List
if($category!="")
{
    $cake = mysqli_query($conn,"
    SELECT cake.*, category.category_name
    FROM cake
    INNER JOIN category
    ON cake.category_id = category.cid
    WHERE cake.category_id='$category'
    AND cake.status='Active'
    ORDER BY cake.cake_id DESC");
}
else
{
    $cake = mysqli_query($conn,"
    SELECT cake.*, category.category_name
    FROM cake
    INNER JOIN category
    ON cake.category_id = category.cid
    WHERE cake.status='Active'
    ORDER BY cake.cake_id DESC");
}

// Category List
$category_list = mysqli_query($conn,"
SELECT * FROM category
ORDER BY category_name ASC");
?>

<!DOCTYPE html>
<html>
<head>

    <title>Swiffin Cake House</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

    <style>

        body{
            margin:0;
            padding:0;
            background:#fff7f8;
            font-family:Arial, sans-serif;
        }

        /* Header */

        .top-header{
            background:linear-gradient(135deg,#ff6b81,#ff8fa3);
            color:white;
            padding:25px 0;
            box-shadow:0 3px 10px rgba(0,0,0,0.15);
        }

        .shop-title{
            font-size:32px;
            font-weight:bold;
            margin:0;
        }

        .shop-subtitle{
            margin-top:5px;
            font-size:15px;
        }

        .logout-btn{
            background:white;
            color:#ff4f6d;
            border:none;
            padding:9px 18px;
            border-radius:20px;
            font-weight:bold;
            text-decoration:none;
        }

        .logout-btn:hover{
            background:#ffe5ea;
            color:#ff3154;
        }


        /* Main Container */

        .main-container{
            margin-top:35px;
            margin-bottom:50px;
        }


        /* Category Box */

        .category-box{
            background:white;
            border-radius:15px;
            padding:20px;
            box-shadow:0 3px 15px rgba(0,0,0,0.08);
            position:sticky;
            top:20px;
        }

        .category-title{
            font-size:22px;
            font-weight:bold;
            color:#333;
            margin-bottom:15px;
        }

        .category-link{
            border:none !important;
            margin-bottom:8px;
            border-radius:10px !important;
            padding:12px 15px;
            color:#555;
            background:#fff1f3;
            font-weight:500;
            transition:0.3s;
        }

        .category-link:hover{
            background:#ff6b81;
            color:white;
            transform:translateX(5px);
        }

        .category-link.active{
            background:#ff6b81;
            color:white;
        }


        /* Cake Heading */

        .cake-heading{
            font-size:27px;
            font-weight:bold;
            color:#333;
            margin-bottom:25px;
        }


        /* Cake Card */

        .cake-card{
            border:none;
            border-radius:15px;
            overflow:hidden;
            background:white;
            box-shadow:0 4px 15px rgba(0,0,0,0.10);
            transition:0.3s;
            height:100%;
        }

        .cake-card:hover{
            transform:translateY(-7px);
            box-shadow:0 8px 25px rgba(0,0,0,0.16);
        }

        .cake-image{
            width:100%;
            height:220px;
            object-fit:cover;
        }

        .cake-body{
            padding:18px;
        }

        .cake-name{
            font-size:20px;
            font-weight:bold;
            color:#333;
            margin-bottom:8px;
        }

        .cake-category{
            color:#777;
            font-size:14px;
            margin-bottom:8px;
        }

        .cake-price{
            font-size:21px;
            font-weight:bold;
            color:#ff4f6d;
            margin-bottom:15px;
        }


        /* Buttons */

        .view-btn{
            background:#6c63ff;
            border:none;
            border-radius:8px;
            padding:8px 14px;
        }

        .view-btn:hover{
            background:#5148e5;
        }

        .cart-btn{
            background:#28a745;
            border:none;
            border-radius:8px;
            padding:8px 14px;
        }

        .cart-btn:hover{
            background:#218838;
        }


        /* No Cake */

        .no-cake{
            background:white;
            border:none;
            border-radius:15px;
            padding:30px;
            text-align:center;
            color:#777;
            box-shadow:0 3px 15px rgba(0,0,0,0.08);
        }


        /* Footer */

        .footer{
            background:#222;
            color:white;
            text-align:center;
            padding:20px;
            margin-top:30px;
        }


        /* Responsive */

        @media(max-width:768px){

            .shop-title{
                font-size:25px;
            }

            .category-box{
                position:static;
                margin-bottom:25px;
            }

            .cake-image{
                height:200px;
            }

        }

    </style>

</head>

<body>


<!-- Header -->

<div class="top-header">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h1 class="shop-title">
                    🍰 Swiffin Cake House
                </h1>

                <div class="shop-subtitle">
                    Fresh & Delicious Cakes For Every Occasion
                </div>

            </div>

            <div>

                <a href="logout.php" class="logout-btn">
                    Logout
                </a>

            </div>

        </div>

    </div>

</div>


<!-- Main Content -->

<div class="container main-container">

    <div class="row">


        <!-- Category -->

        <div class="col-md-3">

            <div class="category-box">

                <div class="category-title">
                    📂 Categories
                </div>

                <div class="list-group">


                    <!-- All Cakes -->

                    <a href="home.php"
                    class="list-group-item list-group-item-action category-link
                    <?php
                    if($category=="")
                    {
                        echo "active";
                    }
                    ?>">

                        🎂 All Cakes

                    </a>


                    <?php

                    while($cat=mysqli_fetch_assoc($category_list))
                    {

                    ?>

                    <a href="home.php?category=<?php echo $cat['cid'];?>"

                    class="list-group-item list-group-item-action category-link
                    <?php
                    if($category==$cat['cid'])
                    {
                        echo "active";
                    }
                    ?>">

                        🍰 <?php echo $cat['category_name']; ?>

                    </a>

                    <?php

                    }

                    ?>

                </div>

            </div>

        </div>


        <!-- Cake List -->

        <div class="col-md-9">


            <div class="cake-heading">

                <?php

                if($category!="")
                {
                    echo "Cakes";
                }
                else
                {
                    echo "Our Delicious Cakes";
                }

                ?>

            </div>


            <div class="row">


                <?php

                if(mysqli_num_rows($cake)>0)
                {

                    while($row=mysqli_fetch_assoc($cake))
                    {

                ?>

                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="cake-card">


                        <!-- Cake Image -->

                        <img src="../uploads/cakes/<?php echo $row['image'];?>"

                        class="cake-image"

                        alt="<?php echo $row['cake_name'];?>">


                        <div class="cake-body">


                            <!-- Cake Name -->

                            <div class="cake-name">

                                <?php echo $row['cake_name']; ?>

                            </div>


                            <!-- Category -->

                            <div class="cake-category">

                                Category :
                                <b>
                                    <?php echo $row['category_name']; ?>
                                </b>

                            </div>


                            <!-- Price -->

                            <div class="cake-price">

                                ₹ <?php echo $row['price']; ?>

                            </div>


                            <!-- Buttons -->

                            <div class="d-flex gap-2">

                                <a href="cake_details.php?id=<?php echo $row['cake_id'];?>"

                                class="btn btn-primary btn-sm view-btn">

                                    View Details

                                </a>


                                <a href="cart.php?add=<?php echo $row['cake_id'];?>"

                                class="btn btn-success btn-sm cart-btn">

                                    🛒 Add To Cart

                                </a>

                            </div>


                        </div>

                    </div>

                </div>


                <?php

                    }

                }

                else

                {

                ?>

                <div class="col-12">

                    <div class="no-cake">

                        <h4>
                            😔 No Cake Available
                        </h4>

                        <p>
                            Please select another category.
                        </p>

                    </div>

                </div>

                <?php

                }

                ?>


            </div>

        </div>


    </div>

</div>


<!-- Footer -->

<div class="footer">

    <div>
        © 2026 Swiffin Cake House
    </div>

    <small>
        Fresh Cakes • Best Quality • Happy Customers
    </small>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>
