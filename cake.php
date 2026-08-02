<?php
session_start();
include("../includes/db.php");

// Login Check
if(!isset($_SESSION['data']))
{
    header("Location: ../login.php");
    exit();
}

$cake_id = "";
$category_id = "";
$cake_name = "";
$price = "";
$description = "";
$status = "Active";
$image = "";
$btn = "save";

// ================= ADD CAKE =================

if(isset($_POST['save']))
{
    $category_id = $_POST['category_id'];
    $cake_name = mysqli_real_escape_string($conn,$_POST['cake_name']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $status = $_POST['status'];

    $image = $_FILES['image']['name'];
    $temp = $_FILES['image']['tmp_name'];

    move_uploaded_file($temp,"../uploads/cakes/".$image);

    $query = "INSERT INTO cake(category_id,cake_name,price,description,image,status)
              VALUES('$category_id','$cake_name','$price','$description','$image','$status')";

    mysqli_query($conn,$query);

    echo "<script>
    alert('Cake Added Successfully');
    window.location='cake.php';
    </script>";
}

// ================= DELETE CAKE =================

if(isset($_GET['delete']))
{
    $cake_id = $_GET['delete'];

    mysqli_query($conn,"DELETE FROM cake WHERE cake_id='$cake_id'");

    echo "<script>
    alert('Cake Deleted Successfully');
    window.location='cake.php';
    </script>";
}

// ================= EDIT CAKE =================

if(isset($_GET['edit']))
{
    $cake_id = $_GET['edit'];

    $result = mysqli_query($conn,"SELECT * FROM cake WHERE cake_id='$cake_id'");
    $row = mysqli_fetch_assoc($result);

    $category_id = $row['category_id'];
    $cake_name = $row['cake_name'];
    $price = $row['price'];
    $description = $row['description'];
    $status = $row['status'];
    $image = $row['image'];

    $btn = "update";
}

// ================= UPDATE CAKE =================

if(isset($_POST['update']))
{
    $cake_id = $_POST['cake_id'];
    $category_id = $_POST['category_id'];
    $cake_name = mysqli_real_escape_string($conn,$_POST['cake_name']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $status = $_POST['status'];

    if($_FILES['image']['name']!="")
    {
        $image = $_FILES['image']['name'];
        $temp = $_FILES['image']['tmp_name'];

        move_uploaded_file($temp,"../uploads/cakes/".$image);

        mysqli_query($conn,"UPDATE cake SET
        category_id='$category_id',
        cake_name='$cake_name',
        price='$price',
        description='$description',
        image='$image',
        status='$status'
        WHERE cake_id='$cake_id'");
    }
    else
    {
        mysqli_query($conn,"UPDATE cake SET
        category_id='$category_id',
        cake_name='$cake_name',
        price='$price',
        description='$description',
        status='$status'
        WHERE cake_id='$cake_id'");
    }

    echo "<script>
    alert('Cake Updated Successfully');
    window.location='cake.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cake Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f5f5;
        }

        .container{
            margin-top:40px;
        }

        .card{
            box-shadow:0 0 10px lightgray;
        }
    </style>

</head>

<body>

<div class="container">

<div class="card">

<div class="card-header bg-primary text-white">
<h3>Cake Management</h3>
</div>

<div class="card-body">

<form method="post" enctype="multipart/form-data">

<input type="hidden" name="cake_id" value="<?php echo $cake_id; ?>">

<div class="mb-3">

<label>Category</label>

<select name="category_id" class="form-control" required>

<option value="">Select Category</option>

<?php

$cat = mysqli_query($conn,"SELECT * FROM category");

while($c = mysqli_fetch_assoc($cat))
{
?>

<option value="<?php echo $c['cid']; ?>"

<?php
if($category_id==$c['cid'])
{
echo "selected";
}
?>

>

<?php echo $c['category_name']; ?>

</option>

<?php
}
?>

</select>

</div>

<div class="mb-3">

<label>Cake Name</label>

<input type="text"
name="cake_name"
class="form-control"
value="<?php echo $cake_name; ?>"
required>

</div>

<div class="mb-3">

<label>Price</label>

<input type="number"
name="price"
class="form-control"
value="<?php echo $price; ?>"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control"
rows="4"
required><?php echo $description; ?></textarea>

</div>

<div class="mb-3">

<label>Cake Image</label>

<input type="file"
name="image"
class="form-control">

<?php
if($btn=="update")
{
?>

<br>

<img src="../uploads/cakes/<?php echo $image;?>"
width="120"
height="100">

<?php
}
?>

</div>

<div class="mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="Active"
<?php if($status=="Active") echo "selected"; ?>>
Active
</option>

<option value="Inactive"
<?php if($status=="Inactive") echo "selected"; ?>>
Inactive
</option>

</select>

</div>

<?php
if($btn=="save")
{
?>

<button
type="submit"
name="save"
class="btn btn-success">

Save

</button>

<?php
}
else
{
?>

<button
type="submit"
name="update"
class="btn btn-warning">

Update

</button>

<a href="cake.php"
class="btn btn-secondary">

Cancel

</a>

<?php
}
?>

</form>

</div>

</div>

<br>

<div class="card">

    <div class="card-header bg-dark text-white">
        <h4>Cake List</h4>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead class="table-primary">

                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Cake Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th width="180">Action</th>
                </tr>

            </thead>

            <tbody>

            <?php

            $query = "SELECT cake.*, category.category_name
                      FROM cake
                      INNER JOIN category
                      ON cake.category_id = category.cid
                      ORDER BY cake.cake_id DESC";

            $result = mysqli_query($conn,$query);

            if(mysqli_num_rows($result)>0)
            {
                while($row=mysqli_fetch_assoc($result))
                {
            ?>

            <tr>

                <td><?php echo $row['cake_id']; ?></td>

                <td>
                    <img src="../uploads/cakes/<?php echo $row['image']; ?>"
                         width="80"
                         height="80"
                         style="border-radius:5px;">
                </td>

                <td><?php echo $row['cake_name']; ?></td>

                <td><?php echo $row['category_name']; ?></td>

                <td>₹ <?php echo $row['price']; ?></td>

                <td>
                    <?php
                    if($row['status']=="Active")
                    {
                        echo "<span class='badge bg-success'>Active</span>";
                    }
                    else
                    {
                        echo "<span class='badge bg-danger'>Inactive</span>";
                    }
                    ?>
                </td>

                <td>

                    <a href="cake.php?edit=<?php echo $row['cake_id']; ?>"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="cake.php?delete=<?php echo $row['cake_id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this cake?')">
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
                <td colspan="7" class="text-center">
                    No Cake Found
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

</body>
</html>
