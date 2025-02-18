<?php 
    require_once '../../config.php';
    require_once 'function.php';
    session_start();
    addAudit($pdo, $_SESSION["username"], 'Logout', 'Authentication', 'User has logged out');
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
?>