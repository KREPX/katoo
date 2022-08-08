<?php 

    session_start();
    unset($_SESSION['u_id']);
    header('location: index.php');
