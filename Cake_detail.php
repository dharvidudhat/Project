<?php
session_start();
include("../includes/db.php");

// Login Check
if(!isset($_SESSION['data']))
{
    header("Location: ../login.php");
    exit();
}

// Cake ID Check
if(!isset($_GET['id']))
{
    header("Location: index.php");
    exit();
}

$cake_id = $_GET['id'];

// Fetch Cake Details
$query = mysqli_query($conn,"
SELECT cake.*, category.category_name
FROM cake
INNER JOIN category
ON cake.category_id = category.cid
WHERE cake.cake_id='$cake_id'
AND cake.status='Active'
");

if(mysqli_num_rows($query)==0)
{
    header("Location: home.php");
    exit();
}

$row = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Cake Details</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card">

<div class="row">

<div class="col-md-5">

<img src="../uploads/cakes/<?php echo $row['image']; ?>"
class="img-fluid rounded"
style="height:400px;width:100%;object-fit:cover;">

</div>

<div class="col-md-7">

<div class="card-body">

<h2><?php echo $row['cake_name']; ?></h2>

<hr>

<p>

<b>Category :</b>

<?php echo $row['category_name']; ?>

</p>

<p>

<b>Price :</b>

₹ <?php echo $row['price']; ?>

</p>

<p>

<b>Description :</b>

<br>

<?php echo $row['description']; ?>

</p>

<br>

<a href="cart.php?add=<?php echo $row['cake_id']; ?>"
class="btn btn-success">

Add To Cart

</a>

<a href="home.php"
class="btn btn-secondary">

Back

</a>

</div>

</div>

</div>

</div>

</div>

</body>

</html>
