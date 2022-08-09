<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['u_id']) || $_SESSION['type'] != '1') {
    header('location: ../index.php');
}

if (isset($_SESSION['u_id'])) {
    $uid = $_SESSION['u_id'];
}

$query = $connection->prepare("SELECT * FROM users WHERE u_id=:uid");

$query->bindParam("uid", $uid, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

if (isset($_REQUEST['save'])) {

    $fullname = $_REQUEST['fullname'];

    $img = $_FILES['img']['name'];
    $tmp_dir = $_FILES['img']['tmp_name'];
    $upload_dir = '../img/user_profile/' . $img;
    $dicectory = '../img/user_profile/';

    if ($img) {
        if (!file_exists($upload_dir)) {
            unlink($dicectory . $result['img']);
            move_uploaded_file($tmp_dir, '../img/user_profile/' . $img);
        }
    } else {
        $img = $result['img'];
    }

    $query = $connection->prepare("UPDATE users SET fullname=:fullname, img=:img WHERE u_id=:uid");
    $query->bindParam("fullname", $fullname, PDO::PARAM_STR);
    $query->bindParam("img", $img, PDO::PARAM_STR);
    $query->bindParam("uid", $uid, PDO::PARAM_STR);
    if ($query->execute()) {
        $updateMsg = "อัพเดทข้อมูลสำเร็จ";
        header("Refresh:1;admin.php");
    } else {
        $errorMsg = "อัพเดทข้อมูลไม่สำเร็จ";
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขโปรไฟล์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>

    <div class="edit_profile">
        <div class="edit_profile--container">
            <div class="edit_profile--header">
                <h1>Edit Profile</h1>
            </div>
            <div class="edit_profile--body">
                <div class="edit_profile--content">
                    <div class="edit_profile--profile">
                        <div class="edit_profile--profile--img">
                            <img src="../img/user_profile/<?php echo $result['img'] ?>" alt="">
                        </div>
                        <hr>
                    </div>
                    <div class="edit_profile--profile--edit">
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
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="img">เปลี่ยนรูปโปรไฟล์</label>
                                <input type="file" accept="image/*" class="form-control" name="img">
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $result['email'] ?>" placeholder="email" readonly>
                                <label for="fullname">อีเมล</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $result['fullname'] ?>" placeholder="fullname">
                                <label for="fullname">ชื่อ - นามสกุล</label>
                            </div>

                            <button type="submit" name="save" value="save" class="btn btn-primary">บันทึก</button>
                            <a href="admin.php" class="btn btn-danger">ยกเลิก</a>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</body>

</html>