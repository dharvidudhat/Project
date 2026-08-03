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

// ================= PLACE ORDER =================

if(isset($_POST['place_order']))
{
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $mobile = mysqli_real_escape_string($conn,$_POST['mobile']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);
    $payment = mysqli_real_escape_string($conn,$_POST['payment']);

    // Grand Total
    $total = 0;

    $cart = mysqli_query($conn,
    "SELECT * FROM cart WHERE user_id='$user_id'");

    while($item=mysqli_fetch_assoc($cart))
    {
        $total += $item['subtotal'];
    }

    // Save Order
    mysqli_query($conn,
    "INSERT INTO orders
    (user_id,name,mobile,address,payment_method,total_amount,order_status,order_date)
    VALUES
    ('$user_id','$name','$mobile','$address','$payment','$total','Pending',NOW())");

    $order_id = mysqli_insert_id($conn);

    // Save Order Details
    $cart = mysqli_query($conn,
    "SELECT * FROM cart WHERE user_id='$user_id'");

    while($item=mysqli_fetch_assoc($cart))
    {
        mysqli_query($conn,
        "INSERT INTO order_details
        (order_id,cake_id,quantity,price,subtotal)
        VALUES
        (
        '$order_id',
        '".$item['cake_id']."',
        '".$item['quantity']."',
        '".$item['price']."',
        '".$item['subtotal']."'
        )");
    }

    // Empty Cart
    mysqli_query($conn,
    "DELETE FROM cart WHERE user_id='$user_id'");

    echo "<script>
    alert('Order Placed Successfully');
    window.location='myorders.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Checkout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<div class="card">

<div class="card-header bg-primary text-white">

<h3>Checkout</h3>

</div>

<div class="card-body">

<form method="post">

<div class="mb-3">

<label>Customer Name</label>

<input type="text"
name="name"
class="form-control"
value="<?php echo $_SESSION['data']['name']; ?>"
required>

</div>

<div class="mb-3">

<label>Mobile Number</label>

<input type="text"
name="mobile"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Delivery Address</label>

<textarea
name="address"
class="form-control"
rows="4"
required></textarea>

</div>

<div class="mb-3">

<label>Payment Method</label>

<select
name="payment"
class="form-control"
required>

<option value="">Select Payment</option>

<option value="Cash On Delivery">
Cash On Delivery
</option>

<option value="Online Payment">
Online Payment
</option>

</select>

</div>

<br>
<?php

$total = 0;

$cart = mysqli_query($conn,"
SELECT cart.*,cake.cake_name
FROM cart
INNER JOIN cake
ON cart.cake_id=cake.cake_id
WHERE cart.user_id='$user_id'
");

?>

<h4>Order Summary</h4>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>Cake</th>
<th>Qty</th>
<th>Price</th>
<th>Subtotal</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($cart))
{
$total += $row['subtotal'];

?>

<tr>

<td><?php echo $row['cake_name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>₹ <?php echo $row['price']; ?></td>

<td>₹ <?php echo $row['subtotal']; ?></td>

</tr>

<?php
}
?>

<tr>

<th colspan="3" class="text-end">

Grand Total

</th>

<th>

₹ <?php echo $total; ?>

</th>

</tr>

</tbody>

</table>

<div class="text-end">

<button
type="submit"
name="place_order"
class="btn btn-success">

Place Order

</button>

<a href="cart.php"
class="btn btn-secondary">

Back To Cart

</a>

</div>

</form>

</div>

</div>

</div>

</body>
</html>
