<?php
session_start();
require_once '../../classes/admin.php';
require_once '../../classes/teacher.php';
require_once '../../classes/student.php';

if (isset($_SESSION['user'])) {

    $user = unserialize($_SESSION['user']);

    $user->logout();
    header("Location: ../../index.php");
    exit();
} else {
    echo "User not logged in";
    header("Location: ../login.php");
    exit();
}

?>