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

// ================= CHANGE PASSWORD =================

if(isset($_POST['change_password']))
{
    $current_password = mysqli_real_escape_string($conn,$_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn,$_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn,$_POST['confirm_password']);

    // Check Current Password
    $query = mysqli_query($conn,
    "SELECT * FROM registration
     WHERE id='$user_id'
     AND password='$current_password'");

    if(mysqli_num_rows($query)>0)
    {
        if($new_password == $confirm_password)
        {
            mysqli_query($conn,
            "UPDATE registration
             SET password='$new_password'
             WHERE id='$user_id'");

            echo "<script>
            alert('Password Changed Successfully');
            window.location='change_password.php';
            </script>";
        }
        else
        {
            echo "<script>
            alert('New Password and Confirm Password do not match');
            </script>";
        }
    }
    else
    {
        echo "<script>
        alert('Current Password is Incorrect');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Change Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card">

<div class="card-header bg-primary text-white">

<h3>Change Password</h3>

</div>

<div class="card-body">

<form method="post">

<div class="mb-3">

<label>Current Password</label>

<input type="password"
name="current_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>New Password</label>

<input type="password"
name="new_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Confirm New Password</label>

<input type="password"
name="confirm_password"
class="form-control"
required>

</div>

<div class="text-end">

<button
type="submit"
name="change_password"
class="btn btn-success">

Change Password

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

</div>

</div>

</body>
</html>
