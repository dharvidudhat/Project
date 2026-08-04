<?php
session_start();
include("../includes/db.php");

// Login Check
if(!isset($_SESSION['data']))
{
    header("Location: ../login.php");
    exit();
}

// Delete Feedback
if(isset($_GET['delete']))
{
    $id = $_GET['delete'];

    mysqli_query($conn,
    "DELETE FROM feedback
    WHERE feedback_id='$id'");

    echo "<script>
    alert('Feedback Deleted Successfully');
    window.location='feedback.php';
    </script>";
}

// Fetch All Feedback
$query = mysqli_query($conn,
"SELECT *
FROM feedback
ORDER BY feedback_id DESC");
?>

<!DOCTYPE html>
<html>
<head>

    <title>Feedback Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<div class="card">

<div class="card-header bg-primary text-white">

<h3>Feedback Management</h3>

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Feedback</th>
<th>Date</th>
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

<td><?php echo $row['feedback_id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['message']; ?></td>

<td><?php echo $row['feedback_date']; ?></td>

<td>

<a href="feedback.php?delete=<?php echo $row['feedback_id']; ?>"

class="btn btn-danger btn-sm"

onclick="return confirm('Are you sure you want to delete this feedback?')">

Delete

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

No Feedback Found

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<br>

</div>

</div>

</div>

<?php
mysqli_close($conn);
?>

</body>

</html>
