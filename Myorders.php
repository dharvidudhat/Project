<?php
session_start();
include("../includes/db.php");

// Login Check
if(!isset($_SESSION['data']))
{
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['data']['id'];

// ================= MY ORDERS =================

$query = mysqli_query($conn,"
SELECT *
FROM orders
WHERE user_id='$user_id'
ORDER BY order_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>

    <title>My Orders</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<div class="card">

<div class="card-header bg-primary text-white">

<h3>My Orders</h3>

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Order ID</th>
<th>Total Amount</th>
<th>Payment</th>
<th>Status</th>
<th>Order Date</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($query)>0)
{

while($row=mysqli_fetch_assoc($query))
{

?>

<tr>

<td><?php echo $row['order_id']; ?></td>

<td>₹ <?php echo $row['total_amount']; ?></td>

<td><?php echo $row['payment_method']; ?></td>

<td>

<span class="badge bg-success">

<?php echo $row['order_status']; ?>

</span>

</td>

<td><?php echo $row['order_date']; ?></td>

<td>

<a href="myorders.php?view=<?php echo $row['order_id']; ?>"

class="btn btn-info btn-sm">

View Details

</a>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="6" class="text-center text-danger">

No Orders Found

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<br>

<?php

if(isset($_GET['view']))
{

$order_id = $_GET['view'];

$details = mysqli_query($conn,"
SELECT
order_details.*,
cake.cake_name,
cake.image
FROM order_details
INNER JOIN cake
ON order_details.cake_id = cake.cake_id
WHERE order_details.order_id='$order_id'
");

?>

<div class="card">

<div class="card-header bg-success text-white">

<h4>Order Details</h4>

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-primary">

<tr>

<th>Image</th>
<th>Cake Name</th>
<th>Quantity</th>
<th>Price</th>
<th>Subtotal</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($details))
{

?>

<tr>

<td>

<img src="../uploads/cakes/<?php echo $row['image']; ?>"
width="80"
height="80">

</td>

<td><?php echo $row['cake_name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>₹ <?php echo $row['price']; ?></td>

<td>₹ <?php echo $row['subtotal']; ?></td>

</tr>

<?php
}
?>

</tbody>

</table>

<a href="myorders.php"
class="btn btn-secondary">

Back

</a>

</div>

</div>

<?php
}
?>

</div>

</body>
</html>
