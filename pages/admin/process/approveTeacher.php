<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../classes/Admin.php';


if (!isset($_SESSION['user'])) {
  header('Location: ../../auth/login.php');
  exit();
} else {
  $user = unserialize($_SESSION['user']);
  if (!($user instanceof Admin)) {
    header('Location: ../../auth/login.php');
    exit();
  }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['teacherId'])) {
    $teacherId = $_POST['teacherId'];
    $status = $user->activateUser($PDOConn, $teacherId);
    if ($status) {
      $_SESSION['adminActionSuccess'] = 'Teacher activated successfully';
      header('Location: ../adminDashboard.php');
      exit();
    } else {
      $_SESSION['adminActionError'] = 'Teacher activation failed';
      header('Location: ../adminDashboard.php');
      exit();
    }
  }
}

?>