<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['u_id'])) {
  header('location: ../index.php');
}

if (isset($_SESSION['u_id'])) {
  $uid = $_SESSION['u_id'];
}

$query = $connection->prepare("SELECT * FROM users WHERE u_id=:uid");

$query->bindParam("uid", $uid, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KaTooTip 🥲</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

</head>

<body>

  <div class="board d-flex flex-column justify-content-center mb-3">
    <div class="board--container d-flex">
      <div class="board--body d-flex flex-column">
        <div class="board--header d-flex">

          <div class="p-2 flex-grow-1 bd-highlight">
            <h1>KaTooTip 🥲</h1>
          </div>

          <div class="p-2 bd-highlight">
            <a href="add_category.php" class="btn btn-dark">เพิ่มหมวดหมู่</a>
          </div>

        </div>

        <div class="board--content">

          <div class="d-flex flex-row">
            <div class="p-2 bd-highlight w-50">
              <table id="table_id" class="display">
                <?php

                $user = $connection->prepare("SELECT * FROM users WHERE type='0' ORDER BY u_id DESC");
                $user->execute();
                $users = $user->fetchAll(PDO::FETCH_ASSOC);

                $cg = $connection->prepare("SELECT * FROM category ORDER BY cg_id DESC");
                $cg->execute();
                $cg_result = $cg->fetchAll(PDO::FETCH_ASSOC);

                ?>
                <thead>
                  <tr>
                    <th>สมาชิก</th>
                    <th>ลบ / แก้ไข</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($users as $u) {
                  ?>
                    <tr>

                      <td><?php echo $u['fullname'] ?></td>
                      <td>
                        <div class="b p-2 bd-highlight" style="background: linear-gradient(110deg, #FFA8A8 50%, #40DFEF 60%);">
                          <a href="edit_member.php?u_id=<?php echo $u['u_id'] ?>" style="text-decoration:none; color:black;">ลบ/แก้ไข</a>
                        </div>
                      </td>

                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

            <div class="p-2 bd-highlight w-50">
              <table id="table_id2" class="display">
                <thead>
                  <tr>
                    <th>หมวดหมู่</th>
                    <th>ลบ / แก้ไข</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($cg_result as $cg_row) {
                  ?>
                    <tr>
                      <td><?php echo $cg_row['cg_name'] ?></td>
                      <td>
                        <div class="b p-2 bd-highlight" style="background: linear-gradient(110deg, #FFA8A8 50%, #40DFEF 60%);">
                          <a href="edit_category.php?cg_id=<?php echo $cg_row['cg_id'] ?>" style="text-decoration:none; color:black;">ลบ/แก้ไข</a>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>

      <?php

      if (isset($_SESSION['u_id'])) {

      ?>
        <div class="board--profile">
          <div class="profile-img">
            <img src="../img/user_profile/<?php echo $result['img'] ?>" alt="">
          </div>
          <hr>
          <div class="username">
            <p><?php echo $result['fullname']  ?></p>
          </div>
          <?php
          if ($result['type'] == 1) {
          ?>
            <hr>
            <a href="../index.php" class="btn btn-dark">หน้าแรก</a>
          <?php
          }
          ?>
          <hr>
          <a href="../admin/edit_profile.php?id=<?php echo $result['u_id'] ?>" class="btn btn-primary">แก้ไขโปรไฟล์</a>
          <a href="../logout.php" class="btn btn-danger">ออกจากระบบ</a>
        </div>

      <?php
      } else {
      ?>

        <div class="board--profile">
          <hr>
          <div class="username">
            <p>ยังไม่ได้เข้าสู่ระบบ</p>
          </div>
          <hr>
          <a href="login.php" class="btn btn-outline-primary">เข้าสู่ระบบ</a>
          <a href="register.php" class="btn btn-outline-dark">สมัครสมาชิก</a>
        </div>

      <?php
      }
      ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
  <script>
    $(document).ready(function() {
      $('#table_id').DataTable();
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#table_id2').DataTable();
    });
  </script>
</body>

</html>