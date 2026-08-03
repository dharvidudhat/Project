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

// ================= FETCH USER DATA =================

$query = mysqli_query($conn,
"SELECT * FROM registration
 WHERE id='$user_id'");

$user = mysqli_fetch_assoc($query);

// ================= UPDATE PROFILE =================

if(isset($_POST['update']))
{
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $mobile = mysqli_real_escape_string($conn,$_POST['mobile']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);

    mysqli_query($conn,
    "UPDATE registration
     SET
     name='$name',
     email='$email',
     mobile='$mobile',
     address='$address'
     WHERE id='$user_id'");

    // Update Session Data
    $_SESSION['data']['name'] = $name;
    $_SESSION['data']['email'] = $email;

    echo "<script>
    alert('Profile Updated Successfully');
    window.location='profile.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>My Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<div class="card">

<div class="card-header bg-primary text-white">

<h3>My Profile</h3>

</div>

<div class="card-body">

<form method="post">

<div class="mb-3">

<label>Full Name</label>

<input type="text"
name="name"
class="form-control"
value="<?php echo $user['name']; ?>"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input type="email"
name="email"
class="form-control"
value="<?php echo $user['email']; ?>"
required>

</div>

<div class="mb-3">

<label>Mobile Number</label>

<input type="text"
name="mobile"
class="form-control"
value="<?php echo $user['mobile']; ?>"
required>

</div>

<div class="mb-3">

<label>Address</label>

<textarea
name="address"
class="form-control"
rows="4"
required><?php echo $user['address']; ?></textarea>

</div>

<div class="text-end">

<button
type="submit"
name="update"
class="btn btn-success">

Update Profile

</button>

<a href="home.php"
class="btn btn-secondary">

Back

</a>

</div>

</form>

</div>

</div>

</div>

</body>
</html>
