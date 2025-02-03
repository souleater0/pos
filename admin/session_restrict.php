<?php 
    session_start();
    if (!isset($_SESSION['user_id'])) {
        // header("Location: admin/login.php");
        header("Location: 404.php");
        die();
    }
?>