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

  if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];
    $status = $user->suspendUser($PDOConn, $userId);
    if ($status) {

      $_SESSION['adminActionSuccess'] = 'User suspended successfully';
      header('Location: ../adminDashboard.php');
      exit();
    } else {
      $_SESSION['adminActionError'] = 'User suspension failed';
      header('Location: ../adminDashboard.php');
      exit();
    }
  }
} else {
  header('Location: ../adminDashboard.php');
  exit();
}

?>