<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['u_id'])) {
    header('location: index.php');
}

if (isset($_GET['bm_id'])) {
    $bm_id = $_GET['bm_id'];
}

$query = $connection->prepare("SELECT * FROM board_main WHERE bm_id=:bm_id");
$query->bindParam("bm_id", $bm_id, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

if (isset($_REQUEST['editboard'])) {

    $bm_detail = $_REQUEST['bm_detail'];

    $img = $_FILES['bm_img']['name'];
    $tmp_dir = $_FILES['bm_img']['tmp_name'];
    $upload_dir = 'img/board/' . $img;
    $dicectory = 'img/board/';

    if ($img) {
        if (!file_exists($upload_dir)) {
            unlink($dicectory . $result['bm_img']);
            move_uploaded_file($tmp_dir, 'img/board/' . $img);
        }
    } else {
        $img = $result['bm_img'];
    }

    $query = $connection->prepare("UPDATE board_main SET bm_detail=:bm_detail, bm_img=:img WHERE bm_id=:bm_id");
    $query->bindParam("bm_detail", $bm_detail, PDO::PARAM_STR);
    $query->bindParam("img", $img, PDO::PARAM_STR);
    $query->bindParam("bm_id", $bm_id, PDO::PARAM_STR);
    if ($query->execute()) {
        $updateMsg = "อัพเดทกระทู้สำเร็จ";
        header("Refresh:1;detail.php?bm_id=$bm_id");
    } else {
        $errorMsg = "อัพเดทกระทู้ไม่สำเร็จ";
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขกระทู้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <div class="addboard">
        <div class="addboard--container">
            <div class="addboard--header">
                <h1>แก้ไขกระทู้</h1>
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
                    <div class="detail--content--img mb-3">
                        <img src="img/board/<?php echo $result['bm_img'] ?>" alt="">
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <textarea class="form-control mb-2" placeholder="ตอบกระทู้" name="bm_detail" style="height: 100px"><?php echo $result['bm_detail']; ?></textarea>
                            <label for="detail_sub">แก้ไขกระทู้</label>
                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="img">เปลี่ยนรูปในกระทู้</label>
                            <input type="file" accept="image/*" class="form-control" name="bm_img">
                        </div>

                        <hr>
                        <button type="submit" name="editboard" value="editboard" class="btn butt-register">แก้ไขกระทู้</button>
                        <a href="detail.php?bm_id=<?php echo $result['bm_id'] ?>;" class="btn butt-cancle">ยกเลิก</a>

                    </form>


                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>