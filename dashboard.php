<?php
session_start();

if(!isset($_SESSION['data']))
{
    header("Location: ../login.php");
    exit();
}

include("../includes/db.php");
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f5f5f5;
}

.sidebar{
width:250px;
height:100vh;
background:#212529;
position:fixed;
}

.sidebar h3{
color:white;
padding:20px;
text-align:center;
}

.sidebar a{

display:block;
padding:15px;
color:white;
text-decoration:none;

}

.sidebar a:hover{

background:#0d6efd;

}

.content{

margin-left:260px;
padding:30px;

}

</style>

</head>

<body>

<div class="sidebar">

<h3>Swiffin Cake House</h3>

<a href="dashboard.php">Dashboard</a>

<a href="category.php">Category</a>

<a href="cake.php">Cake</a>

<a href="orders.php">Orders</a>

<a href="users.php">Users</a>

<a href="logout.php">Logout</a>

</div>

<div class="content">

<div class="card">

<div class="card-body">

<h2>Welcome Admin</h2>

<h5>

<?php

echo $_SESSION['data'];

?>

</h5>

</div>

</div>

</div>

</body>

</html>
