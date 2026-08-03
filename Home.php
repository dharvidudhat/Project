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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<h2 class="text-center mb-4">
Welcome To Swiffin Cake House
</h2>

<div class="row">

<div class="col-md-3">

<h4>Categories</h4>

<div class="list-group">

<a href="home.php"
class="list-group-item list-group-item-action">

All Cakes

</a>

<?php

while($cat=mysqli_fetch_assoc($category_list))
{

?>

<a href="home.php?category=<?php echo $cat['cid'];?>"

class="list-group-item list-group-item-action">

<?php echo $cat['category_name']; ?>

</a>

<?php
}
?>

</div>

</div>

<div class="col-md-9">

<div class="row">

<?php

if(mysqli_num_rows($cake)>0)
{

while($row=mysqli_fetch_assoc($cake))
{

?>

<div class="col-md-4 mb-4">

<div class="card">

<img src="../uploads/cakes/<?php echo $row['image'];?>"

class="card-img-top"

height="220">

<div class="card-body">

<h5>

<?php echo $row['cake_name']; ?>

</h5>

<p>

Category :
<b><?php echo $row['category_name']; ?></b>

</p>

<p>

Price :
<b>₹ <?php echo $row['price']; ?></b>

</p>

<div class="mb-2">

<a href="cake_details.php?id=<?php echo $row['cake_id']; ?>"
class="btn btn-primary btn-sm">

View Details

</a>

<a href="cart.php?add=<?php echo $row['cake_id']; ?>"
class="btn btn-success btn-sm">

Add To Cart

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

<div class="alert alert-warning text-center">

No Cake Available

</div>

</div>

<?php

}

?>

</div>

</div>

</div>

</div>

</body>

</html>
