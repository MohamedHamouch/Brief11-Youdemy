<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/user.php';
require_once '../../../classes/student.php';
require_once '../../../classes/teacher.php';
require_once '../../../classes/admin.php';

if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
} else {
    header('Location: ../../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['firstName']) && isset($_POST['lastName'])) {
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);

        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $status = $user->updateProfile($PDOConn);
        if ($status === true) {
            $_SESSION['user'] = serialize($user);
            $_SESSION['updateProfileSuccess'] = "Profile updated successfully.";
        } else {
            $_SESSION['updateProfileError'] = "An error occurred while updating your profile.";
        }
        header('Location: ../profile.php');
        exit();
    }
} else {

    header('Location: ../profile.php');
    exit();
}