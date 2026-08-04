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

// Check Order ID
if(!isset($_GET['order_id']))
{
    header("Location: myorders.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch Order
$order = mysqli_query($conn,
"SELECT * FROM orders
WHERE order_id='$order_id'
AND user_id='$user_id'");

$order_data = mysqli_fetch_assoc($order);

// Save Payment
if(isset($_POST['pay_now']))
{
    $payment_method = $_POST['payment_method'];

    $payment_status = "Paid";

    mysqli_query($conn,
    "INSERT INTO payment
    (order_id,user_id,payment_method,payment_status,payment_date)
    VALUES
    ('$order_id','$user_id',
    '$payment_method',
    '$payment_status',
    NOW())");

    mysqli_query($conn,
    "UPDATE orders
    SET order_status='Confirmed'
    WHERE order_id='$order_id'");

    echo "<script>
    alert('Payment Successful');
    window.location='myorders.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Payment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card">

<div class="card-header bg-primary text-white">

<h3>Payment</h3>

</div>

<div class="card-body">

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
    <th>Total Amount</th>
    <td>₹ <?php echo $order_data['total_amount']; ?></td>
</tr>

</table>

<form method="post">

<div class="mb-3">

<label>Payment Method</label>

<select
name="payment_method"
class="form-control"
required>

<option value="">Select Payment Method</option>

<option value="Cash On Delivery">
Cash On Delivery
</option>

<option value="Online Payment">
Online Payment
</option>

</select>

</div>

<div class="text-end">

<button
type="submit"
name="pay_now"
class="btn btn-success">

Pay Now

</button>

<a href="myorders.php"
class="btn btn-secondary">

Back

</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>
