<?php
session_start();
include("includes/db.php");

$message = "";

if(isset($_POST['login']))
{
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM registration
              WHERE email='$email'
              AND password='$password'";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1)
    {
        $user = mysqli_fetch_assoc($result);

        // Store complete user data in session
        $_SESSION['data'] = $user;

        // Admin / User redirect
        if(isset($user['role']) && $user['role'] == 'admin')
        {
            header("Location: admin/dashboard.php");
            exit();
        }
        else
        {
            header("Location: user/home.php");
            exit();
        }
    }
    else
    {
        $message = "Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Login - Swiffin Cake House</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

    <style>

        body{
            background:#fff1f3;
        }

        .login-box{
            max-width:420px;
            margin:80px auto;
            background:white;
            padding:30px;
            border-radius:15px;
            box-shadow:0 5px 20px rgba(0,0,0,0.15);
        }

        .title{
            text-align:center;
            color:#ff4f6d;
            font-weight:bold;
            margin-bottom:25px;
        }

        .login-btn{
            width:100%;
            background:#ff4f6d;
            border:none;
            padding:10px;
            font-weight:bold;
        }

        .login-btn:hover{
            background:#e83e5b;
        }

    </style>

</head>

<body>

<div class="login-box">

    <h2 class="title">
        🍰 Swiffin Cake House
    </h2>

    <h4 class="text-center mb-4">
        Login
    </h4>

    <?php
    if($message!="")
    {
    ?>

    <div class="alert alert-danger">
        <?php echo $message; ?>
    </div>

    <?php
    }
    ?>

    <form method="post">

        <div class="mb-3">

            <label class="form-label">
                Email
            </label>

            <input type="email"
                   name="email"
                   class="form-control"
                   required>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Password
            </label>

            <input type="password"
                   name="password"
                   class="form-control"
                   required>

        </div>

        <button type="submit"
                name="login"
                class="btn btn-primary login-btn">

            Login

        </button>

    </form>

</div>

</body>
</html>
