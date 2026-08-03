<?php
session_start();
include("../includes/db.php");

// Login Check
if(!isset($_SESSION['data']))
{
    header("Location: ../login.php");
    exit();
}

// ================= UPDATE ORDER STATUS =================

if(isset($_POST['update_status']))
{
    $order_id = $_POST['order_id'];
    $status = $_POST['order_status'];

    mysqli_query($conn,
    "UPDATE orders
     SET order_status='$status'
     WHERE order_id='$order_id'");

    echo "<script>
    alert('Order Status Updated Successfully');
    window.location='orders.php';
    </script>";
}

// ================= VIEW ORDER =================

$order_id = "";

if(isset($_GET['view']))
{
    $order_id = $_GET['view'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

<div class="card">

<div class="card-header bg-primary text-white">
    <h3>Order Management</h3>
</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Order ID</th>
<th>Customer</th>
<th>Mobile</th>
<th>Total</th>
<th>Payment</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

$query = mysqli_query($conn,"
SELECT *
FROM orders
ORDER BY order_id DESC
");

while($row = mysqli_fetch_assoc($query))
{

?>

<tr>

<td><?php echo $row['order_id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['mobile']; ?></td>

<td>₹ <?php echo $row['total_amount']; ?></td>

<td><?php echo $row['payment_method']; ?></td>

<td>

<span class="badge bg-info">

<?php echo $row['order_status']; ?>

</span>

</td>

<td><?php echo $row['order_date']; ?></td>

<td>

<a href="orders.php?view=<?php echo $row['order_id'];?>"
class="btn btn-primary btn-sm">

View

</a>

</td>

</tr>

<?php
}
?>

</tbody>

</table>

<br>

<?php

if($order_id!="")
{

$order = mysqli_query($conn,
"SELECT * FROM orders WHERE order_id='$order_id'");

$data = mysqli_fetch_assoc($order);

?>

<div class="card">

<div class="card-header bg-success text-white">

<h4>Update Order Status</h4>

</div>

<div class="card-body">

<form method="post">

<input type="hidden"
name="order_id"
value="<?php echo $data['order_id']; ?>">

<div class="mb-3">

<label>Customer Name</label>

<input type="text"
class="form-control"
value="<?php echo $data['name']; ?>"
readonly>

</div>

<div class="mb-3">

<label>Order Status</label>

<select
name="order_status"
class="form-control">

<option value="Pending"
<?php if($data['order_status']=="Pending") echo "selected"; ?>>
Pending
</option>

<option value="Confirmed"
<?php if($data['order_status']=="Confirmed") echo "selected"; ?>>
Confirmed
</option>

<option value="Preparing"
<?php if($data['order_status']=="Preparing") echo "selected"; ?>>
Preparing
</option>

<option value="Out For Delivery"
<?php if($data['order_status']=="Out For Delivery") echo "selected"; ?>>
Out For Delivery
</option>

<option value="Delivered"
<?php if($data['order_status']=="Delivered") echo "selected"; ?>>
Delivered
</option>

<option value="Cancelled"
<?php if($data['order_status']=="Cancelled") echo "selected"; ?>>
Cancelled
</option>

</select>

</div>

<button
type="submit"
name="update_status"
class="btn btn-success">

Update Status

</button>

<a href="orders.php"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

<br>

<?php
}
?>

<?php

if($order_id!="")
{

$details = mysqli_query($conn,"
SELECT
order_details.*,
cake.cake_name,
cake.image
FROM order_details
INNER JOIN cake
ON order_details.cake_id=cake.cake_id
WHERE order_details.order_id='$order_id'
");

?>

<div class="card">

<div class="card-header bg-dark text-white">

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

</div>

</div>

<?php
}
?>

</div>

</body>
</html>
