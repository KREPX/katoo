<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['u_id'])) {
    header('location: index.php');
}

if (isset($_GET['bme'])) {
    $bme = $_GET['bme'];
}

$query = $connection->prepare("SELECT * FROM board_main WHERE bm_id=:bme");
$query->bindParam("bme", $bme, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

if (isset($_REQUEST['editboard'])) {
    $bm_title = $_REQUEST['bm_title'];
    $query = $connection->prepare("UPDATE board_main SET bm_title=:bm_title WHERE bm_id=:bm_id");
    $query->bindParam("bm_id", $bme, PDO::PARAM_STR);
    $query->bindParam("bm_title", $bm_title, PDO::PARAM_STR);
    if ($query->execute()) {
        $updateMsg = "แก้ไขกระทู้สำเร็จ";
        header("Refresh:1;index.php");
    } else {
        $errorMsg = "แก้ไขกระทู้ไม่สำเร็จ";
    }
}

if (isset($_REQUEST['delete_id'])) {

    $delete_id = $_REQUEST['delete_id'];

    $query = $connection->prepare("SELECT * FROM board_main WHERE bm_id = :bm_id");
    $query->bindParam("bm_id", $delete_id, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    unlink("img/board/" . $result['bm_img']);


    $query = $connection->prepare("DELETE board_main FROM board_main WHERE board_main.bm_id = :bm_id");
    $query->bindParam("bm_id", $delete_id, PDO::PARAM_STR);
    if ($query->execute()) {

        $query = $connection->prepare("SELECT * FROM board_sub WHERE bm_id = :bm_id");
        $query->bindParam("bm_id", $delete_id, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        unlink("img/board/sub/" . $result['bs_img']);

        $query = $connection->prepare("DELETE board_sub FROM board_sub WHERE board_sub.bm_id = :bm_id");
        $query->bindParam("bm_id", $delete_id, PDO::PARAM_STR);
        if ($query->execute()) {
            header("location: index.php");
        } else {
            $errorMsg = "ลบกระทู้ไม่สำเร็จ";
        }
        $updateMsg = "ลบกระทู้สำเร็จ";
        header("location: index.php");
    } else {
        $errorMsg = "ลบกระทู้ไม่สำเร็จ";
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขหัวข้อกระทู้ / ลบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <div class="addboard">
        <div class="addboard--container">
            <div class="addboard--header">
                <h1>แก้ไขหัวข้อกระทู้ / ลบ</h1>
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
                            <input type="text" class="form-control" id="bm_title" name="bm_title" value="<?php echo $result['bm_title']; ?>" placeholder="หัวข้อ">
                            <label for="title">หัวข้อ</label>
                        </div>
                        <div class="form-floating">
                            <?php

                            $cg = $connection->prepare("SELECT * FROM category WHERE cg_id = :cg_id");
                            $cg->bindParam("cg_id", $result['cg_id'], PDO::PARAM_STR);
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
                        <button type="submit" name="editboard" value="editboard" class="btn butt-register">แก้ไขกระทู้</button>
                        <a href="index.php" class="btn butt-cancle">ยกเลิก</a>
                        <a href="?delete_id=<?php echo $result['bm_id'] ?>" class="btn btn-danger">ลบ</a>

                    </form>


                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>