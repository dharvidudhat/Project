<?php
session_start();
include("../includes/db.php");

// Login Check
if(!isset($_SESSION['data']))
{
    header("Location: ../login.php");
    exit();
}

// ================= SEARCH =================

$search = "";

if(isset($_POST['search']))
{
    $search = mysqli_real_escape_string($conn,$_POST['search']);
}

// ================= DELETE USER =================

if(isset($_GET['delete']))
{
    $id = $_GET['delete'];

    // Admin User Delete ન થાય
    $check = mysqli_query($conn,
    "SELECT * FROM registration WHERE id='$id'");

    $row = mysqli_fetch_assoc($check);

    if($row['role']=="admin")
    {
        echo "<script>
        alert('Admin Account Cannot Be Deleted');
        window.location='users.php';
        </script>";
    }
    else
    {
        mysqli_query($conn,
        "DELETE FROM registration WHERE id='$id'");

        echo "<script>
        alert('User Deleted Successfully');
        window.location='users.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>

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
            <h3>User Management</h3>
        </div>

        <div class="card-body">

            <form method="post">

                <div class="row">

                    <div class="col-md-10">

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search by Name or Email"
                               value="<?php echo $search; ?>">

                    </div>

                    <div class="col-md-2">

                        <button type="submit"
                                name="search"
                                class="btn btn-success w-100">

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <br>
<div class="card">

    <div class="card-header bg-dark text-white">
        <h4>User List</h4>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead class="table-primary">

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Role</th>
                    <th width="200">Action</th>
                </tr>

            </thead>

            <tbody>

            <?php

            if($search!="")
            {
                $query = "SELECT * FROM registration
                          WHERE name LIKE '%$search%'
                          OR email LIKE '%$search%'
                          ORDER BY id DESC";
            }
            else
            {
                $query = "SELECT * FROM registration
                          ORDER BY id DESC";
            }

            $result = mysqli_query($conn,$query);

            if(mysqli_num_rows($result)>0)
            {
                while($row=mysqli_fetch_assoc($result))
                {
            ?>

            <tr>

                <td><?php echo $row['id']; ?></td>

                <td><?php echo $row['name']; ?></td>

                <td><?php echo $row['email']; ?></td>

                <td><?php echo $row['mobile']; ?></td>

                <td><?php echo ucfirst($row['role']); ?></td>

                <td>

                    <a href="view_user.php?id=<?php echo $row['id']; ?>"
                       class="btn btn-info btn-sm">
                        View
                    </a>

                    <?php if($row['role']!="admin"){ ?>

                    <a href="users.php?delete=<?php echo $row['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this user?')">
                        Delete
                    </a>

                    <?php } ?>

                </td>

            </tr>

            <?php
                }
            }
            else
            {
            ?>

            <tr>
                <td colspan="6" class="text-center">
                    No User Found
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
