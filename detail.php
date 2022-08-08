<?php
session_start();
require_once 'config/db.php';

if (isset($_SESSION['u_id'])) {
    $uid = $_SESSION['u_id'];
}

if (isset($_GET['bm_id'])) {
    $bm_id = $_GET['bm_id'];
}


$query = $connection->prepare("SELECT * FROM board_main INNER JOIN category ON board_main.cg_id = category.cg_id INNER JOIN users ON board_main.u_id = users.u_id WHERE bm_id = :bm_id");
$query->bindParam("bm_id", $bm_id, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);



if (isset($_REQUEST['answer'])) {
    $bs_detail = $_REQUEST['bs_detail'];

    $img = $_FILES['bs_img']['name'];
    $tmp_dir = $_FILES['bs_img']['tmp_name'];
    $upload_dir = 'img/board/sub/' . $img;
    move_uploaded_file($tmp_dir, $upload_dir);
    $bs_date = date("Y-m-d H:i:s");

    $query = $connection->prepare("INSERT INTO board_sub (bm_id, u_id, bs_detail, bs_img, bs_date) VALUES (:bm_id, :u_id, :bs_detail, :bs_img ,:bs_date )");
    $query->bindParam("bm_id", $bm_id, PDO::PARAM_STR);
    $query->bindParam("u_id", $uid, PDO::PARAM_STR);
    $query->bindParam("bs_detail", $bs_detail, PDO::PARAM_STR);
    $query->bindParam("bs_img", $img, PDO::PARAM_STR);
    $query->bindParam("bs_date", $bs_date, PDO::PARAM_STR);
    $query->execute();
    //close connection
    $connection = null;
    header("Location: detail.php?bm_id=$bm_id");
}

if (isset($_REQUEST['delete_id'])) {
    $delete_id = $_REQUEST['delete_id'];

    $query = $connection->prepare("SELECT * FROM board_sub WHERE bs_id = :bs_id");
    $query->bindParam("bs_id", $delete_id, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    unlink("img/board/sub/" . $result['bs_img']);

    $del = $connection->prepare("DELETE FROM board_sub WHERE bs_id = :bs_id AND u_id = :u_id AND bm_id = :bm_id");
    $del->bindParam("bs_id", $delete_id, PDO::PARAM_STR);
    $del->bindParam("u_id", $uid, PDO::PARAM_STR);
    $del->bindParam("bm_id", $bm_id, PDO::PARAM_STR);
    $del->execute();

    //close connection
    $connection = null;
    header("Location: detail.php?bm_id=$bm_id");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายละเอียด</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>

<body>
    <div class="detail">
        <div class="detail--container">
            <div class="detail--header">
                <button class="btn btn-success mb-3" onclick="location.href='index.php'">ย้อนกลับ</button>
                <h1><?php echo $result['bm_title'] ?></h1>

                <div class="detail--body">
                    <div class="detail--content d-flex flex-column">

                        <div class="detail--content--img mb-3">
                            <img src="img/board/<?php echo $result['bm_img'] ?>" alt="">
                        </div>


                        <div class="p-2 detail--content--text">


                            <p><?php echo $result['bm_detail'] ?></p>

                            <?php
                            if (isset($_SESSION['u_id'])) {
                                $uid = $_SESSION['u_id'];
                            } else {
                                $uid = "";
                            }

                            if ($uid == $result['u_id']) {
                            ?>
                                <div class="detail--comment--edit">
                                    <a href="edit_board.php?bm_id=<?php echo $result['bm_id'] ?>" class="btn btn-primary">แก้ไข</a>
                                </div>

                            <?php
                            }
                            ?>

                        </div>

                        <div class="p-2 detail--content--by mb-2">
                            <p>โดย <b><?php echo $result['fullname'] ?></b> เมื่อ <b><?php echo $result['bm_date'] ?></b></p>
                        </div>
                    </div>
                    <div class="detail--comment d-flex flex-column">
                        <?php
                        $bm_s = $connection->prepare("SELECT * FROM board_sub INNER JOIN users ON board_sub.u_id = users.u_id WHERE bm_id = :bm_id");
                        $bm_s->bindParam("bm_id", $bm_id, PDO::PARAM_STR);
                        $bm_s->execute();
                        $result_s = $bm_s->fetchAll(PDO::FETCH_ASSOC);
                        $num = 0;
                        foreach ($result_s as $row) {
                            $num++;

                        ?>
                            <hr>

                            <div class="detail--comment--header mb-3 p-2">
                                <h4>ความคิดเห็นที่ <?php echo $num ?></h4>
                            </div>
                            <?php
                            if ($row['bs_img'] != "") {
                            ?>
                                <div class="detail--comment--img mb-3">
                                    <img src="img/board/sub/<?php echo $row['bs_img'] ?>" loop=infinite alt="">
                                </div>
                            <?php } ?>

                            <div class="detail--comment--text p-2">
                                <p><?php echo $row['bs_detail'] ?></p>

                                <?php
                                if (isset($_SESSION['u_id'])) {
                                    $uid = $_SESSION['u_id'];
                                } else {
                                    $uid = "";
                                }

                                if ($uid == $row['u_id']) {
                                ?>
                                    <div class="detail--comment--edit">
                                        <a href="edit_comment.php?bs_id=<?php echo $row['bs_id'] ?>" class="btn btn-primary">แก้ไข</a>
                                        <a href="?delete_id=<?php echo $row['bs_id'] ?>&bm_id=<?php echo $row['bm_id'] ?>" class="btn btn-danger">ลบ</a>
                                    </div>

                                <?php
                                }
                                ?>
                            </div>
                            <div class="p-2 detail--comment--by mb-2">
                                <p>โดย <b><?php echo $row['fullname'] ?></b> เมื่อ <b><?php echo $row['bs_date'] ?></b></p>
                            </div>

                        <?php
                        }
                        ?>

                    </div>



                    <div class="detail--reply">

                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-floating">
                                <?php if (isset($_SESSION['u_id'])) { ?>
                                    <textarea class="form-control mb-2" placeholder="ตอบกระทู้" id="bs_detail" name="bs_detail" style="height: 100px"></textarea>
                                    <label for="detail_sub">ตอบกระทู้</label>
                                <?php } else { ?>
                                    <textarea class="form-control mb-2" placeholder="ตอบกระทู้" id="bs_detail" name="bs_detail" style="height: 100px" readonly></textarea>
                                    <label for="detail_sub">ต้องเข้าสู่ระบบก่อนตอบกระทู้</label>
                                <?php } ?>
                            </div>
                            <div class="input-group mb-2">
                                <?php if (isset($_SESSION['u_id'])) { ?>
                                    <label class="input-group-text" for="img">เลือกรูป</label>
                                    <input type="file" accept="image/*" class="form-control" id="bs_img" name="bs_img">
                                    <button type="submit" name="answer" value="answer" class="btn btn-primary">ตอบกระทู้</button>
                                <?php } else { ?>
                                    <!-- <label class="input-group-text" for="img">เลือกรูป</label>
                                    <input type="file" accept="image/*" class="form-control" id="bs_img" name="bs_img"> -->
                                    <button type="submit" name="answer" value="answer" class="btn btn-primary" disabled>ตอบกระทู้</button>
                                <?php } ?>
                            </div>

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