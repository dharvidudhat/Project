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

// ================= ADD TO CART =================

if(isset($_GET['add']))
{
    $cake_id = $_GET['add'];

    // Cake માહિતી મેળવો
    $cake = mysqli_query($conn,"SELECT * FROM cake WHERE cake_id='$cake_id'");
    $row = mysqli_fetch_assoc($cake);

    $price = $row['price'];

    // Check cake already in cart
    $check = mysqli_query($conn,
    "SELECT * FROM cart
     WHERE user_id='$user_id'
     AND cake_id='$cake_id'");

    if(mysqli_num_rows($check)>0)
    {
        mysqli_query($conn,
        "UPDATE cart
         SET quantity = quantity + 1,
             subtotal = (quantity + 1) * price
         WHERE user_id='$user_id'
         AND cake_id='$cake_id'");
    }
    else
    {
        mysqli_query($conn,
        "INSERT INTO cart(user_id,cake_id,quantity,price,subtotal)
         VALUES('$user_id','$cake_id','1','$price','$price')");
    }

    echo "<script>
    alert('Cake Added To Cart');
    window.location='cart.php';
    </script>";
}

// ================= UPDATE QUANTITY =================

if(isset($_POST['update']))
{
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['quantity'];
    $price = $_POST['price'];

    $subtotal = $qty * $price;

    mysqli_query($conn,
    "UPDATE cart
     SET quantity='$qty',
         subtotal='$subtotal'
     WHERE cart_id='$cart_id'
     AND user_id='$user_id'

    header("Location: cart.php");
}

// ================= REMOVE FROM CART =================

if(isset($_GET['delete']))
{
    $cart_id = $_GET['delete'];
  mysqli_query($conn,"DELETE FROM cartWHERE cart_id='$cart_id AND user_id='$user_id'");

    echo "<script>
    alert('Item Removed From Cart');
    window.location='cart.php';
    </script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

<div class="card">

<div class="card-header bg-primary text-white">
    <h3>Shopping Cart</h3>
</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Image</th>
<th>Cake</th>
<th>Price</th>
<th>Quantity</th>
<th>Subtotal</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

$total = 0;

$query = mysqli_query($conn,"
SELECT cart.*,cake.cake_name,cake.image
FROM cart
INNER JOIN cake
ON cart.cake_id=cake.cake_id
WHERE cart.user_id='$user_id'
");

while($row=mysqli_fetch_assoc($query))
{

$total += $row['subtotal'];

?>

<tr>

<td>

<img src="../uploads/cakes/<?php echo $row['image'];?>"
width="80"
height="80">

</td>

<td>

<?php echo $row['cake_name']; ?>

</td>

<td>

₹ <?php echo $row['price']; ?>

</td>

<td>

<form method="post">

<input type="hidden"
name="cart_id"
value="<?php echo $row['cart_id'];?>">

<input type="hidden"
name="price"
value="<?php echo $row['price'];?>">

<input type="number"
name="quantity"
value="<?php echo $row['quantity'];?>"
min="1"
class="form-control">

<br>

<button
class="btn btn-success btn-sm"
name="update">

Update

</button>

</form>

</td>

<td>

₹ <?php echo $row['subtotal']; ?>

</td>

<td>

<a href="cart.php?delete=<?php echo $row['cart_id'];?>"

class="btn btn-danger btn-sm"

onclick="return confirm('Remove Item?')">

Remove

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
if($total>0)
{
?>

<div class="row">

    <div class="col-md-6 offset-md-6">

        <table class="table table-bordered">

            <tr>

                <th>Grand Total</th>

                <th>₹ <?php echo $total; ?></th>

            </tr>

        </table>

        <div class="text-end">

            <a href="checkout.php"
               class="btn btn-primary">

                Proceed To Checkout

            </a>

        </div>

    </div>

</div>

<?php
}
else
{
?>

<div class="alert alert-warning text-center">

    <h5>Your Cart is Empty!</h5>

    <a href="index.php" class="btn btn-success mt-2">
        Continue Shopping
    </a>

</div>

<?php
}
?>

</div>

</div>

</div>

</body>
</html>
