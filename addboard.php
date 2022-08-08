<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['u_id'])) {
    header('location: index.php');
}

if (isset($_SESSION['u_id'])) {
    $uid = $_SESSION['u_id'];
}

if (isset($_REQUEST['addboard'])) {
    $bm_title = $_REQUEST['bm_title'];
    $bm_detail = $_REQUEST['bm_detail'];
    $cg_id = $_REQUEST['cg_id'];
    $uid = $_SESSION['u_id'];
    
    $bm_date = date("Y-m-d H:i:s");

    $img = $_FILES['bm_img']['name'];
    $tmp_dir = $_FILES['bm_img']['tmp_name'];
    $upload_dir = 'img/board/' . $img;
    move_uploaded_file($tmp_dir, $upload_dir);


    $query = $connection->prepare("INSERT INTO board_main (u_id, bm_title, bm_detail, cg_id,bm_img, bm_date) VALUES (:u_id, :bm_title, :bm_detail, :cg_id, :bm_img,:bm_date)");
    $query->bindParam("u_id", $uid, PDO::PARAM_STR);
    $query->bindParam("bm_title", $bm_title, PDO::PARAM_STR);
    $query->bindParam("bm_detail", $bm_detail, PDO::PARAM_STR);
    $query->bindParam("cg_id", $cg_id, PDO::PARAM_STR);
    $query->bindParam("bm_img", $img, PDO::PARAM_STR);
    $query->bindParam("bm_date", $bm_date, PDO::PARAM_STR);

    if ($query->execute()) {
        $updateMsg = "เพิ่มกระทู้สำเร็จ";
        header("Refresh:1;index.php");
    } else {
        $errorMsg = "เพิ่มกระทู้ไม่สำเร็จ";
    }
}


?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เพิ่มกระทู้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <div class="addboard">
        <div class="addboard--container">
            <div class="addboard--header">
                <h1>เพิ่มกระทู้</h1>
            </div>
            <div class="addboard--body">
                <div class="addboard--content">
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
                            <input type="text" class="form-control" id="bm_title" name="bm_title" placeholder="หัวข้อ" required>
                            <label for="title">หัวข้อ</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="รายละเอียด" id="bm_detail" name="bm_detail" style="height: 100px"></textarea>
                            <label for="detail">รายละเอียด</label>
                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="img">เลือกรูป</label>
                            <input type="file" accept="image/*" class="form-control" id="bm_img" name="bm_img">
                        </div>
                        <div class="form-floating">
                            <?php

                            $cg = $connection->prepare("SELECT * FROM category");
                            $cg->execute();
                            $cg_result = $cg->fetchAll(PDO::FETCH_ASSOC);

                            ?>
                            <select class="form-select" name="cg_id" id="cg_id">
                                <?php
                                foreach ($cg_result as $cg_row) {
                                    echo "<option value='" . $cg_row['cg_id'] . "'>" . $cg_row['cg_name'] . "</option>";
                                }
                                ?>

                            </select>
                            <label for="category">เลือกหมวดหมู่</label>
                        </div>
                        <hr>
                        <button type="submit" name="addboard" value="addboard" class="btn butt-register">เพิ่มกระทู้</button>
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