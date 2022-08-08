<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['u_id'])) {
    header('location: index.php');
}

if (isset($_GET['bs_id'])) {
    $bs_id = $_GET['bs_id'];
}

$query = $connection->prepare("SELECT * FROM board_sub WHERE bs_id = :bs_id");
$query->bindParam("bs_id", $bs_id, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

if (isset($_REQUEST['editboard'])) {

    $bs_detail = $_REQUEST['bs_detail'];

    $img = $_FILES['bs_img']['name'];
    $tmp_dir = $_FILES['bs_img']['tmp_name'];
    $upload_dir = 'img/board/sub/' . $img;
    $dicectory = 'img/board/sub/';

    if ($img) {
        if (!file_exists($upload_dir)) {
            unlink($dicectory . $result['bs_img']);
            move_uploaded_file($tmp_dir, 'img/board/sub/' . $img);
        }
    } else {
        $img = $result['bs_img'];
    }

    $query = $connection->prepare("UPDATE board_sub SET bs_detail=:bs_detail, bs_img=:img WHERE bs_id=:bs_id");
    $query->bindParam("bs_detail", $bs_detail, PDO::PARAM_STR);
    $query->bindParam("img", $img, PDO::PARAM_STR);
    $query->bindParam("bs_id", $bs_id, PDO::PARAM_STR);

    $bm_id = $result['bm_id'];
    if ($query->execute()) {
        $updateMsg = "อัพเดทความคิดเห็นสำเร็จ";
        header("Refresh:1;detail.php?bm_id=$bm_id");
    } else {
        $errorMsg = "อัพเดทความคิดเห็นไม่สำเร็จ";
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขความคิดเห็น</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <div class="addboard">
        <div class="addboard--container">
            <div class="addboard--header">
                <h1>แก้ไขความคิดเห็น</h1>
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
                        <?php
                        if ($result['bs_img'] != "") {
                        ?>
                            <img src="img/board/sub/<?php echo $result['bs_img'] ?>">
                        <?php } ?>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <textarea class="form-control mb-2" placeholder="ตอบกระทู้" name="bs_detail" style="height: 100px"><?php echo $result['bs_detail']; ?></textarea>
                            <label for="detail_sub">แก้ไขความคิดเห็น</label>
                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="img">เปลี่ยนรูปในความคิดเห็น</label>
                            <input type="file" accept="image/*" class="form-control" name="bs_img">
                        </div>

                        <hr>
                        <button type="submit" name="editboard" value="editboard" class="btn butt-register">แก้ไขความคิดเห็น</button>
                        <a href="detail.php?bm_id=<?php echo $result['bm_id'] ?>" class="btn butt-cancle">ยกเลิก</a>

                    </form>


                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>