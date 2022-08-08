<?php
session_start();
require_once 'config/db.php';

if (isset($_SESSION['u_id'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
    header('location: index.php');
}

if (isset($_REQUEST['register'])) {

    $email = $_REQUEST['email'];
    $fullname = $_REQUEST['fullname'];
    $password = $_REQUEST['password'];

    $img = $_FILES['img']['name'];
    $tmp_dir = $_FILES['img']['tmp_name'];
    $upload_dir = 'img/user_profile/' . $img;
    move_uploaded_file($tmp_dir, $upload_dir);

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $query = $connection->prepare("INSERT INTO users (email, fullname, password, img) VALUES (:email, :fullname, :password, :img)");
    $query->bindParam("email", $email, PDO::PARAM_STR);
    $query->bindParam("fullname", $fullname, PDO::PARAM_STR);
    $query->bindParam("password", $passwordHash, PDO::PARAM_STR);
    $query->bindParam("img", $img, PDO::PARAM_STR);
    if ($query->execute()) {
        $updateMsg = "สมัครสมาชิกสำเร็จ";
        header("Refresh:1;index.php");
    } else {
        $errorMsg = "สมัครสมาชิกไม่สำเร็จ";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="register">
        <div class="register--container">
            <div class="register--header">
                <h1>สมัครสมาชิก</h1>
            </div>
            <div class="register--body">
                <div class="register--content">
                    <?php
                    if (isset($errorMsg)) {
                    ?>
                        <div class="alert alert-danger">
                            <strong><?php echo $errorMsg; ?></strong>
                        </div>
                    <?php } ?>
                    <?php
                    if (isset($updateMsg)) {
                    ?>
                        <div class="alert alert-success">
                            <strong><?php echo $updateMsg; ?></strong>
                        </div>
                    <?php } ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
                            <label for="floatingInput">อีเมล</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="fullname" class="form-control" name="fullname" placeholder="fullname" required>
                            <label for="floatingInput">ชื่อ - นามสกุล</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                            <label for="floatingPassword">รหัสผ่าน</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" placeholder="Con-Password" required>
                            <label for="floatingPassword">ยืนยันรหัสผ่าน</label>
                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="img">รูปโปรไฟล์</label>
                            <input type="file" accept="image/*" class="form-control" name="img">
                        </div>
                        <hr>
                        <button type="submit" name="register" value="register" class="btn butt-register">สมัครสมาชิก</button>
                        <a href="index.php" class="btn butt-cancle">ยกเลิก</a>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>