<?php
session_start();
include("../includes/db.php");

// Login Check
if(!isset($_SESSION['data']))
{
    header("Location: ../login.php");
    exit();
}

// Check Order ID
if(!isset($_GET['order_id']))
{
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch Order Information
$order = mysqli_query($conn,"
SELECT *
FROM orders
WHERE order_id='$order_id'
");

$order_data = mysqli_fetch_assoc($order);

// Fetch Order Details
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

<!DOCTYPE html>
<html>
<head>

    <title>Order Details</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<div class="card">

<div class="card-header bg-primary text-white">

<h3>Order Details</h3>

</div>

<div class="card-body">

<h5>Customer Information</h5>

<table class="table table-bordered">

<tr>
    <th>Order ID</th>
    <td><?php echo $order_data['order_id']; ?></td>
</tr>

<tr>
    <th>Customer Name</th>
    <td><?php echo $order_data['name']; ?></td>
</tr>

<tr>
    <th>Mobile</th>
    <td><?php echo $order_data['mobile']; ?></td>
</tr>

<tr>
    <th>Address</th>
    <td><?php echo $order_data['address']; ?></td>
</tr>

<tr>
    <th>Payment Method</th>
    <td><?php echo $order_data['payment_method']; ?></td>
</tr>

<tr>
    <th>Order Status</th>
    <td><?php echo $order_data['order_status']; ?></td>
</tr>

</table>

<h5 class="mt-4">Ordered Cakes</h5>

<table class="table table-bordered table-hover">

<thead class="table-dark">

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
$total = 0;

while($row=mysqli_fetch_assoc($details))
{
$total += $row['subtotal'];
?>

<tr>

<td>
<img src="../uploads/cakes/<?php echo $row['image']; ?>"
width="80" height="80">
</td>

<td><?php echo $row['cake_name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>₹ <?php echo $row['price']; ?></td>

<td>₹ <?php echo $row['subtotal']; ?></td>

</tr>

<?php
}
?>

<tr>

<th colspan="4" class="text-end">

Grand Total

</th>

<th>

₹ <?php echo $total; ?>

</th>

</tr>

</tbody>

</table>

<a href="orders.php" class="btn btn-secondary">

Back

</a>

</div>

</div>

</div>

</body>
</html>
