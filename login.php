<?php
session_start();
include('config/db.php');

if (isset($_SESSION['u_id'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: index.php');
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = $connection->prepare("SELECT * FROM users WHERE email=:email");
    $query->bindParam("email", $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        echo "<script>alert('เข้าสู่ระบบล้มเหลว');</script>";
    } else {
        if (password_verify($password, $result['password'])) {
            $_SESSION['u_id'] = $result['u_id'];
            $_SESSION['type'] = $result['type'];
            header("Location: index.php");
        } else {
            echo "<script>alert('เข้าสู่ระบบล้มเหลว');</script>";
        }
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <div class="login">
        <div class="login--container">
            <div class="login--header">
                <h1>เข้าสู่ระบบ</h1>
            </div>
            <div class="login--body">
                <div class="login--content">
                    <form action="" method="post" class="mb-3">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                            <label for="email">อีเมล</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">รหัสผ่าน</label>
                        </div>
                        <hr>
                        <button type="submit" name="login" value="login" class="btn butt-login">เข้าสู่ระบบ</button>
                        <a href="register.php" class="btn butt-register">สมัครสมาชิก</a>

                    </form>
                    <a href="index.php" class="btn butt-cancle">ยกเลิก</a>

                </div>


            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>


</html>