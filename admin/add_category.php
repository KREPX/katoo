<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['u_id'])) {
    header('location: ../index.php');
}

if (isset($_SESSION['u_id'])) {
    $uid = $_SESSION['u_id'];
}

if (isset($_REQUEST['addcg'])) {

    $cg_name = $_REQUEST['cg_name'];


    $query = $connection->prepare("INSERT INTO category (cg_name) VALUES (:cg_name)");
    $query->bindParam("cg_name", $cg_name, PDO::PARAM_STR);


    if ($query->execute()) {
        //close connection
        $connection = null;
        $updateMsg = "เพิ่มหมวดหมู่สำเร็จ";
        header("Refresh:1;admin.php");
    } else {
        $errorMsg = "เพิ่มหมวดหมู่ไม่สำเร็จ";
    }
}


?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เพิ่มหมวดหมู่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <div class="addboard">
        <div class="addboard--container">
            <div class="addboard--header">
                <h1>เพิ่มหมวดหมู่</h1>
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
                            <input type="text" class="form-control" name="cg_name" placeholder="ชื่อหมวดหมู่" required>
                            <label for="title">ชื่อหมวดหมู่</label>
                        </div>
                        <hr>
                        <button type="submit" name="addcg" value="addcg" class="btn butt-register">เพิ่มหมวดหมู่</button>
                        <a href="admin.php" class="btn butt-cancle">ยกเลิก</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>