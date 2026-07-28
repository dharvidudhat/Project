<?php
session_start();
include("../includes/db.php");

// Login Check
if(!isset($_SESSION['data']))
{
    header("Location: ../login.php");
    exit();
}

$id = "";
$category_name = "";
$btn = "save";

// ================= ADD CATEGORY =================

if(isset($_POST['save']))
{
    $category_name = mysqli_real_escape_string($conn,$_POST['category_name']);

    if($category_name!="")
    {
        $check = mysqli_query($conn,"SELECT * FROM category WHERE category_name='$category_name'");

        if(mysqli_num_rows($check)>0)
        {
            echo "<script>alert('Category Already Exists');</script>";
        }
        else
        {
            mysqli_query($conn,"INSERT INTO category(category_name)
            VALUES('$category_name')");

            echo "<script>
            alert('Category Added Successfully');
            window.location='category.php';
            </script>";
        }
    }
}

// ================= DELETE CATEGORY =================

if(isset($_GET['delete']))
{
    $id=$_GET['delete'];

    mysqli_query($conn,"DELETE FROM category WHERE id='$id'");

    echo "<script>
    alert('Category Deleted Successfully');
    window.location='category.php';
    </script>";
}

// ================= EDIT CATEGORY =================

if(isset($_GET['edit']))
{
    $id=$_GET['edit'];

    $result=mysqli_query($conn,
    "SELECT * FROM category WHERE id='$id'");

    $row=mysqli_fetch_assoc($result);

    $category_name=$row['category_name'];

    $btn="update";
}

// ================= UPDATE CATEGORY =================

if(isset($_POST['update']))
{
    $id=$_POST['id'];

    $category_name=mysqli_real_escape_string($conn,$_POST['category_name']);

    mysqli_query($conn,
    "UPDATE category
    SET category_name='$category_name'
    WHERE id='$id'");

    echo "<script>
    alert('Category Updated Successfully');
    window.location='category.php';
    </script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Category Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f5f5;
        }

        .container{
            margin-top:40px;
        }

        .card{
            box-shadow:0px 0px 10px lightgray;
        }
    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <div class="card-header bg-primary text-white">
            <h3>Category Management</h3>
        </div>

        <div class="card-body">

            <form method="post">

                <input type="hidden"
                       name="id"
                       value="<?php echo $id; ?>">

                <div class="mb-3">

                    <label class="form-label">
                        Category Name
                    </label>

                    <input type="text"
                           name="category_name"
                           class="form-control"
                           placeholder="Enter Category Name"
                           value="<?php echo $category_name; ?>"
                           required>

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

                <a href="category.php"
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
        <h4>Category List</h4>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead class="table-primary">

                <tr>
                    <th width="80">ID</th>
                    <th>Category Name</th>
                    <th width="200">Action</th>
                </tr>

            </thead>

            <tbody>

            <?php

            $result = mysqli_query($conn,
            "SELECT * FROM category ORDER BY id DESC");

            if(mysqli_num_rows($result)>0)
            {
                while($row=mysqli_fetch_assoc($result))
                {
            ?>

            <tr>

                <td><?php echo $row['id']; ?></td>

                <td><?php echo $row['category_name']; ?></td>

                <td>

                    <a href="category.php?edit=<?php echo $row['id'];?>"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="category.php?delete=<?php echo $row['id'];?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this category?')">
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
                <td colspan="3" class="text-center">
                    No Category Found
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
