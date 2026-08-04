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

// Fetch User Details
$user = mysqli_query($conn,
"SELECT * FROM registration WHERE id='$user_id'");

$data = mysqli_fetch_assoc($user);

// Submit Feedback
if(isset($_POST['submit_feedback']))
{
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $message = mysqli_real_escape_string($conn,$_POST['message']);

    mysqli_query($conn,
    "INSERT INTO feedback
    (user_id,name,email,message,feedback_date)
    VALUES
    ('$user_id','$name','$email','$message',NOW())");

    echo "<script>
    alert('Feedback Submitted Successfully');
    window.location='feedback.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Feedback</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-8">

<div class="card">

<div class="card-header bg-primary text-white">

<h3>Feedback Form</h3>

</div>

<div class="card-body">

<form method="post">

<div class="mb-3">

<label>Full Name</label>

<input type="text"
name="name"
class="form-control"
value="<?php echo $data['name']; ?>"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input type="email"
name="email"
class="form-control"
value="<?php echo $data['email']; ?>"
required>

</div>

<div class="mb-3">

<label>Your Feedback</label>

<textarea
name="message"
class="form-control"
rows="5"
placeholder="Write your feedback here..."
required></textarea>

</div>

<div class="text-end">

<button
type="submit"
name="submit_feedback"
class="btn btn-success">

Submit Feedback

</button>

<a href="home.php"
class="btn btn-secondary">

Back

</a>

</div>

</form>

<br>

<?php

$feedback = mysqli_query($conn,"
SELECT *
FROM feedback
WHERE user_id='$user_id'
ORDER BY feedback_id DESC
");

?>

<h4>Your Feedback History</h4>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Feedback</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($feedback)>0)
{

while($row=mysqli_fetch_assoc($feedback))
{

?>

<tr>

<td><?php echo $row['feedback_id']; ?></td>

<td><?php echo $row['message']; ?></td>

<td><?php echo $row['feedback_date']; ?></td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="3" class="text-center">

No Feedback Available

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

</body>

</html>
